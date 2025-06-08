<template>
    <AuthenticatedLayout>
        <template #header>
            <Breadcrumb :crumbs="crumbs" />
        </template>

        <div class="container mx-auto px-4 py-8">
            <form @submit.prevent="submit"
                class="space-y-6 bg-white dark:bg-gray-800  dark:text-white  p-6 rounded-2xl shadow-md">
                <h2 class="text-xl font-semibold mb-4">Editar Información del Paciente</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- First Name -->
                    <div>
                        <label for="first_name"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre</label>
                        <div class="relative">
                            <UserIcon class="absolute left-3 top-2.5 text-gray-400 dark:text-gray-500"
                                style="pointer-events: none;" />
                            <input v-model="form.first_name" id="first_name" type="text"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                                placeholder="Nombre" />
                        </div>
                        <p v-if="errors.first_name" class="mt-1 text-xs text-red-600">{{ errors.first_name }}</p>
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label for="last_name"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Apellido</label>
                        <input v-model="form.last_name" id="last_name" type="text"
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                            placeholder="Apellido" />
                        <p v-if="errors.last_name" class="mt-1 text-xs text-red-600">{{ errors.last_name }}</p>
                    </div>


                     <!-- DNI -->
                    <div>
                        <label for="DNI"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">DNI</label>
                             <div class="relative">
                                <CardIcon class="absolute left-3 top-2.5 text-gray-400 dark:text-gray-500"
                                style="pointer-events: none;" />
                        <input v-model="form.DNI" id="DNI" type="text"
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                            placeholder="Documento de Identidad" />
                        <p v-if="errors.DNI" class="mt-1 text-xs text-red-600">{{ errors.DNI }}</p>
                    </div></div>

                    <!-- Phone Number -->
                    <div>
                        <label for="phone_number"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Teléfono</label>
                        <div class="relative">
                            <PhoneIcon class="absolute left-3 top-2.5 text-gray-400 dark:text-gray-500"
                                style="pointer-events: none;" />
                            <input v-model="form.phone_number" id="phone_number" type="tel"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                                placeholder="Ej. 809-555-1234" />
                        </div>
                        <p v-if="errors.phone_number" class="mt-1 text-xs text-red-600">{{ errors.phone_number }}</p>
                    </div>

                    <!-- ARS -->
                    <div>
                        <label for="ars"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ARS</label>
                        <select v-model="form.ars"
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white">
                            <option value=""> Seleccione una opción</option>
                            <option value="ARS Humano">ARS Humano</option>
                            <option value="ARS Universal">ARS Universal</option>
                            <option value="ARS Monumental">ARS Monumental</option>
                            <option value="ARS Senasa">ARS Senasa</option>
                        </select>


                        <p v-if="errors.ars" class="mt-1 text-xs text-red-600">{{ errors.ars }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha de
                            Nacimiento</label>
                        <VueDatePicker v-model="form.date_of_birth"
                            class="border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white" />
                    </div>

                    <!-- Address (full width) -->
                    <div class="md:col-span-2">
                        <label for="address"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Dirección</label>
                         <div class="relative">
                            <LocationIcon class="absolute left-3 top-2.5 text-gray-400 dark:text-gray-500"
                                style="pointer-events: none;" />
                            <input v-model="form.address" id="address" type="text"
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                            placeholder="Dirección completa" />
                        <p v-if="errors.address" class="mt-1 text-xs text-red-600">{{ errors.address }}</p>
                    </div></div>

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
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
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
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
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
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                            placeholder="Describa las alergias"></textarea>
                    </div>

                    <!-- Motive (full width) -->
                    <div class="md:col-span-2">
                        <label for="motive"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Motivo de
                            consulta</label>
                        <textarea id="motive" v-model="form.motive" rows="3"
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                            placeholder="Describa el motivo de la consulta"></textarea>
                    </div>
                </div>

                <div v-if="error" class="text-red-500 text-sm">{{ error }}</div>

                <div class="flex justify-end space-x-4">
                    <Link :href="route('patients.show', patient)">
                    <SecondaryButton>Cancelar</SecondaryButton>
                    </Link>
                    <PrimaryButton type="submit">Guardar Cambios</PrimaryButton>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import { useForm, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Breadcrumb from '@/Components/BreadCrumb.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import UserIcon from '@/Components/Icons/UserIcon.vue';
import PhoneIcon from '@/Components/Icons/PhoneIcon.vue';
import LocationIcon from '@/Components/Icons/LocationIcon.vue';
import CardIcon from '@/Components/Icons/CardIcon.vue';
import { useToast } from 'vue-toastification'
import { markRaw } from 'vue';
const toast = useToast();
export default {
    props: {
        patient: Object,
        errors: Object,
    },
    components: {
        AuthenticatedLayout,
        Breadcrumb,
        PrimaryButton,
        SecondaryButton,
        Link,
        VueDatePicker,
        UserIcon,
        PhoneIcon,
        LocationIcon,
        CardIcon
    },
    data() {
        return {
            error: '',
            form: useForm({
                first_name: this.patient.first_name || '',
                last_name: this.patient.last_name || '',
                DNI: this.patient.DNI || '',
                phone_number: this.patient.phone_number || '',
                ars: this.patient.ars || '',
                date_of_birth: this.patient.date_of_birth || '',
                complications: this.patient.complications || false,
                complications_detail: this.patient.complications_detail || '',
                drugs: this.patient.drugs || false,
                drugs_detail: this.patient.drugs_detail || '',
                alergies: this.patient.alergies || false,
                alergies_detail: this.patient.alergies_detail || '',
                address: this.patient.address || '',
                motive: this.patient.motive || '',
            }),
             crumbs: [
                { icon: markRaw(UserIcon), label: 'Pacientes', to: route('patients.index') },
                { label: this.patient.first_name +' '+ this.patient.last_name, to: route('patients.show',this.patient) }
            ]
        };
    },
    methods: {
        submit() {
            if (this.form.drugs && !this.form.drugs_detail) {
                this.error = 'Debe indicar los medicamentos que usa.';
                return;
            }
            if (this.form.alergies && !this.form.alergies_detail) {
                this.error = 'Debe especificar las alergias.';
                return;
            }
            if (this.form.complications && !this.form.complications_detail) {
                this.error = 'Debe detallar las complicaciones.';
                return;
            }
            if (!this.form.date_of_birth) {
                this.error = 'Seleccione la fecha de nacimiento.';
                return;
            }

            this.error = '';
            this.form.put(route('patients.update', this.patient.id), {
                onSuccess: () => {
                        toast.success('Paciente actualizado correctamente.');

                },
                onError: () =>{
                     toast.error('Hubo un error al actualizar el paciente.');
                }
            });
        },
    },
};
</script>

<style scoped>
.input {
    width: 100%;
    padding: 0.5rem 0.75rem;
    border: 1px solid #cbd5e0;
    border-radius: 0.375rem;
    background-color: white;
}
</style>
