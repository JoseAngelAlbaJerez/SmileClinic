<template>

    <Head title="Recibos" />
    <AuthenticatedLayout>
        <template #header>
            <Breadcrumb :crumbs="crumbs" />

        </template>

        <template #default>
            <div
                class="flex items-center justify-center rounded-lg bg-white-500 py-12 dark:bg-gray-900 dark:text-white">
                <div class="container mx-auto w-full px-2">

                    <!-- Search & Actions -->
                    <div class="my-2 flex flex-col sm:flex-row lg:mx-10 gap-2 items-stretch sm:items-center">
                        <LastDaysFilter v-model="filters.lastDays" @change="submitFilters()" />
                        <AccessGate permission="CXC.delete">
                            <button @click="toggleShowDeleted()"
                                :class="form.showDeleted ? 'bg-red-500 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 ' : 'bg-gray-500 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600 '"
                                class="lg:hidden flex justify-center rounded-lg   px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm sm:px-4">
                                <DeleteIcon /> {{ form.showDeleted ? 'Mostrar Eliminados' : 'Ocultar Eliminados' }}
                            </button>
                        </AccessGate>
                        <!-- Print -->
                        <button @click="showReport = true"
                            class="flex justify-center gap-2 rounded-lg bg-green-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <PrintIcon />
                        </button>
                        <ReportModal :open="showReport" @close="showReport = false" table="CXC" />

                        <!-- Spacer pushes actions right -->
                        <div class="flex flex-1 sm:flex-none sm:ml-auto items-center gap-2">
                            <input @input="submitFilters()" v-model="filters.search" type="text" placeholder="Buscar"
                                class="w-full sm:w-64 lg:w-96 rounded-lg border-0 px-3 py-2 shadow-sm ring-1 ring-slate-300 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:bg-gray-800 dark:ring-slate-600" />

                            <Link :href="route('payments.create')" as="button"
                                class="flex justify-center gap-2 rounded-lg bg-pink-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-pink-600 focus:outline-none focus:ring-2 focus:ring-pink-500">
                            <AddIcon class="size-5" />
                            <span class="hidden sm:inline">Nuevo Recibo</span>
                            </Link>
                        </div>
                    </div>

                    <!-- Table (Desktop) -->
                    <div
                        class="hidden lg:block relative overflow-x-auto border border-gray-200 dark:border-gray-700/60 rounded-lg my-4 mx-4 lg:mx-10">
                        <div class="min-w-full overflow-x-auto">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs uppercase bg-pink-500 text-white dark:bg-gray-800 dark:text-gray-200">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 cursor-pointer whitespace-nowrap"
                                            @click="sort('id')">
                                            # <span v-if="form.sortField === 'id'">{{ form.sortDirection === 'asc' ? '↑'
                                                : '↓' }}</span>
                                        </th>
                                        <th scope="col" class="px-6 py-3 cursor-pointer" @click="sort('patient_id')">
                                            Paciente <span v-if="form.sortField === 'patient_id'">{{ form.sortDirection
                                                === 'asc' ? '↑' : '↓' }}</span>
                                        </th>
                                        <th scope="col " class="cursor-pointer " @click="sort('branch_id')">
                                            Balance
                                            <span v-if="form.sortField === 'branch_id'">
                                                {{ form.sortDirection === 'asc' ? '↑' : '↓' }}
                                            </span>
                                        </th>

                                        <th scope="col" class="cursor-pointer" @click="sort('created_at')">
                                            Fecha de Creación <span v-if="form.sortField === 'created_at'">{{
                                                form.sortDirection
                                                    === 'asc' ? '↑' : '↓' }}</span>
                                        </th>

                                        <th class="cursor-pointer text-nowrap p-4">
                                            <div class="flex items-center justify-between" @click="toggleShowDeleted()">
                                                <div class="flex items-center gap-1">
                                                    <h2>Estado</h2>
                                                    <FunnelIcon />
                                                </div>
                                            </div>
                                        </th>
                                        <th scope="col" class="sm:p-4">Acciones</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr v-for="CXCs in CXC.data" :key="CXCs.id">
                                        <td class="p-4">{{ CXCs.id }}</td>
                                        <td class="p-4 text-pink-600">
                                            <Link :href="route('patients.show', CXCs.patient)">{{
                                                CXCs.patient.first_name }} {{ CXCs.patient.last_name }}</Link>
                                        </td>
                                        <td class="p-4">
                                            {{ new
                                                Intl.NumberFormat('es-DO', {
                                                    style:
                                                        'currency', currency: 'DOP'
                                                }).format(CXCs.balance
                                                    || 0) }}
                                        </td>
                                        <td class="p-4">{{ formatDate(CXCs.created_at) }}</td>

                                        <td class="p-4">
                                            <div class="flex items-center gap-2">
                                                <span :class="statusIndicatorClasses(CXCs.active)" />
                                                <p :class="statusBadgeClasses(CXCs.active)">
                                                    <HandThumbDown v-if="CXCs.active == 0" />
                                                    <HandThumbUp v-else />
                                                </p>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <Link :href="route('CXC.show', CXCs)" class="text-pink-500 cursor-pointer">
                                            Abrir</Link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div v-if="!CXC.data.length"
                                class="text-center text-gray-500 dark:text-gray-400 py-4 w-full">
                                No hay registros disponibles.
                            </div>
                        </div>
                        <Pagination :pagination="CXC" :filters="form" />
                    </div>

                    <!-- Card Layout (Mobile) -->
                    <div class="lg:hidden grid gap-3 my-4 mx-2">
                        <div v-for="CXCs in CXC.data" :key="CXCs.id"
                            class="border rounded-lg bg-white dark:bg-gray-800 p-4 shadow-sm dark:border-gray-700">
                            <div class="flex justify-between items-center">
                                <h3 class="font-semibold text-pink-600">
                                    <Link :href="route('patients.show', CXCs.patient)">{{ CXCs.patient.first_name }} {{
                                        CXCs.patient.last_name }}</Link>
                                </h3>
                                <Link :href="route('CXC.show', CXCs)" class="text-pink-500 text-sm">Abrir</Link>
                            </div>
                            <p class="text-sm text-gray-700 dark:text-gray-300 font-medium mt-1">
                                #{{ CXCs.id }}
                            </p>
                            <div class="mt-2 grid grid-cols-2 gap-y-1 text-sm">
                                <p><span class="font-medium">Procedimientos:</span>
                                <ul class="list-disc ml-4 ">

                                    <template v-for="detail in CXCs.bill">
                                        <li>
                                            {{ detail.procedure.name }}
                                        </li>
                                    </template>

                                </ul>
                                </p>
                                <p><span class="font-medium">Total:</span> {{ new
                                    Intl.NumberFormat('es-DO', {
                                        style: 'currency', currency: 'DOP'
                                    }).format(CXC.total
                                        ||
                                        0) }}</p>
                                <p><span class="font-medium">Creado:</span> {{ formatDate(CXCs.created_at) }}</p>

                                <p class="flex items-center gap-1">
                                    <span class="font-medium">Estado:</span>
                                    <span :class="statusBadgeClasses(CXCs.active)">
                                        <HandThumbDown v-if="CXCs.active == 0" />
                                        <HandThumbUp v-else />
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div v-if="!CXC.data.length" class="text-center text-gray-500 dark:text-gray-400 py-4 w-full">
                            No hay registros disponibles.
                        </div>
                        <Pagination :pagination="CXC" :filters="form" />
                    </div>
                </div>
            </div>


        </template>






    </AuthenticatedLayout>

