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
                    <div class="my-2 flex lg:mx-10 gap-2 items-center">
                        <LastDaysFilter v-model="filters.lastDays" @change="submitFilters()" />

                        <!-- Espacio flexible para separar TableDropDown de la derecha -->
                        <div class="flex ml-auto items-center gap-2">

                            <input @input="submitFilters()" v-model="filters.search" type="text" placeholder="Buscar "
                                class="rounded-lg border-0 p-1.5 px-3 py-2 shadow-sm ring-1 ring-slate-300 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 lg:w-96 dark:bg-gray-800 dark:ring-slate-600" />
                            <Link :href="route('prescriptions.create')" as="button"
                                class="flex justify-center gap-2 rounded-lg bg-blue-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500 sm:px-4">
                            <AddIcon class="size-6" />
                            Nueva Receta
                            </Link>
                        </div>
                    </div>

                    <!-- Table -->
                    <div
                        class="relative overflow-x-auto border border-gray-200 dark:border-gray-700/60 rounded-lg my-4 mx-4 lg:mx-10">
                        <div class="min-w-full overflow-x-auto">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs  uppercase bg-blue-500 text-white dark:bg-gray-800 dark:text-gray-200">
                                    <tr>
                                        <th scope="col"
                                            class="px-4 py-3 cursor-pointer whitespace-nowrap hidden sm:table-cell"
                                            @click="sort('id')">
                                            # <span v-if="form.sortField === 'id'">{{ form.sortDirection ===
                                                'asc' ?
                                                '↑' :
                                                '↓' }}</span>
                                        </th>
                                        <th scope="col" class="  cursor-pointer" @click="sort('patient_id')">Paciente
                                            <span v-if="form.sortField === 'patient_id'">{{ form.sortDirection === 'asc'
                                                ?
                                                '↑' :
                                                '↓'
                                                }}</span>
                                        </th>
                                        <th scope="col" class="px-6 py-3 cursor-pointer"
                                            @click="sort('patients.motive')">
                                            Diagnosis<span v-if="form.sortField === 'patient.motive'">{{
                                                form.sortDirection ===
                                                    'asc' ? '↑' :
                                                    '↓'
                                            }}</span></th>
                                        <th scope="col" class=" cursor-pointer" @click="sort('users.name')">
                                            Doctor <span v-if="form.sortField === 'users.name'">{{
                                                form.sortDirection ===
                                                    'asc' ?
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

                                        <th scope="col " class="sm:p-4">Acciones</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <tr v-for="prescription in prescriptions.data" :key="prescription.id">
                                        <td class="p-4  items-center">{{ prescription.id }}</td>
                                        <td class="p-4  items-center">{{ prescription.patient.first_name }} {{
                                            prescription.patient.last_name }}</td>
                                        <td class="p-4  items-center">{{ prescription.patient.motive }} </td>
                                        <td class="p-4  items-center">{{ prescription.doctor.name }} {{
                                            prescription.doctor.last_name }}</td>
                                        <td class="p-4  items-center">{{ formatDate(prescription.created_at) }}</td>
                                        <td class="p-4  items-center">
                                            <div class="flex items-center gap-2">
                                                <span :class="statusIndicatorClasses(prescription.active)" />
                                                <p :class="statusBadgeClasses(prescription.active)">
                                                    <HandThumbDown v-if="prescription.active == 0" />
                                                    <HandThumbUp v-else />
                                                </p>
                                            </div>
                                        </td>
                                        <td class="p-4 items-center">
                                            <p @click="openModal(prescription)" class="text-blue-500 cursor-pointer">
                                                Abrir
                                            </p>
                                        </td>

                                    </tr>

                                </tbody>
                            </table>
                            <div v-if="!prescriptions.data.length"
                                class="text-center text-gray-500 dark:text-gray-400 py-4 w-full">
                                No hay registros disponibles.
                            </div>
                        </div>
                        <Pagination :pagination="prescriptions" :filters="form" />
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <Modal :show="showModal" @close="showModal = false" :max-width="'3xl'">
                <div class="text-gray-800 p-5 ">
                    <h2 class="text-2xl font-semibold  p-4 text-blue-500 ">
                        Detalles de la Receta
                    </h2>

                    <div v-if="selectedprescription" class="space-y-3 p-4 ">
                        <div class="flex items-center gap-2">
                            <span class="font-medium text-gray-500 dark:text-gray-200 w-30">Paciente:</span>
                            <Link :href="route('patients.show', selectedprescription.patient.id)" class="text-blue-500">
                            {{
                                selectedprescription.patient.first_name }} {{
                                selectedprescription.patient.last_name }}
                            </Link>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="font-medium text-gray-500 dark:text-gray-200 w-30">Motivo de última
                                Consulta:</span>
                            <span class="text-gray-900 dark:text-gray-300">{{ selectedprescription.patient.motive
                                }}</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="font-medium text-gray-500 dark:text-gray-200 w-30">Doctor:</span>
                            <Link :href="route('users.show', selectedprescription.doctor.id)" class="text-blue-500">
                            {{ selectedprescription.doctor.name }} {{
                                selectedprescription.doctor.last_name }}</Link>
                        </div>
                        <div class="max-h-[70vh] overflow-y-auto space-y-4 px-2">
                            <div v-for="(details, index) in selectedprescription.prescriptions_details"
                                :key="details.id"
                                class="max-w-lg mx-auto border rounded-xl bg-white dark:bg-gray-900 shadow-lg overflow-hidden ">
                                <!-- Encabezado clickeable -->
                                <button @click="toggleDetail(index)"
                                    class="w-full text-left p-4 flex justify-between items-center border dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                                    <h3 class="text-lg font-bold text-blue-600 dark:text-blue-400">
                                        {{ details.drugs.name }}
                                    </h3>

                                </button>

                                <!-- Contenido expandible -->
                                <transition name="fade">
                                    <div v-show="expandedIndexes.includes(index)"
                                        class="p-6 space-y-3 max-h-80 overflow-y-auto">
                                        <div class="space-y-1">
                                            <p>
                                                <span
                                                    class="font-semibold text-gray-600 dark:text-gray-300">Instrucciones:
                                                </span>
                                                <span class="text-gray-800 dark:text-gray-200">{{ details.description
                                                    }}</span>
                                            </p>

                                            <template v-if="details.drugs">

                                                <p>
                                                    <span
                                                        class="font-semibold text-gray-600 dark:text-gray-300">Detalles
                                                        del medicamento: </span>
                                                    <span class="italic text-gray-600 dark:text-gray-400">{{
                                                        details.drugs.description }}</span>
                                                </p>
                                            </template>

                                            <p v-else class="italic text-gray-400">No hay medicamento asignado</p>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold text-gray-600 dark:text-gray-300">Activo:</span>
                                            <span>
                                                <span v-if="details.active"
                                                    class="text-green-600 font-semibold">Sí</span>
                                                <span v-else class="text-red-600 font-semibold">No</span>
                                            </span>
                                        </div>

                                        <div class="text-sm text-gray-400 dark:text-gray-500 italic">
                                            Creado: {{ new Date(details.created_at).toLocaleString() }}
                                        </div>
                                    </div>
                                </transition>
                            </div>
                        </div>

                    </div>

                    <div class=" flex  gap-2 ">
                        <button @click="print"
                            class=" ml-auto flex justify-center mt-2 py-3 gap-2 rounded-lg bg-green-500 px-2  text-sm font-semibold leading-6 text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-500 sm:px-4">
                            <PrintIcon />
                        </button>
                        <Link v-if="selectedprescription.active"
                            :href="route('odontographs.edit', selectedprescription)"
                            class="flex   mt-2  gap-2 rounded-lg bg-yellow-500 px-2 py-3 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 sm:px-4">
                        <EditIcon />
                        </Link>
                        <DangerButton v-if="selectedprescription.active"
                            @click="deleteprescription(selectedprescription)"
                            class="flex  mt-2  gap-2 rounded-lg bg-red-500 px-2 py-3 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 sm:px-4">
                            <DeleteIcon />
                        </DangerButton>
                        <button v-else @click="restoreprescription(selectedprescription)"
                            class="flex mt-2  gap-2 rounded-lg bg-green-500 px-2 py-3 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 sm:px-4">
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
import {
    Head,
    Link
} from '@inertiajs/vue3';
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
import {
    ref
} from 'vue';
import {
    markRaw
} from 'vue'
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TableIcon from '@/Components/Icons/TableIcon.vue';
import EditIcon from '@/Components/Icons/EditIcon.vue';
import RestoreIcon from '@/Components/Icons/RestoreIcon.vue';
import RXIcon from '@/Components/Icons/RXIcon.vue';
import PrintIcon from '@/Components/Icons/PrintIcon.vue';

export default {

    props: {
        prescriptions: Object,
        filters: Object,
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
        RestoreIcon,
        RXIcon,
        PrintIcon

    },
    watch: {
        selectedOptions() {
            this.fetchFilteredprescriptions();
        },

    },
    data() {
        return {
            form: {
                search: this.filters?.search || '',
                sortField: this.filters?.sortField || 'prescriptions.updated_at',
                sortDirection: this.filters?.sortDirection || 'asc',
                patient_id: this.filters?.patient_id || 0,
                lastDays: this.filters?.lastDays || '1',
                showDeleted: this.filters?.showDeleted || true,
            },
            expandedIndexes: [],
            showModal: ref(false),
            selectedprescription: ref(null),
            timeout: 3000,
            crumbs: [{
                icon: markRaw(RXIcon),
                label: 'Recetas',
                to: route('prescriptions.index')
            },
            {
                icon: markRaw(TableIcon),
                label: 'Listado'
            }
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
        dateFromNow(dateInput, past) {
            const date = new Date(dateInput);
            const now = new Date();
            const diff = (date - now) / 1000;
            const isPast = diff < 0;

            const units = [{
                name: 'año',
                seconds: 31536000
            },
            {
                name: 'mes',
                seconds: 2592000
            },
            {
                name: 'día',
                seconds: 86400
            },
            {
                name: 'hora',
                seconds: 3600
            },
            {
                name: 'minuto',
                seconds: 60
            },
            {
                name: 'segundo',
                seconds: 1
            },
            ];

            for (const unit of units) {
                const amount = Math.floor(Math.abs(diff) / unit.seconds);
                if (amount >= 1) {
                    const plural = amount > 1 ? 's' : '';
                    return isPast ?
                        `Venció hace ${amount} ${unit.name}${plural}` :
                        `Vence en ${amount} ${unit.name}${plural}`;
                }
            }

            return isPast ? 'Venció hace unos segundos' : 'Vence en unos segundos';
        },

        openModal(prescription) {
            this.selectedprescription = prescription;
            this.showModal = true;
        },
        Attendprescription() {
            this.selectedprescription.attended = !this.selectedprescription.attended;
            this.$inertia.put(route('prescriptions.update', this.selectedprescription.id), {
                attended: this.selectedprescription.attended
            },);
        },
        deleteprescription(id) {
            this.$inertia.delete(route('prescriptions.destroy', id),);
        },
        restoreprescription(id) {
            this.$inertia.put(route('prescriptions.update', id), {
                active: true
            },);
        },
        toggleDetail(index) {
            const i = this.expandedIndexes.indexOf(index);
            if (i > -1) {
                this.expandedIndexes.splice(i, 1);
            } else {
                this.expandedIndexes.push(index);
            }
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

                    this.$inertia.get(route('prescriptions.index'), this.form, {
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

        },
        async print() {
            window.open(route('report.prescription', {
                prescription: this.selectedprescription
            }), '_blank');
        }

    }
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: all 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    max-height: 0;
}

.fade-enter-to,
.fade-leave-from {
    opacity: 1;
    max-height: 20rem;

}
</style>
