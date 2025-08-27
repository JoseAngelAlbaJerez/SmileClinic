<template>
  <Modal :show="open" @close="open = false">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl w-full  shadow-xl border border-gray-200 dark:border-gray-700">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white flex items-center">
          <svg class="w-5 h-5 mr-2 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          Generar Reporte
        </h2>
        <button @click="close" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>

      <!-- Field Selector -->
      <div class="mb-6">
        <div class="flex justify-between items-center mb-3">
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Seleccionar Campos {{ table }}</label>
          <div class="text-xs">
            <button @click="selectAll" class="text-pink-600 hover:text-pink-800 dark:text-pink-400 dark:hover:text-pink-300 mr-2">Todos</button>
            <button @click="selectNone" class="text-pink-600 hover:text-pink-800 dark:text-pink-400 dark:hover:text-pink-300">Ninguno</button>
          </div>
        </div>
        <div class="grid grid-cols-2 gap-3 max-h-40 overflow-y-auto p-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700">
          <div v-for="field in fields" :key="field" class="flex items-center">
            <label class="flex items-center space-x-2 cursor-pointer">
              <input type="checkbox" v-model="selectedFields" :value="field" class="hidden">
              <span class="w-5 h-5 border border-gray-400 rounded-md bg-white dark:bg-gray-600 inline-block relative">
                <svg v-if="selectedFields.includes(field)" class="w-5 h-5 text-pink-600 absolute" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
              </span>
              <span class="text-sm text-gray-700 dark:text-gray-300">{{ field }}</span>
            </label>
          </div>
        </div>
      </div>

      <!-- Date Range -->
      <div class="mb-6">
        <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Rango de Fechas</label>
        <div class="grid grid-cols-2 gap-3">
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
            </div>
            <input type="date" v-model="dateFrom" class="pl-10 border border-gray-300 dark:border-gray-600 rounded-lg p-2.5 text-sm w-full bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
          </div>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
            </div>
            <input type="date" v-model="dateTo" class="pl-10 border border-gray-300 dark:border-gray-600 rounded-lg p-2.5 text-sm w-full bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
          </div>
        </div>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 flex items-center">
          <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          Dejar vacío si no desea filtrar por fecha.
        </p>
      </div>

      <!-- Actions -->
      <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
        <button @click="close" class="px-5 py-2.5 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors font-medium">
          Cancelar
        </button>
        <button @click="submit" class="px-5 py-2.5 rounded-lg bg-pink-600 hover:bg-pink-700 text-white shadow-md transition-all font-medium flex items-center">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          Generar
        </button>
      </div>
    </div>
  </Modal>
</template>

<script setup>
import { ref, watch } from "vue"
import { router } from "@inertiajs/vue3"
import axios from "axios"
import Modal from "./Modal.vue"

const props = defineProps({
  open: Boolean,
  table: String,
})

const emit = defineEmits(["close"])

const fields = ref([])
const selectedFields = ref([])
const dateFrom = ref("")
const dateTo = ref("")

watch(() => props.open, async (val) => {
  if (val && props.table) {
    const { data } = await axios.get(route("report.fields"), { params: { table: props.table } })
    fields.value = data
  }
})

function close() {
  emit("close")
}
function selectAll() {
  selectedFields.value = [...fields.value]
}
function selectNone() {
  selectedFields.value = []
}

function submit() {
  router.get(route("report.generate", props.table), {
    fields: selectedFields.value,
    dateFrom: dateFrom.value,
    dateTo: dateTo.value,
  })
}
</script>


