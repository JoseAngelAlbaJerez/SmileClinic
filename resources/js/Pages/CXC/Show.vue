<template>

    <Head title="Recibos" />
    <AuthenticatedLayout>
        <template #header>
            <BreadCrumb :crumbs="crumbs" />
        </template>
        <template #default>
            <div class="container mx-auto  my-5 dark:text-white bg-white dark:bg-gray-700   rounded-2xl shadow-md">


                <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">

                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">

                        <!-- Header -->
                        <div
                            class="p-6 bg-pink-500  dark:bg-pink-600 rounded-t-2xl text-white shadow-inner flex justify-between items-center gap-3">
                            <div>
                                <h2 class="text-2xl font-bold">Cuenta de {{ CXC.patient.first_name }} {{
                                    CXC.patient.last_name }}</h2>
                                <p class="text-pink-100 text-sm mt-0.5">Detalles completos de los recibos</p>
                            </div>
                            <!-- Botones -->
                            <div class="flex justify-end gap-3  ">

                                <template v-if="CXC.active">

                                    <button @click="print()"
                                        class="flex items-center gap-2 bg-green-500 hover:bg-green-600 px-4 py-2 rounded-lg text-white shadow">
                                        <PrintIcon />
                                    </button>

                                    <AccessGate permission="CXC.update">
                                        <Link :href="route('payments.edit', CXC.id)"
                                            class="flex items-center gap-2 bg-yellow-500 hover:bg-yellow-600 px-4 py-2 rounded-lg text-white shadow">
                                        <EditIcon />
                                        </Link>
                                    </AccessGate>

                                    <AccessGate permission="CXC.delete">
                                        <DangerButton @click="deleteCXC(CXC.id)"
                                            class="flex items-center gap-2 bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg text-white shadow">
                                            <DeleteIcon />
                                        </DangerButton>
                                    </AccessGate>

                                </template>

                                <template v-else>
                                    <PrimaryButton @click="restoreCXC(CXC.id)"
                                        class="flex items-center gap-2 bg-green-500 hover:bg-green-600 px-4 py-2 rounded-lg text-white shadow">
                                        <RestoreIcon /> Restaurar
                                    </PrimaryButton>
                                </template>

                            </div>

                        </div>

                        <div class="grid grid-cols-1 gap-6 p-6">

                            <!-- ENCABEZADO DE CUENTA POR COBRAR -->
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
                                    Información de la Cuenta
                                </h3>

                                <div class="space-y-3 text-sm">



                                    <!-- Paciente -->
                                    <div class="flex">
                                        <span class="w-40 font-medium text-gray-600 dark:text-gray-300">Paciente:</span>
                                        <span class="text-gray-800 dark:text-gray-100">
                                            {{ CXC?.patient?.first_name }} {{ CXC?.patient?.last_name }}
                                        </span>
                                    </div>

                                    <!-- Fecha de creación -->
                                    <div class="flex">
                                        <span class="w-40 font-medium text-gray-600 dark:text-gray-300">Fecha
                                            apertura:</span>
                                        <span class="text-gray-800 dark:text-gray-100">
                                            {{ formatDate(CXC?.created_at) }}
                                        </span>
                                    </div>

                                    <!-- Balance -->
                                    <div class="flex">
                                        <span class="w-40 font-medium text-gray-600 dark:text-gray-300">Balance
                                            pendiente:</span>
                                        <span class="text-gray-800 dark:text-gray-100 font-semibold">
                                            {{ new Intl.NumberFormat('es-DO', {
                                                style: 'currency', currency: 'DOP'
                                            }).format(CXC?.balance || 0) }}
                                        </span>
                                    </div>

                                </div>
                            </div>

                        </div>
                        <!-- Contenido en Grid -->
                        <div class="grid grid-cols-1 gap-6 p-6">

                            <div v-for="bill in CXC.bills" :key="bill.id"
                                class="bg-white dark:bg-gray-700/40 rounded-xl  shadow-sm border border-gray-100 dark:border-gray-600/40 ">


                                    <div @click="toggleSection(bill.id)"
                                        class="p-4 border-b border-gray-200   dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 cursor-pointer items-center">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <div class="p-2 bg-pink-100 dark:bg-pink-900/30 rounded-lg">
                                                    <DocumentMoney class="w-6 h-6 text-pink-600 dark:text-pink-400" />
                                                </div>
                                                <div>
                                                    <h2 class="font-semibold text-gray-800 dark:text-white">
                                                        Factura # {{ bill.id }}

                                                    </h2>
                                                    <div
                                                        class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                                        <BuildingIcon class="w-4 h-4" />
                                                        <span>{{ bill.branch.name }}</span>
                                                        <UserIcon class="w-4 h-4" />
                                                        <span>{{ bill.doctor.first_name }} {{ bill.doctor.last_name
                                                        }}</span>
                                                        <span class="mx-1">•</span>
                                                        <CalendarIcon class="w-4 h-4" />
                                                        <span>{{ formatDate(bill.created_at) }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Icono chevron que rota -->
                                            <svg class="w-5 h-5 text-gray-600 dark:text-gray-300 transform transition-transform duration-300"
                                                :class="openSection[bill.id] ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>

                                    </div>
                                    <Transition
                                        enter-active-class="transition-all duration-600 ease-[cubic-bezier(0.34,1.56,0.64,1)]"
                                        enter-from-class="max-h-0 opacity-0 -translate-y-3"
                                        enter-to-class="max-h-[2000px] opacity-100 translate-y-0"
                                        leave-active-class="transition-all duration-500 ease-[cubic-bezier(0.4,0,0.2,1)]"
                                        leave-from-class="max-h-[2000px] opacity-100 translate-y-0"
                                        leave-to-class="max-h-0 opacity-0 -translate-y-2">
                                        <div v-show="openSection[bill.id]" class="overflow-hidden">
                                            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                                                <div v-if="bill.payments.length" v-for="details in bill.payments"
                                                    :key="details.id"
                                                    class="p-5  dark:hover:bg-gray-900 transition-colors duration-200">
                                                    <div
                                                        class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-3">

                                                    </div>

                                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                                        <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                                            <p class="text-sm text-gray-500 dark:text-gray-400">Fecha de
                                                                Creacion</p>
                                                            <p class="font-medium">{{ formatDate(details.created_at) }}
                                                            </p>
                                                        </div>
                                                        <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                                            <p class="text-sm text-gray-500 dark:text-gray-400">Monto
                                                            </p>
                                                            <p class="font-medium">{{ new Intl.NumberFormat('es-DO', {
                                                                style:
                                                                    'currency', currency: 'DOP'
                                                            }).format(details.amount_paid
                                                                || 0) }}</p>
                                                        </div>
                                                        <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                                            <p class="text-sm text-gray-500 dark:text-gray-400">Restante
                                                            </p>
                                                            <p class="font-medium">{{ new Intl.NumberFormat('es-DO', {
                                                                style:
                                                                    'currency', currency: 'DOP'
                                                            }).format(details.total - details.amount_paid
                                                                || 0) }}</p>
                                                        </div>
                                                    </div>



                                                    <div
                                                        class="flex items-center  justify-between border-t border-gray-200 dark:border-gray-700 pt-4">

                                                        <div v-if="CXC.active && details.active">
                                                            <DangerButton @click="deletePayment(details.id)"
                                                                class="flex ml-auto justify-center gap-2 rounded-lg bg-red-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 sm:px-4">
                                                                <DeleteIcon />
                                                            </DangerButton>
                                                        </div>
                                                        <PrimaryButton v-else @click="restorePayment(details.id)"
                                                            class="flex ml-auto justify-center gap-2 rounded-lg bg-green-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-500 sm:px-4">
                                                            <RestoreIcon />
                                                        </PrimaryButton>
                                                    </div>
                                                </div>
                                                <div v-else class="text-center py-6 mt-2">
                                                    <div class="inline-flex flex-col items-center">
                                                        <svg class="h-8 w-8 text-gray-400 dark:text-gray-300"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                        </svg>
                                                        <p class="text-gray-500 dark:text-gray-400 font-medium">No hay
                                                            recibos
                                                            registrados</p>

                                                        <p class="text-sm text-gray-400 dark:text-gray-500">Crea un
                                                            nuevo
                                                            recibo para
                                                            comenzar
                                                        </p>
                                                        <AccessGate permission="event.create" class="mt-4">
                                                            <Link
                                                                :href="route('payments.create', { patient_id: CXC.patient })"
                                                                as="button"
                                                                class="flex justify-center  rounded-lg bg-pink-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-pink-600 focus:outline-none focus:ring-2 focus:ring-pink-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-pink-500 sm:px-4">
                                                            <AddIcon class="size-6" />
                                                            Nuevo Recibo
                                                            </Link>
                                                        </AccessGate>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-6 border-t">
                                                <div
                                                    class=" rounded-b bg-white dark:bg-gray-800  border-gray-200 dark:border-gray-700 p-5">
                                                    <div class="flex items-center justify-between">

                                                        <p class="text-xl font-bold text-gray-700 dark:text-gray-200">
                                                            Total:
                                                        </p>

                                                        <p
                                                            class="text-3xl font-extrabold text-green-600 dark:text-green-400">
                                                            {{ new
                                                                Intl.NumberFormat('es-DO',
                                                                    {
                                                                        style:
                                                                            'currency', currency: 'DOP'
                                                                    }).format(bill.total
                                                                        || 0) }}
                                                        </p>
                                                    </div>


                                                </div>
                                            </div>



                                        </div>
                                    </Transition>
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
import AccessGate from '@/Components/AccessGate.vue';
import BuildingIcon from '@/Components/Icons/BuildingIcon.vue';
import CalendarIcon from '@/Components/Icons/CalendarIcon.vue';
import DocumentTextIcon from '@/Components/Icons/DocumentTextIcon.vue';


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
        AddIcon,
        DocumentIcon,
        AccessGate,
        BuildingIcon,
        CalendarIcon,
        DocumentTextIcon
    },


    data() {
        return {
            crumbs: [
                { icon: markRaw(CashIcon), label: 'Recibos', to: route('CXC.index') },
                { icon: markRaw(UserIcon), label: this.CXC.patient.first_name + ' ' + this.CXC.patient.last_name, to: route('patients.show', this.CXC.patient) },
                { icon: markRaw(DocumentIcon), label: this.CXC.id },

            ],
            activeIndex: null,
            showModal: ref(false),
            selectedBudget: [],
            openSection: {},

            form_payments: [],
            previous_amount_paid: {}

        }
    },
    methods: {
        toggleSection(id) {
            this.openSection[id] = !this.openSection[id];
        },

        SumPayments(payments) {
            let sum;
            console.log(payments)
            payments.forEach(payment => {
                payment = +sum;
                console.log(sum)
            });

            return sum;
        },
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
        deleteCXC(id) {
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
        deletePayment(id) {
            this.$inertia.delete(route('Payments.destroy', id),);
        },
        restorePayment(id) {
            this.$inertia.put(route('Payments.update', id), {
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
