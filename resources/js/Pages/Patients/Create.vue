<template>
    <AuthenticatedLayout>
        <template #header>

            <Breadcrumb :crumbs="[
                { label: 'Pacientes', to: route('patients.index') },
                { label: 'Crear Paciente' }
            ]" />

        </template>
        <template #default class="h-full bg-gray-50 dark:bg-slate-900 dark:text-white py-12">
            <div class="max-w-4xl mt-5 mx-auto bg-white dark:bg-slate-800 rounded-xl shadow-lg p-10">
                <!-- Título -->
                <h2 class="text-3xl font-semibold mb-8 text-gray-900 dark:text-white">

                </h2>

                <!-- Error general -->
                <div v-if="error" class="mb-6 text-red-600 font-medium">
                    {{ error }}
                </div>

                <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <!-- First Name -->
                    <div>
                        <label for="first_name"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre</label>
                        <div class="relative">
                            <UserIcon class="absolute left-3 top-2.5 text-gray-400 dark:text-gray-500"
                                style="pointer-events: none;" />
                            <input v-model="form.first_name" id="first_name" type="text"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-white"
                                placeholder="Nombre" />
                        </div>
                        <p v-if="errors.first_name" class="mt-1 text-xs text-red-600">{{ errors.first_name }}</p>
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label for="last_name"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Apellido</label>
                        <input v-model="form.last_name" id="last_name" type="text"
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-white"
                            placeholder="Apellido" />
                        <p v-if="errors.last_name" class="mt-1 text-xs text-red-600">{{ errors.last_name }}</p>
                    </div>

                    <!-- DNI -->
                    <div>
                        <label for="DNI"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">DNI</label>
                        <input v-model="form.DNI" id="DNI" type="text"
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-white"
                            placeholder="Documento de Identidad" />
                        <p v-if="errors.DNI" class="mt-1 text-xs text-red-600">{{ errors.DNI }}</p>
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label for="phone_number"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Teléfono</label>
                        <div class="relative">
                            <PhoneIcon class="absolute left-3 top-2.5 text-gray-400 dark:text-gray-500"
                                style="pointer-events: none;" />
                            <input v-model="form.phone_number" id="phone_number" type="tel"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-white"
                                placeholder="Ej. 809-555-1234" />
                        </div>
                        <p v-if="errors.phone_number" class="mt-1 text-xs text-red-600">{{ errors.phone_number }}</p>
                    </div>

                    <!-- ARS -->
                    <div>
                        <label for="ars"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ARS</label>
                        <select v-model="form.ars"
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-white">
                            <option value=""> Seleccione una opción</option>
                            <option value="ARS Humano">ARS Humano</option>
                            <option value="ARS Universal">ARS Universal</option>
                            <option value="ARS Monumental">ARS Monumental</option>
                            <option value="ARS Senasa">ARS Senasa</option>
                        </select>


                        <p v-if="errors.ars" class="mt-1 text-xs text-red-600">{{ errors.ars }}</p>
                    </div>

                    <!-- Date of Birth -->
                    <div>

                       <div class="mb-4 px-2 w-full-mt-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="select-item">Fecha de Nacimiento:</label>

                        <VueDatePicker class="border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-white" v-model="form.date_of_birth" />
                    </div> </div>

                    <!-- Address (full width) -->
                    <div class="md:col-span-2">
                        <label for="address"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Dirección</label>
                        <input v-model="form.address" id="address" type="text"
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-white"
                            placeholder="Dirección completa" />
                        <p v-if="errors.address" class="mt-1 text-xs text-red-600">{{ errors.address }}</p>
                    </div>

                    <!-- Complications Checkbox -->
                    <div class="flex items-center space-x-3">
                        <input id="complications" type="checkbox" v-model="form.complications"
                            class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500" />
                        <label for="complications" class="text-gray-700 dark:text-gray-300 font-medium">
                            ¿Tiene complicaciones médicas?
                        </label>
                    </div>

                    <!-- Complications Detail -->
                    <div v-if="form.complications" class="md:col-span-2">
                        <label for="complications_detail"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Detalle de
                            complicaciones</label>
                        <textarea id="complications_detail" v-model="form.complications_detail" rows="3"
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-white"
                            placeholder="Describe las complicaciones médicas"></textarea>
                    </div>

                    <!-- Drugs Checkbox -->
                    <div class="flex items-center space-x-3">
                        <input id="drugs" type="checkbox" v-model="form.drugs"
                            class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500" />
                        <label for="drugs" class="text-gray-700 dark:text-gray-300 font-medium">
                            ¿Toma medicamentos?
                        </label>
                    </div>

                    <!-- Drugs Detail -->
                    <div v-if="form.drugs" class="md:col-span-2">
                        <label for="drugs_detail"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Detalle de
                            medicamentos</label>
                        <textarea id="drugs_detail" v-model="form.drugs_detail" rows="3"
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-white"
                            placeholder="Enumere los medicamentos"></textarea>
                    </div>

                    <!-- Allergies Checkbox -->
                    <div class="flex items-center space-x-3">
                        <input id="alergies" type="checkbox" v-model="form.alergies"
                            class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500" />
                        <label for="alergies" class="text-gray-700 dark:text-gray-300 font-medium">
                            ¿Tiene alergias?
                        </label>
                    </div>

                    <!-- Allergies Detail -->
                    <div v-if="form.alergies" class="md:col-span-2">
                        <label for="alergies_detail"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Detalle de
                            alergias</label>
                        <textarea id="alergies_detail" v-model="form.alergies_detail" rows="3"
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-white"
                            placeholder="Describa las alergias"></textarea>
                    </div>

                    <!-- Motive (full width) -->
                    <div class="md:col-span-2">
                        <label for="motive"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Motivo de
                            consulta</label>
                        <textarea id="motive" v-model="form.motive" rows="3"
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-white"
                            placeholder="Describa el motivo de la consulta"></textarea>
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
        LocationIcon
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

        };
    },
    methods: {
        submit() {

            if (this.form.drugs == true && this.form.drugs_detail == '') {
                this.error = 'Por favor, asigne un detalle al medicamento.';
                return;
            }
            if (this.form.alergies == true && this.form.alergies_detail == '') {
                this.error = 'Por favor, asigne un detalle al medicamento.';
                return;
            }
            if (this.form.complications == true && this.form.complications_detail == '') {
                this.error = 'Por favor, asigne un detalle al medicamento.';
                return;
            }
            if (!this.form.date_of_birth) {
                this.error = 'Por favor, seleccione la fecha de nacimiento.';
                return;
            }
            this.form.date_of_birth = this.formatDate(this.form.date_of_birth);

            this.error = null;
            this.form.post(route('patients.store'), {

                onSuccess: () => {

                    this.form.reset();

                }
            });

        },
        formatDate(date) {
            const d = new Date(date);
            return d.toISOString().split('T')[0];
        },

    },

};
</script>
