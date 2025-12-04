<template>

    <Head title="Odontograma" />
    <AuthenticatedLayout>
        <template #header>
            <Breadcrumb :crumbs="crumbs" />
        </template>

        <template #default class="h-full bg-gray-50 dark:bg-gray-900 dark:text-white py-12 ">
            <div class="max-w-7xl mt-10 mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-lg p-10">



                <form @submit.prevent="submit" class="grid grid-cols-1  gap-y-6">
                    <div>
                        <Odontograph v-model="odontogramData" :patients="patients" :patient="selected_patient"
                            @update:patient="selected_patient = $event" />

                    </div>

                    <!-- Error general -->

                    <!-- Botones -->
                    <div class="md:col-span-2 flex justify-end space-x-4 mt-6">
                        <div v-if="error" class="mb-6 text-red-600 font-medium mr-auto">
                            {{ error }}
                        </div>
                        <SecondaryButton type="button" @click="resetForm()">
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
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Breadcrumb from '@/Components/BreadCrumb.vue';
import UserIcon from '@/Components/Icons/UserIcon.vue';
import AddIcon from '@/Components/Icons/AddIcon.vue';
import Odontograph from '@/Components/Odontograph.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { ref, markRaw } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import OdontographChild from '@/Components/OdontographChild.vue';
import PatientSelector from '@/Components/PatientSelector.vue';
import Modal from '@/Components/Modal.vue';
import XIcon from '@/Components/Icons/XIcon.vue';


export default {
    components: {
        AuthenticatedLayout,
        Breadcrumb,
        UserIcon,
        AddIcon,
        Odontograph,
        PrimaryButton,
        SecondaryButton,
        OdontographChild,
        Head,
        PatientSelector,
        Modal,
        XIcon
    },
    props: {
        patient: Object,
        patients: Object,
    },
    setup(props) {
        const error = ref(null);
        const odontogramData = ref({});
        const odontographKey = ref(Date.now());
        const selected_patient = ref(props.patient || null);
        const showPatientModal = ref(false);

        const resetForm = () => {
            odontogramData.value = {};
            odontographKey.value = Date.now();
            error.value = null;
        };
        const crumbs = [
            { icon: markRaw(UserIcon), label: 'Odontogramas', to: route('odontographs.index') },
            { icon: markRaw(AddIcon), label: 'Crear Odontograma' }
        ];

        const submit = () => {

            router.post(route('odontographs.store'), {
                patient_id: selected_patient.value.id,
                data: odontogramData.value,
            }, {
                onError: (e) => {
                    error.value = e || 'Error al guardar el odontograma.';
                }
            });
        };


        return {
            showPatientModal,
            selected_patient,
            error,
            odontogramData,
            odontographKey,
            crumbs,
            resetForm,
            submit
        };
    }
}
</script>
