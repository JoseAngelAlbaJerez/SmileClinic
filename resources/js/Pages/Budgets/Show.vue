<template>

    <Head title="Presupuestos" />
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
                                <h2 class="text-2xl font-bold">Presupuesto</h2>
                                <p class="text-pink-100 text-sm mt-0.5">Detalles completos del presupuesto</p>
                            </div>
                            <!-- Botones -->
                            <div class="flex justify-end gap-3  ">

                                <template v-if="budgets.active">

                                    <button @click="print()"
                                        class="flex items-center gap-2 bg-green-500 hover:bg-green-600 px-4 py-2 rounded-lg text-white shadow">
                                        <PrintIcon />
                                    </button>

                                    <AccessGate permission="budget.update">
                                        <Link :href="route('budgets.edit', budgets.id)"
                                            class="flex items-center gap-2 bg-yellow-500 hover:bg-yellow-600 px-4 py-2 rounded-lg text-white shadow">
                                        <EditIcon />
                                        </Link>
                                    </AccessGate>

                                    <AccessGate permission="budget.delete">
                                        <DangerButton @click="deleteBudget(budgets.id)"
                                            class="flex items-center gap-2 bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg text-white shadow">
                                            <DeleteIcon />
                                        </DangerButton>
                                    </AccessGate>

                                </template>

                                <template v-else>
                                    <PrimaryButton @click="restoreBudget(budgets.id)"
                                        class="flex items-center gap-2 bg-green-500 hover:bg-green-600 px-4 py-2 rounded-lg text-white shadow">
                                        <RestoreIcon />
                                    </PrimaryButton>
                                </template>

                            </div>

                        </div>


                        <!-- Contenido en Grid -->
                        <div class="grid grid-cols-1 gap-6 p-6">

                            <div
                                class="bg-white dark:bg-gray-700/40 rounded-xl shadow-sm border border-gray-100 dark:border-gray-600/40 ">

                                <div @click="toggleSection"
                                    class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 cursor-pointer items-center">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="p-2 bg-pink-100 dark:bg-pink-900/30 rounded-lg">
                                                <DocumentMoney class="w-6 h-6 text-pink-600 dark:text-pink-400" />
                                            </div>
                                            <div>
                                                <h2 class="font-semibold text-gray-800 dark:text-white">
                                                    Presupuesto #{{ budgets.id }}
                                                </h2>
                                                <div
                                                    class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                                    <BuildingIcon class="w-4 h-4" />
                                                    <span>{{ budgets.branch.name }}</span>
                                                    <UserIcon class="w-4 h-4" />
                                                    <span>{{ budgets.patient.first_name }} {{ budgets.patient.last_name
                                                    }}</span>
                                                    <span class="mx-1">•</span>
                                                    <CalendarIcon class="w-4 h-4" />
                                                    <span>{{ formatDate(budgets.created_at) }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Icono chevron que rota -->
                                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-300 transform transition-transform duration-300"
                                            :class="openSection ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24"
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
                                    <div v-show="openSection" class="overflow-hidden">
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
                                                        <p class="text-sm text-gray-500 dark:text-gray-400">Descuento
                                                        </p>
                                                        <p class="font-medium">{{ details.discount }}%</p>
                                                    </div>
                                                    <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                                        <p class="text-sm text-gray-500 dark:text-gray-400">Cantidad</p>
                                                        <p class="font-medium">{{ details.quantity }}</p>
                                                    </div>
                                                </div>



                                                <div
                                                    class="flex items-center justify-between border-t border-gray-200 dark:border-gray-700 pt-4">
                                                    <p class="font-semibold text-lg">Subtotal: {{ new
                                                        Intl.NumberFormat('es-DO',
                                                            {
                                                                style:
                                                                    'currency', currency: budgets.currency
                                                            }).format(details.total
                                                                || 0) }}
                                                    </p>


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
                                                                    'currency', currency: budgets.currency
                                                            }).format(budgets.total
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
import CalendarIcon from '@/Components/Icons/CalendarIcon.vue';
import { ref } from 'vue';
import BuildingIcon from '@/Components/Icons/BuildingIcon.vue';
import AccessGate from '@/Components/AccessGate.vue';


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
        Head,
        CalendarIcon,
        BuildingIcon,
        AccessGate
    },
    data() {
        return {
            crumbs: [
                { icon: markRaw(DocumentMoney), label: 'Presupuestos', to: route('budgets.index') },
                { icon: markRaw(UserIcon), label: this.budgets.patient.first_name + ' ' + this.budgets.patient.last_name, to: route('patients.show', this.budgets.patient) },
                { icon: markRaw(DocumentIcon), label: this.budgets.id },

            ],
            openSection: ref(true),

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
        toggleSection() {
            this.openSection = !this.openSection;
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
