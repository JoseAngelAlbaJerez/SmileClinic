<template>
    <section class="relative bg-stone-50 dark:bg-gray-900">
        <div class="w-full py-8 relative z-10 backdrop-blur-3xl dark:bg-gray-900/80">
            <div class="w-full max-w-8xl mx-auto px-2 lg:px-8">

                <div class="grid grid-cols-12 gap-8 max-w-4xl mx-auto xl:max-w-full">

                    <!-- Upcoming Events List -->
                    <div class="col-span-12 xl:col-span-5">
                        <h2 class="font-manrope text-3xl leading-tight text-gray-900 dark:text-white mb-1.5">Próximas
                            Citas</h2>
                        <div class="flex justify-between mb-2">
                            <p class="text-lg font-normal text-gray-600 dark:text-gray-300 ">No faltes al horario
                            </p>
                            <AccessGate permission="event.delete">
                                <button @click="toggleShowDeleted()"
                                :class="showDeleted ? 'bg-red-500 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 ' : 'bg-gray-500 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600 '"
                                    class="flex justify-center rounded-lg   px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm sm:px-4">
                                    <DeleteIcon /> {{ showDeleted ? 'Mostrar Eliminados' : 'Ocultar Eliminados'  }}
                                </button>
                            </AccessGate>
                        </div>

                        <!-- Search and Filter Controls -->
                        <div class="mb-6 flex flex-col sm:flex-row gap-4">
                            <input v-model="searchQuery" @input="handleSearch" type="text" placeholder="Buscar..."
                                class="flex-grow px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white">
                            <select v-model="timeRange" @change="handleTimeRangeChange"
                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white">
                                <option value="1">Hoy</option>
                                <option value="7">Últimos 7 días</option>
                                <option value="30">Últimos 30 días</option>
                                <option value="month">Este mes</option>
                                <option value="year">Este año</option>
                            </select>
                        </div>

                        <div class="flex gap-5 flex-col ">
                            <!-- Event List -->
                            <div v-for="event in filteredEvents" :key="event.id"
                                class="p-6 rounded-xl bg-white dark:bg-gray-800 shadow-sm dark:shadow-none">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-2.5">
                                        <span class="w-2.5 h-2.5 rounded-full"
                                            :class="getEventColorClass(event)"></span>
                                        <p class="text-base font-medium text-gray-900 dark:text-gray-100">
                                            {{ formatDate(event.start) }} - {{ formatTime(event.start) }}
                                            <span v-if="event.end_date">to {{ formatTime(event.end_date) }}</span>
                                        </p>
                                    </div>
                                    <div class="dropdown relative inline-flex">
                                        <button type="button" @click="toggleDropdown(event)"
                                            class="dropdown-toggle inline-flex justify-center py-2.5 px-1 items-center gap-2 text-sm text-black dark:text-white rounded-full cursor-pointer font-semibold text-center shadow-xs transition-all duration-500 hover:text-blue-600 dark:hover:text-blue-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="4"
                                                viewBox="0 0 12 4" fill="none">
                                                <path
                                                    d="M1.85624 2.00085H1.81458M6.0343 2.00085H5.99263M10.2124 2.00085H10.1707"
                                                    stroke="currentcolor" stroke-width="2.5" stroke-linecap="round">
                                                </path>
                                            </svg>
                                        </button>

                                    </div>
                                </div>
                                <h6 class="text-xl leading-8 font-semibold text-black dark:text-white mb-1">{{
                                    event.title }}</h6>
                                <p class="text-base font-normal text-gray-600 dark:text-gray-300">
                                    <span v-if="event.patient">Paciente: {{ event.patient.first_name }} {{
                                        event.patient.last_name }}</span>
                                    <span v-if="event.doctor"> | Doctor: {{ event.doctor.name }}</span>
                                </p>
                                <p v-if="event.description"
                                    class="text-base font-normal text-gray-600 dark:text-gray-300 mt-2">{{
                                        event.description }}</p>
                            </div>

                            <!-- Empty State -->
                            <div v-if="filteredEvents.length === 0"
                                class="p-6 rounded-xl bg-white dark:bg-gray-800 text-center text-gray-500 dark:text-gray-400">
                                No eventos encontrados
                            </div>
                        </div>
                    </div>

                    <!-- Calendar View -->
                    <div
                        class="col-span-12 xl:col-span-7 px-2.5 py-5 sm:p-8 bg-gradient-to-b from-white/25 to-white dark:from-gray-800/25 dark:to-gray-800 xl:bg-white dark:xl:bg-gray-800 rounded-2xl max-xl:row-start-1 shadow-sm dark:shadow-none">
                        <div class="flex flex-col md:flex-row gap-4 items-center  mb-5">
                            <div class="flex items-center gap-4">
                                <h5 class="text-xl leading-8 font-semibold text-gray-900 dark:text-white">{{
                                    currentMonth }} {{
                                        currentYear }}</h5>
                                <div class="flex items-center">
                                    <button @click="previousMonth"
                                        class="text-blue-600 dark:text-blue-400 p-1 rounded transition-all duration-300 hover:text-white dark:hover:text-white hover:bg-blue-600 dark:hover:bg-blue-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 16 16" fill="none">
                                            <path d="M10.0002 11.9999L6 7.99971L10.0025 3.99719" stroke="currentcolor"
                                                stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round">
                                            </path>
                                        </svg>
                                    </button>
                                    <button @click="nextMonth"
                                        class="text-blue-600 dark:text-blue-400 p-1 rounded transition-all duration-300 hover:text-white dark:hover:text-white hover:bg-blue-600 dark:hover:bg-blue-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 16 16" fill="none">
                                            <path d="M6.00236 3.99707L10.0025 7.99723L6 11.9998" stroke="currentcolor"
                                                stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <AccessGate permission="event.create" class="ml-auto" >
                                <Link :href="route('events.create')" as="button"
                                    class="flex justify-center  rounded-lg bg-blue-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500 sm:px-4">
                                <AddIcon class="size-6" />
                                Nueva Cita
                                </Link>
                            </AccessGate>
                            <div class="flex items-center rounded-md p-1 bg-blue-50 dark:bg-gray-700 gap-px">

                                <button @click="viewMode = 'day'"
                                    :class="{ 'bg-blue-600 dark:bg-blue-500 text-white': viewMode === 'day', 'bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400': viewMode !== 'day' }"
                                    class="py-2.5 px-5 rounded-lg text-sm font-medium transition-all duration-300 hover:bg-blue-600 dark:hover:bg-blue-500 hover:text-white">
                                    Day
                                </button>
                                <button @click="viewMode = 'week'"
                                    :class="{ 'bg-blue-600 dark:bg-blue-500 text-white': viewMode === 'week', 'bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400': viewMode !== 'week' }"
                                    class="py-2.5 px-5 rounded-lg text-sm font-medium transition-all duration-300 hover:bg-blue-600 dark:hover:bg-blue-500 hover:text-white">
                                    Week
                                </button>
                                <button @click="viewMode = 'month'"
                                    :class="{ 'bg-blue-600 dark:bg-blue-500 text-white': viewMode === 'month', 'bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400': viewMode !== 'month' }"
                                    class="py-2.5 px-5 rounded-lg text-sm font-medium transition-all duration-300 hover:bg-blue-600 dark:hover:bg-blue-500 hover:text-white">
                                    Month
                                </button>
                            </div>
                        </div>

                        <!-- Calendar Grid -->
                        <div class="border border-blue-200 dark:border-gray-600 rounded-xl">
                            <div class="grid grid-cols-7 rounded-t-3xl border-b border-blue-200 dark:border-gray-600">
                                <div v-for="day in daysOfWeek" :key="day"
                                    class="py-3.5 border-r border-blue-200 dark:border-gray-600 bg-blue-50  dark:bg-gray-700 flex items-center justify-center text-sm font-medium text-blue-600 dark:text-blue-400"
                                    :class="{ 'rounded-tl-xl': day === 'Sun', 'rounded-tr-xl': day === 'Sat' }">
                                    {{ day }}
                                </div>
                            </div>
                            <div class="grid grid-cols-7 rounded-b-xl">
                                <!-- Previous month days -->
                                <div v-for="day in previousMonthDays" :key="'prev-' + day"
                                    class="flex xl:aspect-square max-xl:min-h-[60px] p-3.5 bg-gray-50 dark:bg-gray-700 border-r border-b border-blue-200 dark:border-gray-600 transition-all duration-300 hover:bg-blue-50 dark:hover:bg-gray-600">
                                    <span class="text-xs font-semibold text-gray-400 dark:text-gray-500">{{ day
                                        }}</span>
                                </div>

                                <!-- Current month days -->
                                <div v-for="day in currentMonthDays" :key="day.date.getDate()"
                                    @click="selectDate(day.date)"
                                    class="flex xl:aspect-square max-xl:min-h-[60px] p-3.5 bg-white dark:bg-gray-800 border-r border-b border-blue-200 dark:border-gray-600 transition-all duration-300 hover:bg-blue-50 dark:hover:bg-gray-700 cursor-pointer relative"
                                    :class="{
                                        'rounded-bl-xl': day.isFirstDayOfWeek,
                                        'rounded-br-xl': day.isLastDayOfWeek,
                                        'bg-blue-50 dark:bg-blue-900/30': isToday(day.date),
                                        'bg-blue-100 dark:bg-blue-800': isSelectedDate(day.date)
                                    }">
                                    <span class="text-xs font-semibold" :class="{
                                        'text-gray-900 dark:text-gray-100': !isToday(day.date) && !isSelectedDate(day.date),
                                        'text-blue-600 dark:text-blue-400': isToday(day.date),
                                        'text-blue-800': isSelectedDate(day.date)
                                    }">
                                        {{ day.date.getDate() }}
                                    </span>

                                    <!-- Event indicators -->
                                    <div v-for="event in getEventsForDate(day.date)" :key="event.id"
                                        class="absolute top-9 bottom-1 left-3.5 p-1.5 xl:px-2.5 h-max rounded bg-blue-200 dark:bg-blue-400"
                                        :class="getEventColorClass(event, true)">
                                        <p
                                            class="hidden xl:block text-xs font-medium mb-px whitespace-nowrap dark:text-white">
                                            {{ event.title }}
                                        </p>
                                        <span
                                            class="hidden xl:block text-xs font-normal whitespace-nowrap dark:text-gray-200">
                                            {{ formatTime(event.start) }}<span v-if="event.end_date"> - {{
                                                formatTime(event.end_date) }}</span>
                                        </span>
                                        <p class="xl:hidden w-2 h-2 rounded-full" :class="getEventDotColorClass(event)">
                                        </p>
                                    </div>
                                </div>

                                <!-- Next month days -->
                                <div v-for="(day, index) in nextMonthDays" :key="'next-' + day"
                                    class="flex xl:aspect-square max-xl:min-h-[60px] p-3.5 bg-gray-50 dark:bg-gray-700 border-r border-b border-blue-200 dark:border-gray-600 transition-all duration-300 hover:bg-blue-50 dark:hover:bg-gray-600"
                                    :class="{
                                        'border-r-0': index === nextMonthDays.length - 1,
                                        'rounded-br-xl': index === nextMonthDays.length - 1
                                    }">
                                    <span class="text-xs font-semibold text-gray-400 dark:text-gray-500">{{ day
                                        }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <Modal :show="showEventModal" @close="showEventModal = false">
            <div class="text-gray-800 p-5  ">
                <h2 class="text-2xl font-semibold  p-4  dark:text-white  ">
                    Detalles del Evento
                </h2>

                <div v-if="eventForm" class="space-y-3 p-4 ">
                    <div class="flex items-center gap-2">
                        <h1 class=" dark:text-white font-semibold text-2xl text-gray-700"> {{ eventForm.title }}</h1>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-medium text-gray-500 dark:text-gray-200 w-30">Paciente:</span>
                        <Link :href="route('patients.show', eventForm.patient.id)" class="text-blue-500">{{
                            eventForm.patient.first_name }} {{
                            eventForm.patient.last_name }}
                        </Link>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-medium text-gray-500 dark:text-gray-200 w-30">Motivo de última
                            Consulta:</span>
                        <span class="text-gray-900 dark:text-gray-300">{{ eventForm.patient.motive }}</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="font-medium text-gray-500 dark:text-gray-200 w-30">Doctor:</span>
                        <Link :href="route('users.show', eventForm.doctor.id)" class="text-blue-500">{{
                            eventForm.doctor.name }} {{
                            eventForm.doctor.last_name }}
                        </Link>
                    </div>


                    <div class="flex items-center gap-2">
                        <span class="font-medium text-gray-500 dark:text-gray-200 w-30">Hora de Inicio:</span>
                        <span class="text-gray-900 dark:text-gray-300">{{ formatTime(eventForm.start) }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-medium text-gray-500 dark:text-gray-200 w-30">Hora de Finalización:</span>
                        <span class="text-gray-900 dark:text-gray-300">{{ formatTime(eventForm.end) }}</span>
                    </div>

                    <div class="flex items-start gap-2">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" @click="AttendEvent(eventForm)" :checked="eventForm.attended"
                                class="sr-only peer">
                            <div
                                class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-500 peer-checked:bg-blue-500 dark:peer-checked:bg-blue-500">
                            </div>
                            <span v-if="eventForm.attended"
                                class="ms-3 text-sm font-medium text-green-300  ">Atendido</span>
                            <span v-else class="ms-3 text-sm font-medium text-red-300  ">No Atendido</span>
                        </label>
                    </div>
                </div>

                <div class=" flex  gap-2 ">
                    <AccessGate permission="event.delete" class=" ml-auto ">
                        <Link v-if="eventForm.active" :href="route('events.edit', eventForm)"
                            class="flex mt-2  gap-2 rounded-lg bg-yellow-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 sm:px-4">
                        <EditIcon />
                        </Link>
                    </AccessGate>
                    <AccessGate permission="event.delete">
                        <DangerButton v-if="eventForm.active" @click="deleteEvent(eventForm)"
                            class="flex  mt-2  gap-2 rounded-lg bg-red-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 sm:px-4">
                            <DeleteIcon />
                        </DangerButton>
                        <button v-else @click="restoreEvent(eventForm)"
                            class="flex  ml-auto mt-2  gap-2 rounded-lg bg-green-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 sm:px-4">
                            <RestoreIcon />
                        </button>
                    </AccessGate>
                </div>
            </div>
        </Modal>
    </section>
</template>

<script>
import { Inertia } from '@inertiajs/inertia';
import { ref, computed, watch, onMounted } from 'vue';
import { format, parseISO, isSameDay, isSameMonth, isToday, addMonths, subMonths, startOfMonth, endOfMonth, eachDayOfInterval, isSameYear, getDay, addDays, subDays } from 'date-fns';
import { Link } from '@inertiajs/vue3';
import EditIcon from './Icons/EditIcon.vue';
import DeleteIcon from './Icons/DeleteIcon.vue';
import DangerButton from './DangerButton.vue';
import RestoreIcon from './Icons/RestoreIcon.vue';
import Modal from './Modal.vue';
import AddIcon from './Icons/AddIcon.vue';
import AccessGate from './AccessGate.vue';
export default {
    props: {
        initialEvents: {
            type: Array,
            required: true
        },
        filters: {
            type: Object,
            default: () => ({
                search: '',
                patient_id: null,
                sortField: null,
                sortDirection: 'asc',
                activeStates: [],
                lastDays: '1',
                showDeleted: true
            })
        }
    },
    components: {
        Link,
        EditIcon,
        DeleteIcon,
        DangerButton,
        RestoreIcon,
        Modal,
        AddIcon,
        AccessGate

    },

    setup(props) {

        // Reactive state
        const events = ref(props.initialEvents);
        const currentDate = ref(new Date());
        const selectedDate = ref(new Date());
        const activeDropdown = ref(null);
        const showEventModal = ref(false);
        const editingEvent = ref(null);
        const viewMode = ref('month');
        const searchQuery = ref(props.filters.search || '');
        const timeRange = ref(props.filters.lastDays || '1');
        const showDeleted = ref(props.filters.showDeleted !== false);

        // Event form
        const eventForm = ref({
            title: '',
            description: '',
            start: format(new Date(), "yyyy-MM-dd'T'HH:mm"),
            end_date: '',
            patient_id: props.filters.patient_id || null
        });

        // Computed properties
        const currentMonth = computed(() => {
            return format(currentDate.value, 'MMMM');
        });

        const currentYear = computed(() => {
            return format(currentDate.value, 'yyyy');
        });

        const daysOfWeek = computed(() => {
            return ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        });

        const firstDayOfMonth = computed(() => {
            return startOfMonth(currentDate.value);
        });

        const lastDayOfMonth = computed(() => {
            return endOfMonth(currentDate.value);
        });


        const previousMonthDays = computed(() => {
            const firstDay = firstDayOfMonth.value;
            const firstDayOfWeek = getDay(firstDay);

            if (firstDayOfWeek === 0) return [];

            const prevMonth = subMonths(firstDay, 1);
            const prevMonthLastDay = endOfMonth(prevMonth);

            return Array.from({ length: firstDayOfWeek }, (_, i) => {
                return prevMonthLastDay.getDate() - (firstDayOfWeek - 1 - i);
            });
        });

        const currentMonthDays = computed(() => {
            const days = eachDayOfInterval({
                start: firstDayOfMonth.value,
                end: lastDayOfMonth.value
            });

            return days.map(date => {
                const dayOfWeek = getDay(date);
                return {
                    date,
                    isFirstDayOfWeek: dayOfWeek === 0,
                    isLastDayOfWeek: dayOfWeek === 6
                };
            });
        });

        const nextMonthDays = computed(() => {
            const lastDay = lastDayOfMonth.value;
            const lastDayOfWeek = getDay(lastDay);

            if (lastDayOfWeek === 6) return [];

            return Array.from({ length: 6 - lastDayOfWeek }, (_, i) => i + 1);
        });

        const filteredEvents = computed(() => {

            let result = events.value;

            // Filter by search query
            if (searchQuery.value.trim()) {
                const query = searchQuery.value.toLowerCase();
                result = result.filter(event => {
                    return (
                        event.title.toLowerCase().includes(query) ||
                        (event.description && event.description.toLowerCase().includes(query)) ||
                        (event.patient && (
                            event.patient.first_name.toLowerCase().includes(query) ||
                            event.patient.last_name.toLowerCase().includes(query) ||
                            `${event.patient.first_name} ${event.patient.last_name}`.toLowerCase().includes(query)
                        ) ||
                            (event.doctor && event.doctor.name.toLowerCase().includes(query))
                        )
                    )
                });
            }

            // Filter by time range
            const now = new Date();
            if (timeRange.value === 'month') {
                const startOfMonth = new Date(now.getFullYear(), now.getMonth(), 1);
                result = result.filter(event => {
                    const eventDate = new Date(event.start);
                    return eventDate >= startOfMonth;
                });
            } else if (timeRange.value === 'year') {
                const startOfYear = new Date(now.getFullYear(), 0, 1);
                result = result.filter(event => {
                    const eventDate = new Date(event.start);
                    return eventDate >= startOfYear;
                });
            } else if (!isNaN(timeRange.value)) {
                const days = parseInt(timeRange.value);
                const startDate = subDays(now, days);
                result = result.filter(event => {
                    const eventDate = new Date(event.start);
                    return eventDate >= startDate;
                });
            }


            // Sort by date
            return result.sort((a, b) => new Date(a.start) - new Date(b.start));
        });

        // Methods
        const formatDate = (dateString) => {
            return format(parseISO(dateString), 'MMM dd, yyyy');
        };

        const formatTime = (dateString) => {
            return format(parseISO(dateString), 'HH:mm');
        };

        const getEventsForDate = (date) => {
            return filteredEvents.value.filter(event =>
                isSameDay(parseISO(event.start), date)
            );
        };

        const getEventColorClass = (event, isBg = false) => {
            if (!event || !event.type) return isBg ? 'bg-blue-50' : 'bg-blue-600';

            const type = event.type.toLowerCase();
            const colors = {
                meeting: { bg: 'blue', text: 'blue' },
                appointment: { bg: 'sky', text: 'sky' },
                followup: { bg: 'emerald', text: 'emerald' },
                consultation: { bg: 'amber', text: 'amber' },
                surgery: { bg: 'red', text: 'red' }
            };

            const color = colors[type] || colors.meeting;
            return isBg ? `bg-${color.bg}-50 text-${color.text}-600` : `bg-${color.bg}-600`;
        };

        const getEventDotColorClass = (event) => {
            if (!event || !event.type) return 'bg-blue-600';

            const type = event.type.toLowerCase();
            const colors = {
                meeting: 'blue',
                appointment: 'sky',
                followup: 'emerald',
                consultation: 'amber',
                surgery: 'red'
            };

            return `bg-${colors[type] || colors.meeting}-600`;
        };

        const isToday = (date) => {
            return isSameDay(date, new Date());
        };

        const isSelectedDate = (date) => {
            return selectedDate.value && isSameDay(date, selectedDate.value);
        };

        const selectDate = (date) => {
            selectedDate.value = date;
            // You could add logic here to filter events for the selected date
        };

        const previousMonth = () => {
            currentDate.value = subMonths(currentDate.value, 1);
        };

        const nextMonth = () => {
            currentDate.value = addMonths(currentDate.value, 1);
        };

        const toggleDropdown = (event) => {
            showEventModal.value = true;
            eventForm.value = {
                ...event,
                start: format(parseISO(event.start), "yyyy-MM-dd'T'HH:mm"),
                end_date: event.end_date ? format(parseISO(event.end_date), "yyyy-MM-dd'T'HH:mm") : ''
            };
        };

        const closeDropdowns = () => {
            activeDropdown.value = null;
        };



        const deleteEvent = (event) => {

            Inertia.delete(route('events.destroy', event),);
            showEventModal.value = false;
            activeDropdown.value = null;
        };

        const AttendEvent = (event) => {
            eventForm.attended = !eventForm.attended;
            Inertia.put(route('events.update', event),
                { attended: eventForm.attended },
                { preserveScroll: true, preserveState: true }

            );
        };

        const saveEvent = () => {
            const method = editingEvent.value ? 'put' : 'post';
            const url = editingEvent.value
                ? route('events.update', editingEvent.value.id)
                : route('events.store');

            Inertia[method](url, eventForm.value, {
                preserveScroll: true,
                onSuccess: () => {
                    showEventModal.value = false;

                }
            });
        };

        const handleSearch = () => {
            Inertia.get(route('events.index'), {
                search: searchQuery.value,
                lastDays: timeRange.value,
                showDeleted: showDeleted.value
            }, {
                preserveState: true,
                preserveScroll: true,
                replace: true
            });
        };
        const toggleShowDeleted = () => {
            showDeleted.value = !showDeleted.value;
            Inertia.get(route('events.index'), {
                search: searchQuery.value,
                lastDays: timeRange.value,
                showDeleted: showDeleted.value
            }, {
                preserveState: true,
                preserveScroll: true,
                replace: true
            });
        };

        const handleTimeRangeChange = () => {
            Inertia.get(route('events.index'), {
                search: searchQuery.value,
                lastDays: timeRange.value,
                showDeleted: showDeleted.value
            }, {
                preserveState: true,
                preserveScroll: true,
                replace: true
            });
        };

        // Close dropdowns when clicking outside
        onMounted(() => {
            document.addEventListener('click', (e) => {
                if (!e.target.closest('.dropdown')) {
                    closeDropdowns();
                }
            });
        });

        // Watch for changes in props
        watch(() => props.initialEvents, (newEvents) => {
            events.value = newEvents;
        });

        watch(() => props.filters, (newFilters) => {
            searchQuery.value = newFilters.search || '';
            timeRange.value = newFilters.lastDays || '1';

        });

        return {
            events,
            currentDate,
            selectedDate,
            activeDropdown,
            showEventModal,
            editingEvent,
            viewMode,
            searchQuery,
            timeRange,
            showDeleted,
            eventForm,

            // Computed
            currentMonth,
            currentYear,
            daysOfWeek,
            previousMonthDays,
            currentMonthDays,
            nextMonthDays,
            filteredEvents,

            // Methods
            formatDate,
            formatTime,
            getEventsForDate,
            getEventColorClass,
            getEventDotColorClass,
            isToday,
            isSelectedDate,
            selectDate,
            previousMonth,
            toggleShowDeleted,
            nextMonth,
            toggleDropdown,
            closeDropdowns,
            AttendEvent,
            deleteEvent,
            saveEvent,
            handleSearch,
            handleTimeRangeChange
        };
    }
};
</script>

<style scoped>
.dropdown-menu {
    z-index: 50;
}

/* Add any additional styles here */
</style>
