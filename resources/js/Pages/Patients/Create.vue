<template>
    <Head title="Pacientes" />
    <AuthenticatedLayout>
        <template #header>
            <Breadcrumb :crumbs="crumbs" />
        </template>

        <template #default>
            <div class="min-h-full  dark:bg-gray-900 py-8 px-4 sm:px-6 lg:px-8">
                <div class="max-w-4xl mx-auto">
                    <!-- Form Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <!-- Card Header -->
                        <div class="bg-gradient-to-r from-pink-500 to-pink-600 px-6 py-4">
                            <h2 class="text-xl font-bold text-white">Registro de Paciente</h2>
                            <p class="text-pink-100 mt-1">Complete todos los campos requeridos</p>
                        </div>

                        <!-- Form Content -->
                        <div class="p-6 md:p-8">
                            <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Personal Information Section -->
                                <div class="md:col-span-2">
                                    <h3
                                        class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-pink-500"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Información Personal
                                    </h3>
                                </div>

                                <!-- First Name -->
                                <div>
                                    <label for="first_name"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Nombre <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <UserIcon class="h-5 w-5 text-gray-400 dark:text-gray-500" />
                                        </div>
                                        <input v-model="form.first_name" id="first_name" type="text"
                                            class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white"
                                            placeholder="Nombre" />
                                    </div>
                                    <p v-if="errors.first_name" class="mt-1 text-sm text-red-600">{{ errors.first_name
                                        }}</p>
                                </div>

                                <!-- Last Name -->
                                <div>
                                    <label for="last_name"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Apellido <span class="text-red-500">*</span>
                                    </label>
                                    <input v-model="form.last_name" id="last_name" type="text"
                                        class="block w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white"
                                        placeholder="Apellido" />
                                    <p v-if="errors.last_name" class="mt-1 text-sm text-red-600">{{ errors.last_name }}
                                    </p>
                                </div>

                                <!-- DNI -->
                                <div>
                                    <label for="DNI"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        DNI <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <CardIcon class="h-5 w-5 text-gray-400 dark:text-gray-500" />
                                        </div>
                                        <DNIInput v-model="form.DNI"
                                            class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white" />
                                    </div>
                                    <p v-if="errors.DNI" class="mt-1 text-sm text-red-600">{{ errors.DNI }}</p>
                                </div>

                                <!-- Phone Number -->
                                <div>
                                    <label for="phone_number"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Teléfono <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <PhoneIcon class="h-5 w-5 text-gray-400 dark:text-gray-500" />
                                        </div>
                                        <PhoneInput v-model="form.phone_number"
                                            class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white" />
                                    </div>
                                    <p v-if="errors.phone_number" class="mt-1 text-sm text-red-600">{{
                                        errors.phone_number }}</p>
                                </div>

                                <!-- ARS -->
                                <div>
                                    <label for="ars"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        ARS <span class="text-red-500">*</span>
                                    </label>
                                    <select v-model="form.ars"
                                        class="block w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white">
                                        <option value="">Seleccione una opción</option>
                                        <option value="ARS Humano">ARS Humano</option>
                                        <option value="ARS Universal">ARS Universal</option>
                                        <option value="ARS Monumental">ARS Monumental</option>
                                        <option value="ARS Senasa">ARS Senasa</option>
                                    </select>
                                    <p v-if="errors.ars" class="mt-1 text-sm text-red-600">{{ errors.ars }}</p>
                                </div>

                                <!-- Date of Birth -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Fecha de Nacimiento <span class="text-red-500">*</span>
                                    </label>
                                    <VueDatePicker v-model="form.date_of_birth"
                                        class="border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 [&_.dp__input]:dark:bg-gray-700 [&_.dp__input]:dark:text-white" />
                                </div>

                                <!-- Address -->
                                <div class="md:col-span-2">
                                    <label for="address"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Dirección <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <LocationIcon class="h-5 w-5 text-gray-400 dark:text-gray-500" />
                                        </div>
                                        <input v-model="form.address" id="address" type="text"
                                            class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white"
                                            placeholder="Dirección completa" />
                                    </div>
                                    <p v-if="errors.address" class="mt-1 text-sm text-red-600">{{ errors.address }}</p>
                                </div>

                                <!-- Medical Information Section -->
                                <div class="md:col-span-2 mt-6">
                                    <h3
                                        class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-pink-500"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M7 2a1 1 0 00-.707 1.707L7 4.414v3.758a1 1 0 01-.293.707l-4 4C.817 14.769 2.156 18 4.828 18h10.343c2.673 0 4.012-3.231 2.122-5.121l-4-4A1 1 0 0113 8.172V4.414l.707-.707A1 1 0 0013 2H7zm2 6.172V4h2v4.172a3 3 0 00.879 2.12l1.027 1.028a4 4 0 00-2.171.102l-.47.156a4 4 0 01-2.53 0l-.563-.187a1.993 1.993 0 00-.114-.035l1.063-1.063A3 3 0 009 8.172z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Información Médica
                                    </h3>
                                </div>

                                <!-- Complications -->
                                <div class="flex items-center space-x-3">
                                    <input id="complications" type="checkbox" v-model="form.complications"
                                        class="h-4 w-4 text-pink-600 border-gray-300 rounded focus:ring-pink-500" />
                                    <label for="complications"
                                        class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        ¿Tiene complicaciones médicas?
                                    </label>
                                </div>

                                <!-- Complications Detail -->
                                <div v-if="form.complications" class="md:col-span-2">
                                    <label for="complications_detail"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Detalle de complicaciones
                                    </label>
                                    <textarea id="complications_detail" v-model="form.complications_detail" rows="3"
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white"
                                        placeholder="Describe las complicaciones médicas"></textarea>
                                </div>

                                <!-- Drugs -->
                                <div class="flex items-center space-x-3">
                                    <input id="drugs" type="checkbox" v-model="form.drugs"
                                        class="h-4 w-4 text-pink-600 border-gray-300 rounded focus:ring-pink-500" />
                                    <label for="drugs" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        ¿Toma medicamentos?
                                    </label>
                                </div>

                                <!-- Drugs Detail -->
                                <div v-if="form.drugs" class="md:col-span-2">
                                    <label for="drugs_detail"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Detalle de medicamentos
                                    </label>
                                    <textarea id="drugs_detail" v-model="form.drugs_detail" rows="3"
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white"
                                        placeholder="Enumere los medicamentos"></textarea>
                                </div>

                                <!-- Allergies -->
                                <div class="flex items-center space-x-3">
                                    <input id="alergies" type="checkbox" v-model="form.alergies"
                                        class="h-4 w-4 text-pink-600 border-gray-300 rounded focus:ring-pink-500" />
                                    <label for="alergies" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        ¿Tiene alergias?
                                    </label>
                                </div>

                                <!-- Allergies Detail -->
                                <div v-if="form.alergies" class="md:col-span-2">
                                    <label for="alergies_detail"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Detalle de alergias
                                    </label>
                                    <textarea id="alergies_detail" v-model="form.alergies_detail" rows="3"
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white"
                                        placeholder="Describa las alergias"></textarea>
                                </div>

                                <!-- Consultation Motive -->
                                <div class="md:col-span-2">
                                    <label for="motive"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Motivo de consulta <span class="text-red-500">*</span>
                                    </label>
                                    <textarea id="motive" v-model="form.motive" rows="4"
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white"
                                        placeholder="Describa el motivo de la consulta"></textarea>
                                    <p v-if="errors.motive" class="mt-1 text-sm text-red-600">{{ errors.motive }}</p>
                                </div>

                                <!-- Error Message -->
                                <div v-if="error" class="md:col-span-2 p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 mr-2"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-red-700 dark:text-red-300 font-medium">{{ error }}</span>
                                    </div>
                                </div>

                                <!-- Form Actions -->
                                <div
                                    class="md:col-span-2 flex justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                                    <SecondaryButton type="button" @click="form.reset()" class="px-6 py-2.5">
                                        Limpiar
                                    </SecondaryButton>
                                    <PrimaryButton type="submit" :disabled="form.processing" class="px-6 py-2.5"
                                        :class="{ 'opacity-75': form.processing }">
                                        <span v-if="!form.processing">Guardar Paciente</span>
                                        <span v-else class="flex items-center">
                                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                            Procesando...
                                        </span>
                                    </PrimaryButton>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
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
import '@vuepic/vue-datepicker/dist/main.css';
import InsuranceIcon from '@/Components/Icons/InsuranceIcon.vue';
import PhoneIcon from '@/Components/Icons/PhoneIcon.vue';
import CardIcon from '@/Components/Icons/CardIcon.vue';
import UserIcon from '@/Components/Icons/UserIcon.vue';
import LocationIcon from '@/Components/Icons/LocationIcon.vue';
import { useToast } from 'vue-toastification';
import { markRaw } from 'vue';
import AddIcon from '@/Components/Icons/AddIcon.vue';
import PhoneInput from '@/Components/PhoneInput.vue';
import DNIInput from '@/Components/DNIInput.vue';
export default {
    props: {
        errors: [Array, Object],
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
        AddIcon,
        PhoneInput,
        DNIInput
    },
    data() {
        return {
            isVisible: false,
            form: useForm({
                first_name: '',
                last_name: '',
                DNI: '',
                phone_number: '',
                ars: '',
                date_of_birth: '',
                complications: false,
                complications_detail: '',
                drugs: false,
                drugs_detail: '',
                alergies: false,
                alergies_detail: '',
                address: '',
                motive: '',
            }),
            error: '',
            crumbs: [
                { icon: markRaw(UserIcon), label: 'Pacientes', to: route('patients.index') },
                { icon: markRaw(AddIcon), label: 'Crear' }
            ]
        };
    },
    methods: {
        submit() {
            if (!this.form.first_name) {
                this.error = 'Por favor, ingrese el nombre.';
                return;
            }
            if (!this.form.last_name) {
                this.error = 'Por favor, ingrese el apellido.';
                return;
            }
            if (!this.form.DNI) {
                this.error = 'Por favor, ingrese el DNI.';
                return;
            }
            if (!this.form.phone_number) {
                this.error = 'Por favor, ingrese el número de télefono.';
                return;
            }
            if (!this.form.ars) {
                this.error = 'Por favor, seleccione un seguro médico.';
                return;
            }
            if (!this.form.date_of_birth) {
                this.error = 'Por favor, seleccione la fecha de nacimiento.';
                return;
            }
            if (!this.form.address) {
                this.error = 'Por favor, ingrese la dirección.';
                return;
            }
            if (this.form.drugs == true && this.form.drugs_detail == '') {
                this.error = 'Por favor, asigne un detalle al medicamento.';
                return;
            }
            if (this.form.alergies == true && this.form.alergies_detail == '') {
                this.error = 'Por favor, asigne un detalle las alergias.';
                return;
            }
            if (this.form.complications == true && this.form.complications_detail == '') {
                this.error = 'Por favor, asigne un detalle a las complicaciones.';
                return;
            }

            this.form.date_of_birth = this.formatDate(this.form.date_of_birth);

            this.error = null;
            this.form.post(route('patients.store'),);

        },
        formatDate(date) {
            const d = new Date(date);
            return d.toISOString().split('T')[0];
        },

    },

};
</script>
