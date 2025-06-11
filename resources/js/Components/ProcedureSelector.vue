<script setup>
import { ref } from 'vue';
import Pagination from './Pagination.vue';

const props = defineProps({
    message: String,
    procedures: Object,
});

const emit = defineEmits(['selected']);

const selectedProcedures = ref(null);

const selectProcedure = (procedure) => {
    if (selectedProcedures.value === procedure.id) {
        selectedProcedures.value = null;
        emit('selected', null);
    } else {
        selectedProcedures.value = procedure.id;
        emit('selected', procedure);
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
                    v-for="procedure in procedures.data"
                    :key="procedure.id"
                    class="cursor-pointer"
                    :class="{
                        'bg-blue-500 text-gray-900 dark:bg-blue-500 dark:text-white': selectedProcedures === procedure.id,
                        'hover:bg-gray-200 hover:text-gray-800': selectedProcedures !== procedure.id
                    }"
                    @click="selectProcedure(procedure)"
                >
                    <td class="p-4 items-center">{{ procedure.id }}</td>
                    <td class="p-4 items-center">{{ procedure.name }}</td>
                </tr>
            </tbody>
        </table>

        <div v-if="!procedures.data.length" class="text-center text-gray-500 dark:text-gray-400 py-4 w-full">
            No hay registros disponibles.
        </div>

        <Pagination :pagination="procedures" />
    </div>
    <h2 v-if="selectedProcedures" class="text-sm text-green-500 lg:mx-10 mx-4">
        Procedimiento Seleccionado
    </h2>
</template>
