<template>

    <Head title="Facturas" />
    <AuthenticatedLayout>
        <template #header>
            <Breadcrumb :crumbs="crumbs" />

        </template>

        <template #default>
            <div
                class="flex items-center justify-center rounded-lg bg-white-500 py-6 sm:py-12 dark:bg-gray-900 dark:text-white">
                <div class="container mx-auto w-full px-2">
                    <!-- Search & Exports -->
                    <div class="my-2 flex flex-col sm:flex-row lg:mx-10 gap-2 sm:items-center">
                        <LastDaysFilter v-model="filters.lastDays" @change="submitFilters()" />
                        <button @click="print()"
                            class="flex justify-center gap-2 rounded-lg bg-green-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 sm:px-4">
                            <PrintIcon />
                        </button>

                        <div class="flex flex-col sm:flex-row sm:ml-auto gap-2 w-full sm:w-auto">
                            <input @input="submitFilters()" v-model="filters.search" type="text" placeholder="Buscar"
                                class="rounded-lg border-0 w-full sm:w-72 lg:w-96 p-2 shadow-sm ring-1 ring-slate-300 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:ring-slate-600" />
                            <Link :href="route('bills.create')" as="button"
                                class="flex justify-center gap-2 rounded-lg bg-blue-500 px-3 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 sm:px-4">
                            <AddIcon class="size-5 sm:size-6" />
                            <span class="hidden sm:inline">Nueva Factura</span>
                            </Link>
                        </div>
                    </div>

                    <!-- Mobile Cards (only on xs) -->
                    <div class="sm:hidden space-y-4 mt-4">
                        <div v-for="bill in bills.data" :key="bill.id"
                            class="border rounded-lg p-4 bg-white dark:bg-gray-800 shadow">
                            <p><strong>#:</strong> {{ bill.id }}</p>
                            <p><strong>Paciente:</strong> {{ bill.patient.first_name }} {{ bill.patient.last_name }}</p>
                            <p><strong>Tipo:</strong> {{ bill.type }}</p>
                            <p><strong>Total:</strong> {{ new Intl.NumberFormat('es-DO', {
                                style: 'currency', currency:
                                    'DOP' }).format(bill.total || 0) }}</p>
                            <p><strong>Creación:</strong> {{ formatDate(bill.created_at) }}</p>
                            <p><strong>Estado:</strong>
                                <span :class="statusBadgeClasses(bill.active)">
                                    {{ bill.active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </p>
                            <Link :href="route('bills.show', bill)" class="text-blue-500 mt-2 inline-block">Abrir</Link>
                        </div>
                    </div>

                    <!-- Desktop Table (unchanged, hidden on xs) -->
                    <div
                        class="hidden sm:block relative overflow-x-auto border border-gray-200 dark:border-gray-700/60 rounded-lg my-4 mx-4 lg:mx-10">
                        <div class="min-w-full overflow-x-auto">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs uppercase bg-blue-500 text-white dark:bg-gray-800 dark:text-gray-200">
                                    <tr>
                                        <th scope="col"
                                            class="px-4 py-3 cursor-pointer whitespace-nowrap hidden sm:table-cell"
                                            @click="sort('id')">
                                            # <span v-if="form.sortField === 'id'">{{ form.sortDirection === 'asc' ? '↑'
                                                : '↓' }}</span>
                                        </th>
                                        <th scope="col" class="px-6 py-3 cursor-pointer" @click="sort('patient_id')">
                                            Paciente <span v-if="form.sortField === 'patient_id'">{{ form.sortDirection
                                                === 'asc' ? '↑' : '↓' }}</span>
                                        </th>
                                        <th scope="col" class="cursor-pointer" @click="sort('type')">
                                            Tipo: <span v-if="form.sortField === 'type'">{{ form.sortDirection === 'asc'
                                                ? '↑' : '↓' }}</span>
                                        </th>
                                        <th scope="col" class="cursor-pointer" @click="sort('total')">
                                            Total <span v-if="form.sortField === 'total'">{{ form.sortDirection ===
                                                'asc' ? '↑' : '↓' }}</span>
                                        </th>
                                        <th scope="col" class="cursor-pointer" @click="sort('created_at')">
                                            Fecha Creación <span v-if="form.sortField === 'created_at'">{{
                                                form.sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                        </th>
                                        <th class="cursor-pointer text-nowrap p-4">
                                            <div class="flex items-center justify-between" @click="toggleShowDeleted()">
                                                <div class="flex items-center">
                                                    <h2>Estado</h2>
                                                    <FunnelIcon />
                                                </div>
                                            </div>
                                        </th>
                                        <th scope="col" class="sm:p-4">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="bill in bills.data" :key="bill.id">
                                        <td class="p-4 items-center">{{ bill.id }}</td>
                                        <td class="p-4 items-center">{{ bill.patient.first_name }} {{
                                            bill.patient.last_name }}</td>
                                        <td class="p-4 items-center">{{ bill.type }}</td>
                                        <td class="p-4 items-center">{{ new Intl.NumberFormat('es-DO', {
                                            style:
                                                'currency', currency: 'DOP' }).format(bill.total || 0) }}</td>
                                        <td class="p-4 items-center">{{ formatDate(bill.created_at) }}</td>
                                        <td class="p-4 items-center">
                                            <div class="flex items-center gap-2">
                                                <span :class="statusIndicatorClasses(bill.active)" />
                                                <p :class="statusBadgeClasses(bill.active)">
                                                    <HandThumbDown v-if="bill.active == 0" />
                                                    <HandThumbUp v-else />
                                                </p>
                                            </div>
                                        </td>
                                        <td class="p-4 items-center">
                                            <Link :href="route('bills.show', bill)"
                                                class="text-blue-500 cursor-pointer">Abrir</Link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div v-if="!bills.data.length"
                                class="text-center text-gray-500 dark:text-gray-400 py-4 w-full">
                                No hay registros disponibles.
                            </div>
                        </div>
                        <Pagination :pagination="bills" :filters="form" />
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
export default {

    props: {
        bills: Object,
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
        PrintIcon


    },

    data() {
        return {
            form: {
                search: this.filters?.search || '',
                sortField: this.filters?.sortField || 'bills.updated_at',
                sortDirection: this.filters?.sortDirection || 'asc',

                lastDays: this.filters?.lastDays || '1',
                showDeleted: this.filters?.showDeleted || true,
            },
            timeout: 3000,
            crumbs: [
                { icon: markRaw(DocumentMoney), label: 'Facturas', to: route('bills.index') },
                { icon: markRaw(TableIcon), label: 'Listado' }
            ],
            showModal: ref(false),
            form_modal: useForm({
                description: '',
                amount: '',
            }),
            error: '',

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
            this.form_modal.post(route('bills.store'),);

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
                    this.$inertia.get(route('bills.index'), this.form, {
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
            window.open(route('report.bills', {
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
