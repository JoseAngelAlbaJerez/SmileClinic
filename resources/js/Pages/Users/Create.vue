<template>
    <AuthenticatedLayout>
        <template #header>

            <Breadcrumb :crumbs="crumbs" />

        </template>
        <template #default class="h-full bg-gray-50 dark:bg-gray-900 dark:text-white py-12">
            <div class="max-w-4xl mt-5 mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-lg p-10">

                <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <!-- First Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre
                            <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <UserIcon class="absolute left-3 top-2.5 text-gray-400 dark:text-gray-500"
                                style="pointer-events: none;" />
                            <input v-model="form.name" id="name" type="text"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                                placeholder="Nombre" />
                        </div>
                        <p v-if="errors.name" class="mt-1 text-xs text-red-600">{{ errors.name }}</p>
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label for="last_name"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Apellido <span
                                class="text-red-500">*</span></label>
                        <input v-model="form.last_name" id="last_name" type="text"
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                            placeholder="Apellido" />
                        <p v-if="errors.last_name" class="mt-1 text-xs text-red-600">{{ errors.last_name }}</p>
                    </div>

                    <!-- password -->
                    <div>
                        <label for="password"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Contraseña
                            <span class="text-red-500">*</span></label>
                        <div class="relative">

                            <input id="password" type="password"
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                                v-model="form.password" required autocomplete="new-password"  placeholder="Contraseña"  />
                            <p v-if="errors.password" class="mt-1 text-xs text-red-600">{{ errors.password }}</p>
                        </div>
                    </div>
                    <div>
                        <label for="password_confirmation"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confimar contraseña
                            <span class="text-red-500">*</span></label>
                        <div class="relative">

                            <input id="password_confirmation" type="password_confirmation"
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                                v-model="form.password_confirmation" required
                                autocomplete="new-password_confirmation" placeholder="Contraseña" />
                            <p v-if="errors.password_confirmation" class="mt-1 text-xs text-red-600">{{
                                errors.password_confirmation }}</p>
                        </div>
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label for="email"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Correo Electrónico
                            <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <MailIcon class="absolute left-3 top-2.5 text-gray-400 dark:text-gray-500"
                                style="pointer-events: none;" />
                            <input v-model="form.email" placeholder="Correo Electrónico"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white">
                            </input>
                        </div>
                        <p v-if="errors.email" class="mt-1 text-xs text-red-600">{{ errors.email }}</p>
                    </div>

                    <!-- Date of Birth -->
                    <div>

                        <div class=" w-full ">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                                for="select-item">Fecha de Nacimiento: <span class="text-red-500">*</span></label>
                            <VueDatePicker
                                class="border-gray-300  dark:bg-gray-500 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                                v-model="form.date_of_birth" />
                        </div>
                    </div>
                    <!-- role -->
                    <div class="col-span-full mb-2">
                        <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rol
                            <span class="text-red-500">*</span></label>
                        <select v-model="form.role" :value="form.role"
                            class="block w-full  py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white">
                            <option value=""> Seleccione una opción</option>
                            <option value="admin">Admin</option>
                            <option value="doctor">Doctor</option>
                            <option value="staff">Staff</option>
                            <option value="patient">Paciente</option>
                        </select>


                        <p v-if="errors.ars" class="mt-1 text-xs text-red-600">{{ errors.ars }}</p>
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
                        <PrimaryButton type="submit" :disabled="form.processing" :class="{ 'opacity-25': form.processing}" :is-loading="form.processing">Guardar</PrimaryButton>
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

import { markRaw } from 'vue';
import AddIcon from '@/Components/Icons/AddIcon.vue';
import MailIcon from '@/Components/Icons/MailIcon.vue';
import TextInput from '@/Components/TextInput.vue';
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
        MailIcon,
        TextInput
    },
    data() {
        return {
            isVisible: false,
            form: useForm({
                name: '',
                last_name: '',
                date_of_birth: '',
                email: '',
                password: '',
                password_confirmation: '',
                role: '',
            }),
            error: '',
            crumbs: [
                { icon: markRaw(UserIcon), label: 'Usuarios', to: route('users.index') },
                { icon: markRaw(AddIcon), label: 'Crear' }
            ]
        };
    },
    methods: {
        submit() {
            if (!this.form.name) {
                this.error = 'Por favor, ingrese el nombre.';
                return;
            }
              if (!this.form.role) {
                this.error = 'Por favor, seleccione el rol.';
                return;
            }
            if (!this.form.last_name) {
                this.error = 'Por favor, ingrese el apellido.';
                return;
            }
            if (!this.form.email) {
                this.error = 'Por favor, ingrese el DNI.';
                return;
            }

            if (!this.form.date_of_birth) {
                this.error = 'Por favor, seleccione la fecha de nacimiento.';
                return;
            }
            if (!this.form.password) {
                this.error = 'Por favor, ingrese la contraseña.';
                return;
            }
            if (!this.form.password_confirmation) {
                this.error = 'Por favor, confirme la contraseña.';
                return;
            }
            this.form.date_of_birth = this.formatDate(this.form.date_of_birth);

            this.error = null;
            this.form.post(route('users.store'),);

        },
        formatDate(date) {
            const d = new Date(date);
            return d.toISOString().split('T')[0];
        },

    },

};
</script>
