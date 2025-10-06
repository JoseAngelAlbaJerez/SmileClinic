<template>

    <Head title="Egresos" />
    <AuthenticatedLayout>
        <template #header>
            <Breadcrumb :crumbs="crumbs" />

        </template>

        <template #default>
            <div
                class="flex items-center justify-center rounded-lg bg-white-500 py-12 dark:bg-gray-900 dark:text-white">
                <div class="container mx-auto w-full px-2">

                    <!-- Search & Exports -->
                    <div class="my-1 flex flex-col lg:flex-row lg:mx-10 gap-2 items-stretch lg:items-center">
                        <div class="flex flex-col md:flex-row md:items-center gap-2 w-full mt-5">
                            <!-- Filter -->
                            <LastDaysFilter v-model="filters.lastDays" @change="submitFilters()"
                                class="w-full md:w-auto" />

                            <!-- Custom Report (admin only) -->
                            <AccessGate :role="['admin']">
                                <button @click="showReport = true"
                                    class="w-full md:w-auto flex items-center justify-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                    <PrintIcon class="mr-2" />
                                    Cuadre de Caja Personalizado
                                </button>
                            </AccessGate>

                            <!-- Report Modal -->
                            <ReportModal :open="showReport" @close="showReport = false" table="expenses"
                                :default-selected="['id', 'amount', 'description', 'created_at']" />

                            <!-- Daily Report -->
                            <button @click="print"
                                class="w-full md:w-auto flex items-center justify-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                <PrintIcon class="mr-2" />
                                Cuadre de Caja Diario
                            </button>
                        </div>

                        <!-- Espacio flexible para separar en responsive -->
                        <div
                            class="flex flex-col sm:flex-row ml-auto gap-2 items-stretch sm:items-center w-full sm:w-auto">
                            <input @input="submitFilters()" v-model="filters.search" type="text" placeholder="Buscar..."
                                class="rounded-lg mt-5 border-0 px-3 py-2 shadow-sm ring-1 ring-slate-300 placeholder:text-slate-400
                   focus:outline-none focus:ring-2 focus:ring-pink-500 lg:w-96 dark:bg-gray-800 dark:ring-slate-600 w-full" />

                            <button @click="showModal = true;"
                                class="flex justify-center gap-2 rounded-lg bg-pink-500 px-4 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-pink-600 focus:outline-none focus:ring-2 focus:ring-pink-500">
                                <AddIcon class="size-5" />
                                <span class="hidden sm:inline">Nuevo Egreso</span>
                            </button>
                        </div>
                    </div>

                    <!-- Table -->
                    <div
                        class="relative overflow-x-auto border border-gray-200 dark:border-gray-700/60 rounded-lg my-4 mx-4 lg:mx-10">
                        <div class="min-w-full overflow-x-auto">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs uppercase bg-pink-500 text-white dark:bg-gray-800 dark:text-gray-200">
                                    <tr>
                                        <th scope="col"
                                            class="px-4 py-3 cursor-pointer whitespace-nowrap hidden sm:table-cell"
                                            @click="sort('id')">
                                            #
                                            <span v-if="form.sortField === 'id'">{{ form.sortDirection === 'asc' ? '↑' :
                                                '↓' }}</span>
                                        </th>
                                        <th scope="col" class="px-6 py-3 cursor-pointer" @click="sort('description')">
                                            Descripción
                                            <span v-if="form.sortField === 'description'">{{ form.sortDirection ===
                                                'asc' ? '↑' : '↓' }}</span>
                                        </th>
                                        <th scope="col" class="px-6 py-3 cursor-pointer" @click="sort('users.id')">
                                            Creado Por
                                            <span v-if="form.sortField === 'users.id'">{{ form.sortDirection === 'asc' ?
                                                '↑' : '↓' }}</span>
                                        </th>
                                        <th scope="col" class="px-6 py-3 cursor-pointer" @click="sort('amount')">
                                            Monto
                                            <span v-if="form.sortField === 'amount'">{{ form.sortDirection === 'asc' ?
                                                '↑' : '↓' }}</span>
                                        </th>
                                        <th scope="col" class="px-6 py-3 cursor-pointer" @click="sort('created_at')">
                                            Fecha de Creación
                                            <span v-if="form.sortField === 'created_at'">{{ form.sortDirection === 'asc'
                                                ? '↑' : '↓' }}</span>
                                        </th>
                                        <th scope="col " v-if="$page.props.auth.user.roles[0] === 'admin'"
                                            class="cursor-pointer " @click="sort('branch_id')">
                                            Sucursal
                                            <span v-if="form.sortField === 'branch_id'">
                                                {{ form.sortDirection === 'asc' ? '↑' : '↓' }}
                                            </span>
                                        </th>
                                        <th class="px-6 py-3 cursor-pointer text-nowrap" @click="toggleShowDeleted()">
                                            <div class="flex items-center justify-between">
                                                Estado
                                                <FunnelIcon />
                                            </div>
                                        </th>
                                        <th scope="col" class="px-6 py-3">Acciones</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr v-for="expense in expenses.data" :key="expense.id"
                                        class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                        <td class="p-4">{{ expense.id }}</td>
                                        <td class="p-4">{{ expense.description }}</td>
                                        <td class="p-4">{{ expense.user.name }} {{ expense.user.last_name }}</td>
                                        <td class="p-4">
                                            {{ new Intl.NumberFormat('es-DO', {
                                                style: 'currency', currency: 'DOP'
                                            }).format(expense.amount || 0) }}
                                        </td>
                                        <td class="p-4">{{ formatDate(expense.created_at) }}</td>
                                        <AccessGate role="admin">
                                            <td class="p-4">{{ expense.branch.name }}</td>
                                        </AccessGate>
                                        <td class="p-4">
                                            <div class="flex items-center gap-2">
                                                <span :class="statusIndicatorClasses(expense.active)" />
                                                <p :class="statusBadgeClasses(expense.active)">
                                                    <HandThumbDown v-if="expense.active == 0" />
                                                    <HandThumbUp v-else />
                                                </p>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <p @click="openModal(expense)" class="text-pink-500 cursor-pointer">Abrir
                                            </p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- Empty state -->
                            <div v-if="!expenses.data.length"
                                class="text-center text-gray-500 dark:text-gray-400 py-4 w-full">
                                No hay registros disponibles.
                            </div>
                        </div>

                        <!-- Pagination -->
                        <Pagination :pagination="expenses" :filters="form" />
                    </div>

                </div>
            </div>

            <!-- Modal -->
            <Modal :show="showModal" @close="showModal = false">
                <div class="text-gray-800 p-8  ">
                    <h2 class="text-2xl font-semibold mb-4 text-pink-500  pb-2">
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

                                <select v-model="form_modal.description"
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-800 dark:text-white">
                                    <option disabled value="">Seleccione un tipo de egreso</option>
                                    <option value="Luz">Luz</option>
                                    <option value="Local">Local</option>
                                    <option value="Envíos">Envíos</option>
                                    <option value="Depósito">Depósito</option>
                                    <option value="TSS">TSS</option>
                                    <option value="Contabilidad">Contabilidad</option>
                                    <option value="Laboratorio">Laboratorio</option>
                                    <option value="Empleado">Empleado</option>
                                    <option value="Publicidad">Publicidad</option>
                                    <option value="Teléfono">Teléfono</option>
                                    <option value="Gastos ">Gastos </option>
                                    <option value="Dra. Castro">Dra. Castro</option>
                                    <option value="Dra. Madelin">Dra. Madelin</option>
                                    <option value="Dra. Odalisa">Dra. Odalisa</option>
                                    <option value="Dra. Orquídea ">Dra. Orquídea </option>
                                    <option value="Dra. Paloma">Dra. Paloma</option>
                                    <option value="Dr. Francisco">Dr. Francisco</option>


                                </select>
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
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-800 dark:text-white"
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
                            <PrimaryButton type="submit" :disabled="form.processing"
                                :class="{ 'opacity-25': form.processing }" :is-loading="form.processing">Guardar
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </Modal>
            <!-- Modal -->
            <Modal :show="showDetailModal" @close="showDetailModal = false">
                <div class="text-gray-800 p-5  ">
                    <h2 class="text-2xl font-semibold p-4 text-pink-500  pb-2">
                        Detalles del Egreso
                    </h2>

                    <div v-if="selectedExpense" class="space-y-3 p-4">
                        <div class="flex items-center gap-2">
                            <span class="font-medium text-gray-500 dark:text-gray-200 w-30">Creado Por: </span>
                            <Link :href="route('users.show', selectedExpense.user)"
                                class="text-pink-500 dark:text-pink-300">{{ selectedExpense.user.name }} {{
                                    selectedExpense.user.last_name }}</Link>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="font-medium text-gray-500 dark:text-gray-200 w-30">Descripción: </span>
                            <span class="text-gray-900 dark:text-gray-300">{{ selectedExpense.description }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="font-medium text-gray-500 dark:text-gray-200 w-30">Monto:</span>
                            <span class="text-gray-900 dark:text-gray-300">$ {{ formatNumber(selectedExpense.amount)
                            }}</span>
                        </div>
                    </div>

                    <div class=" flex  gap-2 ">
                        <Link v-if="selectedExpense.active" :href="route('odontographs.edit', selectedExpense)"
                            class="flex  ml-auto mt-2  gap-2 rounded-lg bg-yellow-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 sm:px-4">
                        <EditIcon />
                        </Link>
                        <DangerButton v-if="selectedExpense.active" @click="deleteExpense(selectedExpense)"
                            class="flex  mt-2  gap-2 rounded-lg bg-red-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 sm:px-4">
                            <DeleteIcon />
                        </DangerButton>
                        <button v-else @click="restoreExpense(selectedExpense)"
                            class="flex  ml-auto mt-2  gap-2 rounded-lg bg-green-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 sm:px-4">
                            <RestoreIcon />
                        </button>

                    </div>
                </div>
            </Modal>


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
import CartIcon from '@/Components/Icons/CartIcon.vue';
import UserIcon from '@/Components/Icons/UserIcon.vue';
import DocumentMoney from '@/Components/Icons/DocumentMoney.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { router } from '@inertiajs/vue3'
import EditIcon from '@/Components/Icons/EditIcon.vue';
import DeleteIcon from '@/Components/Icons/DeleteIcon.vue';
import DangerButton from '@/Components/DangerButton.vue';
import RestoreIcon from '@/Components/Icons/RestoreIcon.vue';
import PrintIcon from '@/Components/Icons/PrintIcon.vue';
import AccessGate from '@/Components/AccessGate.vue';
import ReportModal from '@/Components/ReportModal.vue';
import { Inertia } from '@inertiajs/inertia';
export default {

    props: {
        expenses: Object,
        filters: Object,
        errors: [Array, Object],
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
        CartIcon,
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
        PrintIcon,
        AccessGate,
        ReportModal
    },

    data() {
        return {
            form: useForm({
                search: this.filters?.search || '',
                sortField: this.filters?.sortField || 'expenses.updated_at',
                sortDirection: this.filters?.sortDirection || 'asc',

                lastDays: this.filters?.lastDays || '1',
                showDeleted: this.filters?.showDeleted || true,
            }),
            timeout: 5000,
            crumbs: [
                { icon: markRaw(CartIcon), label: 'Egresos', to: route('expenses.index') },
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
            showReport: ref(false)

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

        submit() {
            if (!this.form_modal.description) {
                this.error = 'Por favor, ingrese la descripción del egreso.';
                return;
            }
            if (!this.form_modal.amount) {
                this.error = 'Por favor, ingrese el monto del egreso.';
                return;
            }
            if (!this.form_modal.amount < 0) {
                this.error = 'El monto debe ser mayor a 0.';
                return;
            }

            this.error = null;
            this.showModal = false;
            this.form_modal.post(route('expenses.store'),);
            this.form_modal.reset();

        },
        openModal(expense) {
            this.selectedExpense = expense;
            this.showDetailModal = true;
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
                    this.$inertia.get(route('expenses.index'), this.form, {
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
            this.$inertia.delete(route('expenses.destroy', id),);
        },
        restoreExpense(id) {
            this.showDetailModal = false;
            this.$inertia.put(route('expenses.update', id),
                { active: true },
            );
        },
        print() {
            router.visit(route("report.dailycashbalance"))
        }

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
