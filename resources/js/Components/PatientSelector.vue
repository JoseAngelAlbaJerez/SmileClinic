<script setup>
import { ref, watch } from 'vue';
import AccessGate from './AccessGate.vue';
import AddIcon from './Icons/AddIcon.vue';
import { Link } from '@inertiajs/vue3';
import axios from "axios";

const props = defineProps({
    message: String,
    patients: Object, // listado inicial desde backend
    filters: Object,  // filtros actuales
});

const emit = defineEmits(['selected']);

// Estado local
const patients = ref(props.patients);
const searchTerm = ref(props.filters?.name ?? '');
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

const fetchPatients = async (pageUrl = null) => {
    try {
        const url = pageUrl || route('patients.filter');

        const params = {};
        if (searchTerm.value) {
            params.filters = { name: searchTerm.value };
        }

        const response = await axios.get(url, { params });

        if (response.data && response.data.patients) {
            patients.value = response.data.patients;
        }
    } catch (error) {
        console.error("Error cargando pacientes", error);
    }
};



// Resetear a página 1 cuando cambie búsqueda
watch(searchTerm, () => {
    fetchPatients(route('patients.filter'));
});
</script>

<template>
    <div class="my-4 mx-4 lg:mx-10">
        <!-- Barra de búsqueda -->
        <div class="flex justify-between gap-4">
            <input v-model="searchTerm" type="text" placeholder="Buscar paciente por nombre..."
                class="mb-4 w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white" />

            <AccessGate permission="patient.create">
                <Link :href="route('patients.create')" as="button"
                    class="flex justify-center mb-3 rounded-lg bg-green-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 sm:px-4">
                    <AddIcon class="size-6" />
                </Link>
            </AccessGate>
        </div>

        <!-- Tabla -->
        <div class="relative overflow-x-auto border border-gray-200 dark:border-gray-700/60 rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-100">
                    <tr>
                        <th scope="col" class="px-4 py-3 hidden sm:table-cell">#</th>
                        <th scope="col" class="px-6 py-3">Nombre</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="patient in patients.data" :key="patient.id" class="cursor-pointer" :class="{
                        'bg-blue-500 text-gray-900 dark:bg-blue-500 dark:text-white': selectedPatientId === patient.id,
                        'hover:bg-gray-200 hover:text-gray-800': selectedPatientId !== patient.id
                    }" @click="selectPatient(patient)">
                        <td class="p-4 hidden sm:table-cell">{{ patient.id }}</td>
                        <td class="p-4">{{ patient.first_name }} {{ patient.last_name }}</td>
                    </tr>
                </tbody>
            </table>

            <div v-if="!patients.data.length" class="text-center text-gray-500 dark:text-gray-400 py-4 w-full">
                No hay registros disponibles.
            </div>

            <!-- Paginación LOCAL -->
            <div v-if="patients.last_page > 1" class="flex justify-center items-center gap-2 py-3">
                <button
                    :disabled="!patients.prev_page_url"
                    @click="fetchPatients(patients.prev_page_url)"
                    class="px-3 py-1 border rounded disabled:opacity-50"
                >
                    Anterior
                </button>

                <span class="text-sm text-gray-600 dark:text-gray-300">
                    Página {{ patients.current_page }} de {{ patients.last_page }}
                </span>

                <button
                    :disabled="!patients.next_page_url"
                    @click="fetchPatients(patients.next_page_url)"
                    class="px-3 py-1 border rounded disabled:opacity-50"
                >
                    Siguiente
                </button>
            </div>
        </div>

        <!-- Indicador -->
        <h2 v-if="selectedPatientId" class="text-sm text-green-500 mt-2">
            Paciente Seleccionado
        </h2>
    </div>
</template>
