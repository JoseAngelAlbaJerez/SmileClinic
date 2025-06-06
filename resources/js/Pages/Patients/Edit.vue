<template>
  <AuthenticatedLayout>
    <template #header>
      <Breadcrumb
        :crumbs="[
          { label: 'Pacientes', to: route('patients.index') },
          { label: patient.first_name + ' ' + patient.last_name },
          { label: 'Editar' },
        ]"
      />
    </template>

    <div class="container mx-auto px-4 py-8">
      <form @submit.prevent="submit" class="space-y-6 bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-md">
        <h2 class="text-xl font-semibold mb-4">Editar Información del Paciente</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium">Nombre</label>
            <input v-model="form.first_name" type="text" class="input" />
          </div>

          <div>
            <label class="block text-sm font-medium">Apellido</label>
            <input v-model="form.last_name" type="text" class="input" />
          </div>

          <div>
            <label class="block text-sm font-medium">DNI</label>
            <input v-model="form.DNI" type="text" class="input" />
          </div>

          <div>
            <label class="block text-sm font-medium">Teléfono</label>
            <input v-model="form.phone_number" type="text" class="input" />
          </div>

          <div>
            <label class="block text-sm font-medium">ARS</label>
            <input v-model="form.ars" type="text" class="input" />
          </div>

          <div>
            <label class="block text-sm font-medium">Fecha de Nacimiento</label>
            <VueDatePicker v-model="form.date_of_birth" class="w-full" />
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm font-medium">Dirección</label>
            <input v-model="form.address" type="text" class="input" />
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm font-medium">Motivo de Visita</label>
            <input v-model="form.motive" type="text" class="input" />
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="flex items-center space-x-2">
              <input type="checkbox" v-model="form.complications" />
              <span>Complicaciones</span>
            </label>
            <textarea v-model="form.complications_detail" class="input mt-1" rows="2" placeholder="Detalle si aplica" />
          </div>

          <div>
            <label class="flex items-center space-x-2">
              <input type="checkbox" v-model="form.drugs" />
              <span>Medicamentos</span>
            </label>
            <textarea v-model="form.drugs_detail" class="input mt-1" rows="2" placeholder="Detalle si aplica" />
          </div>

          <div>
            <label class="flex items-center space-x-2">
              <input type="checkbox" v-model="form.alergies" />
              <span>Alergias</span>
            </label>
            <textarea v-model="form.alergies_detail" class="input mt-1" rows="2" placeholder="Detalle si aplica" />
          </div>
        </div>

        <div v-if="error" class="text-red-500 text-sm">{{ error }}</div>

        <div class="flex justify-end space-x-4">
          <Link :href="route('patients.index')">
            <SecondaryButton>Cancelar</SecondaryButton>
          </Link>
          <PrimaryButton type="submit">Guardar Cambios</PrimaryButton>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>

<script>
import { useForm, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Breadcrumb from '@/Components/BreadCrumb.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

export default {
  props: {
    patient: Object,
    errors: Object,
  },
  components: {
    AuthenticatedLayout,
    Breadcrumb,
    PrimaryButton,
    SecondaryButton,
    Link,
    VueDatePicker,
  },
  data() {
    return {
      error: '',
      form: useForm({
        first_name: this.patient.first_name || '',
        last_name: this.patient.last_name || '',
        DNI: this.patient.DNI || '',
        phone_number: this.patient.phone_number || '',
        ars: this.patient.ars || '',
        date_of_birth: this.patient.date_of_birth || '',
        complications: this.patient.complications || false,
        complications_detail: this.patient.complications_detail || '',
        drugs: this.patient.drugs || false,
        drugs_detail: this.patient.drugs_detail || '',
        alergies: this.patient.alergies || false,
        alergies_detail: this.patient.alergies_detail || '',
        address: this.patient.address || '',
        motive: this.patient.motive || '',
      }),
    };
  },
  methods: {
    submit() {
      if (this.form.drugs && !this.form.drugs_detail) {
        this.error = 'Debe indicar los medicamentos que usa.';
        return;
      }
      if (this.form.alergies && !this.form.alergies_detail) {
        this.error = 'Debe especificar las alergias.';
        return;
      }
      if (this.form.complications && !this.form.complications_detail) {
        this.error = 'Debe detallar las complicaciones.';
        return;
      }
      if (!this.form.date_of_birth) {
        this.error = 'Seleccione la fecha de nacimiento.';
        return;
      }

      this.error = '';
      this.form.put(route('patients.update', this.patient.id), {
        onSuccess: () => {
          this.$inertia.visit(route('patients.show', this.patient.id));
        },
      });
    },
  },
};
</script>

<style scoped>
.input {
  width: 100%;
  padding: 0.5rem 0.75rem;
  border: 1px solid #cbd5e0;
  border-radius: 0.375rem;
  background-color: white;
}
</style>
