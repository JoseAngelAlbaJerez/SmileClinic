<template>

    <Head title="Sucursales" />
    <AuthenticatedLayout>
        <template #header>
            <BreadCrumb :crumbs="crumbs" />
        </template>
        <template #default>
            <div class="container mx-auto mb-5 dark:text-white bg-white dark:bg-gray-700 mt-5   rounded-2xl shadow-md">

                <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">

                        <!-- Header -->
                        <div
                            class="p-6 bg-pink-500  dark:bg-pink-600 rounded-t-2xl text-white shadow-inner flex justify-between items-center gap-3">
                            <div>
                                <h2 class="text-2xl font-bold">Sucursal {{ branch.name }}</h2>
                                <p class="text-pink-100 text-sm mt-0.5">Detalles completos de la sucursal</p>
                            </div>
                            <!-- Botones -->
                            <div class="flex justify-end gap-3  ">

                                <template v-if="branch.active">

                                    <button @click="print()"
                                        class="flex items-center gap-2 bg-green-500 hover:bg-green-600 px-4 py-2 rounded-lg text-white shadow">
                                        <PrintIcon />
                                    </button>

                                    <AccessGate permission="branch.update">
                                        <Link :href="route('branches.edit', branch.id)"
                                            class="flex items-center gap-2 bg-yellow-500 hover:bg-yellow-600 px-4 py-2 rounded-lg text-white shadow">
                                        <EditIcon />
                                        </Link>
                                    </AccessGate>

                                    <AccessGate permission="branch.delete">
                                        <DangerButton @click="deleteBranch(branch.id)"
                                            class="flex items-center gap-2 bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg text-white shadow">
                                            <DeleteIcon />
                                        </DangerButton>
                                    </AccessGate>

                                </template>

                                <template v-else>
                                    <PrimaryButton @click="restorePatient(patient.id)"
                                        class="flex items-center gap-2 bg-green-500 hover:bg-green-600 px-4 py-2 rounded-lg text-white shadow">
                                        <RestoreIcon /> Restaurar
                                    </PrimaryButton>
                                </template>

                            </div>

                        </div>


                        <!-- Contenido en Grid -->
                        <div class="grid grid-cols-1 gap-6 p-6">

                            <div
                                class="bg-white dark:bg-gray-700/40 p-5 rounded-xl shadow-sm border border-gray-100 dark:border-gray-600/40">

                                <h3
                                    class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-pink-500"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Información de la Sucursal
                                </h3>

                                <div class="space-y-3 text-sm">

                                    <div class="flex">
                                        <span class=" font-medium text-gray-600 dark:text-gray-300">Nombre
                                            :</span>
                                        <span class="text-gray-800 dark:text-gray-100">{{ branch.name }} </span>
                                    </div>

                                    <div class="flex">
                                        <span class=" font-medium text-gray-600 dark:text-gray-300">Dirección:</span>
                                        <span class="text-gray-800 dark:text-gray-100">{{ branch.address }}</span>
                                    </div>

                                    <div class="flex">
                                        <span class=" font-medium text-gray-600 dark:text-gray-300">Teléfono:</span>
                                        <span class="text-gray-800 dark:text-gray-100">{{ branch.phone_number }}</span>
                                    </div>


                                    <div class="flex">
                                        <span class=" font-medium text-gray-600 dark:text-gray-300">Fecha de
                                            Creación:</span>
                                        <span class="text-gray-800 dark:text-gray-100">{{ formatDate(branch.created_at
                                        ) }}</span>
                                    </div>

                                </div>
                            </div>




                        </div>

                        <!-- Citas -->
                        <AccessGate permission="event.view" class="p-5">
                            <div
                                class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">

                                <!-- Encabezado con Efecto de Vidrio -->
                                <div
                                    class="mb-6 flex items-center gap-3 backdrop-blur-sm bg-white/30 dark:bg-gray-700/50 p-3 rounded-xl border border-gray-200/50 dark:border-gray-600/50 shadow-sm">
                                    <div class="p-2 bg-pink-100 dark:bg-pink-900 rounded-lg">
                                        <CalendarIcon class="w-6 h-6 text-pink-600 dark:text-pink-300" />
                                    </div>

                                    <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">Citas</h2>

                                    <div class="ml-auto flex gap-2">
                                        <Link v-if="events.length" :href="route('events.index')"
                                            class="flex items-center justify-center size-9 rounded-lg bg-blue-500 text-white hover:bg-blue-600 transition-all transform hover:scale-105 shadow-md">
                                        <EyeIcon class="size-5" />
                                        </Link>

                                        <AccessGate permission="event.create">
                                            <Link :href="route('events.create')" as="button"
                                                class="flex items-center justify-center size-9 rounded-lg bg-pink-500 text-white hover:bg-pink-600 transition-all transform hover:scale-105 shadow-md">
                                            <AddIcon class="size-5" />
                                            </Link>
                                        </AccessGate>
                                    </div>
                                </div>

                                <!-- Lista de Citas -->
                                <div class="max-h-96 overflow-y-auto pr-2 space-y-3">
                                    <div v-for="event in events" :key="event.id"
                                        class="group relative bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">

                                        <div class="p-4">
                                            <div class="flex items-start mb-3">
                                                <div class="flex-1">
                                                    <div class="flex justify-between">
                                                        <h3
                                                            class="text-lg font-bold text-gray-800 dark:text-gray-100 group-hover:text-pink-600 dark:group-hover:text-pink-300 transition-colors">
                                                            #{{ event.id }} - {{ event.title }}
                                                        </h3>

                                                        <!-- Estado -->
                                                        <span
                                                            class="text-sm font-medium px-2 py-1 rounded-full text-white"
                                                            :class="event.attended ? 'bg-green-500' : 'bg-red-500'">
                                                            {{ event.attended ? 'Atendido' : 'No Atendido' }}
                                                        </span>
                                                    </div>

                                                    <!-- Información -->
                                                    <div class="mt-2 grid grid-cols-2 gap-2 text-sm">
                                                        <div class="flex items-center text-gray-600 dark:text-gray-300">
                                                            <CalendarIcon class="w-4 h-4 mr-1 text-pink-500" />
                                                            <span>{{ event.date }}</span>
                                                        </div>

                                                        <div class="flex items-center text-gray-600 dark:text-gray-300">
                                                            <ClockIcon class="w-4 h-4 mr-1 text-pink-500" />
                                                            <span>{{ event.starttime }} - {{ event.endtime }}</span>
                                                        </div>

                                                        <div
                                                            class="col-span-2 flex items-center text-gray-600 dark:text-gray-300">
                                                            <UserIcon class="w-4 h-4 mr-1 text-pink-500" />
                                                            <span>Dr. {{ event.doctor.first_name }} {{
                                                                event.doctor.last_name
                                                            }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Acciones -->
                                            <div
                                                class="flex items-center justify-between mt-4 pt-3 border-t border-gray-100 dark:border-gray-600">

                                                <!-- Switch de asistencia -->
                                                <label class="inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" @click="AttendEvent(event)"
                                                        :checked="event.attended" class="sr-only peer">
                                                    <div
                                                        class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-pink-300 dark:peer-focus:ring-pink-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:bg-pink-500 after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-500">
                                                    </div>
                                                    <span class="ms-3 text-sm font-medium"
                                                        :class="event.attended ? 'text-green-500' : 'text-red-500'">
                                                        {{ event.attended ? 'Atendido' : 'No Atendido' }}
                                                    </span>
                                                </label>

                                                <!-- Botones -->
                                                <div class="flex gap-2">
                                                    <Link v-if="event.active" :href="route('events.edit', event)"
                                                        class="flex items-center justify-center size-8 rounded-lg bg-yellow-500 text-white hover:bg-yellow-600 transition-all transform hover:scale-110 shadow-md">
                                                    <EditIcon class="size-4" />
                                                    </Link>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Mensaje cuando no hay citas -->
                                <div v-if="!events.length" class="text-center py-6">
                                    <div class="inline-flex flex-col items-center">
                                        <CalendarIcon class="w-12 h-12 text-gray-400 dark:text-gray-500 mb-2" />
                                        <p class="text-gray-500 dark:text-gray-400 font-medium">No hay citas programadas
                                        </p>
                                        <p class="text-sm text-gray-400 dark:text-gray-500">Agrega una nueva cita para
                                            comenzar
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </AccessGate>

                        <!-- Ingresos -->
                        <AccessGate permission="bill.view" class="p-5">
                            <div
                                class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">

                                <!-- Header -->
                                <div
                                    class="mb-6 flex items-center gap-3 backdrop-blur-sm bg-white/30 dark:bg-gray-700/50 p-3 rounded-xl border border-gray-200/50 dark:border-gray-600/50 shadow-sm">
                                    <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                                        <CashIcon class="w-6 h-6 text-green-600 dark:text-green-300" />
                                    </div>

                                    <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">
                                        Ingresos del Día
                                    </h2>

                                    <div class="ml-auto flex gap-2">
                                        <Link v-if="income.length" :href="route('bills.index')"
                                            class="flex items-center justify-center size-9 rounded-lg bg-blue-500 text-white hover:bg-blue-600 transition-all transform hover:scale-105 shadow-md">
                                        <EyeIcon class="size-5" />
                                        </Link>
                                    </div>
                                </div>

                                <!-- Listado -->
                                <div class="max-h-96 overflow-y-auto pr-2 space-y-3">

                                    <div v-for="bill in income" :key="bill.id"
                                        class="group bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 p-4">

                                        <div class="flex justify-between">
                                            <h3
                                                class="text-lg font-bold text-gray-800 dark:text-gray-100 group-hover:text-green-600">
                                                Factura #{{ bill.id }}
                                            </h3>

                                            <span class="px-2 py-1 text-sm rounded-full bg-green-500 text-white">
                                                +${{ bill.total }}
                                            </span>
                                        </div>

                                        <div class="mt-2 text-sm grid grid-cols-2 gap-2">
                                            <div class="flex items-center text-gray-600 dark:text-gray-300">
                                                <UserIcon class="w-4 h-4 mr-1 text-green-500" />
                                                <span>Paciente: {{ bill.patient.first_name }} {{ bill.patient.last_name
                                                }}</span>
                                            </div>

                                            <div class="flex items-center text-gray-600 dark:text-gray-300">
                                                <UserIcon class="w-4 h-4 mr-1 text-green-500" />
                                                <span>Doctor: {{ bill.doctor.first_name }} {{ bill.doctor.last_name
                                                }}</span>
                                            </div>

                                            <div class="flex items-center text-gray-600 dark:text-gray-300 col-span-2">
                                                <CalendarIcon class="w-4 h-4 mr-1 text-green-500" />
                                                <span>Emitida: {{ bill.emission_date }}</span>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div v-if="!income.length" class="text-center py-6 text-gray-500 dark:text-gray-400">
                                    No hay ingresos registrados hoy.
                                </div>

                            </div>
                        </AccessGate>

                        <!-- Egresos -->
                        <AccessGate permission="expense.view" class="p-5">
                            <div
                                class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">

                                <!-- Header -->
                                <div
                                    class="mb-6 flex items-center gap-3 backdrop-blur-sm bg-white/30 dark:bg-gray-700/50 p-3 rounded-xl border border-gray-200/50 dark:border-gray-600/50 shadow-sm">
                                    <div class="p-2 bg-red-100 dark:bg-red-900 rounded-lg">
                                        <ArrowDownCircleIcon class="w-6 h-6 text-red-600 dark:text-red-300" />
                                    </div>

                                    <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">
                                        Egresos del Día
                                    </h2>

                                    <div class="ml-auto flex gap-2">
                                        <Link v-if="expenses.length" :href="route('expenses.index')"
                                            class="flex items-center justify-center size-9 rounded-lg bg-blue-500 text-white hover:bg-blue-600 transition-all transform hover:scale-105 shadow-md">
                                        <EyeIcon class="size-5" />
                                        </Link>
                                    </div>
                                </div>

                                <!-- Listado -->
                                <div class="max-h-96 overflow-y-auto pr-2 space-y-3">

                                    <div v-for="exp in expenses" :key="exp.id"
                                        class="group bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 p-4">

                                        <div class="flex justify-between">
                                            <h3
                                                class="text-lg font-bold text-gray-800 dark:text-gray-100 group-hover:text-red-600">
                                                Egreso #{{ exp.id }}
                                            </h3>

                                            <span class="px-2 py-1 text-sm rounded-full bg-red-500 text-white">
                                                -${{ exp.amount }}
                                            </span>
                                        </div>

                                        <p class="mt-2 text-gray-700 dark:text-gray-300 text-sm">
                                            {{ exp.description }}
                                        </p>

                                        <div class="mt-2 text-sm">
                                            <div class="flex items-center text-gray-600 dark:text-gray-300">
                                                <UserIcon class="w-4 h-4 mr-1 text-red-500" />
                                                <span>Registrado por: {{ exp.user?.name ?? 'Sistema' }}</span>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div v-if="!expenses.length" class="text-center py-6 text-gray-500 dark:text-gray-400">
                                    No hay egresos registrados hoy.
                                </div>

                            </div>
                        </AccessGate>


                    </div>





                </div>

            </div>

        </template>

    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import { Head, Link } from '@inertiajs/vue3';
import EditIcon from '@/Components/Icons/EditIcon.vue';
import DeleteIcon from '@/Components/Icons/DeleteIcon.vue';
import PrintIcon from '@/Components/Icons/PrintIcon.vue';
import UserIcon from '@/Components/Icons/UserIcon.vue';
import DoctorIcon from '@/Components/Icons/DoctorIcon.vue';
import BuildingIcon from '@/Components/Icons/BuildingIcon.vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import DocumentIcon from '@/Components/Icons/DocumentIcon.vue';
import { markRaw } from 'vue';
import BreadCrumb from '@/Components/BreadCrumb.vue';
import RestoreIcon from '@/Components/Icons/RestoreIcon.vue';
import AccessGate from '@/Components/AccessGate.vue';
import CalendarIcon from '@/Components/Icons/CalendarIcon.vue';
import EyeIcon from '@/Components/Icons/EyeIcon.vue';
import AddIcon from '@/Components/Icons/AddIcon.vue';
import ClockIcon from '@/Components/Icons/ClockIcon.vue';
import CashIcon from '@/Components/Icons/CashIcon.vue';
import ArrowDownCircleIcon from '@/Components/Icons/ArrowDownCircleIcon.vue';



export default {
    props: {
        branch: Object,
        events: Object,
        income: Object,
        expenses: Object
    },
    components: {
        AuthenticatedLayout,
        BreadCrumb,
        PrimaryButton,
        DangerButton,
        Link,
        EditIcon,
        DeleteIcon,
        PrintIcon,
        UserIcon,
        DoctorIcon,
        BuildingIcon,
        VueDatePicker,
        DocumentIcon,
        RestoreIcon,
        Head,
        AccessGate,
        CalendarIcon,
        EyeIcon,
        ClockIcon,
        CashIcon,
        AddIcon,
        ArrowDownCircleIcon
    },
    data() {
        return {
            crumbs: [
                { icon: markRaw(BuildingIcon), label: 'Sucursales', to: route('branches.index') },
                { icon: markRaw(UserIcon), label: this.branch.name, to: route('branches.show', this.branch) },
                { icon: markRaw(DocumentIcon), label: this.branch.id },

            ],
        }
    },
    methods: {
        formatDate(date) {
            if (!date) return '';
            const d = new Date(date);
            return d.toLocaleDateString('es-DO', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
            });
        },
        formatNumber(n) {
            return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
        deleteBranch(id) {
            this.$inertia.delete(route('branches.destroy', id),);
        },
        restoreBranch(id) {
            this.$inertia.put(route('branches.update', id), {
                active: true
            },
            );
        },

    }
}
</script>
