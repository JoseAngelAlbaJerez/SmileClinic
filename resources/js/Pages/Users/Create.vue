<template>
    <Head title="Usuarios" />
    <AuthenticatedLayout>
        <template #header>

            <Breadcrumb :crumbs="crumbs" />

        </template>
        <template #default>
            <div class="min-h-full dark:bg-gray-900 py-8 px-4 sm:px-6 lg:px-8">
                <div class="max-w-4xl mx-auto">
                    <!-- Form Card -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-lg  transition-all duration-300 hover:shadow-xl">
                        <!-- Form Header with Gradient -->
                        <div class="bg-gradient-to-r from-pink-500 to-pink-600 px-6 py-4">
                            <h2 class="text-xl font-bold text-white">Registro de Usuario</h2>
                            <p class="text-pink-100 text-sm">Complete la información requerida</p>
                        </div>

                        <!-- Form Content -->
                        <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
                            <!-- First Name -->
                            <div class="space-y-1">
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Nombre <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <UserIcon class="h-5 w-5 text-gray-400 dark:text-gray-500" />
                                    </div>
                                    <input v-model="form.first_name" id="first_name" type="text"
                                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white transition duration-200"
                                        placeholder="Nombre" />
                                </div>
                                <p v-if="errors.first_name" class="mt-1 text-xs text-red-600 dark:text-red-400">{{ errors.name
                                    }}</p>
                            </div>

                            <!-- Last Name -->
                            <div class="space-y-1">
                                <label for="last_name"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Apellido <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <UserIcon class="h-5 w-5 text-gray-400 dark:text-gray-500" />
                                    </div>
                                    <input v-model="form.last_name" id="last_name" type="text"
                                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white transition duration-200"
                                        placeholder="Apellido" />
                                </div>
                                <p v-if="errors.last_name" class="mt-1 text-xs text-red-600 dark:text-red-400">{{
                                    errors.last_name }}</p>
                            </div>

                            <!-- Email -->
                            <div class="space-y-1">
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Correo Electrónico <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <MailIcon class="h-5 w-5 text-gray-400 dark:text-gray-500" />
                                    </div>
                                    <input v-model="form.email" id="email" type="email"
                                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white transition duration-200"
                                        placeholder="correo@ejemplo.com" />
                                </div>
                                <p v-if="errors.email" class="mt-1 text-xs text-red-600 dark:text-red-400">{{
                                    errors.email }}</p>
                            </div>

                            <!-- Date of Birth -->
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Fecha de Nacimiento <span class="text-red-500">*</span>
                                </label>
                                <VueDatePicker
                                    class="date-picker-custom border-gray-300 dark:border-gray-600 rounded-lg hover:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white transition duration-200"
                                    v-model="form.date_of_birth" :enable-time-picker="false"
                                    placeholder="Seleccione fecha" />
                                <p v-if="errors.date_of_birth" class="mt-1 text-xs text-red-600 dark:text-red-400">{{
                                    errors.date_of_birth }}</p>
                            </div>

                            <!-- DNI -->
                            <div class="space-y-1">
                                <label for="DNI"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    DNI <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <CardIcon class="h-5 w-5 text-gray-400 dark:text-gray-500" />
                                    </div>
                                    <DNIInput v-model="form.DNI"
                                        class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white" />
                                </div>
                                <p v-if="errors.DNI" class="mt-1 text-sm text-red-600">{{ errors.DNI }}</p>
                            </div>

                            <!-- phone_number -->
                            <div class="space-y-1">
                                <label for="phone_number"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Teléfono <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <PhoneIcon class="h-5 w-5 text-gray-400 dark:text-gray-500" />
                                    </div>
                                    <PhoneInput v-model="form.phone_number"
                                        class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white" />
                                </div>
                                <p v-if="errors.phone_number" class="mt-1 text-sm text-red-600">{{
                                    errors.phone_number }}</p>

                            </div>
                            <!-- Position -->
                            <div class="space-y-1">
                                <label for="position"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Posición <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <BuildingIcon class="h-5 w-5 text-gray-400 dark:text-gray-500" />
                                    </div>
                                    <input v-model="form.position" id="position" type="text"
                                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white transition duration-200"
                                        placeholder="Posición" />
                                </div>
                                <p v-if="errors.position" class="mt-1 text-xs text-red-600 dark:text-red-400">{{
                                    errors.position }}</p>
                            </div>
                            <!-- Specialty -->
                            <div class="space-y-1">
                                <label for="specialty"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Especialidad <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <AcademicCapIcon class="h-5 w-5 text-gray-400 dark:text-gray-500" />
                                    </div>
                                    <input v-model="form.specialty" id="specialty" type="text"
                                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white transition duration-200"
                                        placeholder="Especialidad" />
                                </div>
                                <p v-if="errors.specialty" class="mt-1 text-xs text-red-600 dark:text-red-400">{{
                                    errors.specialty }}</p>
                            </div>
                            <!-- Password -->
                            <div class="space-y-1">
                                <label for="password"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Contraseña <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <LockClosedIcon class="h-5 w-5 text-gray-400 dark:text-gray-500" />
                                    </div>
                                    <input v-model="form.password" id="password" type="password"
                                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white transition duration-200"
                                        placeholder="••••••••" autocomplete="new-password" />
                                </div>
                                <p v-if="errors.password" class="mt-1 text-xs text-red-600 dark:text-red-400">{{
                                    errors.password }}</p>
                            </div>

                            <!-- Confirm Password -->
                            <div class="space-y-1">
                                <label for="password_confirmation"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Confirmar Contraseña <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <LockClosedIcon class="h-5 w-5 text-gray-400 dark:text-gray-500" />
                                    </div>
                                    <input v-model="form.password_confirmation" id="password_confirmation"
                                        type="password"
                                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white transition duration-200"
                                        placeholder="••••••••" autocomplete="new-password" />
                                </div>
                                <p v-if="errors.password_confirmation"
                                    class="mt-1 text-xs text-red-600 dark:text-red-400">{{ errors.password_confirmation
                                    }}</p>
                            </div>

                            <!-- Role Selection -->
                            <div class="md:col-span-2 space-y-1">
                                <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Rol <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <UserIcon class="h-5 w-5 text-gray-400 dark:text-gray-500" />
                                    </div>
                                    <select v-model="form.role" id="role"
                                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white transition duration-200 appearance-none">
                                        <option value="">Seleccione un rol</option>
                                        <option value="admin">Administrador</option>
                                        <option value="doctor">Doctor</option>
                                        <option value="staff">Personal</option>
                                        <option value="patient">Paciente</option>
                                    </select>
                                </div>
                                <p v-if="errors.role" class="mt-1 text-xs text-red-600 dark:text-red-400">{{ errors.role
                                    }}</p>
                            </div>

                            <!-- Error Message -->
                            <div v-if="error"
                                class="md:col-span-2 p-4 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                                <div class="flex items-center text-red-600 dark:text-red-400">

                                    <span>{{ error }}</span>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div
                                class="md:col-span-2 flex justify-end space-x-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <SecondaryButton type="button" @click="form.reset()"
                                    class="hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-200">

                                    Limpiar
                                </SecondaryButton>
                                <PrimaryButton type="submit" :disabled="form.processing"
                                    :class="{ 'opacity-75': form.processing }"
                                    class="hover:bg-pink-600 transition duration-200">

                                    <span v-if="form.processing">Registrando...</span>
                                    <span v-else>Registrar Usuario</span>
                                </PrimaryButton>
                            </div>
                        </form>
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

import { markRaw } from 'vue';
import AddIcon from '@/Components/Icons/AddIcon.vue';
import MailIcon from '@/Components/Icons/MailIcon.vue';
import TextInput from '@/Components/TextInput.vue';
import LockClosedIcon from '@/Components/Icons/LockClosedIcon.vue';
import DNIInput from '@/Components/DNIInput.vue';
import PhoneInput from '@/Components/PhoneInput.vue';
import AcademicCapIcon from '@/Components/Icons/AcademicCapIcon.vue';
import BuildingIcon from '@/Components/Icons/BuildingIcon.vue';

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
        TextInput,
        LockClosedIcon,
        DNIInput,
        PhoneInput,
        AcademicCapIcon,
        BuildingIcon
    },
    data() {
        return {
            isVisible: false,
            form: useForm({
                first_name: '',
                last_name: '',
                date_of_birth: '',
                email: '',
                password: '',
                password_confirmation: '',
                role: '',
                DNI: '',
                specialty: '',
                position: '',
                phone_number: '',
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
            if (!this.form.first_name) {
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
