<template>
    <AuthenticatedLayout>
        <template #header>
            <BreadCrumb :crumbs="crumbs" />
        </template>
        <template #default>
            <div
                class="container mx-auto px-6 py-4  dark:text-white bg-white dark:bg-gray-700 mt-5  pb-8  rounded-2xl shadow-md">
                <div class="my-2 mt-5 flex  gap-2 items-center p-1">
                    <h2 class="text-lg font-semibold "> Presupuesto de {{ budgets.patient.first_name }} {{
                        budgets.patient.last_name }}
                    </h2>
                    <div v-if="budgets.active" class=" flex ml-auto gap-2 mb-2 ">
                        <Link :href="route('budgets.edit', budgets.id)"
                            class="flex justify-center gap-2 rounded-lg bg-green-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-500 sm:px-4">
                        <PrintIcon /> Imprimir
                        </Link>
                        <Link :href="route('budgets.edit', budgets.id)"
                            class="flex justify-center gap-2 rounded-lg bg-yellow-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-500 sm:px-4">
                        <EditIcon /> Editar
                        </Link>
                        <DangerButton @click="deleteBudget(budgets.id)"
                            class="flex justify-center gap-2 rounded-lg bg-red-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 sm:px-4">
                            <DeleteIcon /> Eliminar
                        </DangerButton>
                    </div>
                    <div class="flex ml-auto gap-2" v-else>
                        <PrimaryButton @click="restoreBudget(budgets.id)"
                            class="flex justify-center gap-2 rounded-lg bg-green-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-500 sm:px-4">
                            <RestoreIcon /> Restaurar
                        </PrimaryButton>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-1 gap-x-8 gap-y-6 " v-if="budgets.type == 'Contado'">
                    <div class="text-sm text-gray-700 dark:text-gray-300 ml-1 flex items-center gap-4">
                        <p><strong>Tipo:</strong> {{ budgets.type }}</p>
                        <p><strong>Fecha de Emisión:</strong> {{ budgets.emission_date }}</p>
                        <p><strong>Total:</strong> ${{ formatNumber(budgets.total) }}</p>
                    </div>
                    <div class="bg-gray-100 dark:bg-gray-800 p-6 rounded-2xl shadow-md ">
                        <div class="my-2  flex items-center gap-2">
                            <DocumentMoney class="w-6 h-6 text-blue-600 dark:text-blue-400 mb-1" />
                            <h2 class="text-lg font-semibold dark:text-white ">Procedimientos</h2>

                        </div>

                        <div class="bg-white hover:bg-blue-500  my-2 border border-blue-500 dark:border-blue-400 shadow-md dark:bg-gray-700 p-4 rounded-xl shadow-sm hover:shadow-md transition duration-200"
                            v-for="details in budgets.budgetdetail" :key="details.id">
                            <div class="flex justify-between items-center mb-2 ">
                                <h3 class="text-md font-semibold text-gray-800 dark:text-gray-100">
                                    {{
                                        details.procedure.name }}</h3>
                                <span class="text-sm font-medium px-2 py-1 rounded-full"
                                    :class="details.procedure.coberture ? 'bg-green-100 text-green-600 dark:bg-green-800 dark:text-green-300' : 'bg-red-100 text-red-600 dark:bg-red-800 dark:text-red-300'">
                                    {{ details.procedure.coberture ? 'Asegurado' : 'No Asegurado' }}
                                </span>

                            </div>

                            <div class="text-sm text-gray-700 dark:text-gray-300 space-y-2 ">
                                <div>
                                    <label for="amount"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 ">Monto: ${{
                                            formatNumber(details.amount) }}
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
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 ">Cantidad: {{
                                            details.quantity }}
                                    </label>


                                </div>

                            </div>
                            <div class=" flex items-center gap-2 mt-4">
                                <h3 class="text-md font-semibold text-gray-800 dark:text-gray-100">Subtotal: ${{
                                    formatNumber(details.total) }}</h3>
                                <DangerButton v-if="budgets.active, details.active"
                                    @click="deleteBudgetDetail(details.id)"
                                    class="flex  ml-auto mt-2  gap-2 rounded-lg bg-red-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 sm:px-4">
                                    <DeleteIcon />
                                </DangerButton>
                                <button v-else @click="restoreBudgetDetail(details.id)"
                                    class="flex  ml-auto  gap-2 rounded-lg bg-green-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-500 sm:px-4">
                                    <RestoreIcon />
                                </button>

                            </div>

                        </div>

                    </div>

                </div>
                <div class="grid grid-cols-1 md:grid-cols-1 gap-x-8 gap-y-6" v-else>

                    <div class="text-sm text-gray-700 dark:text-gray-300 ml-1 flex flex-wrap gap-4">
                        <p><strong>Tipo:</strong> {{ budgets.type }}</p>
                        <p><strong>Fecha de Emisión:</strong> {{ budgets.emission_date }}</p>
                        <p><strong>Total:</strong> ${{ formatNumber(budgets.total) }}</p>
                    </div>


                    <div class="bg-gray-100 dark:bg-gray-800 p-6 rounded-2xl shadow-md">
                        <!-- Encabezado -->
                        <div class="flex items-center gap-2 mb-4">
                            <DocumentMoney class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Procedimientos</h2>
                        </div>


                        <div v-for="details in budgets.budgetdetail" :key="details.id"
                            class="bg-white dark:bg-gray-700 border border-blue-500 dark:border-blue-400 p-4 my-4 rounded-xl shadow-sm hover:bg-blue-50 dark:hover:bg-blue-600 transition duration-200">
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="text-md font-semibold text-gray-800 dark:text-gray-100">
                                    {{ details.procedure.name }}
                                </h3>
                                <p><strong>Monto:</strong> ${{ formatNumber(details.amount) }}</p>
                                <p><strong>Descuento:</strong> {{ details.discount }} %</p>
                                <p><strong>Cantidad:</strong> {{ details.quantity }}</p>
                                <span class="text-sm font-medium px-2 py-1 rounded-full" :class="details.procedure.coberture
                                    ? 'bg-green-100 text-green-600 dark:bg-green-800 dark:text-green-300'
                                    : 'bg-red-100 text-red-600 dark:bg-red-800 dark:text-red-300'">
                                    {{ details.procedure.coberture ? 'Asegurado' : 'No Asegurado' }}
                                </span>

                            </div>



                            <div v-if="details.payment.length" class="mt-4 space-y-2">
                                <h4 class="text-sm font-bold text-gray-800 dark:text-gray-100">Pagos realizados:</h4>
                                <div v-for="(payment, pIndex) in details.payment" :key="payment.id"
                                    class="text-sm bg-gray-50 dark:bg-gray-800 p-3 rounded-md border dark:border-gray-700">
                                    <div class="flex justify-between items-center mb-3">
                                        <p><strong>Pago #{{ pIndex + 1 }}</strong></p>

                                        <span class="text-sm font-medium px-2 py-1 rounded-full" :class="!payment.remaining_amount
                                            ? 'bg-green-100 text-green-600 dark:bg-green-800 dark:text-green-300'
                                            : 'bg-red-100 text-red-600 dark:bg-red-800 dark:text-red-300'">
                                            {{ !payment.remaining_amount ? 'Pagado' : 'No Pagado' }}
                                        </span>

                                    </div>

                                    <p>Monto pagado: ${{ formatNumber(payment.amount_paid) }}</p>
                                    <p>Restante: ${{ formatNumber(payment.remaining_amount) }}</p>
                                    <p>Total: ${{ formatNumber(payment.total) }}</p>
                                    <p> Fecha de Vencimiento: {{ formatDate(payment.expiration_date) }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 mt-4">
                                <h3 class="text-md font-semibold text-gray-800 dark:text-gray-100">
                                    Subtotal: ${{ formatNumber(details.total) }}
                                </h3>

                                <DangerButton v-if="budgets.active && details.active"
                                    @click="deleteBudgetDetail(details.id)"
                                    class="ml-auto mt-2 bg-red-500 px-3 py-2 text-sm text-white rounded-lg hover:bg-red-600 transition">
                                    <DeleteIcon />
                                </DangerButton>

                                <button v-else @click="restoreBudgetDetail(details.id)"
                                    class="ml-auto bg-green-500 px-3 py-2 text-sm text-white rounded-lg hover:bg-green-600 transition">
                                    <RestoreIcon />
                                </button>
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
import { Link } from '@inertiajs/vue3';
import EditIcon from '@/Components/Icons/EditIcon.vue';
import DeleteIcon from '@/Components/Icons/DeleteIcon.vue';
import PrintIcon from '@/Components/Icons/PrintIcon.vue';
import UserIcon from '@/Components/Icons/UserIcon.vue';
import DoctorIcon from '@/Components/Icons/DoctorIcon.vue';
import DocumentMoney from '@/Components/Icons/DocumentMoney.vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import DocumentIcon from '@/Components/Icons/DocumentIcon.vue';
import { markRaw } from 'vue';
import BreadCrumb from '@/Components/BreadCrumb.vue';
import RestoreIcon from '@/Components/Icons/RestoreIcon.vue';


export default {
    props: {
        budgets: Object,
        procedures: Object
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
        RestoreIcon
    },
    data() {
        return {
            crumbs: [
                { icon: markRaw(DocumentMoney), label: 'Presupuestos', to: route('budgets.index') },
                { icon: markRaw(DocumentIcon), label: this.budgets.id },

            ],
        }
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
        formatNumber(n) {
            return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
        deleteBudget(id) {
            this.$inertia.delete(route('budgets.destroy', id),);
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
    }
}
</script>
