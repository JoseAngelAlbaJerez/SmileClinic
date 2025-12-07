<template>

    <Head title="Odontogramas" />
    <AuthenticatedLayout>
        <template #header>
            <Breadcrumb :crumbs="crumbs" />

        </template>

        <template #default>
            <div
                class="flex items-center justify-center rounded-lg bg-white-500 py-6 sm:py-12 dark:bg-gray-900 dark:text-white">
                <div class="container mx-auto w-full px-2">
                    <!-- Search & Exports -->
                    <div class="my-2 flex flex-col sm:flex-row mx-4 lg:mx-10 gap-2 sm:items-center">
                        <LastDaysFilter v-model="filters.lastDays" @change="submitFilters()" />
                          <AccessGate permission="odontograph.delete">
                            <button @click="toggleShowDeleted()"
                                :class="form.showDeleted ? 'bg-red-500 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 ' : 'bg-gray-500 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600 '"
                                class="lg:hidden flex justify-center rounded-lg   px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm sm:px-4">
                                <DeleteIcon /> {{ form.showDeleted ? 'Mostrar Eliminados' : 'Ocultar Eliminados' }}
                            </button>
                        </AccessGate>
                        <button @click="showReport = true"
                            class="flex justify-center gap-2 rounded-lg bg-green-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 sm:px-4">
                            <PrintIcon />
                        </button>
                        <ReportModal :open="showReport" @close="showReport = false" table="odontographs" />

                        <!-- Search + Button -->
                        <div class="flex flex-col sm:flex-row sm:ml-auto gap-2 w-full sm:w-auto">
                            <input @input="submitFilters()" v-model="filters.search" type="text" placeholder="Buscar"
                                class="rounded-lg border-0 w-full sm:w-72 lg:w-96 p-2 shadow-sm ring-1 ring-slate-300 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:bg-gray-800 dark:ring-slate-600" />
                            <AccessGate :permission="'odontograph.create'">
                                <Link :href="route('odontographs.create')" as="button"
                                    class="flex justify-center gap-2 rounded-lg bg-pink-500 px-3 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-pink-600 focus:outline-none focus:ring-2 focus:ring-pink-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-pink-500">
                                <AddIcon class="size-5 sm:size-6" />
                                <span class="hidden sm:inline">Nuevo Odontograma</span>
                                </Link>
                            </AccessGate>
                        </div>
                    </div>

                    <!-- Table -->
                    <div
                        class="hidden lg:block relative overflow-x-auto border border-gray-200 dark:border-gray-700/60 rounded-lg my-4 mx-2 sm:mx-4 lg:mx-10">
                        <div class="overflow-x-auto w-full">
                            <table class="min-w-[600px] w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs uppercase bg-pink-500 text-white dark:bg-gray-800 dark:text-gray-200">
                                    <tr>
                                        <th scope="col"
                                            class="px-4 py-3 cursor-pointer whitespace-nowrap hidden md:table-cell"
                                            @click="sort('id')">
                                            #
                                            <span v-if="form.sortField === 'id'">
                                                {{ form.sortDirection === 'asc' ? '↑' : '↓' }}
                                            </span>
                                        </th>
                                        <th scope="col" class="px-4 py-3 cursor-pointer" @click="sort('first_name')">
                                            Paciente
                                            <span v-if="form.sortField === 'first_name'">
                                                {{ form.sortDirection === 'asc' ? '↑' : '↓' }}
                                            </span>
                                        </th>
                                        <th scope="col" class="px-4 py-3 cursor-pointer" @click="sort('first_name')">
                                            Doctor
                                            <span v-if="form.sortField === 'first_name'">
                                                {{ form.sortDirection === 'asc' ? '↑' : '↓' }}
                                            </span>
                                        </th>
                                        <th scope="col" class="px-4 py-3 cursor-pointer" @click="sort('first_name')">
                                            Diagnóstico
                                            <span v-if="form.sortField === 'first_name'">
                                                {{ form.sortDirection === 'asc' ? '↑' : '↓' }}
                                            </span>
                                        </th>
                                        <th scope="col" class="px-4 py-3 cursor-pointer hidden sm:table-cell"
                                            @click="sort('date_of_birth')">
                                            Fecha de Creación
                                            <span v-if="form.sortField === 'date_of_birth'">
                                                {{ form.sortDirection === 'asc' ? '↑' : '↓' }}
                                            </span>
                                        </th>

                                        <th class="cursor-pointer text-nowrap p-4">
                                            <div class="flex items-center justify-between" @click="toggleShowDeleted()">
                                                <div class="flex items-center gap-1">
                                                    <h2>Estado</h2>
                                                    <FunnelIcon />
                                                </div>
                                            </div>
                                        </th>
                                        <th scope="col" class="px-4 py-3">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="odontograph in odontographs.data" :key="odontograph.id">
                                        <td class="p-4 hidden md:table-cell">{{ odontograph.id }}</td>
                                        <td class="p-4 text-pink-600"><Link :href="route('patients.show',odontograph.patient)"> {{ odontograph.patient.first_name }} {{
                                            odontograph.patient.last_name }}</Link></td>
                                        <td class="p-4">{{ odontograph.doctor.first_name }} {{
                                            odontograph.doctor.last_name }}</td>
                                        <td class="p-4">
                                            <ul class="list-disc ">

                                                <template v-for="(surfaces, tooth) in odontograph.data" :key="tooth">
                                                    <li v-if="surfaces && Object.keys(surfaces).length > 0">
                                                        <span v-for="(procedure, surface) in surfaces" :key="surface">
                                                            Pieza {{ tooth }} — {{ surface }}: {{ procedure }}
                                                        </span>

                                                    </li>

                                                </template>

                                            </ul>
                                        </td>




                                        <td class="p-4 hidden lg:table-cell">{{ formatDate(odontograph.created_at) }}
                                        </td>

                                        <td class="p-4">
                                            <div class="flex items-center gap-2">
                                                <span :class="statusIndicatorClasses(odontograph.active)" />
                                                <p :class="statusBadgeClasses(odontograph.active)">
                                                    <HandThumbDown v-if="odontograph.active == 0" />
                                                    <HandThumbUp v-else />
                                                </p>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <Link :href="route('odontographs.show', odontograph.id)"
                                                class="text-pink-500">Abrir
                                            </Link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div v-if="!odontographs.data.length"
                                class="text-center text-gray-500 dark:text-gray-400 py-4 w-full">
                                No hay registros disponibles.
                            </div>
                        </div>
                        <Pagination :pagination="odontographs" :filters="form" />
                    </div>
                    <!-- Card Layout (Mobile) -->
                    <div class="lg:hidden grid gap-3 my-4 mx-2">
                        <div v-for="odontograph in odontographs.data" :key="odontograph.id"
                            class="border rounded-lg bg-white  p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                            <div class="flex justify-between items-center">
                                <h3 class="font-semibold text-pink-600">
                                <Link :href="route('patients.show',odontograph.patient)">
                                        {{ odontograph.patient.first_name }} {{ odontograph.patient.last_name }}
                                </Link>
                                    </h3>
                                <Link :href="route('odontographs.show', odontograph.id)" class="text-pink-500 text-sm">
                                Abrir
                                </Link>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">#{{ odontograph.id }}</p>
                            <div class="mt-2 grid grid-cols-2 gap-y-1 text-sm">

                                <p><span class="font-medium">Diagnóstico:</span>
                                <ul class="list-disc ml-4">

                                    <template v-for="(surfaces, tooth) in odontograph.data" :key="tooth">
                                        <li v-if="surfaces && Object.keys(surfaces).length > 0">
                                            <span v-for="(procedure, surface) in surfaces" :key="surface">
                                                Pieza {{ tooth }} — {{ surface }}: {{ procedure }}
                                            </span>

                                        </li>

                                    </template>

                                </ul>
                                </p>
                                  <p><span class="font-medium">Doctor:</span> {{ odontograph.doctor.first_name }} {{
                                    odontograph.doctor.last_name }}</p>
                                <p><span class="font-medium">Creado el:</span> {{ formatDate(odontograph.created_at) }}
                                </p>

                                <p class="flex items-center gap-1">
                                    <span class="font-medium">Estado:</span>
                                    <span :class="statusBadgeClasses(odontograph.active)">
                                        <HandThumbDown v-if="odontograph.active == 0" />
                                        <HandThumbUp v-else />
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div v-if="!odontographs.data.length"
                            class="text-center text-gray-500 dark:text-gray-400 py-4 w-full">
                            No hay registros disponibles.
                        </div>

                        <Pagination :pagination="odontographs" :filters="form" />
                    </div>
                </div>
            </div>
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
import UserIcon from '@/Components/Icons/UserIcon.vue';
import { markRaw } from 'vue';
import TableIcon from '@/Components/Icons/TableIcon.vue';
import AccessGate from '@/Components/AccessGate.vue';
import PrintIcon from '@/Components/Icons/PrintIcon.vue';
import ReportModal from '@/Components/ReportModal.vue';
import { ref } from 'vue';
import BuildingIcon from '@/Components/Icons/BuildingIcon.vue';
import TeethIcon from '@/Components/Icons/TeethIcon.vue';
import DeleteIcon from '@/Components/Icons/DeleteIcon.vue';
export default {


    props: {
        odontographs: Object,
        filters: Object
    },
    components: {
        AuthenticatedLayout,
        Head,
        Pagination,
        HandThumbUp,
        HandThumbDown,
        Breadcrumb,
        ArrowDownTrayIcon,
        LastDaysFilter,
        ArrowLongUpIcon,
        ArrowLongDownIcon,
        ChevronDoubleLeftIcon,
        ChevronDoubleRightIcon,
        ChevronLeftIcon,
        ChevronRightIcon,
        DocumentIcon,
        FunnelIcon,
        SearchIcon,
        Link,
        AddIcon,
        UserIcon,
        TableIcon,
        AccessGate,
        PrintIcon,
        ReportModal,
        TeethIcon,
        DeleteIcon

    },
    watch: {
        selectedOptions() {
            this.fetchFilteredbranches();
        },

    },
    data() {
        return {
            form: {
                search: this.filters?.search || '',
                sortField: this.filters?.sortField || 'odontographs.updated_at',
                sortDirection: this.filters?.sortDirection || 'asc',

                lastDays: this.filters?.lastDays || '',
                showDeleted: this.filters?.showDeleted || true,
            },
            timeout: 3000,
            crumbs: [
                { icon: markRaw(TeethIcon), label: 'Odontogramas', to: route('odontographs.index') },
                { icon: markRaw(TableIcon), label: 'Listado' }
            ],
            showReport: ref(false)

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

                this.form.lastDays = this.filters.lastDays || '30',
                    this.form.search = this.filters.search || '',



                    this.$inertia.get(route('branches.index'), this.form, {
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
