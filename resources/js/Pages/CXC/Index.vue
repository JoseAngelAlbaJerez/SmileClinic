<template>

    <Head title="Cuentas Por Cobrar" />
    <AuthenticatedLayout>
        <template #header>
            <Breadcrumb :crumbs="crumbs" />

        </template>

        <template #default>
            <div
                class="flex items-center justify-center rounded-lg bg-white-500 py-12 dark:bg-gray-900 dark:text-white">
                <div class="container mx-auto w-full px-2">
                    <!-- Search & Exports -->
                    <div class="my-2 flex lg:mx-10 gap-2 items-center">
                        <LastDaysFilter v-model="filters.lastDays" @change="submitFilters()" />

                        <!-- Espacio flexible para separar TableDropDown de la derecha -->
                        <div class="flex ml-auto items-center gap-2">

                            <input @input="submitFilters()" v-model="filters.search" type="text" placeholder="Buscar "
                                class="rounded-lg border-0 p-1.5 px-3 py-2 shadow-sm ring-1 ring-slate-300 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 lg:w-96 dark:bg-gray-800 dark:ring-slate-600" />

                        </div>
                    </div>


                    <!-- Table -->
                    <div
                        class="relative overflow-x-auto border border-gray-200 dark:border-gray-700/60 rounded-lg my-4 mx-4 lg:mx-10">
                        <div class="min-w-full overflow-x-auto">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-blue-500 text-white dark:bg-gray-800 dark:text-gray-200">
                                    <tr>
                                        <th scope="col"
                                            class="px-4 py-3 cursor-pointer whitespace-nowrap hidden sm:table-cell"
                                            @click="sort('id')">
                                            # <span v-if="form.sortField === 'id'">{{ form.sortDirection ===
                                                'asc' ?
                                                '↑' :
                                                '↓' }}</span>
                                        </th>
                                        <th scope="col" class="px-6 py-3 cursor-pointer" @click="sort('patient_id')">
                                            Paciente<span v-if="form.sortField === 'patient_id'">{{
                                                form.sortDirection ===
                                                    'asc' ? '↑' :
                                                    '↓'
                                            }}</span></th>
                                        <th scope="col" class="  cursor-pointer" @click="sort('doctor_id')">Pagos
                                            Pendientes
                                            <span v-if="form.sortField === 'doctor_id'">{{ form.sortDirection === 'asc'
                                                ?
                                                '↑' :
                                                '↓'
                                            }}</span>
                                        </th>
                                        <th scope="col " class=" cursor-pointer" @click="sort('balance')">
                                            Balance <span v-if="form.sortField === 'balance'">{{
                                                form.sortDirection ===
                                                    'asc' ?
                                                    '↑' :
                                                    '↓'
                                            }}</span>
                                        </th>
                                        <th scope="col" class=" cursor-pointer" @click="sort('created_at')">
                                            Fecha de
                                            Creación<span v-if="form.sortField === 'created_at'">{{ form.sortDirection
                                                === 'asc' ?
                                                '↑' :
                                                '↓'
                                                }}</span></th>

                                        <th class="cursor-pointer text-nowrap p-4">
                                            <div class="flex items-center justify-between" @click="toggleShowDeleted()">
                                                <div class="flex items-center ">

                                                    <h2>Estado</h2>
                                                    <FunnelIcon />
                                                </div>

                                            </div>
                                        </th>


                                        <th scope="col" class=" ">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr v-for="CXCS in CXC.data" :key="CXCS.id">
                                        <td class="p-4  items-center">{{ CXCS.id }}</td>
                                        <td class="p-4  items-center">{{ CXCS.patient.first_name }} {{
                                            CXCS.patient.last_name }} </td>
                                        <td class="p-4  items-center">
                                            {{ pending_payments[CXCS.id].length }}
                                        </td>
                                        <td class="p-4  items-center">$ {{ formatNumber(CXCS.balance) }} </td>
                                        <td class="p-4  items-center">{{ formatDate(CXCS.created_at) }}</td>
                                        <td class="p-4  items-center">
                                            <div class="flex items-center gap-2">
                                                <span :class="statusIndicatorClasses(CXCS.active)" />
                                                <p :class="statusBadgeClasses(CXCS.active)">
                                                    <HandThumbDown v-if="CXCS.active == 0" />
                                                    <HandThumbUp v-else />
                                                </p>
                                            </div>
                                        </td>
                                        <td class="p-4 items-center">
                                            <Link :href="route('CXC.show', CXCS)" class="text-blue-500 cursor-pointer">
                                            Abrir
                                            </Link>
                                        </td>

                                    </tr>



                                </tbody>
                            </table>
                            <div v-if="!CXC.data.length"
                                class="text-center text-gray-500 dark:text-gray-400 py-4 w-full">
                                No hay registros disponibles.
                            </div>
                        </div>
                        <Pagination :pagination="CXC" :filters="form" />
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <Modal :show="showModal" @close="showModal = false">
                <div class="text-gray-800 p-8  ">
                    <h2 class="text-2xl font-semibold mb-4 text-blue-500  pb-2">
                        Crear Egreso
                    </h2>
                    <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <!-- Descripción -->
                        <div>
                            <label for="description"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descripción</label>
                            <div class="relative">
                                <UserIcon class="absolute left-3 top-2.5 text-gray-400 dark:text-gray-500"
                                    style="pointer-events: none;" />
                                <input v-model="form_modal.description" id="description" type="text"
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                                    placeholder="Descripción..." />
                            </div>
                            <p v-if="errors.description" class="mt-1 text-xs text-red-600">{{ errors.description }}</p>
                        </div>

                        <!-- Monto -->
                        <div>
                            <label for="amount"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Monto</label>
                            <div class="relative">
                                <DocumentMoney class="absolute left-3 top-2.5 text-gray-400 dark:text-gray-500"
                                    style="pointer-events: none;" />
                                <input v-model="form_modal.amount" id="amount" type="number"
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                                    placeholder="Monto" />
                            </div>
                            <p v-if="errors.amount" class="mt-1 text-xs text-red-600">{{ errors.amount }}</p>
                        </div>

                        <!-- Error general -->
                        <div v-if="error" class="md:col-span-2 text-red-600 font-medium">
                            {{ error }}
                        </div>

                        <!-- Botones -->
                        <div class="md:col-span-2 flex justify-end space-x-4 mt-6">
                            <SecondaryButton type="button" @click="form_modal.reset()">
                                Limpiar
                            </SecondaryButton>
                            <PrimaryButton type="submit" :disabled="form.processing" :class="{ 'opacity-25': form.processing}" :is-loading="form.processing">Guardar</PrimaryButton>
                        </div>
                    </form>
                </div>
            </Modal>
            <!-- Modal -->



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
import CashIcon from '@/Components/Icons/CashIcon.vue';
import UserIcon from '@/Components/Icons/UserIcon.vue';
import DocumentMoney from '@/Components/Icons/DocumentMoney.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import EditIcon from '@/Components/Icons/EditIcon.vue';
import DeleteIcon from '@/Components/Icons/DeleteIcon.vue';
import DangerButton from '@/Components/DangerButton.vue';
import RestoreIcon from '@/Components/Icons/RestoreIcon.vue';

