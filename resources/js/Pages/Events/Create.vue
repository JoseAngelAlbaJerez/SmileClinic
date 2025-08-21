<template>
    <AuthenticatedLayout>
        <template #header>
            <Breadcrumb :crumbs="crumbs" />
        </template>

        <template #default>
           <div class=" dark:bg-gray-900 py-8 px-4 sm:px-6 lg:px-8 ">
                <div class="max-w-4xl mx-auto">
                    <!-- Form Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg ">
                       <!-- Form Header -->
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4 rounded-t-xl">
                        <h2 class="text-xl font-bold text-white">Nueva Cita Médica</h2>
                        <p class="text-blue-100 text-sm">Complete los detalles de la cita</p>
                    </div>

                    <!-- Form Content -->
                    <form @submit.prevent="submit" class="space-y-6 p-6">
                        <!-- Title -->
                        <div class="space-y-1">
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Título <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <EditIcon class="h-5 w-5 text-gray-400 dark:text-gray-500" />
                                </div>
                                <input v-model="form.title" id="title" type="text"
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition duration-200"
                                    placeholder="Ej: Consulta de rutina" />
                            </div>
                            <p v-if="errors.title" class="mt-1 text-xs text-red-600 dark:text-red-400">{{ errors.title
                                }}</p>
                        </div>

                        <!-- Doctor & Patient Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Doctor -->
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Doctor <span class="text-red-500">*</span>
                                </label>
                                <div @click="openUserModal()"
                                    class="flex items-center cursor-pointer w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition duration-200">
                                    <UserIcon class="h-5 w-5 text-gray-400 dark:text-gray-500 mr-2" />
                                    <p v-if="form.doctor_id" class="truncate">
                                        {{ selected_doctor.name }} {{ selected_doctor.last_name }}
                                    </p>
                                    <p v-else class="text-gray-400 dark:text-gray-400">Seleccionar Doctor</p>
                                </div>
                                <p v-if="errors.doctor_id" class="mt-1 text-xs text-red-600 dark:text-red-400">{{
                                    errors.doctor_id }}</p>
                            </div>

                            <!-- Patient -->
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Paciente <span class="text-red-500">*</span>
                                </label>
                                <div @click="openPatientModal()"
                                    class="flex items-center cursor-pointer w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition duration-200">
                                    <UserIcon class="h-5 w-5 text-gray-400 dark:text-gray-500 mr-2" />
                                    <p v-if="form.patient_id" class="truncate">
                                        {{ selected_patient.first_name }} {{ selected_patient.last_name }}
                                    </p>
                                    <p v-else class="text-gray-400 dark:text-gray-400">Seleccionar Paciente</p>
                                </div>
                                <p v-if="errors.patient_id" class="mt-1 text-xs text-red-600 dark:text-red-400">{{
                                    errors.patient_id }}</p>
                            </div>
                        </div>

                        <!-- Date & Time Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Date -->
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Fecha <span class="text-red-500">*</span>
                                </label>
                                <VueDatePicker
                                    class="date-picker-custom border-gray-300 dark:border-gray-600 rounded-lg hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition duration-200"
                                    placeholder="Seleccione la fecha" v-model="form.date" :enable-time-picker="false" />
                            </div>

                            <!-- Time Range -->
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Horario <span class="text-red-500">*</span>
                                </label>
                                <VueDatePicker range v-model="timeRange" @update:model-value="onTimeRangeChange"
                                    :time-picker="true" :format="timeFormat" :is-24="true"
                                    format="HH:mm" :enable-time-picker="true" placeholder="Seleccione horario"
                                    class="date-picker-custom border-gray-300 dark:border-gray-600 rounded-lg hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition duration-200" />
                            </div>
                        </div>

                        <!-- Error Message -->
                        <div v-if="error"
                            class="p-4 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                            <div class="flex items-center text-red-600 dark:text-red-400">

                                <span>{{ error }}</span>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <SecondaryButton type="button" @click="form.reset(); timeRange = []"
                                class="hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-200">

                                Limpiar
                            </SecondaryButton>
                            <PrimaryButton type="submit" :disabled="form.processing"
                                :class="{ 'opacity-75': form.processing }"
                                class="hover:bg-blue-600 transition duration-200">

                                <span v-if="form.processing">Guardando...</span>
                                <span v-else>Guardar Cita</span>
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
            </div>

            <!-- Doctor Selection Modal -->
            <Modal :show="showUserModal" @close="showUserModal = false" maxWidth="2xl">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white">
                            <UserIcon class="h-6 w-6 inline-block mr-2 text-blue-500" />
                            Seleccionar Doctor
                        </h3>
                        <button @click="showUserModal = false" class="text-gray-400 hover:text-gray-500">
                            <XIcon class="h-6 w-6" />
                        </button>
                    </div>
                    <UserSelector :users="doctors"
                        @selected="form.doctor_id = $event.id, selected_doctor = $event, showUserModal = false" />
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
                        <button @click="showPatientModal = false" class="text-gray-400 hover:text-gray-500">
                            <XIcon class="h-6 w-6" />
                        </button>
                    </div>
                    <PatientSelector :patients="patients"
                        @selected="form.patient_id = $event.id, selected_patient = $event, showPatientModal = false" />
                </div>
            </Modal>
        </template>
    </AuthenticatedLayout>
