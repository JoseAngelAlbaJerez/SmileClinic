<template>
  <AuthenticatedLayout>
    <template #header>
      <Breadcrumb
        :crumbs="[
          { label: 'Pacientes', to: route('patients.index') },
          { label: patient.first_name + ' ' + patient.last_name },
        ]"
      />
    </template>

    <div class="container mx-auto px-4 py-8">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Información personal -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-md">
          <h2 class="text-lg font-semibold mb-4">Información Personal</h2>
          <div class="space-y-2 text-sm">
            <p><span class="font-medium">Nombre:</span> {{ patient.first_name }} {{ patient.last_name }}</p>
            <p><span class="font-medium">Cédula / DNI:</span> {{ patient.DNI }}</p>
            <p><span class="font-medium">Teléfono:</span> {{ patient.phone_number }}</p>
            <p><span class="font-medium">ARS:</span> {{ patient.ars }}</p>
            <p><span class="font-medium">Fecha de Nacimiento:</span> {{ formatDate(patient.date_of_birth) }}</p>
            <p><span class="font-medium">Dirección:</span> {{ patient.address }}</p>
            <p><span class="font-medium">Motivo de visita:</span> {{ patient.motive }}</p>
          </div>
        </div>

        <!-- Información médica -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-md">
          <h2 class="text-lg font-semibold mb-4">Historial Médico</h2>
          <div class="space-y-2 text-sm">
            <p>
              <span class="font-medium">¿Complicaciones?</span>
              <span>{{ patient.complications ? 'Sí' : 'No' }}</span>
            </p>
            <p v-if="patient.complications_detail">
              <span class="font-medium">Detalle:</span> {{ patient.complications_detail }}
            </p>

            <p>
              <span class="font-medium">¿Usa Medicamentos?</span>
              <span>{{ patient.drugs ? 'Sí' : 'No' }}</span>
            </p>
            <p v-if="patient.drugs_detail">
              <span class="font-medium">Detalle:</span> {{ patient.drugs_detail }}
            </p>

            <p>
              <span class="font-medium">¿Tiene Alergias?</span>
              <span>{{ patient.alergies ? 'Sí' : 'No' }}</span>
            </p>
            <p v-if="patient.alergies_detail">
              <span class="font-medium">Detalle:</span> {{ patient.alergies_detail }}
            </p>
          </div>
        </div>
      </div>

      <div class="mt-6 flex justify-end">
        <Link :href="route('patients.edit', patient.id)">
          <PrimaryButton>Editar</PrimaryButton>
        </Link>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Breadcrumb from '@/Components/BreadCrumb.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Link } from '@inertiajs/vue3';

export default {
  props: {
    patient: Object,
  },
  components: {
    AuthenticatedLayout,
    Breadcrumb,
    PrimaryButton,
    Link,
  },
  methods: {
    formatDate(date) {
      if (!date) return '';
      const d = new Date(date);
      return d.toLocaleDateString('es-DO', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
      });
    },
  },
};
</script>

<style scoped>
p span:first-child {
  color: #6b7280;
}
</style>
