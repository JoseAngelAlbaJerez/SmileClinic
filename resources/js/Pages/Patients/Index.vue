<template>

    <Head title="Patients" />
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
                            <AccessGate permission="patient.create">
                                <Link :href="route('patients.create')" as="button"
                                    class="flex justify-center gap-2 rounded-lg bg-blue-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500 sm:px-4">
                                <AddIcon class="size-6" />
                                Nuevo Paciente
                                </Link>
                            </AccessGate>
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
                                        <th scope="col" class="px-6 py-3 cursor-pointer" @click="sort('first_name')">
                                            Nombre<span v-if="form.sortField === 'first_name'">{{ form.sortDirection ===
                                                'asc' ? '↑' :
                                                '↓'
                                                }}</span></th>
                                        <th scope="col" class=" cursor-pointer" @click="sort('date_of_birth')">
                                            Fecha de Nacimiento <span v-if="form.sortField === 'date_of_birth'">{{
                                                form.sortDirection ===
                                                    'asc' ?
                                                    '↑' :
                                                    '↓'
                                            }}</span>
                                        </th>

                                        <th scope="col" class="  cursor-pointer" @click="sort('ars')">ARS
                                            <span v-if="form.sortField === 'ars'">{{ form.sortDirection === 'asc' ?
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

                                    <tr v-for="patient in patients.data" :key="patient.id">
                                        <td class="p-4  items-center">{{ patient.id }}</td>
                                        <td class="p-4  items-center">{{ patient.first_name }} {{ patient.last_name }}
                                        </td>
                                        <td class="p-4  items-center">{{ patient.date_of_birth }}</td>
                                        <td class="p-4  items-center">{{ patient.ars }}</td>
                                        <td class="p-4  items-center">{{ formatDate(patient.created_at) }}</td>
                                        <td class="p-4  items-center">
                                            <div class="flex items-center gap-2">
                                                <span :class="statusIndicatorClasses(patient.active)" />
                                                <p :class="statusBadgeClasses(patient.active)">
                                                    <HandThumbDown v-if="patient.active == 0" />
                                                    <HandThumbUp v-else />
                                                </p>
                                            </div>
                                        </td>
                                        <td class="p-4 items-center">
                                            <Link :href="route('patients.show', patient.id)" class="text-blue-500">Abrir
                                            </Link>
                                        </td>

                                    </tr>



                                </tbody>
                            </table>
                            <div v-if="!patients.data.length"
                                class="text-center text-gray-500 dark:text-gray-400 py-4 w-full">
                                No hay registros disponibles.
                            </div>
                        </div>
                        <Pagination :pagination="patients" :filters="form" />
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
export default {


    props: {
        patients: Object,
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
        AccessGate

    },
    watch: {
        selectedOptions() {
            this.fetchFilteredPatients();
        },

    },
    data() {
        return {
            form: {
                search: this.filters?.search || '',
                sortField: this.filters?.sortField || 'patients.updated_at',
                sortDirection: this.filters?.sortDirection || 'asc',

                lastDays: this.filters?.lastDays || '',
                showDeleted: this.filters?.showDeleted || true,
            },
            timeout: 3000,
            crumbs: [
                { icon: markRaw(UserIcon), label: 'Pacientes', to: route('patients.index') },
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



                    this.$inertia.get(route('patients.index'), this.form, {
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
