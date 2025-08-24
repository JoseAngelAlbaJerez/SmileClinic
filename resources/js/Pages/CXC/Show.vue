<template>
     <Head title="Cuentas Por Cobrar" />
    <AuthenticatedLayout>
        <template #header>
            <BreadCrumb :crumbs="crumbs" />
        </template>
        <template #default>
            <div
                class="container mx-auto px-6 py-4  dark:text-white bg-white dark:bg-gray-700 mt-5  pb-8  rounded-2xl shadow-md">
                <div class="my-2 mt-5 flex  gap-2 items-center p-1">
                    <h2 class="text-lg font-semibold "> Cuenta de {{ CXC.patient.first_name }} {{
                        CXC.patient.last_name }}
                    </h2>
                    <div v-if="CXC.active" class=" flex ml-auto gap-2 mb-2 ">
                        <button @click="print()"
                            class="flex justify-center gap-2 rounded-lg bg-green-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-500 sm:px-4">
                            <PrintIcon /> Imprimir
                        </button>
                        <Link :href="route('CXC.edit', CXC.id)"
                            class="flex justify-center gap-2 rounded-lg bg-yellow-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-500 sm:px-4">
                        <EditIcon /> Editar
                        </Link>
                        <DangerButton @click="deleteCXC(CXC.id)"
                            class="flex justify-center gap-2 rounded-lg bg-red-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 sm:px-4">
                            <DeleteIcon /> Eliminar
                        </DangerButton>
                    </div>
                    <div class="flex ml-auto gap-2" v-else>
                        <PrimaryButton @click="restoreCXC(CXC.id)"
                            class="flex justify-center gap-2 rounded-lg bg-green-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-500 sm:px-4">
                            <RestoreIcon /> Restaurar
                        </PrimaryButton>
                    </div>
                </div>


              <!-- Presupuestos Section -->
