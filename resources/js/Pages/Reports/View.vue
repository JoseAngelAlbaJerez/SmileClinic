<template>
  <div class="p-4 sm:p-6 bg-white dark:bg-gray-900 min-h-screen">
    <!-- Header Section -->
    <div
      class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 pb-4 border-b border-gray-200 dark:border-gray-700"
    >
      <div class="mb-4 sm:mb-0">
        <h1
          class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white flex items-center flex-wrap"
        >
          <svg
            class="w-6 h-6 mr-2 text-pink-500"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
            ></path>
          </svg>
          Reporte de {{ table }}
        </h1>
        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">
          Generado el {{ currentDate }}
        </p>
      </div>

      <div class="flex flex-wrap gap-2">
        <button
          @click="printReport"
          class="px-3 sm:px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center text-xs sm:text-sm"
        >
          <svg
            class="w-4 h-4 mr-2"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z"
            ></path>
          </svg>
          Imprimir
        </button>
        <button
          @click="downloadCSV"
          class="px-3 sm:px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-lg transition-colors flex items-center text-xs sm:text-sm"
        >
          <svg
            class="w-4 h-4 mr-2"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
            ></path>
          </svg>
          Exportar
        </button>
      </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-6">
      <!-- Total Registros -->
      <div
        class="bg-pink-50 dark:bg-pink-900/30 p-4 rounded-lg border border-pink-100 dark:border-pink-800"
      >
        <div class="flex items-center">
          <div
            class="rounded-full bg-pink-100 dark:bg-pink-800 p-3 mr-4 flex-shrink-0"
          >
            <svg
              class="w-5 h-5 text-pink-600 dark:text-pink-400"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
              ></path>
            </svg>
          </div>
          <div>
            <p
              class="text-xs sm:text-sm font-medium text-pink-600 dark:text-pink-400"
            >
              Total de Registros
            </p>
            <p
              class="text-lg sm:text-2xl font-bold text-pink-800 dark:text-pink-200"
            >
              {{ data.length }}
            </p>
          </div>
        </div>
      </div>

      <!-- Periodo -->
      <div
        v-if="dateFrom && dateTo"
        class="bg-green-50 dark:bg-green-900/30 p-4 rounded-lg border border-green-100 dark:border-green-800"
      >
        <div class="flex items-center">
          <div
            class="rounded-full bg-green-100 dark:bg-green-800 p-3 mr-4 flex-shrink-0"
          >
            <svg
              class="w-5 h-5 text-green-600 dark:text-green-400"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
              ></path>
            </svg>
          </div>
          <div>
            <p
              class="text-xs sm:text-sm font-medium text-green-600 dark:text-green-400"
            >
              Período del Reporte
            </p>
            <p
              class="text-sm sm:text-lg font-semibold text-green-800 dark:text-green-200"
            >
              {{ formatDate(dateFrom) }} - {{ formatDate(dateTo) }}
            </p>
          </div>
        </div>
      </div>

      <!-- Campos -->
      <div
        class="bg-purple-50 dark:bg-purple-900/30 p-4 rounded-lg border border-purple-100 dark:border-purple-800"
      >
        <div class="flex items-center">
          <div
            class="rounded-full bg-purple-100 dark:bg-purple-800 p-3 mr-4 flex-shrink-0"
          >
            <svg
              class="w-5 h-5 text-purple-600 dark:text-purple-400"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
              ></path>
            </svg>
          </div>
          <div>
            <p
              class="text-xs sm:text-sm font-medium text-purple-600 dark:text-purple-400"
            >
              Campos Incluidos
            </p>
            <p
              class="text-sm sm:text-lg font-semibold text-purple-800 dark:text-purple-200"
            >
              {{ fields.length }}
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Data Table -->
    <div
      class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow overflow-hidden"
    >
      <div class="overflow-x-auto">
        <table class="min-w-full text-xs sm:text-sm">
          <thead class="bg-gray-100 dark:bg-gray-700">
            <tr>
              <th
                v-for="(field, index) in fields"
                :key="field"
                class="px-4 sm:px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider"
                :class="{
                  'rounded-tl-lg': index === 0,
                  'rounded-tr-lg': index === fields.length - 1,
                }"
              >
                {{ field }}
              </th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            <tr
              v-for="(row, i) in data"
              :key="i"
              class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors"
            >
              <td
                v-for="field in fields"
                :key="field"
                class="px-4 sm:px-6 py-3 whitespace-nowrap text-gray-800 dark:text-gray-200"
              >
                {{ row[field] || "-" }}
              </td>
            </tr>
            <tr v-if="data.length === 0">
              <td
                :colspan="fields.length"
                class="px-6 py-8 text-center text-gray-500 dark:text-gray-400"
              >
                <svg
                  class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                  ></path>
                </svg>
                <p class="text-lg font-medium">No se encontraron registros</p>
                <p class="mt-1">Intente ajustar los filtros de su reporte</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>


<script setup>
import { ref, computed } from "vue"

defineProps({
  table: String,
  fields: Array,
  data: Array,
  dateFrom: String,
  dateTo: String
})

const currentDate = computed(() => {
  return new Date().toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
})

function formatDate(dateString) {
  if (!dateString) return ''
  const date = new Date(dateString)
  return date.toLocaleDateString('es-ES')
}

function printReport() {
  window.print()
}

function downloadCSV() {
  console.log('Downloading CSV...')
}
</script>

<style scoped>
@media print {
  button {
    display: none !important;
  }
}
</style>
