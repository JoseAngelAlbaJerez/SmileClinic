<template>
    <AuthenticatedLayout>
        <template #header>

            <Breadcrumb :crumbs="crumbs" />

        </template>
        <template #default class="h-full bg-gray-50 dark:bg-gray-900 dark:text-white py-12">
            <div class="max-w-4xl mt-5 mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-lg p-10">



                <form @submit.prevent="submit" class="gap-x-8 gap-y-6 space-y-6">
                    <!-- Title -->
                    <div>
                        <label for="title"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Título</label>
                        <div class="relative">
                            <UserIcon class="absolute left-3 top-2.5 text-gray-400 dark:text-gray-500"
                                style="pointer-events: none;" />
                            <input v-model="form.title" id="title" type="text"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                                placeholder="Título de la cita" />
                        </div>
                        <p v-if="errors.title" class="mt-1 text-xs text-red-600">{{ errors.title }}</p>
                    </div>
                    <!-- Doctor -->
                    <div>
                        <label for="doctor_id"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Doctor</label>
                        <div @click="openUserModal()"
                            class="block cursor-pointer w-full text-left px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white">
                            <p v-if="form.doctor_id">{{ event.doctor.name }} {{ event.doctor.last_name }}
                            </p>
                            <p v-else>Seleccionar Doctor</p>
                        </div>



                        <p v-if="errors.ars" class="mt-1 text-xs text-red-600">{{ errors.doctor_id }}</p>
                    </div>
                    <!-- Paciente -->
                    <div>
                        <label for="patient_id"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Paciente</label>

                        <div @click="openPatientModal()"
                            class="block cursor-pointer  w-full text-left px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white">
                            <p v-if="form.patient_id">{{ event.patient.first_name }} {{ event.patient.last_name }}
                            </p>
                            <p v-else>Seleccionar Paciente</p>
                        </div>



                        <p v-if="errors.ars" class="mt-1 text-xs text-red-600">{{ errors.patient_id }}</p>
                    </div>

                    <!-- Date -->
                    <div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                                for="select-item">Fecha:</label>

                            <VueDatePicker
                                class="border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                                placeholder="Seleccione la fecha" v-model="form.date" />
                        </div>
                    </div>

                    <!-- starttime -->
                    <div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                                for="select-item">Hora de Inicio:</label>

                            <VueDatePicker v-model="form.starttime" model-type="format" :time-picker="true"
                                :is-24="true" :minutes-increment="5" format="HH:mm"
                                placeholder="Seleccione hora de inicio"
                                class="border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white" />

                        </div>
                    </div>
                    <!-- endtime-->
                    <div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                                for="select-item">Hora de Finalización:</label>

                            <VueDatePicker v-model="form.endtime" model-type="format" :time-picker="true" :is-24="true"
                                :minutes-increment="5" format="HH:mm" placeholder="Seleccione hora de finalización"
                                class="border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white" />

                        </div>
                    </div>
                    <!-- Error general -->
                    <div v-if="error" class="mb-6 text-red-600 font-medium">
                        {{ error }}
                    </div>

                    <!-- Botones -->
                    <div class="md:col-span-2 flex justify-end space-x-4 mt-6">
                        <SecondaryButton type="button" @click="form.reset()">
                            Limpiar
                        </SecondaryButton>
                        <PrimaryButton type="submit">Guardar</PrimaryButton>
                    </div>
                </form>
            </div>


            <!-- Modal -->
            <Modal :show="showUserModal" @close="showUserModal = false" class="lg:max-w-2xl">
                <div class="text-gray-800 p-12 p-8  ">
                    <h2 class="text-2xl font-semibold mb-4 text-blue-500  pb-2">
                        Seleccionar Doctor
                    </h2>
                    <UserSelector :users="doctors"
                        @selected="form.doctor_id = $event.id, selected_doctor = $event, showUserModal = false">
                    </UserSelector>

                </div>
            </Modal>
            <!-- Modal -->
            <Modal :show="showPatientModal" @close="showPatientModal = false" class="lg:max-w-2xl">
                <div class="text-gray-800 p-8  ">
                    <h2 class="text-2xl font-semibold mb-4 text-blue-500  pb-2">
                        Seleccionar Paciente
                    </h2>

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
import { markRaw } from 'vue';
import CalendarIcon from '@/Components/Icons/CalendarIcon.vue';
export default {
    props: {
        errors: [Array, Object],
        event: Object,
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
        CalendarIcon

    },
    data() {
        return {
            isVisible: false,
            form: useForm({
                title: this.event.title,
                doctor_id: this.event.doctor_id,
                patient_id: this.event.patient_id,

                starttime: this.event.starttime,
                endtime: this.event.endtime,
                date: this.event.date,


            }),
            error: '',
            showUserModal: ref(false),
            showPatientModal: ref(false),
            selected_patient: '',
            selected_doctor: '',
              crumbs: [
                {icon: markRaw(CalendarIcon),  label: 'Citas', to: route('events.index') },
                {icon: markRaw(UserIcon), label: this.event.patient.first_name + ' ' + this.event.patient.last_name, to: route('patients.show',this.event.patient) },
                {  label: 'Editar' },
            ]
        };
    },
    methods: {
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
            if (this.form.starttime > this.form.endtime) {
                this.error = 'La hora finalización debe ser despues de la hora de inicio';
                return;
            };


            this.error = null;

            this.form.date = this.formatDate(this.form.date);

            this.form.put(route('events.update',this.event));

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
