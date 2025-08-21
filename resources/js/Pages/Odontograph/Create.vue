<template>
    <AuthenticatedLayout>
        <template #header>
            <Breadcrumb :crumbs="crumbs" />
        </template>

        <template #default class="h-full bg-gray-50 dark:bg-gray-900 dark:text-white py-12 ">
            <div class="max-w-7xl mt-10 mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-lg p-10">



                <form @submit.prevent="submit" class="grid grid-cols-1 gap-y-6">
                    <div v-if="patient.age >= 18">
                        <Odontograph v-model="odontogramData" />
                    </div>
                    <div v-else>
                        <OdontographChild v-model="odontogramData" />
                    </div>
                    <!-- Error general -->
                    <div v-if="error" class="mb-6 text-red-600 font-medium">
                        {{ error }}
                    </div>
                    <!-- Botones -->
                    <div class="md:col-span-2 flex justify-end space-x-4 mt-6">
                        <SecondaryButton type="button" @click="resetForm()">
                            Limpiar
                        </SecondaryButton>
                        <PrimaryButton type="submit" >Guardar</PrimaryButton>
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
import { router } from '@inertiajs/vue3';
import OdontographChild from '@/Components/OdontographChild.vue';

export default {
    components: {
        AuthenticatedLayout,
        Breadcrumb,
        UserIcon,
        AddIcon,
        Odontograph,
        PrimaryButton,
        SecondaryButton,
        OdontographChild
    },
    props: {
        patient: Object,
    },
    setup(props) {
        const error = ref(null);
        const odontogramData = ref({});
        const odontographKey = ref(Date.now());

        const resetForm = () => {
            odontogramData.value = {};
            odontographKey.value = Date.now();
            error.value = null;
        };
        const crumbs = [
            { icon: markRaw(UserIcon), label: 'Pacientes', to: route('patients.index') },
            { icon: markRaw(UserIcon), label: `${props.patient.first_name} ${props.patient.last_name}`, to: route('patients.show', props.patient) },
            { icon: markRaw(AddIcon), label: 'Crear Odontograma' }
        ];

        const submit = () => {

            router.post(route('odontographs.store'), {
                patient_id: props.patient.id,
                data: odontogramData.value,
            }, {
                onError: (e) => {
                    error.value = e || 'Error al guardar el odontograma.';
                }
            });
        };

        return {
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
