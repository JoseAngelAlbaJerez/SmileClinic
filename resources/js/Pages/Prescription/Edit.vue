<template>

    <Head title="Receta" />
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
                        <div class="bg-pink-500 px-6 py-4">
                            <h2 class="text-xl font-bold text-white">Editar Receta Médica</h2>
                            <p class="text-pink-100 text-sm">Complete los detalles de la prescripción</p>
                        </div>

                        <!-- Main Form Content -->
                        <div class="p-6 space-y-6">
                            <!-- Patient Selection -->
                            <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
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
                            </div>

                            <!-- Prescription Section -->
                            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-xl shadow-sm mt-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-2">
                                        <RXIcon class="w-6 h-6 text-pink-600 dark:text-pink-400" />
                                        <h3 class="text-lg font-semibold dark:text-white">Receta</h3>
                                    </div>
                                    <button @click="addPrescriptionDetail()"
                                        class="flex items-center space-x-1 rounded-lg bg-pink-500 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-pink-600 focus:outline-none focus:ring-2 focus:ring-pink-500 transition duration-200">
                                        <AddIcon class="size-4" />
                                    </button>
                                </div>

                                <!-- Empty State -->
                                <div v-if="!form.details.length" class="text-center py-6">
                                    <div
                                        class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-600">
                                        <SearchIcon class="h-6 w-6 text-gray-400 dark:text-gray-300" />
                                    </div>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No hay
                                        medicamentos</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Agregue medicamentos para
                                        continuar</p>
                                </div>

                                <!-- Prescription Items -->
                                <div v-else class="space-y-4">
                                    <div v-for="(detail, index) in form.details" :key="index"
                                        class="bg-white dark:bg-gray-600 border border-pink-200 dark:border-pink-400 rounded-lg p-4 shadow-sm hover:shadow-md transition duration-200">
                                        <!-- Item Header -->
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="flex items-center space-x-2">
                                                <h4 class="text-md font-semibold text-gray-800 dark:text-gray-100">
                                                    Medicamento # {{ index + 1 }}</h4>
                                            </div>
                                            <button @click="removePrescriptionDetail(index)" type="button"
                                                class="inline-flex items-center rounded-md bg-red-50 dark:bg-red-900/30 px-2 py-1 text-xs font-medium text-red-600 dark:text-red-300 ring-1 ring-inset ring-red-500/10 hover:bg-red-100 dark:hover:bg-red-800 transition duration-150">
                                                <DeleteIcon class="h-4 w-4 mr-1" />

                                            </button>
                                        </div>

                                        <!-- Item Details -->
                                        <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                                            <!-- Drug Selection -->
                                            <div class="space-y-1">
                                                <label
                                                    class="block text-xs font-medium text-gray-700 dark:text-gray-300">
                                                    Medicamento
                                                </label>

                                                <div class="flex items-center gap-2">
                                                    <!-- Botón de selección de medicamento -->
                                                    <button @click="openDrugModal(index)"
                                                        class="flex-1 flex items-center px-3 py-2 text-sm border border-gray-300 dark:border-gray-500 rounded-md hover:border-pink-400 focus:outline-none focus:ring-1 focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white transition duration-200">
                                                        <span v-if="detail.drug_id" class="truncate">
                                                            {{drugs.data.find(d => d.id === detail.drug_id)?.name ||
                                                                'ID: ' + detail.drug_id}}
                                                        </span>
                                                        <span v-else class="text-gray-400 dark:text-gray-400">Seleccione
                                                            un Medicamento</span>
                                                    </button>

                                                    <!-- Botón pequeño para agregar medicamento -->
                                                    <AccessGate permission="drug.create">
                                                        <button @click="openCreateDrugModal()" as="button"
                                                            class="flex justify-center items-center rounded-md bg-pink-500 px-2 py-1 text-xs font-semibold text-white shadow-sm hover:bg-pink-600 focus:outline-none focus:ring-2 focus:ring-pink-500">
                                                            <AddIcon class="size-4" />
                                                        </button>
                                                    </AccessGate>
                                                </div>
                                            </div>
                                            <!-- Description -->
                                            <div class="space-y-1">
                                                <label
                                                    class="block text-xs font-medium text-gray-700 dark:text-gray-300">
                                                    Descripción
                                                </label>
                                                <textarea v-model="detail.description" type="text"
                                                    class="block w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-500 rounded-md focus:outline-none focus:ring-1 focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white"
                                                    placeholder="Descripción" />
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div
                                class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0 md:space-x-6">
                                <!-- Error Message -->
                                <div v-if="error" class="flex-1">
                                    <div
                                        class="p-3 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                                        <div class="flex items-center text-red-600 dark:text-red-400">
                                            <span>{{ error }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Form Buttons -->
                                <div class="flex space-x-3">
                                    <SecondaryButton type="button" @click="form.reset()"
                                        class="hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-200">
                                        Limpiar
                                    </SecondaryButton>
                                    <PrimaryButton @click="submit()" :disabled="form.processing"
                                        :class="{ 'opacity-75': form.processing }"
                                        class="hover:bg-pink-600 transition duration-200">
                                        <span v-if="form.processing">Guardando...</span>
                                        <span v-else>Guardar</span>
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
                                <RXIcon class="h-6 w-6 inline-block mr-2 text-pink-500" />
                                Seleccionar Medicamento
                            </h3>
                            <button @click="showDrugModal = false"
                                class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                <XIcon class="h-6 w-6" />
                            </button>
                        </div>
                        <DrugSelector :drugs="drugs" @selected="selectedDrugId = $event; addDrug()" />
                    </div>
                </Modal>
                <!-- Drug Creation Modal -->
                <Modal :show="createDrugModal" @close="createDrugModal = false" maxWidth="2xl">
                    <form @submit.prevent="submitModal()">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl font-bold text-gray-800 dark:text-white">
                                    <RXIcon class="h-6 w-6 inline-block mr-2 text-pink-500" />
                                    Crear Medicamento
                                </h3>
                                <button @click="createDrugModal = false"
                                    class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                    <XIcon class="h-6 w-6" />
                                </button>
                            </div>
                            <div class="space-y-1 mb-2">
                                <label for="name"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Nombre <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input v-model="form_modal.name" id="name" type="text"
                                        class=" w-full pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white transition duration-200"
                                        placeholder="Nombre" />
                                </div>

                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Descripción<span class="text-red-500">*</span>
                                </label>
                                <textarea v-model="form_modal.description" type="text"
                                    class="block w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-500 rounded-md focus:outline-none focus:ring-1 focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white"
                                    placeholder="Descripción" />
                            </div>
                            <!-- Form Actions -->
                            <div class="flex justify-end space-x-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <SecondaryButton type="button" @click="form_modal.reset(); timeRange = []"
                                    class="hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-200">

                                    Limpiar
                                </SecondaryButton>
                                <PrimaryButton type="submit" :disabled="form_modal.processing"
                                    :class="{ 'opacity-75': form_modal.processing }"
                                    class="hover:bg-pink-600 transition duration-200">

                                    <span v-if="form_modal.processing">Guardando...</span>
                                    <span v-else>Guardar </span>
                                </PrimaryButton>
                            </div>
                        </div>
                    </form>
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
import DrugSelector from '@/Components/DrugSelector.vue';
import RXIcon from '@/Components/Icons/RXIcon.vue';
import SearchIcon from '@/Components/Icons/SearchIcon.vue';
import XIcon from '@/Components/Icons/XIcon.vue';
import AccessGate from '@/Components/AccessGate.vue';
import EditIcon from '@/Components/Icons/EditIcon.vue';

export default {
    props: {
        drugs: Object,
        patient: Object,
        prescription: Object,
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
        RXIcon,
        SearchIcon,
        XIcon,
        AccessGate,
        Head

    },
    data() {
        return {


            budget_id: '',
            form_details: [],
            selected_patient: this.prescription.patient,
            timeout: 3000,
            crumbs: [
                { icon: markRaw(RXIcon), label: 'Recetas', to: route('prescriptions.index') },
                { icon: markRaw(EditIcon), label: 'Editar' },

            ],
            showDrugModal: ref(false),
            createDrugModal: ref(false),
            showPatientModal: ref(false),
            error: '',
            selectedDrugId: '',
            selectedDrugs: this.prescription.prescriptions_details.map(d => d.drug) || [],
            drugsList: this.drugs.data || [],
            form: useForm({
                patient_id: this.prescription.patient.id,
                details: this.prescription.prescriptions_details.map(d => ({
                    description: d.description || '',
                    drug_id: d.drug_id || null,
                })),
            }),
            form_modal: useForm({
                description: '',
                name: '',
                active: true,
            }),
        };

    },

    methods: {
        openDrugModal(index) {
            this.selectedDrugIndex = index;
            this.showDrugModal = true;
        },
        openCreateDrugModal() {
            this.createDrugModal = true;
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
                if (!detail.description || !detail.drug_id) {
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

            this.$inertia.put(route('prescriptions.update',this.prescription), this.form, {
                onError: (errors) => {
                    this.form.errors = errors;
                }
            });

        },
        submitModal() {
            this.form_modal.post(route('drugs.store'));
            this.createDrugModal = false;
        }
    },

};
</script>




<style scoped>
p span:first-child {
    color: #6b7280;
}
</style>
