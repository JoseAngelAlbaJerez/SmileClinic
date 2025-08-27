<template>
    <div class="p-4 sm:p-6 bg-white dark:bg-gray-900 min-h-screen">
        <!-- Header Section -->
        <div
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white flex items-center flex-wrap">
                    <svg class="w-6 h-6 mr-2 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Reporte de {{ table }}
                </h1>
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Generado el {{ currentDate }}
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <button @click="printReport"
                    class="px-3 sm:px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center text-xs sm:text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z">
                        </path>
                    </svg>
                    Imprimir
                </button>
                <button @click="downloadCSV"
                    class="px-3 sm:px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-lg transition-colors flex items-center text-xs sm:text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Exportar
                </button>
            </div>
        </div>

        <!-- Applied Filters -->
        <div v-if="hasFilters"
            class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-100 dark:border-blue-800/50">
            <div class="flex items-center mb-3">
                <svg class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                    </path>
                </svg>
                <p class="font-semibold text-blue-800 dark:text-blue-200">Filtros aplicados:</p>
            </div>

            <ul class="flex flex-wrap gap-2">
                <li v-if="dateFrom && dateTo"
                    class="bg-white dark:bg-gray-800 px-3 py-2 rounded-lg border border-blue-200 dark:border-blue-700 flex items-center text-xs font-medium text-blue-700 dark:text-blue-300">
                    <svg class="w-4 h-4 mr-1.5 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    {{ formatDate(dateFrom) }} → {{ formatDate(dateTo) }}
                </li>

                <li v-if="search"
                    class="bg-white dark:bg-gray-800 px-3 py-2 rounded-lg border border-purple-200 dark:border-purple-700 flex items-center text-xs font-medium text-purple-700 dark:text-purple-300">
                    <svg class="w-4 h-4 mr-1.5 text-purple-500 dark:text-purple-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    "{{ search }}"
                </li>

                <li v-if="active"
                    class="bg-white dark:bg-gray-800 px-3 py-2 rounded-lg border border-green-200 dark:border-green-700 flex items-center text-xs font-medium text-green-700 dark:text-green-300">
                    <svg class="w-4 h-4 mr-1.5 text-green-500 dark:text-green-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Solo activos
                </li>

                <li v-if="orderBy"
                    class="bg-white dark:bg-gray-800 px-3 py-2 rounded-lg border border-amber-200 dark:border-amber-700 flex items-center text-xs font-medium text-amber-700 dark:text-amber-300">
                    <svg class="w-4 h-4 mr-1.5 text-amber-500 dark:text-amber-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"></path>
                    </svg>
                    Ordenado por <strong class="ml-1">{{ orderBy }}</strong> ({{ orderDirection }})
                </li>

                <li v-if="limit"
                    class="bg-white dark:bg-gray-800 px-3 py-2 rounded-lg border border-indigo-200 dark:border-indigo-700 flex items-center text-xs font-medium text-indigo-700 dark:text-indigo-300">
                    <svg class="w-4 h-4 mr-1.5 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                    Límite: {{ limit }} registros
                </li>

                <li v-if="groupBy"
                    class="bg-white dark:bg-gray-800 px-3 py-2 rounded-lg border border-rose-200 dark:border-rose-700 flex items-center text-xs font-medium text-rose-700 dark:text-rose-300">
                    <svg class="w-4 h-4 mr-1.5 text-rose-500 dark:text-rose-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>
                    Agrupado por {{ groupBy }}
                </li>
            </ul>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-6">
            <!-- Total Registros -->
            <div class="bg-pink-50 dark:bg-pink-900/30 p-4 rounded-lg border border-pink-100 dark:border-pink-800">
                <div class="flex items-center">
                    <div class="rounded-full bg-pink-100 dark:bg-pink-800 p-3 mr-4 flex-shrink-0">
                        <svg class="w-5 h-5 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm font-medium text-pink-600 dark:text-pink-400">
                            Total de Registros
                        </p>
                        <p class="text-lg sm:text-2xl font-bold text-pink-800 dark:text-pink-200">
                            {{ data.length }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Periodo -->
            <div v-if="dateFrom && dateTo"
                class="bg-green-50 dark:bg-green-900/30 p-4 rounded-lg border border-green-100 dark:border-green-800">
                <div class="flex items-center">
                    <div class="rounded-full bg-green-100 dark:bg-green-800 p-3 mr-4 flex-shrink-0">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm font-medium text-green-600 dark:text-green-400">
                            Período del Reporte
                        </p>
                        <p class="text-sm sm:text-lg font-semibold text-green-800 dark:text-green-200">
                            {{ formatDate(dateFrom) }} - {{ formatDate(dateTo) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Campos -->
            <div
                class="bg-purple-50 dark:bg-purple-900/30 p-4 rounded-lg border border-purple-100 dark:border-purple-800">
                <div class="flex items-center">
                    <div class="rounded-full bg-purple-100 dark:bg-purple-800 p-3 mr-4 flex-shrink-0">
                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm font-medium text-purple-600 dark:text-purple-400">
                            Campos Incluidos
                        </p>
                        <p class="text-sm sm:text-lg font-semibold text-purple-800 dark:text-purple-200">
                            {{ fields.length }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div
            class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-xs sm:text-sm">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th v-for="(field, index) in fields" :key="field"
                                class="px-4 sm:px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider"
                                :class="{
                                    'rounded-tl-lg': index === 0,
                                    'rounded-tr-lg': index === fields.length - 1,
                                }">
                                {{ field }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="(row, i) in data" :key="i"
                            class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td v-for="field in fields" :key="field"
                                class="px-4 sm:px-6 py-3 whitespace-nowrap text-gray-800 dark:text-gray-200">
                                {{ row[field] || "-" }}
                            </td>
                        </tr>
                        <tr v-if="data.length === 0">
                            <td :colspan="fields.length" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
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

const props = defineProps({
    table: String,
    fields: Array,
    data: Array,
    dateFrom: String,
    dateTo: String,
    orderBy: String,
    orderDir: String,
    limit: [String, Number],
    search: String,
    active: Boolean,
    groupBy: String,
})


const currentDate = computed(() => {
    return new Date().toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    })
})
const hasFilters = computed(() => {
    return !!(props.dateFrom || props.dateTo || props.orderBy || props.limit || props.search || props.active || props.groupBy)
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
    if (!props.data || !props.data.length) return;

    const header = props.fields.join(",");
    const rows = props.data.map(row =>
        props.fields.map(f => `"${row[f] ?? ""}"`).join(",")
    );
    const csvContent = [header, ...rows].join("\n");

    const blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" });
    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.setAttribute("download", `reporte_${props.table}_${Date.now()}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

</script>

<style scoped>
@media print {
    button {
        display: none !important;
    }
}
</style>
