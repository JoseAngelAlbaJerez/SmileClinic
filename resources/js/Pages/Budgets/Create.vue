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
                    <!-- Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo
                            <span class="text-red-500">*</span></label>
                        <select v-model="form.type" :value="form.type"
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white">
                            <option value="Contado">Contado</option>
                            <option value="Crédito">Crédito</option>

                        </select>


                        <p v-if="errors.type" class="mt-1 text-xs text-red-600">{{ errors.type }}</p>
                    </div>



                    <!-- emission_date -->
                    <div class="mb-1">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                            for="select-item">Fecha de emisión: <span class="text-red-500">*</span></label>

                        <VueDatePicker
                            class="border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                            placeholder="Seleccione la fecha de emisión" v-model="form.emission_date" />
                    </div>
                    <!-- expiration_date -->
                    <div class="mb-1" v-if="form.type == 'Crédito'">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                            for="select-item">Fecha de expiración: <span class="text-red-500">*</span></label>
                        <VueDatePicker
                            class="border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                            placeholder="Seleccione la fecha de expiración" v-model="form.expiration_date" />


                    </div>



                </div>
                <!-- Presupuestos -->
                <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded-2xl shadow-md mt-5">
                    <div class="my-2  flex items-center gap-2">
                        <DocumentMoney class="w-6 h-6 text-blue-600 dark:text-blue-400 mb-1" />
                        <h2 class="text-lg font-semibold dark:text-white ">Procedimientos</h2>
                        <button @click="openProcedureModal()"
                            class=" ml-auto flex justify-center gap-2 rounded-lg bg-green-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-500 sm:px-4">
                            <AddIcon> </AddIcon>
                        </button>
                    </div>

                    <div class="bg-white  my-2 border border-blue-500 dark:border-blue-400 shadow-md dark:bg-gray-700 p-4 rounded-xl shadow-sm hover:shadow-md transition duration-200"
                        v-if="selectedProcedures.length" v-for="(proc, index) in selectedProcedures" :key="proc.id">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="text-md font-semibold text-gray-800 dark:text-gray-100"># - {{ proc.id }} {{
                                proc.name }}</h3>
                            <span class="text-sm font-medium px-2 py-1 rounded-full"
                                :class="proc.coberture ? 'bg-green-100 text-green-600 dark:bg-green-800 dark:text-green-300' : 'bg-red-100 text-red-600 dark:bg-red-800 dark:text-red-300'">
                                {{ proc.coberture ? 'Asegurado' : 'No Asegurado' }}
                            </span>

                        </div>

                        <div
                            class="text-sm text-gray-700 dark:text-gray-300 space-y-1  grid grid-cols-1 md:grid-cols-4 gap-x-8 gap-y-6">
                            <div>
                                <label for="treatment"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tratamiento
                                    <span class="text-red-500">*</span></label>
                                <input v-model="form_details[index].treatment" id="treatment" type="text"
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                                    placeholder="Tratamiento" />
                                <p v-if="form.errors[`details.${index}.treatment`]" class="text-red-600 text-xs">
                                    {{ form.errors[`details.${index}.treatment`] }}
                                </p>
                            </div>
                            <div>
                                <label for="amount"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Monto
                                    <span class="text-red-500">*</span></label>
                                <input v-model="form_details[index].amount" id="amount" type="number"
                                    @input="calcTotal(index)"
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                                    placeholder="Monto" />
                                <p v-if="form.errors[`details.${index}.amount`]" class="text-red-600 text-xs">
                                    {{ form.errors[`details.${index}.amount`] }}
                                </p>
                            </div>

                            <div>
                                <label for="discount"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descuento
                                </label>
                                <DiscountInput v-model="form_details[index].discount" @input="calcTotal(index)" />
                                <p v-if="form.errors[`details.${index}.discount`]" class="text-red-600 text-xs">
                                    {{ form.errors[`details.${index}.discount`] }}
                                </p>
                            </div>
                            <div>
                                <label for="quantity"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cantidad
                                    <span class="text-red-500">*</span></label>
                                <input v-model="form_details[index].quantity" value="1" id="quantity" type="number"
                                    @input="calcTotal(index)"
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                                    placeholder="1" />
                                <p v-if="form.errors[`details.${index}.quantity`]" class="text-red-600 text-xs">
                                    {{ form.errors[`details.${index}.quantity`] }}
                                </p>

                            </div>

                        </div>
                        <div class=" flex  gap-2 ">
                            <h3 class="text-md font-semibold text-gray-800 dark:text-gray-100">Subtotal: ${{
                                formatNumber(form_details[index].total) }}</h3>
                            <DangerButton @click="removeProcedure(index)"
                                class="flex  ml-auto mt-2  gap-2 rounded-lg bg-red-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 sm:px-4">
                                <DeleteIcon />
                            </DangerButton>


                        </div>

                    </div>
                    <div v-else class="text-center text-gray-500 dark:text-gray-400 py-4 w-full">
                        Seleccione un procedimiento.
                    </div>
                </div>
                <!-- Error general -->
                <div v-if="errors.length" class="mt-2 text-red-600 font-medium">
                    {{ errors }}
                </div>
                <div v-if="form.total" class="mt-2 ml-2 text-red-600 font-medium">
                    Total: ${{ formatNumber(form.total) }}
                </div>


                <!-- Botones -->
                <div class="md:col-span-4 flex justify-end space-x-4 mt-6">
                    <SecondaryButton type="button" @click="form.reset() && form_detail.reset()">
                        Limpiar
                    </SecondaryButton>
                    <PrimaryButton @click="submit()">Guardar</PrimaryButton>
                </div>
            </div>


            <!-- Modal -->
            <Modal :show="showProcedureModal" @close="showProcedureModal = false" class="lg:max-w-2xl">
                <div class="text-gray-800 p-12 p-8  ">
                    <h2 class="text-2xl font-semibold mb-4 text-blue-500  pb-2">
                        Seleccionar Procedimiento
                    </h2>
                    <ProcedureSelector :procedures="procedure"
                        @selected="selectedProcedureId = $event.id; addProcedure()"></ProcedureSelector>

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
        DeleteIcon,
        DiscountInput,
        PatientSelector,
        ProcedureSelector

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

            detail.total = (amount * quantity) - discount;
            this.form.total = this.form_details.reduce((sum, detail) => {
                const total = parseFloat(detail.total) || 0;
                return sum + total;
            }, 0);
        },

        removeProcedure(index) {
            const detail = this.form_details[index];
            const amount = parseFloat(detail.amount) || 0;
            const discount = parseFloat(detail.discount) || 0;
            const quantity = parseInt(detail.quantity) || 1;
            detail.total = (amount * quantity) - discount;
            this.form.total = this.form.total - detail.total;
            this.selectedProcedures.splice(index, 1);
        },
        deletePatient(id) {
            this.$inertia.delete(route('patients.destroy', id),);
        },
        restorePatient() {
            this.$inertia.put(route('patients.update', this.patient.id), {
                active: true
            },
            );
        },
        openProcedureModal() {
            this.showProcedureModal = true;
        },
        openPatientModal() {
            this.showPatientModal = true;
        },
        deleteOdontograph(id) {
            this.$inertia.delete(route('odontographs.destroy', id),);
        },
        restoreOdontograph(id) {
            this.$inertia.put(route('odontographs.update', id), {
                active: true
            },
            );
        },
        deleteEvent(id) {
            this.$inertia.delete(route('events.destroy', id));
        },
        restoreEvent(id) {
            this.$inertia.put(route('events.update', id),
                { active: true },


            );
        },
        formatNumber(n) {
            return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
        getDefaultDatetime() {
            const now = new Date();
            return now.toISOString().slice(0, 16); // "2025-06-10T20:05"
        },
        submit() {
            if (!this.form.patient_id) {
                this.errors.patient_id = 'Por favor, seleccione un paciente.';
                return;
            }

            if (this.form.type === 'Crédito' && !this.form.expiration_date) {
                this.error = 'Por favor, ingrese la fecha de expiración.';
                return;
            }

            if (this.selectedProcedures.length <= 0) {
                this.error = 'Por favor, seleccione un procedimiento.';
                return;
            }
            this.error = null;



            const payload = {
                form: {
                    patient_id: this.form.patient_id,
                    type: this.form.type,
                    emission_date: this.form.emission_date,
                    expiration_date: this.form.expiration_date,
                    total: this.form.total,
                },
                details: this.form_details,
            };

            this.$inertia.post(route('budgets.store'), payload, {
                onError: (errors) => {
                    this.form.errors = errors;
                }
            });


        }
        ,

        toggleshowDeleted() {

            this.form.showDeleted = !this.form.showDeleted;

            this.submitFilters();

        },
        submitFilters() {
            if (this.timeout) {
                clearTimeout(this.timeout);
            }

            this.form.search = this.filters.search

            this.timeout = setTimeout(() => {
                this.$inertia.get(route('patients.show', this.patient), this.form, {
                    preserveState: true,
                    preserveScroll: true,
                    replace: true
                });
            }, 300);
        },

    },
};
</script>




<style scoped>
p span:first-child {
    color: #6b7280;
}
</style>