<div class="bg-gradient-to-br from-gray-50 to-blue-50 dark:from-gray-800 dark:to-gray-900 p-6 rounded-2xl shadow-lg my-6 border border-gray-200 dark:border-gray-700">
    <!-- Section Header -->
    <div class="flex items-center gap-3 mb-6">
        <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
            <DocumentMoney class="w-6 h-6 text-blue-600 dark:text-blue-400" />
        </div>
        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">Presupuestos</h2>
    </div>

    <!-- Budgets List -->
    <div class="space-y-4">
        <div v-for="(budgets, index) in CXC.budget" :key="budgets.id" class="group">
            <!-- Budget Header (Clickable) -->
            <div
                @click="openAccordion(index)"
                class="flex flex-col md:flex-row md:items-center gap-4 p-4 bg-white dark:bg-gray-700 rounded-xl shadow-sm border border-gray-200 dark:border-gray-600 cursor-pointer transition-all duration-200 hover:shadow-md hover:border-blue-300 dark:hover:border-blue-500"
                :class="{
                    'opacity-70 border-red-200 dark:border-red-800': isExpired(budgets.emission_date) || !budgets.active
                }"
            >
                <div class="flex-1 grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="flex items-center gap-2">
                        <span class="font-medium text-gray-500 dark:text-gray-400">#ID:</span>
                        <span class="font-semibold">{{ budgets.id }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                        </svg>
                        <span>{{ budgets.emission_date }}</span>
                    </div>
                    <div v-if="budgets.type == 'Crédito'" class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                        </svg>
                        <span>{{ budgets.expiration_date }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
                        </svg>
                        <span class="font-bold text-blue-600 dark:text-blue-400">${{ formatNumber(budgets.total) }}</span>
                    </div>
                </div>

                <div class="flex items-center justify-end">
                    <ChevronDownIcon
                        class="w-5 h-5 text-gray-400 transition-transform duration-200 transform"
                        :class="{ 'rotate-180': activeIndex === index }"
                    />
                </div>
            </div>

            <!-- Budget Details (Accordion Content) -->
            <transition
                name="accordion"
                @enter="enter"
                @leave="leave"
            >
                <div v-if="activeIndex === index" class="mt-2 space-y-3">
                    <div
                        v-for="details in budgets.budgetdetail"
                        :key="details.id"
                        class="bg-white dark:bg-gray-700 p-4 rounded-xl shadow-sm border border-gray-200 dark:border-gray-600 transition-all duration-200 hover:shadow-md"
                        :class="{
                            'bg-red-50 dark:bg-red-900/30 border-red-200 dark:border-red-700': !details.active || !CXC.active
                        }"
                    >
                        <!-- Procedure Header -->
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                                {{ details.procedure.name }}
                                <span
                                    class="text-xs font-medium px-2 py-1 rounded-full"
                                    :class="details.procedure.coberture ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'"
                                >
                                    {{ details.procedure.coberture ? 'Asegurado' : 'No Asegurado' }}
                                </span>

                                <span :class="{
                    'opacity-70 bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': isExpired(budgets.emission_date)
                }"
                                    class="text-xs font-medium px-2 py-1 rounded-full"
                                 >
                                    {{ isExpired(budgets.emission_date) ? 'Vencido' : '' }}
                                </span>
                            </h3>

                        </div>

                        <!-- Procedure Details -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                            <div class="bg-gray-50 dark:bg-gray-800 p-3 rounded-lg">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Monto</p>
                                <p class="font-medium">${{ formatNumber(details.amount) }}</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-800 p-3 rounded-lg">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Descuento</p>
                                <p class="font-medium">{{ details.discount }}%</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-800 p-3 rounded-lg">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Cantidad</p>
                                <p class="font-medium">{{ details.quantity }}</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-800 p-3 rounded-lg">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Inicial</p>
                                <p class="font-medium">${{ formatNumber(details.initial) }}</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-800 p-3 rounded-lg">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Plan de Pagos</p>
                                <p class="font-medium">{{ details.amount_of_payments }} × ${{ formatNumber(details.total / details.amount_of_payments) }}</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-800 p-3 rounded-lg">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Estado</p>
                                <p
                                    class="font-medium"
                                    :class="getRemainingPayments(details) ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400'"
                                >
                                    {{ getRemainingPayments(details) ? `${getRemainingPayments(details)} pagos pendientes` : 'Completado' }}
                                </p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-wrap items-center justify-between gap-3 pt-4 border-t border-gray-200 dark:border-gray-600">
                            <p class="font-bold text-lg">
                                Total: ${{ formatNumber(details.total) }}
                            </p>

                            <div class="flex items-center gap-2">
                                <Link
                                    v-if="CXC.active"
                                    :href="route('budgets.show', budgets)"
                                    class="p-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors"
                                    title="Ver detalles"
                                >
                                    <EyeIcon class="w-5 h-5" />
                                </Link>

                                <button
                                    v-if="CXC.active && details.active && getRemainingPayments(details)"
                                    @click="showModal = true, selectedBudget = details, resetModal()"
                                    class="p-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors"
                                    title="Agregar pago"
                                >
                                    <AddIcon class="w-5 h-5" />
                                </button>

                                <DangerButton
                                    v-if="CXC.active && details.active"
                                    @click="deleteBudgetDetail(details.id)"
                                    class="p-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors"
                                    title="Eliminar"
                                >
                                    <DeleteIcon class="w-5 h-5" />
                                </DangerButton>

                                <button
                                    v-else
                                    @click="restoreBudgetDetail(details.id)"
                                    class="p-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors"
                                    title="Restaurar"
                                >
                                    <RestoreIcon class="w-5 h-5" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </div>
</div>


            </div>
           <!-- Payment Modal -->
