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
                        <Link :href="route('CXC.edit', CXC.id)"
                            class="flex justify-center gap-2 rounded-lg bg-green-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-500 sm:px-4">
                        <PrintIcon /> Imprimir
                        </Link>
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
                <div class="bg-gray-100 dark:bg-gray-800 p-6 rounded-2xl shadow-md">
                    <div class=" flex items-center gap-2">
                        <DocumentMoney class="w-6 h-6 text-blue-600 dark:text-blue-400 mb-1" />
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Presupuestos</h2>
                    </div>

                    <div class=" rounded-2xl ">

                        <div v-for="(budget, index) in CXC.budget" :key="index"
                            class="text-sm p-4 cursor-pointer  bg-gray-300  rounded-lg dark:bg-gray-700 text-gray-700 dark:text-gray-300 ml-1 my-2 space-y-1">

                            <div @click="openAccordion(index)" class="flex items-center gap-4">
                                <p>
                                    <strong># - </strong> {{ budget.id }}
                                </p>
                                <p>
                                    <strong>Tipo:</strong> {{ budget.type }}
                                </p>
                                <p v-if="budget.type === 'Contado'"> <strong>Fecha de Emisión:</strong> {{
                                    budget.emission_date }}</p>
                                <template v-if="budget.type === 'Crédito'">
                                    <p> <strong>Fecha de Expiración:</strong> {{ budget.expiration_date }}</p>
                                </template>
                                <p> <strong>Total:</strong> {{ formatNumber(budget.total) }}</p>
                                <ChevronDownIcon class="flex  ml-auto " v-if="activeIndex === index" />
                                <ChevronUpIcon class="flex  ml-auto " v-else />
                            </div>

                            <transition name="accordion" @enter="enter" @leave="leave">
                                <div v-if="activeIndex === index">
                                    <div v-for="details in budget.budgetdetail" :key="details.id"
                                        class="bg-white  hover:bg-blue-500 my-4 border shadow-md dark:bg-gray-800 p-4 rounded-xl transition-all duration-200 ease-linear">
                                        <div class="flex justify-between items-center mb-2">
                                            <h3 class="text-md font-semibold text-gray-800 dark:text-gray-100"># -{{
                                                details.id
                                            }} {{ details.procedure.name }}</h3>
                                            <span class="text-sm font-medium px-2 py-1 rounded-full"
                                                :class="details.procedure.coberture ? 'bg-green-100 text-green-600 dark:bg-green-800 dark:text-green-300' : 'bg-red-100 text-red-600 dark:bg-red-800 dark:text-red-300'">
                                                {{ details.procedure.coberture ? 'Asegurado' : 'No Asegurado' }}
                                            </span>
                                        </div>

                                        <div class="text-sm text-gray-700 dark:text-gray-300 space-y-2">
                                            <div>
                                                <label class="block text-sm font-medium">Monto: $ {{ formatNumber(details.amount)
                                                }}</label>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium">Descuento: {{ details.discount
                                                }} %</label>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium">Cantidad: {{ details.quantity
                                                }}</label>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2 mt-4">
                                            <h3 class="text-md font-semibold">Subtotal: ${{ formatNumber(details.total)
                                            }}
                                            </h3>
                                            <DangerButton v-if="budget.active && details.active"
                                                @click="deleteBudgetDetail(details.id)"
                                                class="ml-auto mt-2 gap-2 bg-red-500 px-2 py-2 text-sm text-white hover:bg-red-600 rounded-lg">
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
import ChevronDownIcon from '@/Components/Icons/ChevronDownIcon.vue';
import ChevronUpIcon from '@/Components/Icons/ChevronUpIcon.vue';


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
        ChevronUpIcon
    },
    data() {
        return {
            crumbs: [
                { icon: markRaw(DocumentMoney), label: 'Cuentas Por Cobrar', to: route('CXC.index') },
                { icon: markRaw(DocumentIcon), label: this.CXC.id },

            ],
            activeIndex: null,
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
