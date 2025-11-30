<template>
  <div class="max-w-xl mt-20 mx-auto bg-white dark:bg-gray-800 border-2 border-pink-200 dark:border-gray-700 rounded-lg shadow-lg overflow-hidden">
    <!-- Header with medical emblem -->
    <div class="bg-pink-400 dark:bg-pink-400 text-white p-4 flex justify-between items-center">
      <div class="flex items-center space-x-3">
        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center">
          <div class="w-8 h-8 bg-pink-400 rounded-full flex items-center justify-center">
            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 0 011.912-2.706C6.512 5.73 6.974 6 7.5 6A1.5 1.5 0 019 7.5V8a2 2 0 004 0 2 2 0 011.523-1.943A5.977 5.977 0 0116 10c0 .34-.028.675-.083 1H15a2 2 0 00-2 2v2.197A5.973 5.973 0 0110 16v-2a2 2 0 00-2-2 2 2 0 01-2-2 2 2 0 00-1.668-1.973z" clip-rule="evenodd"></path>
            </svg>
          </div>
        </div>
        <div>
          <h1 class="text-xl font-bold">Smile Clinic</h1>
          <p class="text-xs"> Duarte 5, Cayetano Germosén 56000</p>
        </div>
      </div>
      <div class="text-right">
        <h2 class="text-2xl font-bold">RECETA MÉDICA</h2>
        <p class="text-sm">RX #: {{ prescription.id }}</p>
      </div>
    </div>

    <!-- Patient and Doctor Information -->
    <div class="p-6 border-b border-dashed border-gray-300 dark:border-gray-600">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <h3 class="text-pink-400 dark:text-pink-400 font-semibold text-lg mb-2 border-b border-gray-200 dark:border-gray-700 pb-1">PACIENTE</h3>
          <p class="mb-1"><span class="font-medium">Nombre:</span> {{ prescription.patient.first_name }} {{ prescription.patient.last_name }} </p>
          <p><span class="font-medium">Edad:</span> {{ patientAge}}</p>
        </div>
        <div>
          <h3 class="text-pink-400 dark:text-pink-400 font-semibold text-lg mb-2 border-b border-gray-200 dark:border-gray-700 pb-1">DOCTOR/A</h3>
          <p class="mb-1"><span class="font-medium">Nombre:</span> Dr. {{ prescription.doctor.first_name }}</p>
          <p><span class="font-medium">Fecha de Creación:</span> {{ formattedDate }}</p>
        </div>
      </div>
    </div>

    <!-- Medications Section -->
    <div class="p-6">
      <h2 class="text-xl font-bold text-pink-400 dark:text-pink-400 mb-4 text-center border-b border-gray-300 dark:border-gray-600 pb-2">MEDICAMENTOS RECETADOS</h2>

      <div v-for="detail in prescription.prescriptions_details" :key="detail.id" class="mb-4 p-4 bg-pink-50 dark:bg-gray-700 rounded-lg border border-pink-100 dark:border-gray-600">
        <div class="flex justify-between items-start mb-2">
          <span class="font-bold text-pink-400 dark:text-pink-300 text-lg">{{ detail.drugs.name }}</span>
          <span class="text-sm text-gray-600 dark:text-gray-300 bg-pink-100 dark:bg-gray-600 px-2 py-1 rounded">#: {{ detail.drug_id }}</span>
        </div>
        <p class="text-gray-700 dark:text-gray-300 mb-3 italic">{{ detail.description }}</p>

      </div>
    </div>

    <!-- Doctor Signature -->
    <div class="p-6 bg-gray-50 dark:bg-gray-700 border-t border-dashed border-gray-300 dark:border-gray-600">
      <div class="text-center">
        <div class="mb-1 border-b border-gray-400 dark:border-gray-500 w-48 mx-auto"></div>
        <p class="text-sm text-gray-600 dark:text-gray-400">Firma del doctor</p>
        <p class="font-semibold mt-1">Dr. {{ prescription.doctor.first_name }}</p>
      </div>
    </div>

    <!-- Footer -->
    <div class="bg-pink-400 dark:bg-pink-400 text-white p-3 text-center text-xs">
      <p>℞ Esta receta vence en 6 meses desde la fecha de emisión. No se realizan recargas sin autorización.</p>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const { props } = usePage();
const prescription = props.prescription;
const patientAge = computed(() => {
  if (!prescription.patient?.date_of_birth) return null

  const dob = new Date(prescription.patient.date_of_birth)
  const diff = new Date(Date.now() - dob.getTime())
  return Math.abs(diff.getUTCFullYear() - 1970)
})
const formattedDate = computed(() => {
  return prescription.created_at
    ? new Date(prescription.created_at).toLocaleDateString('es-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      })
    : '';
});
</script>
