<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  budgets: {
    type: Array,
    default: () => [],
  },
});

const emit = defineEmits(['selected']);

const searchTerm = ref('');
const selectedBudgetId = ref(null);

const filteredBudgets = computed(() => {
  if (!searchTerm.value) return props.budgets;
  return props.budgets.filter(b =>
    String(b.id).includes(searchTerm.value) ||
    (b.patient?.first_name + ' ' + b.patient?.last_name)
      .toLowerCase()
      .includes(searchTerm.value.toLowerCase())
  );
});

const selectBudget = (budget) => {
  if (selectedBudgetId.value === budget.id) {
    selectedBudgetId.value = null;
    emit('selected', null);
  } else {
    selectedBudgetId.value = budget.id;
    emit('selected', budget);
  }
};
</script>

<template>
  <div class="my-4 mx-4 lg:mx-10">
    <!-- Barra de búsqueda -->
    <div class="flex justify-between gap-4">
      <input v-model="searchTerm" type="text" placeholder="Buscar presupuesto..."
        class="mb-4 w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md
               focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white" />
    </div>

    <!-- Tabla -->
    <div class="relative overflow-x-auto border border-gray-200 dark:border-gray-700/60 rounded-lg">
      <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-100">
          <tr>
            <th scope="col" class="px-4 py-3 hidden sm:table-cell">#</th>
            <th scope="col" class="px-6 py-3">Paciente</th>
            <th scope="col" class="px-6 py-3">Total</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="budget in filteredBudgets" :key="budget.id" class="cursor-pointer"
            :class="{
              'bg-blue-500 text-gray-900 dark:bg-blue-500 dark:text-white': selectedBudgetId === budget.id,
              'hover:bg-gray-200 hover:text-gray-800': selectedBudgetId !== budget.id
            }"
            @click="selectBudget(budget)"
          >
            <td class="p-4 hidden sm:table-cell">{{ budget.id }}</td>
            <td class="p-4">
              {{ budget.patient?.first_name }} {{ budget.patient?.last_name }}
            </td>
            <td class="p-4 font-semibold">${{ budget.budgetdetail }}</td>
          </tr>
        </tbody>
      </table>

      <div v-if="!filteredBudgets.length" class="text-center text-gray-500 dark:text-gray-400 py-4 w-full">
        No hay registros disponibles.
      </div>
    </div>

    <!-- Indicador -->
    <h2 v-if="selectedBudgetId" class="text-sm text-green-500 mt-2">
      Presupuesto Seleccionado
    </h2>
  </div>
</template>
