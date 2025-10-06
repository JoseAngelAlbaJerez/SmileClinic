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


                </div>


                <!-- Presupuestos Section -->
                <div class="container mx-auto px-4 py-6">
                    <!-- Header Section -->
                    <div
                        class="bg-pink-50 dark:bg-gray-900  p-6 rounded-xl shadow-md mb-6 border border-gray-200 dark:border-gray-700">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-800 dark:text-white/90">Cuenta de {{
                                    CXC.patient.first_name }} {{ CXC.patient.last_name }}</h1>

                            </div>

                            <div class="flex items-center gap-2" v-if="CXC.active">
                                <button @click="print()"
                                    class="flex justify-center gap-2 rounded-lg bg-green-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-500 sm:px-4">
                                    <PrintIcon />
                                </button>
                                <Link :href="route('CXC.edit', CXC.id)"
                                    class="flex justify-center gap-2 rounded-lg bg-yellow-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-500 sm:px-4">
                                <EditIcon />
                                </Link>
                                <DangerButton @click="deleteCXC(CXC.id)"
                                    class="flex justify-center gap-2 rounded-lg bg-red-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 sm:px-4">
                                    <DeleteIcon />
                                </DangerButton>
                            </div>
                            <div class="flex ml-auto gap-2" v-else>
                                <PrimaryButton @click="restoreCXC(CXC.id)"
                                    class="flex justify-center gap-2 rounded-lg bg-green-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-500 sm:px-4">
                                    <RestoreIcon /> Restaurar
                                </PrimaryButton>
                                <span
                                    class="px-3 py-1 bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200 rounded-full text-sm font-medium">
                                    Saldo: ${{ formatNumber(CXC.balance) }}
                                </span>
                                <span class="px-3 py-1 rounded-full text-sm font-medium"
                                    :class="CXC.active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'">
                                    {{ CXC.active ? 'Activa' : 'Inactiva' }}
                                </span>
                            </div>
                        </div>
                    </div>



                    <!-- Bills Section -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700">
                        <div
                            class="bg-gradient-to-r from-pink-50 to-pink-50 dark:from-gray-900 dark:to-gray-800 px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Recibos</h3>
                            <span
                                class="px-3 py-1 bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200 rounded-full text-sm font-medium">
                                {{ CXC.bills.length }} Recibo(s)
                            </span>
                        </div>
                        <div class="p-6">
                            <div class="space-y-6">
                                <div v-for="(bill, index) in CXC.bills" :key="bill.id" class="group">
                                    <!-- Bill Header (Clickable) -->
                                    <div @click="openAccordion(index)"
                                        class="flex flex-col md:flex-row md:items-center gap-4 p-5 bg-gray-50 dark:bg-gray-700 rounded-xl shadow-sm border border-gray-200 dark:border-gray-600 cursor-pointer transition-all duration-200 hover:shadow-md hover:border-pink-300 dark:hover:border-pink-500"
                                        :class="{
                                            'opacity-70 border-pink-200 dark:border-pink-200': isExpired(bill.emission_date) || !bill.active
                                        }">
                                        <div class="flex-1 grid grid-cols-1 md:grid-cols-4 gap-4">
                                            <div class="flex items-center gap-2">
                                                <span class="font-medium text-gray-500 dark:text-gray-400">Recibo
                                                    #:</span>
                                                <span class="font-semibold">{{ bill.id }}</span>
                                            </div>

                                            <div class="flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <span>{{ bill.emission_date }}</span>
                                            </div>

                                            <div class="flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path
                                                        d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <span class="font-bold text-pink-600 dark:text-pink-400">${{
                                                    formatNumber(bill.total) }}</span>
                                            </div>

                                            <div class="flex items-center gap-2">
                                                <span class="text-xs font-medium px-2 py-1 rounded-full"
                                                    :class="bill.type === 'Contado' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200'">
                                                    {{ bill.type }}
                                                </span>
                                                <span
                                                    class="text-xs font-medium px-2 py-1 rounded-full bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200">
                                                    {{ bill.currency }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-end">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5 text-gray-400 transition-transform duration-200"
                                                :class="{ 'rotate-180': activeIndex === index }" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>

                                    <!-- Bill Details (Accordion Content) -->
                                    <transition name="accordion">
                                        <div v-if="activeIndex === index" class="mt-4 space-y-4">
                                            <div v-for="details in bill.billdetail" :key="details.id"
                                                class="bg-white dark:bg-gray-700 p-5 rounded-xl shadow-sm border border-gray-200 dark:border-gray-600 transition-all duration-200 hover:shadow-md"
                                                :class="{
                                                    'bg-red-50 dark:bg-red-900/30 border-red-200 dark:border-red-700': !details.active || !CXC.active
                                                }">

                                                <!-- Payment Details -->
                                                <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-1 gap-4 mb-4"
                                                    v-for="payments in CXC.payment">
                                                    <div v-if="bill.id == payments.bill_id"
                                                        class="bg-gray-50 dark:bg-gray-800 p-3 rounded-lg">
                                                        <p class="text-sm text-gray-500 dark:text-gray-400">Monto Pagado
                                                        </p>
                                                        <p class="font-medium">{{ new Intl.NumberFormat('es-DO', {
                                                            style: 'currency', currency:
                                                                'DOP'
                                                        }).format(payments.amount_paid || 0) }}</p>
                                                        <p class="text-sm text-gray-500">Creado el : {{
                                                            formatDate(payments.created_at) }}</p>
                                                    </div>
                                                    <!-- Empty State -->
                                                    <div v-else class="text-center py-8">
                                                        <div
                                                            class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-600 mb-4">
                                                            <svg class="h-8 w-8 text-gray-400 dark:text-gray-300"
                                                                fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z">
                                                                </path>
                                                            </svg>
                                                        </div>
                                                        <h3
                                                            class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                                                            No hay pagos
                                                            registrados</h3>
                                                        <p class="text-gray-500 dark:text-gray-400 mb-4">Agregue pagos
                                                            para continuar</p>
                                                        <Link v-if="CXC.active"
                                                            :href="route('bills.create', { patient_id: CXC.patient.id })"
                                                            class="mt-4 flex items-center gap-2 px-4 py-2 rounded-lg bg-pink-500 from-pink-500 to-indigo-500 text-white font-medium shadow-sm hover:from-pink-600 hover:to-indigo-600 transition-all">
                                                        <AddIcon class="w-5 h-5" />
                                                        <span>Crear primer pago</span>
                                                        </Link>

                                                    </div>

                                                </div>



                                                <div
                                                    class="flex flex-wrap items-center justify-between gap-3 pt-4 border-t border-gray-200 dark:border-gray-600">

                                                    <div class="flex items-center gap-2 ml-auto">
                                                        <Link v-if="CXC.active"
                                                            :href="route('bills.create', { patient_id: CXC.patient.id })"
                                                            class="p-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors"
                                                            title="Ver detalles">
                                                        <AddIcon class="w-5 h-5"></AddIcon>
                                                        </Link>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </transition>
                                </div>
                            </div>

                            <!-- Empty State -->
                            <div v-if="CXC.bills.length === 0" class="text-center py-8">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="mt-4 text-gray-500 dark:text-gray-400">No hay Recibos registradas para este
                                    paciente.</p>
                            </div>
                        </div>
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
import { Head, Link, useForm } from '@inertiajs/vue3';
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
        EyeIcon,
        Head,
        AddIcon
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
