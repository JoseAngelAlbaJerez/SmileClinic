<script setup>
import { ref } from 'vue';
import Pagination from './Pagination.vue';

const props = defineProps({
    message: String,
    patients: Object,
});

const emit = defineEmits(['selected']);

const selectedPatientId = ref(null);

const selectPatient = (patient) => {
    if (selectedPatientId.value === patient.id) {
        selectedPatientId.value = null;
        emit('selected', null);
    } else {
        selectedPatientId.value = patient.id;
        emit('selected', patient);
    }
};
</script>

<template>
    <div class="relative overflow-x-auto border border-gray-200 dark:border-gray-700/60 rounded-lg my-4 mx-4 lg:mx-10 ">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-100">
                <tr>
                    <th scope="col" class="px-4 py-3 cursor-pointer whitespace-nowrap hidden sm:table-cell">#</th>
                    <th scope="col" class="px-6 py-3 cursor-pointer">Nombre</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="patient in patients.data"
                    :key="patient.id"
                    class="cursor-pointer"
                    :class="{
                        'bg-blue-500 text-gray-900 dark:bg-blue-500 dark:text-white': selectedPatientId === patient.id,
                        'hover:bg-gray-200 hover:text-gray-800': selectedPatientId !== patient.id
                    }"
                    @click="selectPatient(patient)"
                >
                    <td class="p-4 items-center">{{ patient.id }}</td>
                    <td class="p-4 items-center">{{ patient.first_name }} {{ patient.last_name }}</td>
                </tr>
            </tbody>
        </table>

        <div v-if="!patients.data.length" class="text-center text-gray-500 dark:text-gray-400 py-4 w-full">
            No hay registros disponibles.
        </div>

        <Pagination :pagination="patients" />
    </div>
    <h2 v-if="selectedPatientId" class="text-sm text-green-500 lg:mx-10 mx-4">
        Paciente Seleccionado
    </h2>
</template>