<Modal :show="showModal" @close="showModal = false" class="max-w-4xl">
    <div class="p-0 h-full max-h-[80vh] flex flex-col">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-6 py-4 rounded-t-lg">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold text-white">Gestión de Pagos</h2>
                <button @click="showModal = false" class="text-white hover:text-blue-200 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div v-if="selectedBudget" class="mt-2">
                <p class="text-blue-100 font-medium">{{ selectedBudget.procedure.name }}</p>
            </div>
        </div>

        <!-- Modal Content -->
        <div class="p-6 overflow-y-auto flex-1">
            <div v-if="selectedBudget" class="space-y-4">
                <!-- Payment Cards -->
                <div v-for="(payment, index) in selectedBudget.payment" :key="payment.id"
                     class="border rounded-xl overflow-hidden transition-all duration-200"
                     :class="{
                         'border-green-200 dark:border-green-800 bg-green-50/50 dark:bg-green-900/20': payment.remaining_amount === 0,
                         'border-gray-200 dark:border-gray-700': payment.remaining_amount !== 0
                     }">
                    <!-- Payment Header -->
                    <div class="px-5 py-3 border-b flex items-center justify-between"
                         :class="{
                             'bg-green-100 dark:bg-green-900/30 border-green-200 dark:border-green-800': payment.remaining_amount === 0,
                             'bg-gray-100 dark:bg-gray-800 border-gray-200 dark:border-gray-700': payment.remaining_amount !== 0
                         }">
                        <h3 class="font-semibold flex items-center gap-2">
                            <span class="text-gray-700 dark:text-gray-300">Cuota #{{ index + 1 }}</span>
                            <span v-if="payment.remaining_amount === 0" class="text-green-600 dark:text-green-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </h3>
                        <span class="text-sm font-medium px-3 py-1 rounded-full"
                              :class="payment.remaining_amount === 0 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'">
                            {{ payment.remaining_amount === 0 ? 'Pagado' : 'Pendiente' }}
                        </span>
                    </div>

                    <!-- Payment Details -->
                    <div class="p-5 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Monto a Pagar <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">$</span>
                                <input @input="calcTotal(index)"
                                       v-model="form_details[index].amount_paid"
                                       :disabled="payment.remaining_amount === 0"
                                       type="number"
                                       min="0"
                                       :max="payment.remaining_amount"
                                       class="w-full pl-8 pr-3 py-2 border rounded-lg dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="0.00" />
                            </div>
                            <p v-if="form_details.errors && form_details.errors[index]" class="text-red-600 text-xs mt-1">
                                {{ form_details.errors[index] }}
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 dark:bg-gray-800 p-3 rounded-lg">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Total de la Cuota</p>
                                <p class="font-medium">${{ formatNumber(payment.total) }}</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-800 p-3 rounded-lg">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Restante</p>
                                <p class="font-medium" :class="payment.remaining_amount === 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
                                    ${{ formatNumber(payment.remaining_amount) }}
                                </p>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-2">
                            <button v-if="payment.remaining_amount !== 0"
                                    @click.prevent="payAll(index)"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium transition-colors flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>
                                Pagar Todo
                            </button>

                            <button v-else
                                    @click.prevent="revert(index)"
                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm font-medium transition-colors flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                Revertir Pago
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 rounded-b-lg flex justify-end gap-3">
            <SecondaryButton @click="showModal = false, resetModal()" class="px-6 py-2">
                Cancelar
            </SecondaryButton>
            <PrimaryButton @click="submit()" class="px-6 py-2">
                Guardar Cambios
            </PrimaryButton>
        </div>
    </div>
</Modal>



        </template>


    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import { Link, useForm } from '@inertiajs/vue3';
import EditIcon from '@/Components/Icons/EditIcon.vue';
import DeleteIcon from '@/Components/Icons/DeleteIcon.vue';
import PrintIcon from '@/Components/Icons/PrintIcon.vue';
import UserIcon from '@/Components/Icons/UserIcon.vue';
import DoctorIcon from '@/Components/Icons/DoctorIcon.vue';
import DocumentMoney from '@/Components/Icons/DocumentMoney.vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import DocumentIcon from '@/Components/Icons/DocumentIcon.vue';
import { markRaw, ref } from 'vue';
import BreadCrumb from '@/Components/BreadCrumb.vue';
import RestoreIcon from '@/Components/Icons/RestoreIcon.vue';
import ChevronDownIcon from '@/Components/Icons/ChevronDownIcon.vue';
import ChevronUpIcon from '@/Components/Icons/ChevronUpIcon.vue';
import AddIcon from '@/Components/Icons/AddIcon.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import CashIcon from '@/Components/Icons/CashIcon.vue';
import EyeIcon from '@/Components/Icons/EyeIcon.vue';


