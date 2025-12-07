<script setup>
import { ref, computed } from 'vue';
import AccessGate from './AccessGate.vue';
import AddIcon from './Icons/AddIcon.vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
    patients: Object,
});

// Estado
const searchTerm = ref('');
const selectedPatientId = ref(null);
const emit = defineEmits(['selected']);
const filtered = computed(() => {
    if (!searchTerm.value) return props.patients.data;

    return props.patients.data.filter(p =>
        (p.first_name + " " + p.last_name)
            .toLowerCase()
            .includes(searchTerm.value.toLowerCase())
    );
});

const selectPatient = (patient) => {
    if (selectedPatientId.value === patient.id) {
        selectedPatientId.value = null;
        emit('selected', null);
    } else {
        selectedPatientId.value = patient.id;
        emit('selected', patient);
    }
};

// Navegar páginas del backend
const goTo = (url) => {
    if (url) {
        router.visit(url, { preserveState: true, preserveScroll: true });
    }
};
</script>

<template>
    <div class="my-4 mx-4 lg:mx-10">

        <!-- Búsqueda frontend -->
        <div class="flex justify-between gap-4">
            <input v-model="searchTerm" type="text" placeholder="Buscar paciente..."
                class="mb-4 w-full px-4 py-2 border rounded-md dark:bg-gray-800 dark:text-white" />

            <AccessGate permission="patient.create">
                <Link :href="route('patients.create')" as="button"
                    class="flex justify-center mb-3 rounded-lg bg-pink-500 px-2 py-2 text-sm text-white shadow-sm hover:bg-pink-600 sm:px-4">
                    <AddIcon class="size-6" />
                </Link>
            </AccessGate>
        </div>

        <!-- Tabla -->
        <div class="relative overflow-x-auto border rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs bg-gray-50 dark:bg-gray-700 dark:text-gray-100">
                    <tr>
                        <th class="px-4 py-3 hidden sm:table-cell">#</th>
                        <th class="px-6 py-3">Nombre</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="patient in filtered" :key="patient.id"
                        class="cursor-pointer"
                        :class="{
                            'bg-pink-500 text-white': selectedPatientId === patient.id,
                            'hover:bg-gray-200 dark:hover:bg-gray-800': selectedPatientId !== patient.id
                        }"
                        @click="selectPatient(patient)"
                    >
                        <td class="p-4 hidden sm:table-cell">{{ patient.id }}</td>
                        <td class="p-4">{{ patient.first_name }} {{ patient.last_name }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Sin resultados -->
            <div v-if="!filtered.length" class="text-center py-4">
                No hay registros disponibles.
            </div>
        </div>

        <!-- Paginación del Backend -->
        <div class="flex justify-center items-center gap-2 mt-4">
            <button v-for="link in props.patients.links"
                :key="link.label"
                v-html="link.label"
                @click="goTo(link.url)"
                :class="[
                    'px-3 py-1 rounded',
                    link.active
                        ? 'bg-pink-500 text-white'
                        : 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white hover:bg-gray-300'
                ]"
                :disabled="!link.url"
            />
        </div>
    </div>
</template>
