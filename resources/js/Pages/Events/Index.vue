<template>

    <Head title="Citas" />
    <AuthenticatedLayout>
        <template #header>
            <Breadcrumb :crumbs="crumbs" />

        </template>

        <template #default>
            <div
                class="flex items-center justify-center rounded-lg bg-white-500 py-12 dark:bg-gray-900 dark:text-white">
                <div class="container mx-auto w-full px-2">
                    <!-- Search & Exports -->
                    <div class="my-2 flex mx-10 gap-2 items-center">
                        <LastDaysFilter v-model="filters.lastDays" @change="submitFilters()" />

                        <!-- Espacio flexible para separar TableDropDown de la derecha -->
                        <div class="flex ml-auto items-center gap-2">

                            <input @input="submitFilters()" v-model="filters.search" type="text" placeholder="Buscar "
                                class="rounded-lg border-0 p-1.5 px-3 py-2 shadow-sm ring-1 ring-slate-300 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 lg:w-96 dark:bg-gray-800 dark:ring-slate-600" />
                            <Link :href="route('events.create')" as="button"
                                class="flex justify-center gap-2 rounded-lg bg-blue-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500 sm:px-4">
                            <AddIcon class="size-6" />
                            Nueva Cita
                            </Link>
                        </div>
                    </div>


                    <!-- Table -->
                    <div
                        class="relative overflow-x-auto border border-gray-200 dark:border-gray-700/60 rounded-lg my-4 mx-4 lg:mx-10">
                        <div class="min-w-full overflow-x-auto">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-blue-500 text-white dark:bg-gray-800 dark:text-gray-200">
                                    <tr>
                                        <th scope="col"
                                            class="px-4 py-3 cursor-pointer whitespace-nowrap hidden sm:table-cell"
                                            @click="sort('id')">
                                            # <span v-if="form.sortField === 'id'">{{ form.sortDirection ===
                                                'asc' ?
                                                '↑' :
                                                '↓' }}</span>
                                        </th>
                                        <th scope="col" class="px-6 py-3 cursor-pointer" @click="sort('title')">
                                            Titulo<span v-if="form.sortField === 'title'">{{ form.sortDirection ===
                                                'asc' ? '↑' :
                                                '↓'
                                                }}</span></th>
                                        <th scope="col" class=" cursor-pointer" @click="sort('doctor.name')">
                                            Doctor <span v-if="form.sortField === 'doctor.name'">{{
                                                form.sortDirection ===
                                                    'asc' ?
                                                    '↑' :
                                                    '↓'
                                            }}</span>
                                        </th>

                                        <th scope="col" class="  cursor-pointer" @click="sort('patient_id')">Paciente
                                            <span v-if="form.sortField === 'patient_id'">{{ form.sortDirection === 'asc'
                                                ?
                                                '↑' :
                                                '↓'
                                            }}</span>
                                        </th>


                                        <th scope="col" class=" cursor-pointer" @click="sort('created_at')">
                                            Fecha de
                                            Creación<span v-if="form.sortField === 'created_at'">{{ form.sortDirection
                                                === 'asc' ?
                                                '↑' :
                                                '↓'
                                                }}</span></th>

                                        <th class="cursor-pointer text-nowrap p-4">
                                            <div class="flex items-center justify-between" @click="toggleShowDeleted()">
                                                <div class="flex items-center ">

                                                    <h2>Estado</h2>
                                                    <FunnelIcon />
                                                </div>

                                            </div>
                                        </th>


                                        <th scope="col" class=" ">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr v-for="event in events.data" :key="event.id">
                                        <td class="p-4  items-center">{{ event.id }}</td>
                                        <td class="p-4  items-center">{{ event.title }} </td>
                                        <td class="p-4  items-center">{{ event.doctor.name }} {{
                                            event.doctor.last_name }}</td>
                                        <td class="p-4  items-center">{{ event.patient.first_name }} {{
                                            event.patient.last_name }}</td>
                                        <td class="p-4  items-center">{{ formatDate(event.created_at) }}</td>
                                        <td class="p-4  items-center">
                                            <div class="flex items-center gap-2">
                                                <span :class="statusIndicatorClasses(event.active)" />
                                                <p :class="statusBadgeClasses(event.active)">
                                                    <HandThumbDown v-if="event.active == 0" />
                                                    <HandThumbUp v-else />
                                                </p>
                                            </div>
                                        </td>
                                        <td class="p-4 items-center">
                                            <p @click="openModal(event)" class="text-blue-500 cursor-pointer">Abrir
                                            </p>
                                        </td>

                                    </tr>



                                </tbody>
                            </table>
                            <div v-if="!events.data.length"
                                class="text-center text-gray-500 dark:text-gray-400 py-4 w-full">
                                No hay registros disponibles.
                            </div>
                        </div>
                        <Pagination :pagination="events" :filters="form" />
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <Modal :show="showModal" @close="showModal = false">
                <div class="text-gray-800 p-5  ">
                    <h2 class="text-2xl font-semibold mb-4 p-4 text-blue-500  pb-2">
                        Detalles del Evento
                    </h2>

                    <div v-if="selectedEvent" class="space-y-3 p-4 ">
                        <div class="flex items-center gap-2">
                            <span class="font-medium text-gray-500 dark:text-gray-200 w-30">Paciente:</span>
                            <Link :href="route('patients.show', selectedEvent.patient.id)" class="text-blue-500">{{
                                selectedEvent.patient.first_name }} {{
                                selectedEvent.patient.last_name }}
                            </Link>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="font-medium text-gray-500 dark:text-gray-200 w-30">Motivo de última
                                Consulta:</span>
                            <span class="text-gray-900 dark:text-gray-300">{{ selectedEvent.patient.motive }}</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="font-medium text-gray-500 dark:text-gray-200 w-30">Doctor:</span>
                            <span class="text-gray-900 dark:text-gray-300">{{ selectedEvent.doctor.name }} {{
                                selectedEvent.doctor.last_name }}</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="font-medium text-gray-500 dark:text-gray-200 w-30">Fecha:</span>
                            <span class="text-gray-900 dark:text-gray-300">{{ selectedEvent.date }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="font-medium text-gray-500 dark:text-gray-200 w-30">Hora de Inicio:</span>
                            <span class="text-gray-900 dark:text-gray-300">{{ selectedEvent.starttime }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="font-medium text-gray-500 dark:text-gray-200 w-30">Hora de Finalización:</span>
                            <span class="text-gray-900 dark:text-gray-300">{{ selectedEvent.endtime }}</span>
                        </div>

                        <div class="flex items-start gap-2">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" @click="AttendEvent()" :checked="selectedEvent.attended"
                                    class="sr-only peer">
                                <div
                                    class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-500 peer-checked:bg-blue-500 dark:peer-checked:bg-blue-500">
                                </div>
                                <span v-if="selectedEvent.attended"
                                    class="ms-3 text-sm font-medium text-green-300  ">Atendido</span>
                                <span v-else class="ms-3 text-sm font-medium text-red-300  ">No Atendido</span>
                            </label>
                        </div>
                    </div>

                    <div class=" flex  gap-2 ">
                        <Link v-if="selectedEvent.active" :href="route('odontographs.edit', selectedEvent)"
                            class="flex  ml-auto mt-2  gap-2 rounded-lg bg-yellow-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 sm:px-4">
                        <EditIcon />
                        </Link>
                        <DangerButton v-if="selectedEvent.active" @click="deleteEvent(selectedEvent)"
                            class="flex  mt-2  gap-2 rounded-lg bg-red-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 sm:px-4">
                            <DeleteIcon />
                        </DangerButton>
                        <button v-else @click="restoreEvent(selectedEvent)"
                            class="flex  ml-auto mt-2  gap-2 rounded-lg bg-green-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 sm:px-4">
                            <RestoreIcon />
                        </button>

                    </div>
                </div>
            </Modal>

        </template>





    </AuthenticatedLayout>

</template>


<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import ArrowDownTrayIcon from '@/Components/Icons/ArrowDownTrayIcon.vue';
import ArrowLongDownIcon from '@/Components/Icons/ArrowLongDownIcon.vue';
import ArrowLongUpIcon from '@/Components/Icons/ArrowLongUpIcon.vue';
import ChevronDoubleLeftIcon from '@/Components/Icons/ChevronDoubleLeftIcon.vue';
import ChevronDoubleRightIcon from '@/Components/Icons/ChevronDoubleRightIcon.vue';
import ChevronLeftIcon from '@/Components/Icons/ChevronLeftIcon.vue';
import ChevronRightIcon from '@/Components/Icons/ChevronRightIcon.vue';
import DocumentIcon from '@/Components/Icons/DocumentIcon.vue';
import FunnelIcon from '@/Components/Icons/FunnelIcon.vue';
import HandThumbUp from '@/Components/Icons/HandThumbUp.vue';
import HandThumbDown from '@/Components/Icons/HandThumbDown.vue';
import AddIcon from '@/Components/Icons/AddIcon.vue';
import LastDaysFilter from '@/Components/LastDaysFilter.vue';
import Pagination from '@/Components/Pagination.vue';
import SearchIcon from '@/Components/Icons/SearchIcon.vue';
import Breadcrumb from '@/Components/BreadCrumb.vue';
import Modal from '@/Components/Modal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import DeleteIcon from '@/Components/Icons/DeleteIcon.vue';
import CalendarIcon from '@/Components/Icons/CalendarIcon.vue';
import { ref } from 'vue';
import { markRaw } from 'vue'
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TableIcon from '@/Components/Icons/TableIcon.vue';
import EditIcon from '@/Components/Icons/EditIcon.vue';
import RestoreIcon from '@/Components/Icons/RestoreIcon.vue';
import { useToast } from 'vue-toastification';
const toast = useToast();
export default {

    props: {
        events: Object,
        filters: Object
    },
    components: {
        AuthenticatedLayout,
        Head,
        Pagination,
        HandThumbUp,
        DeleteIcon,
        HandThumbDown,
        DangerButton,
        SecondaryButton,
        Breadcrumb,
        ArrowDownTrayIcon,
        LastDaysFilter,
        ArrowLongUpIcon,
        ArrowLongDownIcon,
        ChevronDoubleLeftIcon,
        ChevronDoubleRightIcon,
        ChevronLeftIcon,
        ChevronRightIcon,
        EditIcon,
        DocumentIcon,
        FunnelIcon,
        SearchIcon,
        Link,
        Modal,
        TableIcon,
        AddIcon,
        RestoreIcon

    },
    watch: {
        selectedOptions() {
            this.fetchFilteredevents();
        },

    },
    data() {
        return {
            form: {
                search: this.filters?.search || '',
                sortField: this.filters?.sortField || 'events.updated_at',
                sortDirection: this.filters?.sortDirection || 'asc',

                lastDays: this.filters?.lastDays || '1',
                showDeleted: this.filters?.showDeleted || true,
            },
            showModal: ref(false),
            selectedEvent: ref(null),
            timeout: 3000,
            crumbs: [
                { icon: markRaw(CalendarIcon), label: 'Citas', to: route('events.index') },
                { icon: markRaw(TableIcon), label: 'Listado' }
            ]

        }
    },
    mounted() {
        if (this.filters?.activeStates) {
            this.form.activeStates = [...this.filters.activeStates];
        }
    },
    methods: {
        formatDate(date) {
            const d = new Date(date);
            return d.toISOString().split('T')[0];
        },
        openModal(event) {
            this.selectedEvent = event;
            this.showModal = true;
        },
        AttendEvent() {
            this.selectedEvent.attended = !this.selectedEvent.attended;
        },
        deleteEvent(id) {
            this.$inertia.delete(route('events.destroy', id),);
        },
        restoreEvent(id) {
            this.$inertia.put(route('events.update', id),
                { active: true },
            );
        },


        statusBadgeClasses(status) {
            return {
                1: "bg-green-200 text-green-700 px-2 py-1 rounded",
                0: "bg-red-200 text-red-700 px-2 py-1 rounded",
            }[status] || "bg-gray-200 text-gray-700 px-2 py-1 rounded";
        },

        statusIndicatorClasses(status) {
            return {
                Up: "bg-green-600 size-2 rounded-full animate-pulse",
                Down: "bg-red-600 size-2 rounded-full animate-ping",
            }[status] || "bg-gray-600 size-2 rounded-full";
        },
        submitFilters() {
            if (this.timeout) {
                clearTimeout(this.timeout);
            }

            this.timeout = setTimeout(() => {

                this.form.lastDays = this.filters.lastDays || '1',
                    this.form.search = this.filters.search || '',



                    this.$inertia.get(route('events.index'), this.form, {
                        preserveState: true,
                        preserveScroll: true,
                        replace: true
                    });
            }, 300);
        },
        sort(field) {
            this.form.sortField = field;
            this.form.sortDirection = this.form.sortDirection === 'asc' ? 'desc' : 'asc';
            this.submitFilters();
        },
        toggleShowDeleted() {

            this.form.showDeleted = !this.form.showDeleted;

            this.submitFilters();

        }


    }
};

</script>
<style scoped>
.slide-fade-enter-active,
.slide-fade-leave-active {
    transition: all 0.2s ease;
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    opacity: 0;
    transform: translateY(-5px);
}

.slide-fade-enter-to,
.slide-fade-leave-from {
    opacity: 1;
    transform: translateY(0);
}
</style>