export default {
    props: {
        CXC: Object
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
        DocumentMoney,
        VueDatePicker,
        DocumentIcon,
        RestoreIcon,
        ChevronDownIcon,
        ChevronUpIcon,
        AddIcon,
        Modal,
        SecondaryButton,
        CashIcon,
        EyeIcon
    },


    data() {
        return {
            crumbs: [
                { icon: markRaw(CashIcon), label: 'Cuentas Por Cobrar', to: route('CXC.index') },
                { icon: markRaw(UserIcon), label: this.CXC.patient.first_name + ' ' + this.CXC.patient.last_name, to: route('patients.show', this.CXC.patient) },
                { icon: markRaw(DocumentIcon), label: this.CXC.id },

            ],
            activeIndex: null,
            showModal: ref(false),
            selectedBudget: [],

            form_payments: [],
            previous_amount_paid: {}

        }
    },
    methods: {
        getRemainingPayments(detail) {
            if (!detail.payment || !Array.isArray(detail.payment)) return 0;

            return detail.payment.filter(p => Number(p.remaining_amount) > 0).length;
        },

        calcTotal(index) {
            const payment = this.selectedBudget.payment[index];
            if (!payment) return;

            if (payment.remaining_amount_original === undefined) {
                payment.remaining_amount_original = payment.remaining_amount;
            }

            let amount_paid = Number(this.form_details[index].amount_paid);

            if (isNaN(amount_paid) || amount_paid < 0) {
                this.form_details[index].amount_paid = 0;
                payment.remaining_amount = payment.remaining_amount_original;
                this.form_details.errors[index] = 'El monto no puede ser negativo ni nulo.';
                return;
            }

            const remaining_original = payment.remaining_amount_original;

            if (amount_paid > remaining_original) {
                this.form_details[index].amount_paid = 0;
                payment.remaining_amount = remaining_original;
                this.form_details.errors[index] = 'El monto a pagar debe ser menor o igual al restante.';
                return;
            }

            payment.remaining_amount = remaining_original - amount_paid;
            this.form_details.errors[index] = null;
        },
        isExpired(dateString) {
            if (!dateString) return true;
            const today = new Date();
            const emissionDate = new Date(dateString);
            today.setHours(0, 0, 0, 0);
            emissionDate.setHours(0, 0, 0, 0);
            return emissionDate < today;
        },
        payAll(index) {
            if (!this.previous_amount_paid.hasOwnProperty(index)) {
                this.previous_amount_paid[index] = this.form_details[index].amount_paid;
            }

            let payment = this.selectedBudget.payment[index];
            this.form_details[index].amount_paid = payment.remaining_amount_original ?? payment.remaining_amount;
            this.calcTotal(index);
        },
        revert(index) {
            if (this.previous_amount_paid.hasOwnProperty(index)) {
                this.form_details[index].amount_paid = this.previous_amount_paid[index];
                delete this.previous_amount_paid[index];
                this.calcTotal(index);
            }
        }
        ,
        formatDate(date) {
            if (!date) return '';
            const d = new Date(date);
            return d.toLocaleDateString('es-DO', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
            });
        },
        submit() {
            this.form_details.errors = [];
            let hasAtLeastOnePayment = false;
            let valid = true;

            this.form_details.forEach((payment, i) => {
                const amount = Number(payment.amount_paid);

                if (amount > 0) {
                    hasAtLeastOnePayment = true;
                } else if (amount < 0 || isNaN(amount)) {
                    this.form_details.errors[i] = "El monto debe ser mayor o igual a 0";
                    valid = false;
                }
            });

            if (!hasAtLeastOnePayment) {
                this.$toast.error("Debe ingresar al menos un monto de pago válido.");
                return;
            }

            if (!valid) return;

            const payload = this.selectedBudget.payment
                .map((payment, i) => {
                    const amount_paid = Number(this.form_details[i].amount_paid);
                    if (amount_paid <= 0) return null;
                    console.log(payment)
                    return {
                        amount_paid,
                        id: payment.id,
                        remaining_amount: payment.remaining_amount,
                        total: payment.total
                    };
                })
                .filter(p => p !== null);


            this.$inertia.put(route('payments.update', payload), { pagos: payload }, {
                onSuccess: () => {
                    this.showModal = false;
                    this.resetModal();
                },
                onError: (errors) => {
                    console.error(errors);
                }
            });
        },
        resetModal() {
            if (!this.selectedBudget || !this.selectedBudget.payment) return;
            this.form_details = this.selectedBudget.payment.map(p => ({
                amount_paid: 0,
            }));
            this.form_details.errors = [];
        },

        formatNumber(n) {
            return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
        deleteBudget(id) {
        this.$inertia.delete(route('CXC.destroy', id),);
        },
        openAccordion(index) {
            this.activeIndex = this.activeIndex === index ? null : index;
        },
        restoreBudget(id) {
            this.$inertia.put(route('budgets.update', id), {
                active: true
            },
            );
        },
        deleteBudgetDetail(id) {
            this.$inertia.delete(route('budgetDetails.destroy', id),);
        },
        restoreBudgetDetail(id) {
            this.$inertia.put(route('budgetDetails.update', id), {
                active: true
            },
            );
        },
        enter(el) {
            el.style.height = '0';
            el.style.opacity = '0';
            const height = el.scrollHeight;
            setTimeout(() => {
                el.style.transition = 'all 0.3s ease';
                el.style.height = height + 'px';
                el.style.opacity = '1';
            });
        },
        leave(el) {
            el.style.height = el.scrollHeight + 'px';
            el.style.opacity = '1';
            setTimeout(() => {
                el.style.height = el.scrollHeight + 'px';
                el.style.height = '0';
                el.style.opacity = '0';
            })
        },
        async print() {
            window.open(route('report.CXC', {
                CXC: this.CXC
            }), '_blank');
        }

    }
}
</script>

<style scoped>
p span:first-child {
    color: #6b7280;
}

.fade-accordion-enter-active,
.fade-accordion-leave-active {
    transition: all 0.3s ease;
    overflow: hidden;
}

.fade-accordion-enter-from,
.fade-accordion-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}

.fade-accordion-enter-to,
.fade-accordion-leave-from {
    opacity: 1;
    transform: translateY(0);
}

.accordion-enter-active,
.accordion-leave-active {
    overflow: hidden;
}
</style>
