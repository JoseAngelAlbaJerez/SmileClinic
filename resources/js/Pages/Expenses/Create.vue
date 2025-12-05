<template>

    <Head title="Citas" />
    <AuthenticatedLayout>
        <template #header>
            <Breadcrumb :crumbs="crumbs" />
        </template>

        <template #default>
            <div class="min-h-full  dark:bg-gray-900 py-8 px-4 sm:px-6 lg:px-8">
                <div class="max-w-4xl mx-auto">
                      <!-- Form Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg ">
                        <!-- Form Header -->
                        <div class="bg-pink-500  dark:bg-pink-600 px-6 py-4 rounded-t-xl">
                            <h2 class="text-xl font-bold text-white">Nuevo Egreso</h2>
                            <p class="text-pink-100 text-sm">Complete los detalles del egreso</p>
                        </div>

                        <!-- Form Content -->
                        <form @submit.prevent="submit" class="space-y-6 p-6">
                            <!-- Descripción -->
                            <div>
                                <label for="description"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descripción</label>
                                <div class="relative">
                                    <UserIcon class="absolute left-3 top-2.5 text-gray-400 dark:text-gray-500"
                                        style="pointer-events: none;" />

                                    <select v-model="form.description"
                                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-800 dark:text-white">
                                        <option disabled value="">Seleccione un tipo de egreso</option>
                                        <option value="Luz">Luz</option>
                                        <option value="Local">Local</option>
                                        <option value="Envíos">Envíos</option>
                                        <option value="Depósito">Depósito</option>
                                        <option value="TSS">TSS</option>
                                        <option value="Contabilidad">Contabilidad</option>
                                        <option value="Laboratorio">Laboratorio</option>
                                        <option value="Empleado">Empleado</option>
                                        <option value="Publicidad">Publicidad</option>
                                        <option value="Teléfono">Teléfono</option>
                                        <option value="Gastos ">Gastos </option>
                                        <option value="Dra. Castro">Dra. Castro</option>
                                        <option value="Dra. Madelin">Dra. Madelin</option>
                                        <option value="Dra. Odalisa">Dra. Odalisa</option>
                                        <option value="Dra. Orquídea ">Dra. Orquídea </option>
                                        <option value="Dra. Paloma">Dra. Paloma</option>
                                        <option value="Dr. Francisco">Dr. Francisco</option>


                                    </select>
                                </div>
                                <p v-if="errors.description" class="mt-1 text-xs text-red-600">{{ errors.description }}
                                </p>

                            </div>
                            <div>
                                <label for="amount"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Monto</label>
                                <div class="relative">
                                    <DocumentMoney class="absolute left-3 top-2.5 text-gray-400 dark:text-gray-500"
                                        style="pointer-events: none;" />
                                    <input v-model="form.amount" id="amount" type="number"
                                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-800 dark:text-white"
                                        placeholder="Monto" />
                                </div>
                                <p v-if="errors.amount" class="mt-1 text-xs text-red-600">{{ errors.amount }}</p>
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
                                    class="hover:bg-pink-600 transition duration-200">

                                    <span v-if="form.processing">Guardando...</span>
                                    <span v-else>Guardar Egreso</span>
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
    Head,
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
import CartIcon from '@/Components/Icons/CartIcon.vue';
import DocumentMoney from '@/Components/Icons/DocumentMoney.vue';
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
        UserSelector,
        Modal,
        PatientSelector,
        AddIcon,
        CalendarIcon,
        EditIcon,
        XIcon,
        Head,
        CartIcon,
        DocumentMoney
    },
    data() {
        return {
            isVisible: false,
            form: useForm({
                description: '',
                amount: '',
            }),
            error: '',
            crumbs: [
                { icon: markRaw(CartIcon), label: 'Egresos', to: route('expenses.index') },
                { icon: markRaw(AddIcon), label: 'Crear' },

            ]
        };
    },
    methods: {



        submit() {

            this.error = null;
            this.form.post(route('expenses.store'),);
        },
        formatDate(date) {
            const d = new Date(date);
            return d.toISOString().split('T')[0];
        },
        formatTime(time) {
            if (!time) return ''
            return time.substring(0, 5)
        }


    },

};
</script>
