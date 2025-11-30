<template>

    <Head title="Presupuestos" />
    <AuthenticatedLayout>
        <template #header>
            <BreadCrumb :crumbs="crumbs" />
        </template>
        <template #default>
            <div
                class="container mx-auto px-6 py-4 my-5 dark:text-white bg-white dark:bg-gray-700  pb-8  rounded-2xl shadow-md">
                <div
                    class="bg-pink-50 dark:bg-gray-800  p-6 rounded-xl shadow-md  border border-gray-200 dark:border-gray-700 mb-2">
                    <div class="flex  md:flex-row md:items-center md:justify-between  gap-4">

                        <h1 class="text-md font-bold text-gray-800 dark:text-white/90">
                            <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                        clip-rule="evenodd" />
                                </svg>
                                <p><span class="font-semibold">Fecha de Creación: </span>{{ formatDate(budgets.created_at) }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2 text-green-600 dark:text-green-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path
                                        d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                                        clip-rule="evenodd" />
                                </svg>
                                <p><span class="font-semibold">Total:</span> {{ new Intl.NumberFormat('es-DO',
                                    {
                                        style:
                                            'currency', currency: 'DOP'
                                    }).format(budgets.total
                                        || 0) }}</p>
                            </div>
                        </h1>


                        <div v-if="budgets.active" class=" flex ml-auto gap-2 mb-2 ">
                            <button @click="print()"
                                class="flex justify-center gap-2 rounded-lg bg-green-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-500 sm:px-4">
                                <PrintIcon />
                            </button>
                            <Link :href="route('budgets.edit', budgets)"
                                class="flex justify-center gap-2 rounded-lg bg-yellow-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-500 sm:px-4">
                            <EditIcon />
                            </Link>
                            <DangerButton @click="deleteBudget(budgets.id)"
                                class="flex justify-center gap-2 rounded-lg bg-red-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 sm:px-4">
                                <DeleteIcon />
                            </DangerButton>
                        </div>
                        <div class="flex ml-auto gap-2" v-else>
                            <PrimaryButton @click="restoreBudget(budgets.id)"
                                class="flex justify-center gap-2 rounded-lg bg-green-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-500 sm:px-4">
                                <RestoreIcon />
                            </PrimaryButton>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-6">


                    <!-- Procedures Content -->
                    <div>

                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
                            <div
                                class="bg-pink-50 dark:bg-gray-800 px-6 py-3 border-b border-pink-100 dark:border-gray-600 flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-pink-600 dark:text-pink-400"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Procedimientos</h3>
                            </div>

                            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                                <div v-for="details in budgets.budgetdetail" :key="details.id"
                                    class="p-5  dark:hover:bg-gray-900 transition-colors duration-200">
                                    <div
                                        class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-3">
                                        <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                                            {{ details.procedure.name }}
                                        </h4>

                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                        <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Monto</p>
                                            <p class="font-medium">{{ new Intl.NumberFormat('es-DO', {
                                                style:
                                                    'currency', currency: budgets.currency
                                            }).format(details.amount
                                                || 0) }}</p>
                                        </div>
                                        <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Descuento</p>
                                            <p class="font-medium">{{ details.discount }}%</p>
                                        </div>
                                        <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Cantidad</p>
                                            <p class="font-medium">{{ details.quantity }}</p>
                                        </div>
                                    </div>



                                    <div
                                        class="flex items-center justify-between border-t border-gray-200 dark:border-gray-700 pt-4">
                                        <p class="font-semibold text-lg">Subtotal: {{ new Intl.NumberFormat('es-DO',
                                            {
                                                style:
                                                    'currency', currency: budgets.currency
                                            }).format(details.total
                                                || 0) }}
                                        </p>

                                        <div v-if="budgets.active && details.active">

                                            <DangerButton @click="deleteBudgetDetail(budgets.id)"
                                                class="flex justify-center gap-2 rounded-lg bg-red-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 sm:px-4">
                                                <DeleteIcon />
                                            </DangerButton>
                                        </div>
                                        <PrimaryButton v-else @click="restoreBudgetDetail(budgets.id)"
                                            class="flex justify-center gap-2 rounded-lg bg-green-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-500 sm:px-4">
                                            <RestoreIcon />
                                        </PrimaryButton>
                                    </div>
                                </div>
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
import { Head, Link } from '@inertiajs/vue3';
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
        insurance: Object,
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
        RestoreIcon,
        Head
    },
    data() {
        return {
            crumbs: [
                { icon: markRaw(DocumentMoney), label: 'Presupuestos', to: route('budgets.index') },
                { icon: markRaw(UserIcon), label: this.budgets.patient.first_name + ' ' + this.budgets.patient.last_name, to: route('patients.show', this.budgets.patient) },
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
        async print() {
            window.open(route('report.budget', {
                budget: this.budgets
            }), '_blank');
        }
    }
}
</script>
