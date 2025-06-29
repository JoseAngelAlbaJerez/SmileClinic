<template>
    <AuthenticatedLayout>
        <template #header>
            <Breadcrumb :crumbs="crumbs" />
        </template>
        <template #default class="h-full bg-gray-50 dark:bg-gray-900 dark:text-white py-12">
            <div class="max-w-7xl mt-5 mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-lg p-10">



                <div @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-4 gap-x-8 gap-y-6">

                    <!-- Paciente -->
                    <div>
                        <label for="patient_id"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Paciente <span
                                class="text-red-500">*</span></label>
                        <button @click="openPatientModal()"
                            class="block w-full  pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white">
                            <p v-if="form.patient_id">{{ selected_patient.first_name }} {{ selected_patient.last_name }}
                            </p>
                            <p v-else>Seleccionar Paciente</p>
                        </button>


                        <p v-if="errors.patient_id" class="mt-1 text-xs text-red-600">{{ errors.patient_id }}</p>
                    </div>



                </div>
                <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded-2xl shadow-md mt-5">
                    <div class="my-2  flex items-center gap-2">
                        <RXIcon class="w-6 h-6 text-blue-600 dark:text-blue-400 mb-3" />
                        <h2 class="text-lg font-semibold dark:text-white mb-3">Receta</h2>

                        <button @click="addPrescriptionDetail()"
                            class=" ml-auto flex justify-center gap-2 rounded-lg bg-green-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-500 sm:px-4">
                            <AddIcon> </AddIcon>
                        </button>
                    </div>




                    <div v-for="(detail, index) in form.details" :key="index"
                        class="mt-6 p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-base font-semibold text-gray-700 dark:text-gray-100">
                                # - {{ index + 1 }}
                            </h3>
                            <button @click="removePrescriptionDetail(index)"
                                class="text-red-600 hover:text-red-800 transition" title="Eliminar detalle">
                                <DeleteIcon />
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <!-- Descripción -->
                            <div>
                                <label class="block mb-1 text-sm font-medium dark:text-gray-300">
                                    Descripción
                                </label>
                                <input v-model="detail.description" type="text"
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                                    placeholder="Descripción" />
                            </div>

                            <!-- Frecuencia -->
                            <div>
                                <label class="block mb-1 text-sm font-medium dark:text-gray-300">
                                    Frecuencia (fc)
                                </label>
                                <input v-model.number="detail.fc" type="number" min="1"
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                                    placeholder="Frecuencia" />
                            </div>

                            <!-- Intervalo de tiempo -->
                            <div>
                                <label class="block mb-1 text-sm font-medium dark:text-gray-300">
                                    Intervalo (horas)
                                </label>
                                <input v-model.number="detail.time_interval" type="number" min="1"
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                                    placeholder="Intervalo" />
                            </div>

                            <!-- Medicamento -->
                            <div>
                                <label class="block mb-1 text-sm font-medium dark:text-gray-300">
                                    Medicamento
                                </label>
                                <button @click="openDrugModal(index)"
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md  placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white">
                                    <p v-if="detail.drug_id">
                                        {{drugs.data.find(d => d.id === detail.drug_id)?.name || 'ID: ' +
                                        detail.drug_id }}
                                    </p>

                                    <p v-else class="text-gray-500 dark:text-gray-400">Seleccionar Medicamento</p>
                                </button>
                            </div>
                        </div>
                    </div>

                    <p v-if="error" class="mt-1 text-xs text-red-600">{{ error }}</p>

                </div>




                <!-- Botones -->
                <div class="md:col-span-4 flex justify-end space-x-4 mt-6">
                    <SecondaryButton type="button" @click="form.reset()">
                        Limpiar
                    </SecondaryButton>
                    <PrimaryButton @click="submit()">Guardar</PrimaryButton>
                </div>

            </div>


            <!-- Modal -->
            <Modal :show="showDrugModal" @close="showDrugModal = false" class="lg:max-w-2xl">
                <div class="text-gray-800 p-12 p-8  ">
                    <h2 class="text-2xl font-semibold mb-4 text-blue-500  pb-2">
                        Seleccionar Medicamento
                    </h2>
                    <DrugSelector :drugs="drugs" @selected="selectedDrugId = $event; addDrug()"></DrugSelector>

                </div>
            </Modal>
            <!-- Modal -->
            <Modal :show="showPatientModal" @close="showPatientModal = false" class="lg:max-w-2xl">
                <div class="text-gray-800 p-8  ">
                    <h2 class="text-2xl font-semibold mb-4 text-blue-500  pb-2">
                        Seleccionar Paciente
                    </h2>

                    <PatientSelector :patients="patient"
                        @selected="form.patient_id = $event.id, selected_patient = $event, showPatientModal = false" />
                </div>
            </Modal>
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
