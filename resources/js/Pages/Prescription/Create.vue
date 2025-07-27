<template>
    <AuthenticatedLayout>
        <template #header>
            <Breadcrumb :crumbs="crumbs" />
        </template>
        <template #default>
            <div class="min-h-full bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
                <div class="max-w-7xl mx-auto">
                    <!-- Form Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <!-- Form Header -->
                        <div class="bg-blue-500 px-6 py-4">
                            <h2 class="text-xl font-bold text-white">Nueva Receta Médica</h2>
                            <p class="text-blue-100 text-sm">Complete los detalles de la prescripción</p>
                        </div>

                        <!-- Main Form Content -->
                        <div class="p-6 space-y-6">
                            <!-- Patient Selection -->
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                <div class="space-y-1">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Paciente <span class="text-red-500">*</span>
                                    </label>
                                    <button @click="openPatientModal()"
                                        class="flex items-center w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition duration-200">
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
                            </div>

                            <!-- Prescription Section -->
                            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-xl shadow-sm mt-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-2">
                                        <RXIcon class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                                        <h3 class="text-lg font-semibold dark:text-white">Receta</h3>
                                    </div>
                                    <button @click="addPrescriptionDetail()"
                                        class="flex items-center space-x-1 rounded-lg bg-green-500 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 transition duration-200">
                                        <AddIcon class="h-4 w-4" />
                                        <span>Agregar</span>
                                    </button>
                                </div>

                                <!-- Empty State -->
                                <div v-if="!form.details.length" class="text-center py-6">
                                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-600">
                                        <SearchIcon class="h-6 w-6 text-gray-400 dark:text-gray-300" />
                                    </div>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No hay medicamentos</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Agregue medicamentos para continuar</p>
                                </div>

                                <!-- Prescription Items -->
                                <div v-else class="space-y-4">
                                    <div v-for="(detail, index) in form.details" :key="index"
                                        class="bg-white dark:bg-gray-600 border border-blue-200 dark:border-blue-400 rounded-lg p-4 shadow-sm hover:shadow-md transition duration-200">
                                        <!-- Item Header -->
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="flex items-center space-x-2">
                                                <h4 class="text-md font-semibold text-gray-800 dark:text-gray-100">Medicamento #{{ index + 1 }}</h4>
                                            </div>
                                            <button @click="removePrescriptionDetail(index)" type="button"
                                                class="inline-flex items-center rounded-md bg-red-50 dark:bg-red-900/30 px-2 py-1 text-xs font-medium text-red-600 dark:text-red-300 ring-1 ring-inset ring-red-500/10 hover:bg-red-100 dark:hover:bg-red-800 transition duration-150">
                                                <DeleteIcon class="h-4 w-4 mr-1" />
                                                Eliminar
                                            </button>
                                        </div>

                                        <!-- Item Details -->
                                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                            <!-- Description -->
                                            <div class="space-y-1">
                                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">
                                                    Descripción
                                                </label>
                                                <input v-model="detail.description" type="text"
                                                    class="block w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-500 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                                    placeholder="Descripción" />
                                            </div>

                                            <!-- Frequency -->
                                            <div class="space-y-1">
                                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">
                                                    Frecuencia (fc)
                                                </label>
                                                <input v-model.number="detail.fc" type="number" min="1"
                                                    class="block w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-500 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                                    placeholder="Frecuencia" />
                                            </div>

                                            <!-- Time Interval -->
                                            <div class="space-y-1">
                                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">
                                                    Intervalo (horas)
                                                </label>
                                                <input v-model.number="detail.time_interval" type="number" min="1"
                                                    class="block w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-500 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                                    placeholder="Intervalo" />
                                            </div>

                                            <!-- Drug Selection -->
                                            <div class="space-y-1">
                                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">
                                                    Medicamento
                                                </label>
                                                <button @click="openDrugModal(index)"
                                                    class="flex items-center w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-500 rounded-md hover:border-blue-400 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition duration-200">
                                                    <span v-if="detail.drug_id" class="truncate">
                                                        {{ drugs.data.find(d => d.id === detail.drug_id)?.name || 'ID: ' + detail.drug_id }}
                                                    </span>
                                                    <span v-else class="text-gray-400 dark:text-gray-400">Seleccionar</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0 md:space-x-6">
                                <!-- Error Message -->
                                <div v-if="error" class="flex-1">
                                    <div class="p-3 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                                        <div class="flex items-center text-red-600 dark:text-red-400">
                                            <span>{{ error }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Form Buttons -->
                                <div class="flex space-x-3">
                                    <SecondaryButton type="button"
                                        @click="form.reset()"
                                        class="hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-200">
                                        Limpiar
                                    </SecondaryButton>
                                    <PrimaryButton @click="submit()"
                                        :disabled="form.processing"
                                        :class="{ 'opacity-75': form.processing }"
                                        class="hover:bg-blue-600 transition duration-200">
                                        <span v-if="form.processing">Guardando...</span>
                                        <span v-else>Guardar Receta</span>
                                    </PrimaryButton>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Drug Selection Modal -->
                <Modal :show="showDrugModal" @close="showDrugModal = false" maxWidth="2xl">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-800 dark:text-white">
                                <RXIcon class="h-6 w-6 inline-block mr-2 text-blue-500" />
                                Seleccionar Medicamento
                            </h3>
                            <button @click="showDrugModal = false" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                <XIcon class="h-6 w-6" />
                            </button>
                        </div>
                        <DrugSelector
                            :drugs="drugs"
                            @selected="selectedDrugId = $event; addDrug()"
                        />
                    </div>
                </Modal>

                <!-- Patient Selection Modal -->
                <Modal :show="showPatientModal" @close="showPatientModal = false" maxWidth="2xl">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-800 dark:text-white">
                                <UserIcon class="h-6 w-6 inline-block mr-2 text-blue-500" />
                                Seleccionar Paciente
                            </h3>
                            <button @click="showPatientModal = false" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                <XIcon class="h-6 w-6" />
                            </button>
                        </div>
                        <PatientSelector
                            :patients="patient"
                            @selected="form.patient_id = $event.id, selected_patient = $event, showPatientModal = false"
                        />
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
import { useForm } from '@inertiajs/vue3';
import DangerButton from '@/Components/DangerButton.vue';
import DeleteIcon from '@/Components/Icons/DeleteIcon.vue';
import DiscountInput from '@/Components/DiscountInput.vue';
import PatientSelector from '@/Components/PatientSelector.vue';
import ProcedureSelector from '@/Components/ProcedureSelector.vue';
import DrugSelector from '@/Components/DrugSelector.vue';
import RXIcon from '@/Components/Icons/RXIcon.vue';

export default {
    props: {
        drugs: Object,
        patient: Object,
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
        DeleteIcon,
        DiscountInput,
        PatientSelector,
        ProcedureSelector,
        DrugSelector,
        DeleteIcon,
        RXIcon

    },
    data() {
        return {


            budget_id: '',
            form_details: [],
            selected_patient: '',
            timeout: 3000,
            crumbs: [
                { icon: markRaw(RXIcon), label: 'Recetas', to: route('prescriptions.index') },
                { icon: markRaw(AddIcon), label: 'Crear' },

            ],
            showDrugModal: ref(false),
            showPatientModal: ref(false),
            error: '',
            selectedDrugId: '',
            selectedDrugs: [],
            drugsList: this.drugs.data || [],
            form: useForm({
                patient_id: '',
                details: [
                    {
                        description: '',
                        fc: null,
                        time_interval: null,
                        drug_id: null,
                    }
                ],
            }),
        };

    },

    methods: {
        openDrugModal(index) {
            this.selectedDrugIndex = index;
            this.showDrugModal = true;
        },
        openPatientModal() {
            this.showPatientModal = true;
        },
        formatDate(date) {
            if (!date) return '';
            const d = new Date(date);
            return d.toLocaleDateString('es-DO', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
            });
        },

        addDrug() {
            const id = parseInt(this.selectedDrugId.id);

            const found = this.drugs.data.find(p => p.id === id);
            if (found && this.selectedDrugIndex !== null) {
                this.form.details[this.selectedDrugIndex].drug_id = id;
                this.selectedDrugId = null;
                this.selectedDrugIndex = null;
                this.showDrugModal = false;
            }
        },

        addPrescriptionDetail() {
            this.form.details.push({
                description: '',
                fc: null,
                time_interval: null,
                drug_id: null,
            });
        },
        removePrescriptionDetail(index) {
            this.form.details.splice(index, 1);
        },
        submit() {
            if (!this.form.patient_id) {
                this.errors.patient_id = 'Por favor, seleccione un paciente.';
                return;
            }

            if (this.form.details.length === 0) {
                this.error = 'Agregue al menos un detalle de receta.';
                return;
            }
            for (const detail of this.form.details) {
                if (!detail.description || !detail.fc || !detail.time_interval || !detail.drug_id) {
                    this.error = 'Complete todos los campos en los detalles de la receta.';
                    return;
                }
            }

            this.error = null;

            const data = {
                form: {
                    patient_id: this.form.patient_id,
                    type: this.form.type,
                    emission_date: this.form.emission_date,
                    expiration_date: this.form.expiration_date,
                    total: this.form.total,
                },
                details: this.form_details,
                prescription: this.form,
            };

            this.$inertia.post(route('prescriptions.store'), this.form, {
                onError: (errors) => {
                    this.form.errors = errors;
                }
            });

        }
    },

};
</script>




<style scoped>
p span:first-child {
    color: #6b7280;
}
</style>