</template>


<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import FunnelIcon from '@/Components/Icons/FunnelIcon.vue';
import HandThumbUp from '@/Components/Icons/HandThumbUp.vue';
import HandThumbDown from '@/Components/Icons/HandThumbDown.vue';
import AddIcon from '@/Components/Icons/AddIcon.vue';
import LastDaysFilter from '@/Components/LastDaysFilter.vue';
import Pagination from '@/Components/Pagination.vue';
import Breadcrumb from '@/Components/BreadCrumb.vue';
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';
import { markRaw } from 'vue'
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TableIcon from '@/Components/Icons/TableIcon.vue';
import CartIcon from '@/Components/Icons/CartIcon.vue';
import UserIcon from '@/Components/Icons/UserIcon.vue';
import DocumentMoney from '@/Components/Icons/DocumentMoney.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import PrintIcon from '@/Components/Icons/PrintIcon.vue';
import AccessGate from '@/Components/AccessGate.vue';
import ReportModal from '@/Components/ReportModal.vue';
import DeleteIcon from '@/Components/Icons/DeleteIcon.vue';
export default {

    props: {
        CXC: Object,
        filters: Object,
        errors: [Array, Object],
    },
    components: {
        Head,
        Modal,
        FunnelIcon,
        HandThumbDown,
        HandThumbUp,
        Pagination,
        Breadcrumb,
        LastDaysFilter,
        Breadcrumb,
        TableIcon,
        CartIcon,
        AddIcon,
        UserIcon,
        DocumentMoney,
        AuthenticatedLayout,
        PrimaryButton,
        SecondaryButton,
        Link,
        PrintIcon,
        AccessGate,
        ReportModal,
        DeleteIcon


    },

    data() {
        return {
            form: {
                search: this.filters?.search || '',
                sortField: this.filters?.sortField || 'CXC.updated_at',
                sortDirection: this.filters?.sortDirection || 'asc',
                lastDays: this.filters?.lastDays || '1',
                showDeleted: this.filters?.showDeleted || true,
            },
            timeout: 3000,
            crumbs: [
                { icon: markRaw(DocumentMoney), label: 'Recibos', to: route('CXC.index') },
                { icon: markRaw(TableIcon), label: 'Listado' }
            ],
            showModal: ref(false),
            form_modal: useForm({
                description: '',
                amount: '',
            }),
            error: '',
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
        formatNumber(n) {
            return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },

        submit() {
            if (!this.form_modal.description) {
                this.error = 'Por favor, ingrese la descripción del egreso.';
                return;
            }
            if (!this.form_modal.amount) {
                this.error = 'Por favor, ingrese el monto del egreso.';
                return;
            }
            if (!this.form_modal.amount < 0) {
                this.error = 'El monto debe ser mayor a 0.';
                return;
            }

            this.error = null;
            this.showModal = false;
            this.form_modal.post(route('CXC.store'),);

        },
        openModal() {


            this.showModal = true;
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
                    this.$inertia.get(route('CXC.index'), this.form, {
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
            window.open(route('report.CXC', {
                Days: this.filters.lastDays
            }), '_blank');
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
