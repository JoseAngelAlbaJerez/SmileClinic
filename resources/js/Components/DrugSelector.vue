<script setup>
import { ref, computed } from 'vue';
import Pagination from './Pagination.vue';
import AddIcon from './Icons/AddIcon.vue';
import AccessGate from './AccessGate.vue';
const props = defineProps({
    message: String,
    drugs: Object,
});

const emit = defineEmits(['selected']);

const selectedDrugs = ref(null);
const searchTerm = ref('');
const selectDrug = (drug) => {
    if (selectedDrugs.value === drug.id) {
        selectedDrugs.value = null;
        emit('selected', null);
    } else {
        selectedDrugs.value = drug.id;
        emit('selected', drug);
    }
};
const filteredDrugs = computed(() => {
    if (!searchTerm.value.trim()) return props.drugs.data;
    return props.drugs.data.filter((drugs) =>
        `${drugs.name}`.toLowerCase().includes(searchTerm.value.toLowerCase())
    );
});
</script>

<template>
    <div class="my-4 mx-4 lg:mx-10">
        <div class="flex justify-between gap-4">
            <input v-model="searchTerm" type="text" placeholder="Buscar medicamentos por nombre..."
                class="mb-4 w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 dark:bg-gray-800 dark:text-white" />

        </div>
   <div class="relative overflow-x-auto border border-gray-200 dark:border-gray-700/60 rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-100">
                <tr>
                    <th scope="col" class="px-4 py-3 cursor-pointer whitespace-nowrap hidden sm:table-cell">#</th>
                    <th scope="col" class="px-6 py-3 cursor-pointer">Nombre</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="drug in filteredDrugs"
                    :key="drug.id"
                    class="cursor-pointer"
                    :class="{
                        'bg-pink-500 text-gray-900 dark:bg-pink-500 dark:text-white': selectedDrugs === drug.id,
                        'hover:bg-gray-200 hover:text-gray-800': selectedDrugs !== drug.id
                    }"
                    @click="selectDrug(drug)"
                >
                    <td class="p-4 items-center">{{ drug.id }}</td>
                    <td class="p-4 items-center">{{ drug.name }}</td>
                </tr>
            </tbody>
        </table>

        <div v-if="!filteredDrugs.length" class="text-center text-gray-500 dark:text-gray-400 py-4 w-full">
            No hay registros disponibles.
        </div>

        <Pagination :pagination="drugs" />

    </div>

    <h2 v-if="selectedDrugs" class="text-sm text-green-500 lg:mx-10 mx-4">
        Medicamento Seleccionado
    </h2>
    </div>
</template>
