<template>

    <Head title="Sucursales" />
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
                            <h2 class="text-xl font-bold text-white">Registro de Sucursal</h2>
                            <p class="text-pink-100 text-sm">Complete la información requerida</p>
                        </div>

                        <!-- Form Content -->
                        <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
                            <!-- First Name -->
                            <div class="space-y-1 md:col-span-2">
                                <label for="name"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Nombre <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <BuildingIcon class="h-5 w-5 text-gray-400 dark:text-gray-500" />
                                    </div>
                                    <input v-model="form.name" id="name" type="text"
                                        class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white"
                                        placeholder="Nombre" />
                                </div>
                                <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name
                                    }}</p>
                            </div>


                            <!-- Address -->
                            <div class="space-y-1 md:col-span-2">
                                <label for="address"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Dirección
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <LocationIcon class="h-5 w-5 text-gray-400 dark:text-gray-500" />
                                    </div>
                                    <input v-model="form.address" id="address" type="text"
                                        class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white"
                                        placeholder="Dirección completa" />
                                </div>
                                <p v-if="errors.address" class="mt-1 text-sm text-red-600">{{ errors.address }}</p>

                            </div>


                            <!-- phone_number -->
                            <div class="space-y-1 md:col-span-2">
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
                                    <span v-else>Registrar Sucursal</span>
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
                name: '',
                address: '',
                phone_number: '',
            }),
            error: '',
            crumbs: [
                { icon: markRaw(UserIcon), label: 'Sucursales', to: route('branches.index') },
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
            if (!this.form.address) {
                this.error = 'Por favor, ingrese la dirección.';
                return;
            }
            if (!this.form.phone_number) {
                this.error = 'Por favor, ingrese el número de teléfono.';
                return;
            }

            this.error = null;
            this.form.post(route('branches.store'),);

        },
        formatDate(date) {
            const d = new Date(date);
            return d.toISOString().split('T')[0];
        },

    },

};
</script>