export default {

    props: {
        CXC: Object,
        filters: Object,
        errors: [Array, Object],
        pending_payments: Object
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
        CashIcon,
        AddIcon,
        UserIcon,
        DocumentMoney,
        AuthenticatedLayout,
        PrimaryButton,
        SecondaryButton,
        Link,
        EditIcon,
        DeleteIcon,
        DangerButton,
        RestoreIcon,
    },

    data() {
        return {
            form: {
                search: this.filters?.search || '',
                sortField: this.filters?.sortField || 'c_x_c_s.updated_at',
                sortDirection: this.filters?.sortDirection || 'asc',

                lastDays: this.filters?.lastDays || '1',
                showDeleted: this.filters?.showDeleted || true,
            },
            timeout: 3000,
            crumbs: [
                { icon: markRaw(CashIcon), label: 'Cuentas Por Cobrar', to: route('CXC.index') },
                { icon: markRaw(TableIcon), label: 'Listado' }
            ],
            showModal: ref(false),
            showDetailModal: ref(false),
            selectedExpense: ref(null),
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
                    this.$inertia.get(route('CXC.index'), this.form, {
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
        deleteExpense(id) {
            this.showDetailModal = false;
            this.$inertia.delete(route('CXC.destroy', id),);
        },
        restoreExpense(id) {
            this.showDetailModal = false;
            this.$inertia.put(route('CXC.update', id),
                { active: true },
            );
        },


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
