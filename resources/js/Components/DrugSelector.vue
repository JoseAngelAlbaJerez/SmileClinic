<script setup>
import { ref } from 'vue';
import Pagination from './Pagination.vue';

const props = defineProps({
    message: String,
    drugs: Object,
});

const emit = defineEmits(['selected']);

const selectedDrugs = ref(null);

const selectDrug = (drug) => {
    if (selectedDrugs.value === drug.id) {
        selectedDrugs.value = null;
        emit('selected', null);
    } else {
        selectedDrugs.value = drug.id;
        emit('selected', drug);
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
                    v-for="drug in drugs.data"
                    :key="drug.id"
                    class="cursor-pointer"
                    :class="{
                        'bg-blue-500 text-gray-900 dark:bg-blue-500 dark:text-white': selectedDrugs === drug.id,
                        'hover:bg-gray-200 hover:text-gray-800': selectedDrugs !== drug.id
                    }"
                    @click="selectDrug(drug)"
                >
                    <td class="p-4 items-center">{{ drug.id }}</td>
                    <td class="p-4 items-center">{{ drug.name }}</td>
                </tr>
            </tbody>
        </table>

        <div v-if="!drugs.data.length" class="text-center text-gray-500 dark:text-gray-400 py-4 w-full">
            No hay registros disponibles.
        </div>

        <Pagination :pagination="drugs" />

    </div>

    <h2 v-if="selectedDrugs" class="text-sm text-green-500 lg:mx-10 mx-4">
        Medicamento Seleccionado
    </h2>
</template>
