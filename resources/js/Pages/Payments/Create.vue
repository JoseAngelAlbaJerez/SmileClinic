<template>

    <Head title="Facturas" />
    <AuthenticatedLayout>
        <template #header>
            <Breadcrumb :crumbs="crumbs" />
        </template>
        <template #default>
            <div class="min-h-full bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
                <div class="max-w-7xl mx-auto">
                    <!-- Form Card -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
                        <!-- Form Header with Gradient -->
                        <div class="bg-pink-500  dark:bg-pink-600 px-6 py-4">
                            <h2 class="text-xl font-bold text-white">Nuevo Recibo</h2>
                            <p class="text-pink-100 text-sm">Complete los detalles del documento</p>
                        </div>

                        <!-- Main Form Content -->
                        <div class="p-6 space-y-6" >
                            <!-- Client and Document Info -->

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Patient Selection -->
                                <div class="space-y-1">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Paciente <span class="text-red-500">*</span>
                                    </label>
                                    <button @click="openPatientModal()"
                                        class="flex items-center w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg hover:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white transition duration-200">
                                        <UserIcon class="h-5 w-5 text-gray-400 dark:text-gray-500 mr-2" />
                                        <span v-if="selected_patient" class="truncate">
                                            {{ selected_patient.first_name }} {{ selected_patient.last_name }}
                                        </span>
                                        <span v-else class="text-gray-400 dark:text-gray-400">Seleccione un paciente</span>
                                    </button>
                                    <p v-if="errors.patient" class="mt-1 text-xs text-red-600 dark:text-red-400">
                                        {{ errors.patient }}
                                    </p>
                                </div>

                                <!-- Emission Date -->
                                <div class="space-y-1">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Fecha Emisión <span class="text-red-500">*</span>
                                    </label>
                                    <VueDatePicker
                                        class="date-picker-custom border-gray-300 dark:border-gray-600 rounded-lg hover:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white transition duration-200"
                                        placeholder="Seleccione fecha" v-model="form.emission_date"
                                        :enable-time-picker="false" />
                                </div>

                            </div>
                            <div
                                class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-700 p-6 rounded-2xl shadow-lg mt-6 border border-gray-100 dark:border-gray-600">
                                <div class="flex items-center mb-6">
                                    <div class="flex items-center space-x-3">
                                        <div class="p-2 bg-pink-100 dark:bg-pink-900/30 rounded-lg">
                                            <svg class="w-6 h-6 text-pink-600 dark:text-pink-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                </path>
                                            </svg>
                                        </div>
                                        <h3 class="text-xl font-bold dark:text-white">Realizar Recibo</h3>
                                    </div>
                                </div>

                                <!-- Empty State -->
                                <div v-if="!selected_patient.bill" class="text-center py-8">
                                    <div
                                        class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-600 mb-4">
                                        <svg class="h-8 w-8 text-gray-400 dark:text-gray-300" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No hay recibos
                                        pendientes</h3>
                                    <p class="text-gray-500 dark:text-gray-400 mb-4">Agregue Facturas o seleccione un paciente para continuar</p>
                                    <p v-if="errors.procedures"
                                        class="text-sm text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 p-2 rounded-lg">
                                        {{ errors.procedures }}
                                    </p>
                                </div>

                                <!-- Payment Cards -->
                                <div v-else class="space-y-6">


                                    <p v-if="form.errors.length"
                                        class="p-3 text-sm text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 rounded-lg">
                                        {{ form.errors }}
                                    </p>

                                    <!-- Payment Form -->
                                    <form @submit.prevent="makePayment()"
                                        class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-sm">
                                        <div class="mb-4">
                                            <label for="paymentAmount"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Monto a pagar
                                            </label>
                                            <div class="relative">

                                                <input @input="calcPayment()" v-model="form.paymentAmount"
                                                    id="paymentAmount" type="number" min="1" step="0.01"
                                                    placeholder="0.00"
                                                    class=" mt-2 w-full border border-gray-300 dark:border-gray-600 rounded-lg p-3 focus:ring-2 focus:ring-pink-500 focus:border-transparent dark:bg-gray-700 dark:text-white" />
                                            </div>
                                        </div>
                                          <div class="text-left md:text-right">
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Balance restante:</p>
                                            <p class="text-lg font-bold text-pink-600 dark:text-pink-400">
                                                {{ new Intl.NumberFormat('es-DO', {
                                                    style: 'currency', currency: 'DOP'
                                                }).format(form.balance || 0) }}
                                            </p>
                                        </div>

                                        <button type="submit"
                                            :disabled="!form.paymentAmount || form.paymentAmount <= 0"
                                            class="mt-2 w-full bg-pink-500 text-white font-medium rounded-lg px-4 py-3 hover:from-pink-700 hover:to-pink-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 shadow-md hover:shadow-lg">
                                            <span class="flex items-center justify-center">
                                                Registrar Recibo
                                            </span>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Summary and Actions -->
                            <div
                                class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0 md:space-x-6">
                                <!-- Error Message -->
                                <div v-if="errors.length" class="flex-1">
                                    <div
                                        class="p-3 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                                        <div class="flex items-center text-red-600 dark:text-red-400">

                                            <span>{{ errors }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Total -->
                                <div v-if="form.total" class="text-lg font-semibold text-gray-900 dark:text-white">
                                    Total: <span class="text-pink-600 dark:text-pink-400">{{ new
                                        Intl.NumberFormat('es-DO', {
                                            style:
                                                'currency', currency: form.currency
                                        }).format(form.total
                                            || 0) }}</span>
                                </div>

                                <!-- Form Actions -->
                                <div class="flex space-x-3" v-if="!selected_patient.bill">
                                    <SecondaryButton type="button"
                                        @click="form.reset(); form_details = []; form_detail.reset(); selectedProcedures = []"
                                        class="hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-200">

                                        Limpiar
                                    </SecondaryButton>
                                    <PrimaryButton @click="submit()" :disabled="form.processing"
                                        :class="{ 'opacity-75': form.processing }"
                                        class="hover:bg-pink-600 transition duration-200">

                                        <span v-if="form.processing">Guardando...</span>
                                        <span v-else>Guardar </span>
                                    </PrimaryButton>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Bill Selection Modal -->
                <!-- <Modal :show="showBillModal" @close="showBillModal = false" maxWidth="2xl">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white">Seleccionar Presupuesto</h3>

                        <BillSelector v-if="selected_patient && selected_patient.Bill"
                            :Bills="selected_patient.Bill" @selected="selectBill = $event; addBill()" />

                        <p v-else class="text-gray-500 italic">
                            Este paciente no tiene presupuestos.
                        </p>
                    </div>
                </Modal> -->



                <!-- Patient Selection Modal -->
                <Modal :show="showPatientModal" @close="showPatientModal = false" maxWidth="2xl">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-800 dark:text-white">
                                <UserIcon class="h-6 w-6 inline-block mr-2 text-pink-500" />
                                Seleccionar Paciente
                            </h3>
                            <button @click="showPatientModal = false"
                                class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                <XIcon class="h-6 w-6" />
                            </button>
                        </div>
                        <PatientSelector :patients="patient" @selected="SelectPatient($event)" />
                    </div>
                </Modal>

            </div>
        </template>

    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Breadcrumb from '@/Components/BreadCrumb.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AddIcon from '@/Components/Icons/AddIcon.vue';
import UserIcon from '@/Components/Icons/UserIcon.vue';
import { markRaw, ref } from 'vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DocumentMoney from '@/Components/Icons/DocumentMoney.vue';
import Modal from '@/Components/Modal.vue';
import { Head, useForm } from '@inertiajs/vue3';
import DangerButton from '@/Components/DangerButton.vue';
import DeleteIcon from '@/Components/Icons/DeleteIcon.vue';
import DiscountInput from '@/Components/DiscountInput.vue';
import PatientSelector from '@/Components/PatientSelector.vue';
import ProcedureSelector from '@/Components/ProcedureSelector.vue';
import XIcon from '@/Components/Icons/XIcon.vue';
import DocumentIcon from '@/Components/Icons/DocumentIcon.vue';
import SearchIcon from '@/Components/Icons/SearchIcon.vue';
import BudgetSelector from '@/Pages/Budgets/BudgetSelector.vue';
import UserSelector from '@/Components/UserSelector.vue';
import CashIcon from '@/Components/Icons/CashIcon.vue';
import CreditCardIcon from '@/Components/Icons/CreditCardIcon.vue';

export default {
    props: {
        patient: Object,
        patients: Object,
        procedure: Object,
        doctors: Object,
        errors: [Array, Object],
    },
    components: {
        AuthenticatedLayout,
        Breadcrumb,
        PrimaryButton,
        AddIcon,
        UserIcon,
        VueDatePicker,
        SecondaryButton,
        DocumentMoney,
        Modal,
        DangerButton,
        DiscountInput,
        PatientSelector,
        ProcedureSelector,
        XIcon,
        DocumentIcon,
        SearchIcon,
        DeleteIcon,
        BudgetSelector,
        Head,
        UserSelector,
        CreditCardIcon
    },
    data() {
        return {

            form: useForm({
                paymentAmount: null,
                patient: {},
                balance: this.patients?.c_x_c?.balance || 0,
                emission_date: this.getDefaultDatetime(),
            }),
            bill_id: '',
            form_details: [],
            selected_patient: this.patients || '',
            selected_doctor: '',
            timeout: 3000,
            crumbs: [
                { icon: markRaw(CreditCardIcon), label: 'Recibos', to: route('CXC.index') },
                { icon: markRaw(AddIcon), label: 'Crear' },

            ],
            showDoctorModal: ref(false),
            showProcedureModal: ref(false),
            showBudgetModal: ref(false),
            showPatientModal: ref(false),
            error: '',
            selectedProcedureId: '',
            selectedBudgetId: '',
            selectBudget: {},
            selectedProcedures: [],

        };

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
        async makePayment() {
            if (!this.form.paymentAmount) {
                this.form.errors = 'Por favor, ingrese un monto.';
                return;
            }
            this.form.patient = this.selected_patient;
            this.form.post(route('payments.store'), {
                onSuccess: () => {
                    this.form.reset();
                    this.form.reset();
                }
            });
        },
        calcPayment() {
            const balanceBefore = parseFloat(this.selected_patient?.c_x_c?.balance || 0);
            const payingNow = parseFloat(this.form.paymentAmount || 0);
            this.form.balance = Math.max(balanceBefore - payingNow, 0);
        },




        openProcedureModal() {
            this.showProcedureModal = true;
        },
        openBudgetModal() {
            this.showBudgetModal = true;
        },
        openPatientModal() {
            this.showPatientModal = true;
        },
        openDoctorModal() {
            this.showDoctorModal = true;
        },

        formatNumber(n) {
            return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
        getDefaultDatetime() {
            const now = new Date();
            return now.toISOString().slice(0, 16);
        },
        SelectPatient(event) {

            this.form.patient_id = event.id;
            this.selected_patient = event;
            this.showPatientModal = false;

            if (event.c_x_c) {
                this.form.balance = event.c_x_c.balance
            }

        },




    },
};
</script>




<style scoped>
p span:first-child {
    color: #6b7280;
}
</style>
