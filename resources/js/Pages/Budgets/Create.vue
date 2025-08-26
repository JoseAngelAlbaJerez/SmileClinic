<template>

    <Head title="Presupuestos" />
    <AuthenticatedLayout>
        <template #header>
            <Breadcrumb :crumbs="crumbs" />
        </template>
        <template #default>
            <div class="min-h-full bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
                <div class="max-w-7xl mx-auto">
                    <!-- Form Card -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
                        <!-- Form Header with Gradient -->
                        <div class="bg-gradient-to-r from-pink-500 to-pink-600 px-6 py-4">
                            <h2 class="text-xl font-bold text-white">Nuevo Presupuesto</h2>
                            <p class="text-pink-100 text-sm">Complete los detalles del documento</p>
                        </div>

                        <!-- Main Form Content -->
                        <div class="p-6 space-y-6">
                            <!-- Client and Document Info -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Patient Selection -->
                                <div class="space-y-1">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Paciente <span class="text-red-500">*</span>
                                    </label>
                                    <button @click="openPatientModal()"
                                        class="flex items-center w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg hover:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white transition duration-200">
                                        <UserIcon class="h-5 w-5 text-gray-400 dark:text-gray-500 mr-2" />
                                        <span v-if="form.patient_id" class="truncate">
                                            {{ selected_patient.first_name }} {{ selected_patient.last_name }}
                                        </span>
                                        <span v-else class="text-gray-400 dark:text-gray-400">Seleccionar</span>
                                    </button>
                                    <p v-if="errors.patient_id" class="mt-1 text-xs text-red-600 dark:text-red-400">
                                        {{ errors.patient_id }}
                                    </p>
                                </div>



                                <!-- Emission Date -->
                                <div class="space-y-1">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Fecha Emisión <span class="text-red-500">*</span>
                                    </label>
                                    <VueDatePicker
                                        class="date-picker-custom border-gray-300 dark:border-gray-600 rounded-lg hover:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white transition duration-200"
                                        placeholder="Seleccione fecha" v-model="form.emission_date"
                                        :enable-time-picker="false" />
                                </div>

                                <!-- Expiration Date (Conditional) -->
                                <div class="space-y-1" v-if="form.type == 'Crédito'">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Fecha Vencimiento <span class="text-red-500">*</span>
                                    </label>
                                    <VueDatePicker
                                        class="date-picker-custom border-gray-300 dark:border-gray-600 rounded-lg hover:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white transition duration-200"
                                        placeholder="Seleccione fecha" v-model="form.expiration_date"
                                        :enable-time-picker="false" />
                                    <p v-if="errors.expiration_date"
                                        class="mt-1 text-xs text-red-600 dark:text-red-400">
                                        {{ errors.expiration_date }}
                                    </p>
                                </div>
                            </div>

                            <!-- Procedures Section -->
                            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-xl shadow-sm mt-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-2">
                                        <DocumentIcon class="w-6 h-6 text-pink-600 dark:text-pink-400" />
                                        <h3 class="text-lg font-semibold dark:text-white">Procedimientos</h3>
                                    </div>
                                    <button @click="openProcedureModal()"
                                        class="flex items-center space-x-1 rounded-lg bg-green-500 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 transition duration-200">
                                        <AddIcon class="h-4 w-4" />
                                        <span>Agregar</span>
                                    </button>
                                </div>

                                <!-- Empty State -->
                                <div v-if="!selectedProcedures.length" class="text-center py-6">
                                    <div
                                        class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-600">
                                        <SearchIcon class="h-6 w-6 text-gray-400 dark:text-gray-300" />
                                    </div>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No hay
                                        procedimientos</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Agregue procedimientos para
                                        continuar</p>
                                </div>

                                <!-- Procedure Cards -->
                                <div v-else class="space-y-4">
                                    <div v-for="(proc, index) in selectedProcedures" :key="proc.id"
                                        class="bg-white dark:bg-gray-600 border border-pink-200 dark:border-pink-400 rounded-lg p-4 shadow-sm hover:shadow-md transition duration-200">
                                        <!-- Procedure Header -->
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="flex items-center space-x-2">
                                                <span class="text-sm font-medium text-gray-500 dark:text-gray-300">#{{
                                                    proc.id }}</span>
                                                <h4 class="text-md font-semibold text-gray-800 dark:text-gray-100">{{
                                                    proc.name }}</h4>
                                            </div>
                                            <span class="text-xs font-medium px-2 py-1 rounded-full"
                                                :class="proc.coberture ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'">
                                                {{ proc.coberture ? 'Asegurado' : 'No Asegurado' }}
                                            </span>
                                        </div>

                                        <!-- Procedure Details -->
                                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                            <!-- Treatment -->
                                            <div class="space-y-1">
                                                <label
                                                    class="block text-xs font-medium text-gray-700 dark:text-gray-300">
                                                    Tratamiento <span class="text-red-500">*</span>
                                                </label>
                                                <input v-model="form_details[index].treatment" type="text"
                                                    class="block w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-500 rounded-md focus:outline-none focus:ring-1 focus:ring-pink-500 dark:bg-gray-700 dark:text-white"
                                                    placeholder="Descripción" />
                                                <p v-if="form.errors[`details.${index}.treatment`]"
                                                    class="text-xs text-red-600 dark:text-red-400">
                                                    {{ form.errors[`details.${index}.treatment`] }}
                                                </p>
                                            </div>

                                            <!-- Amount -->
                                            <div class="space-y-1">
                                                <label
                                                    class="block text-xs font-medium text-gray-700 dark:text-gray-300">
                                                    Monto <span class="text-red-500">*</span>
                                                </label>
                                                <div class="relative">
                                                    <span
                                                        class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 dark:text-gray-400">$</span>
                                                    <input v-model="form_details[index].amount" type="number"
                                                        @input="calcTotal(index)"
                                                        class="block w-full pl-8 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-500 rounded-md focus:outline-none focus:ring-1 focus:ring-pink-500 dark:bg-gray-700 dark:text-white"
                                                        placeholder="0.00" />
                                                </div>
                                                <p v-if="form.errors[`details.${index}.amount`]"
                                                    class="text-xs text-red-600 dark:text-red-400">
                                                    {{ form.errors[`details.${index}.amount`] }}
                                                </p>
                                            </div>

                                            <!-- Discount -->
                                            <div class="space-y-1">
                                                <label
                                                    class="block text-xs font-medium text-gray-700 dark:text-gray-300">
                                                    Descuento
                                                </label>
                                                <DiscountInput v-model="form_details[index].discount"
                                                    @input="calcTotal(index)" />
                                                <p v-if="form.errors[`details.${index}.discount`]"
                                                    class="text-xs text-red-600 dark:text-red-400">
                                                    {{ form.errors[`details.${index}.discount`] }}
                                                </p>
                                            </div>

                                            <!-- Quantity -->
                                            <div class="space-y-1">
                                                <label
                                                    class="block text-xs font-medium text-gray-700 dark:text-gray-300">
                                                    Cantidad <span class="text-red-500">*</span>
                                                </label>
                                                <input v-model="form_details[index].quantity" type="number"
                                                    @input="calcTotal(index)"
                                                    class="block w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-500 rounded-md focus:outline-none focus:ring-1 focus:ring-pink-500 dark:bg-gray-700 dark:text-white"
                                                    placeholder="1" min="1" />
                                                <p v-if="form.errors[`details.${index}.quantity`]"
                                                    class="text-xs text-red-600 dark:text-red-400">
                                                    {{ form.errors[`details.${index}.quantity`] }}
                                                </p>
                                            </div>

                                            <!-- Credit-specific fields -->
                                            <template v-if="form.type == 'Crédito'">
                                                <!-- Initial Payment -->
                                                <div class="space-y-1">
                                                    <label
                                                        class="block text-xs font-medium text-gray-700 dark:text-gray-300">
                                                        Inicial <span class="text-red-500">*</span>
                                                    </label>
                                                    <div class="relative">
                                                        <span
                                                            class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 dark:text-gray-400">$</span>
                                                        <input v-model="form_details[index].initial" type="number"
                                                            @input="calcTotal(index)"
                                                            class="block w-full pl-8 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-500 rounded-md focus:outline-none focus:ring-1 focus:ring-pink-500 dark:bg-gray-700 dark:text-white"
                                                            placeholder="0.00" />
                                                    </div>
                                                    <p v-if="form.errors[`details.${index}.initial`]"
                                                        class="text-xs text-red-600 dark:text-red-400">
                                                        {{ form.errors[`details.${index}.initial`] }}
                                                    </p>
                                                </div>

                                                <!-- Number of Payments -->
                                                <div class="space-y-1">
                                                    <label
                                                        class="block text-xs font-medium text-gray-700 dark:text-gray-300">
                                                        Cuotas <span class="text-red-500">*</span>
                                                    </label>
                                                    <input v-model="form_details[index].amount_of_payments"
                                                        type="number" @input="calcTotal(index)"
                                                        class="block w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-500 rounded-md focus:outline-none focus:ring-1 focus:ring-pink-500 dark:bg-gray-700 dark:text-white"
                                                        placeholder="1" min="1" />
                                                    <p v-if="form.errors[`details.${index}.amount_of_payments`]"
                                                        class="text-xs text-red-600 dark:text-red-400">
                                                        {{ form.errors[`details.${index}.amount_of_payments`] }}
                                                    </p>
                                                </div>
                                            </template>
                                        </div>

                                        <!-- Procedure Footer -->
                                        <div
                                            class="flex items-center justify-between mt-4 pt-3 border-t border-gray-200 dark:border-gray-500">
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                <p>SubTotal: {{ new Intl.NumberFormat('es-DO',
                                                    {
                                                        style:
                                                            'currency', currency: 'DOP'
                                                    }).format(form_details[index].total
                                                    || 0) }}</p>
                                            </span>
                                            <button @click="removeProcedure(index)" type="button"
                                                class="inline-flex items-center rounded-md bg-red-50 dark:bg-red-900/30 px-2 py-1 text-xs font-medium text-red-600 dark:text-red-300 ring-1 ring-inset ring-red-500/10 hover:bg-red-100 dark:hover:bg-red-800 transition duration-150">
                                                <DeleteIcon class="h-4 w-4 mr-1" />
                                                Eliminar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Summary and Actions -->
                            <div
                                class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0 md:space-x-6">
                                <!-- Error Message -->
                                <div v-if="errors.length" class="flex-1">
                                    <div
                                        class="p-3 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                                        <div class="flex items-center text-red-600 dark:text-red-400">

                                            <span>{{ errors }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Total -->
                                <div v-if="form.total" class="text-lg font-semibold text-gray-900 dark:text-white">
                                    <p class="text-pink-600 dark:text-pink-400">Total: {{ new Intl.NumberFormat('es-DO',
                                        {
                                            style:
                                                'currency', currency: 'DOP'
                                        }).format(form.total
                                            || 0) }}</p>
                                </div>

                                <!-- Form Actions -->
                                <div class="flex space-x-3">
                                    <SecondaryButton type="button"
                                        @click="form.reset(); form_detail.reset(); form_details = []; selectedProcedures = []"
                                        class="hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-200">

                                        Limpiar
                                    </SecondaryButton>
                                    <PrimaryButton @click="submit()" :disabled="form.processing"
                                        :class="{ 'opacity-75': form.processing }"
                                        class="hover:bg-pink-600 transition duration-200">

                                        <span v-if="form.processing">Guardando...</span>
                                        <span v-else>Guardar </span>
                                    </PrimaryButton>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Procedure Selection Modal -->
                <Modal :show="showProcedureModal" @close="showProcedureModal = false" maxWidth="2xl">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-800 dark:text-white">
                                <DocumentIcon class="h-6 w-6 inline-block mr-2 text-pink-500" />
                                Seleccionar Procedimiento
                            </h3>
                            <button @click="showProcedureModal = false"
                                class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                <XIcon class="h-6 w-6" />
                            </button>
                        </div>
                        <ProcedureSelector :procedures="procedure"
                            @selected="selectedProcedureId = $event.id; addProcedure()" />
                    </div>
                </Modal>

                <!-- Patient Selection Modal -->
                <Modal :show="showPatientModal" @close="showPatientModal = false" maxWidth="2xl">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-800 dark:text-white">
                                <UserIcon class="h-6 w-6 inline-block mr-2 text-pink-500" />
                                Seleccionar Paciente
                            </h3>
                            <button @click="showPatientModal = false"
                                class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                <XIcon class="h-6 w-6" />
                            </button>
                        </div>
                        <PatientSelector :patients="patient"
                            @selected="form.patient_id = $event.id, selected_patient = $event, showPatientModal = false" />
                    </div>
                </Modal>
            </div>
        </template>

    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Breadcrumb from '@/Components/BreadCrumb.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AddIcon from '@/Components/Icons/AddIcon.vue';
import UserIcon from '@/Components/Icons/UserIcon.vue';
import { markRaw, ref } from 'vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DocumentMoney from '@/Components/Icons/DocumentMoney.vue';
import Modal from '@/Components/Modal.vue';
import { Head, useForm } from '@inertiajs/vue3';
import DangerButton from '@/Components/DangerButton.vue';
import DeleteIcon from '@/Components/Icons/DeleteIcon.vue';
import DiscountInput from '@/Components/DiscountInput.vue';
import PatientSelector from '@/Components/PatientSelector.vue';
import ProcedureSelector from '@/Components/ProcedureSelector.vue';
import XIcon from '@/Components/Icons/XIcon.vue';
import DocumentIcon from '@/Components/Icons/DocumentIcon.vue';
import SearchIcon from '@/Components/Icons/SearchIcon.vue';

export default {
    props: {
        patient: Object,
        procedure: Object,
        errors: [Array, Object],
    },
    components: {
        AuthenticatedLayout,
        Breadcrumb,
        PrimaryButton,
        AddIcon,
        UserIcon,
        VueDatePicker,
        SecondaryButton,
        DocumentMoney,
        Modal,
        DangerButton,
        DiscountInput,
        PatientSelector,
        ProcedureSelector,
        XIcon,
        DocumentIcon,
        SearchIcon,
        DeleteIcon,
        Head

    },
    data() {
        return {
            form: useForm({
                patient_id: '',
                type: 'Contado',
                emission_date: new Date(),
                expiration_date: '',
                total: '',
            }),
            form_detail: useForm({
                procedure_id: '',
                amount: '',
                total: '',
                treatment: '',
                discount: 0,
                quantity: '',


            }),
            budget_id: '',
            form_details: [],
            selected_patient: '',
            timeout: 3000,
            crumbs: [
                { icon: markRaw(DocumentMoney), label: 'Presupuestos', to: route('budgets.index') },
                { icon: markRaw(AddIcon), label: 'Crear' },

            ],
            showProcedureModal: ref(false),
            showPatientModal: ref(false),
            error: '',
            selectedProcedureId: '',
            selectedProcedures: [],
        };

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

        addProcedure() {

            const id = parseInt(this.selectedProcedureId);
            const found = this.procedure.data.find(p => p.id === id);
            if (found && !this.selectedProcedures.some(p => p.id === id)) {
                this.form_details.push({
                    procedure_id: found.id,
                    amount: found.cost,
                    total: found.cost,
                    treatment: found.name,
                    discount: 0,
                    quantity: 1,
                    initial: 0,
                    amount_of_payments: 0,
                });
                this.selectedProcedures.push({ ...found });
            }
            this.calcTotal(this.form_details.length - 1);
            this.selectedProcedureId = '';

        },
        calcTotal(index) {
            const detail = this.form_details[index];
            const amount = parseFloat(detail.amount) || 0;
            const discount = parseFloat(detail.discount) || 0;
            const quantity = parseInt(detail.quantity) || 1;
            const initial = parseFloat(detail.initial) || 0;
            detail.total = (amount * quantity);
            detail.total -= initial;
            const discounted = (detail.total * discount) / 100;
            detail.total -= discounted;
            this.form.total = this.form_details.reduce((sum, detail) => {
                const total = parseFloat(detail.total) || 0;
                return sum + total;
            }, 0);
        },

        removeProcedure(index) {
            const detail = this.form_details[index];

            this.form_details.splice(index, 1);

            this.selectedProcedures = this.selectedProcedures.filter(
                p => p.id !== detail.procedure_id
            );

            this.form.total = this.form_details.reduce((sum, detail) => {
                return sum + (parseFloat(detail.total) || 0);
            }, 0);

        },

        openProcedureModal() {
            this.showProcedureModal = true;
        },
        openPatientModal() {
            this.showPatientModal = true;
        },

        formatNumber(n) {
            return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
        getDefaultDatetime() {
            const now = new Date();
            return now.toISOString().slice(0, 16);
        },

        submit() {
            if (!this.form.patient_id) {
                this.errors.patient_id = 'Por favor, seleccione un paciente.';
                return;
            }

            if (this.form.type === 'Crédito' && !this.form.expiration_date) {
                this.errors.expiration_date = 'Por favor, ingrese la fecha de expiración.';
                return;
            }

            if (this.selectedProcedures.length <= 0) {
                this.errors.procedures = 'Por favor, seleccione un procedimiento.';
                return;
            }


            this.error = null;
            this.form.processing = true;
            const data = {
                form: {
                    patient_id: this.form.patient_id,
                    type: this.form.type,
                    emission_date: this.form.emission_date,
                    expiration_date: this.form.expiration_date,
                    total: this.form.total,
                },
                details: this.form_details,
            };

            axios.post(route('budgets.store'), data)
                .then(response => {
                    const budgetId = response.data.budget_id;

                    if (budgetId) {
                        window.open(route('report.budget', { budget: budgetId }), '_blank');
                    }
                    window.location.href = route('budgets.show', { budget: budgetId });
                })
                .catch(error => {
                    if (error.response?.status === 422) {
                        this.form.errors = error.response.data.errors;
                        this.form_detail.errors = error.response.data.errors;
                    } else {
                        console.error('Error al guardar el presupuesto:', error);
                    }
                }).finally(() => {
                    this.form.processing = false;
                });
        },



    },
};
</script>




<style scoped>
p span:first-child {
    color: #6b7280;
}
</style>
