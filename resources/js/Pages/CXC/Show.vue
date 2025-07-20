<template>
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
                        <DangerButton @click="deleteBudget(CXC.id)"
                            class="flex justify-center gap-2 rounded-lg bg-red-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 sm:px-4">
                            <DeleteIcon /> Eliminar
                        </DangerButton>
                    </div>
                    <div class="flex ml-auto gap-2" v-else>
                        <PrimaryButton @click="restoreBudget(CXC.id)"
                            class="flex justify-center gap-2 rounded-lg bg-green-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-500 sm:px-4">
                            <RestoreIcon /> Restaurar
                        </PrimaryButton>
                    </div>
                </div>


                <!-- Presupuestos -->
                <div class="bg-gray-100 dark:bg-gray-800 p-6 rounded-2xl shadow-md my-4">
                    <div class=" flex items-center gap-2">
                        <DocumentMoney class="w-6 h-6 text-blue-600 dark:text-blue-400 mb-1" />
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Presupuestos</h2>

                    </div>

                    <div class=" rounded-2xl" v-for="(budgets, index) in CXC.budget" :key="budgets.id">

                        <div class="text-sm p-4 cursor-pointer  bg-gray-300  rounded-lg dark:bg-gray-700 text-gray-700 dark:text-gray-300 ml-1 my-2 space-y-1"
                            :class="{
                                'brightness-70': isExpired(budgets.emission_date) || !budgets.active
                            }">

                            <div @click="openAccordion(index)" class="flex items-center gap-4">
                                <p>
                                    <strong># - </strong> {{ budgets.id }}
                                </p>

                                <p><strong>Fecha de Emisión:</strong> {{ budgets.emission_date }}</p>
                                <p v-if="budgets.type == 'Crédito'"><strong>Fecha de Expiración:</strong> {{
                                    budgets.expiration_date
                                    }}</p>
                                <p> <strong>Total:</strong> ${{ formatNumber(budgets.total) }}</p>
                                <ChevronDownIcon class="flex  ml-auto " v-if="activeIndex === 0" />
                                <ChevronUpIcon class="flex  ml-auto " v-else />
                            </div>

                            <transition name="accordion" @enter="enter" @leave="leave">
                                <div v-if="activeIndex === index">
                                    <div v-for="details in budgets.budgetdetail" :key="details.id"
                                        class="bg-white  hover:bg-blue-300 dark:hover:bg-blue-500 my-4 border shadow-md dark:bg-gray-800 p-4 rounded-xl transition-all duration-200 ease-linear"
                                        :class="{
                                            'bg-red-100 dark:bg-red-500': !details.active || !CXC.active
                                        }">
                                        <div class="flex justify-between items-center mb-2 ">
                                            <h3 class="text-md font-semibold text-gray-800 dark:text-gray-100">
                                                {{ details.procedure.name }}</h3>
                                            <span class="text-sm font-medium px-2 py-1 rounded-full"
                                                :class="details.procedure.coberture ? 'bg-green-100 text-green-600 dark:bg-green-800 dark:text-green-300' : 'bg-red-100 text-red-600 dark:bg-red-800 dark:text-red-300'">
                                                {{ details.procedure.coberture ? 'Asegurado' : 'No Asegurado' }}
                                            </span>

                                        </div>
                                        <div class="text-sm text-gray-700 dark:text-gray-300 space-y-2 ">
                                            <div>
                                                <label for="amount"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 ">Monto:
                                                    ${{ formatNumber(details.amount) }}
                                                </label>

                                            </div>

                                            <div>
                                                <label for="discount"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 ">Descuento:
                                                    {{ details.discount }} %
                                                </label>

                                            </div>
                                            <div>
                                                <label for="quantity"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 ">Cantidad:
                                                    {{ details.quantity }}
                                                </label>


                                            </div>
                                            <div>
                                                <label for="initial"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 ">Inicial:
                                                    ${{ formatNumber(details.initial) }}
                                                </label>


                                            </div>
                                            <div>
                                                <label for="amount_of_payments"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 ">Cantidad
                                                    de Pagos:
                                                    {{ details.amount_of_payments }} pagos de ${{
                                                        formatNumber(details.total / details.amount_of_payments) }}
                                                </label>


                                            </div>
                                            <div>
                                                <label for="amount_of_payments"
                                                    class="block text-sm font-medium text-red-700 dark:text-red-300"
                                                    v-if="getRemainingPayments(details)">
                                                    Pendientes: {{ getRemainingPayments(details) }} pagos de ${{
                                                        formatNumber(details.total / details.amount_of_payments) }}
                                                </label>
                                                <label for="amount_of_payments"
                                                    class="block text-sm font-medium text-green-700 dark:text-green-300"
                                                    v-else>
                                                    No tiene pagos Pendientes
                                                </label>

                                            </div>


                                        </div>


                                        <div class="flex items-center gap-2 mt-4">
                                            <h3 class="text-md font-semibold">Total: ${{ formatNumber(details.total)
                                            }}
                                            </h3>

                                            <Link v-if="CXC.active" :href="route('budgets.show', budgets)"
                                                class="flex  ml-auto mt-2  gap-2 rounded-lg bg-blue-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 sm:px-4">
                                            <EyeIcon />
                                            </Link>
                                            <button v-if="CXC.active && details.active && getRemainingPayments(details)"
                                                @click="showModal = true, selectedBudget = details, resetModal()"
                                                class=" p-2 px-4 mt-2 gap-2 bg-green-500 text-sm text-white hover:bg-green-600 rounded-lg">
                                                <AddIcon />
                                            </button>
                                            <DangerButton v-if="CXC.active && details.active"
                                                @click="deleteBudgetDetail(details.id)"
                                                class=" mt-2 gap-2 bg-red-500 px-2 py-2 text-sm text-white hover:bg-red-600 rounded-lg"
                                                :class="!getRemainingPayments(details) ? 'ml-auto' : ''">
                                                <DeleteIcon />
                                            </DangerButton>

                                            <button v-else @click="restoreBudgetDetail(details.id)"
                                                class="ml-auto gap-2 bg-green-500 px-2 py-2 text-sm text-white hover:bg-green-600 rounded-lg">
                                                <RestoreIcon />
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </transition>

                        </div>

                    </div>

                </div>




            </div>
            <!-- Modal -->
            <Modal :show="showModal" @close="showModal = false" class="max-w-6xl">
                <div class="text-gray-800 p-5 h-full max-h-[700px] overflow-y-auto">
                    <h2 class="text-2xl font-semibold mb-4 text-blue-500">Pagos Disponibles</h2>

                    <div v-if="selectedBudget" class="space-y-3">
                        <div v-for="(payment, index) in selectedBudget.payment" :key="payment.id">
                            <div class="p-4 border rounded-md dark:border-gray-700">
                                <div class="flex justify-between">

                                    <h3 class="font-semibold text-gray-800 dark:text-gray-100 mb-2">
                                        Cuota # {{ index + 1 }} - {{ selectedBudget.procedure.name }}
                                    </h3>
                                    <span class="text-sm font-medium px-3 py-2 rounded-full"
                                        :class="payment.remaining_amount === 0 ? 'bg-green-100 text-green-600 dark:bg-green-800 dark:text-green-300' : 'bg-red-100 text-red-600 dark:bg-red-800 dark:text-red-300'">
                                        {{ payment.remaining_amount === 0 ? 'Pagado' : 'Pendiente' }}
                                    </span>

                                </div>

                                <div class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                    <div>
                                        <label class="block mb-1">Monto a Pagar <span
                                                class="text-red-500">*</span></label>
                                        <input @input="calcTotal(index)" v-model="form_details[index].amount_paid"
                                            :disabled="payment.remaining_amount === 0" type="number" min="0"
                                            class="w-full px-3 py-2 border rounded-md dark:bg-gray-800 dark:text-white"
                                            placeholder="Monto" />
                                        <p v-if="form_details.errors && form_details.errors[index]"
                                            class="text-red-600 text-xs">
                                            {{ form_details.errors[index] }}
                                        </p>
                                    </div>

                                    <div>
                                        <label>
                                            Restante:
                                            <span :class="{ 'text-green-600': payment.remaining_amount === 0 }">
                                                ${{ formatNumber(payment.remaining_amount) }}
                                            </span>
                                        </label>
                                    </div>

                                    <div class="flex justify-end">

                                        <button @click.prevent="payAll(index)" v-if="payment.remaining_amount !== 0"
                                            class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 text-sm">
                                            Pagar Todo
                                        </button>


                                        <button @click.prevent="revert(index)" v-else
                                            class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 text-sm">
                                            Revertir
                                        </button>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="flex gap-2 mt-4 ">
                        <SecondaryButton class="ml-auto" @click="showModal = false, resetModal()">Cancelar
                        </SecondaryButton>
                        <PrimaryButton @click="submit()">Guardar</PrimaryButton>
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
            this.$inertia.delete(route('budgets.destroy', id),);
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