</template>
<script>

import {
    Link, useForm
} from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Breadcrumb from '@/Components/BreadCrumb.vue';
import ArrowDownTrayIcon from '@/Components/Icons/ArrowDownTrayIcon.vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import InsuranceIcon from '@/Components/Icons/InsuranceIcon.vue';
import PhoneIcon from '@/Components/Icons/PhoneIcon.vue';
import CardIcon from '@/Components/Icons/CardIcon.vue';
import UserIcon from '@/Components/Icons/UserIcon.vue';
import LocationIcon from '@/Components/Icons/LocationIcon.vue';
import { ref } from 'vue';
import UserSelector from '@/Components/UserSelector.vue';
import Modal from '@/Components/Modal.vue';
import PatientSelector from '@/Components/PatientSelector.vue';
import AddIcon from '@/Components/Icons/AddIcon.vue';
import { markRaw } from 'vue';
import CalendarIcon from '@/Components/Icons/CalendarIcon.vue';
import EditIcon from '@/Components/Icons/EditIcon.vue';
import XIcon from '@/Components/Icons/XIcon.vue';
export default {
    props: {
        errors: [Array, Object],
        doctors: Object,
        patients: Object
    },
    components: {
        Link,
        PhoneIcon,
        CardIcon,
        UserIcon,
        PrimaryButton,
        SecondaryButton,
        AuthenticatedLayout,
        Breadcrumb,
        VueDatePicker,
        ArrowDownTrayIcon,
        InsuranceIcon,
        LocationIcon,
        UserSelector,
        Modal,
        PatientSelector,
        AddIcon,
        CalendarIcon,
        EditIcon,
        XIcon
    },
    data() {
        return {
            isVisible: false,
            form: useForm({
                title: '',
                doctor_id: '',
                patient_id: '',
                attended: false,
                starttime: '',
                endtime: '',
                date: new Date(),
                active: true,
            }),
            timeRange: [],
            timeFormat: 'HH:mm',
            error: '',
            showUserModal: ref(false),
            showPatientModal: ref(false),
            selected_patient: '',
            selected_doctor: '',
            crumbs: [
                { icon: markRaw(CalendarIcon), label: 'Citas', to: route('events.index') },
                { icon: markRaw(AddIcon), label: 'Crear' },

            ]
        };
    },
    methods: {
        onTimeRangeChange(range) {
            if (Array.isArray(range) && range.length === 2) {
                this.form.starttime = range[0];
                this.form.endtime = range[1];
            } else {
                this.form.starttime = null;
                this.form.endtime = null;
            }
        },
        openUserModal() {
            this.showUserModal = true;
        },
        openPatientModal() {
            this.showPatientModal = true;
        },
        submit() {


            if (!this.form.title) {
                this.error = 'Por favor, Ingrese un título.';
                return;
            }
            if (!this.form.doctor_id) {
                this.error = 'Por favor, seleccione un doctor.';
                return;
            }
            if (!this.form.patient_id) {
                this.error = 'Por favor, seleccione un paciente.';
                return;
            }
            if (!this.form.starttime) {
                this.error = 'Por favor, seleccione una hora de inicio.';
                return;
            }
            if (!this.form.endtime) {
                this.error = 'Por favor, seleccione una hora de finalización.';
                return;
            }

            const formatTime = (time) => {
                const h = time.hours.toString().padStart(2, '0');
                const m = time.minutes.toString().padStart(2, '0');
                return `${h}:${m}`;
            };
            this.form.starttime = formatTime(this.form.starttime);
            this.form.endtime = formatTime(this.form.endtime);

            if (this.form.starttime > this.form.endtime) {
                this.error = 'La hora de finalización debe ser después de la hora de inicio';
                return;
            }

            this.error = null;

            this.form.date = this.formatDate(this.form.date);

            this.form.post(route('events.store'),);

        },
        formatDate(date) {
            const d = new Date(date);
            return d.toISOString().split('T')[0];
        },
        formatTime(time) {
            const d = new Date(time);
            return d.toTimeString().split(' ')[0];
        }


    },

};
</script>
