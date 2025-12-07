<template>

    <Head title="Odontograma" />
    <AuthenticatedLayout>
        <template #header>
            <Breadcrumb :crumbs="crumbs" />
        </template>

        <template #default class="h-full bg-gray-50 dark:bg-gray-900 dark:text-white py-12 ">
            <div class="max-w-7xl mt-10 mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-lg p-10">



                <form @submit.prevent="submit" class="grid grid-cols-1 gap-y-6">
                    <div>
                        <Odontograph v-model="odontogramData"  @update:patient="selected_patient = $event" :patients="patients" :patient="selected_patient" />
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
import EditIcon from '@/Components/Icons/EditIcon.vue';
import Odontograph from '@/Components/Odontograph.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { ref, markRaw } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import TeethIcon from '@/Components/Icons/TeethIcon.vue';

export default {
    components: {
        AuthenticatedLayout,
        Breadcrumb,
        UserIcon,
        EditIcon,
        Odontograph,
        PrimaryButton,
        SecondaryButton,
        Head
    },
    props: {
        odontographs: Object,
        patients: Object,

    },
    setup(props) {
        const error = ref(null);
        const odontogramData = ref(props.odontographs.data || {});
        const odontographKey = ref(Date.now());
        const selected_patient = ref(props.odontographs.patient || null);
        const resetForm = () => {
            odontogramData.value = {};
            odontographKey.value = Date.now();
            error.value = null;
        };
        const crumbs = [
            { icon: markRaw(UserIcon), label: 'Odontogramas', to: route('odontographs.index') },
            { icon: markRaw(TeethIcon), label: props.odontographs.id, to: route('odontographs.show', props.odontographs) },
            { icon: markRaw(EditIcon), label: 'Editar Odontograma' }
        ];

        const submit = () => {

            router.put(route('odontographs.update', props.odontographs), {
                patient_id: props.odontographs.patient.id,
                data: odontogramData.value,
            }, {
                onError: (e) => {
                    error.value = e || 'Error al actualizar el odontograma.';
                }
            });
        };

        return {
            error,
            odontogramData,
            odontographKey,
            crumbs,
            selected_patient,

            resetForm,
            submit
        };
    }
}
</script>
