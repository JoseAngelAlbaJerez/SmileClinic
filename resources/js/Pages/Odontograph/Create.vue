<template>
     <AuthenticatedLayout>
    <template #header>
      <Breadcrumb
        :crumbs="crumbs"
      />
    </template>

  <div class="max-w-5xl mt-5 mx-auto p-6 bg-white rounded-2xl shadow">
    <h2 class="text-2xl font-semibold mb-4">Crear Odontograma</h2>

    <form @submit.prevent="submit">


      <!-- Cavities -->
      <div class="mb-4">
        <label class="block font-medium mb-1">Caries</label>
        <div class="grid grid-cols-3 gap-2">
          <div v-for="key in surfaceKeys" :key="key">
            <label class="text-sm capitalize">{{ key }}</label>
            <input type="number" v-model="form.cavities[key]" class="w-full border rounded p-1" min="0" max="1" />
          </div>
        </div>
      </div>

      <!-- Boolean fields -->
      <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-4">
        <label class="flex items-center gap-2">
          <input type="checkbox" v-model="form.missing" />
          Diente Ausente
        </label>
        <label class="flex items-center gap-2">
          <input type="checkbox" v-model="form.filter" />
          Filtrado
        </label>
        <label class="flex items-center gap-2">
          <input type="checkbox" v-model="form.root_canal" />
          Endodoncia
        </label>
        <label class="flex items-center gap-2">
          <input type="checkbox" v-model="form.cleaning" />
          Limpieza
        </label>
        <label class="flex items-center gap-2">
          <input type="checkbox" v-model="form.active" />
          Activo
        </label>
      </div>

      <!-- Fechas -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div v-for="field in dateFields" :key="field">
          <label class="block text-sm mb-1 capitalize">{{ field.replace('_', ' ') }}</label>
          <input type="date" v-model="form[field]" class="w-full border rounded p-2" />
        </div>
      </div>

      <!-- Botón -->
      <div class="mt-6">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
          Guardar Odontograma
        </button>
      </div>
    </form>
  </div>
  </AuthenticatedLayout>
</template>

<script>

import { useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Breadcrumb from '@/Components/BreadCrumb.vue'
import { useToast } from 'vue-toastification';
const toast = useToast();
import UserIcon from '@/Components/Icons/UserIcon.vue';
import { markRaw } from 'vue';
import AddIcon from '@/Components/Icons/AddIcon.vue';
export default {
  components: {
    AuthenticatedLayout,
    Breadcrumb,
    UserIcon,
    AddIcon
  },
  props: {
    patient: Object,
  },
  data() {
    return {
      surfaceKeys: ['center', 'top', 'bottom', 'left', 'right'],
      dateFields: ['veener', 'graft', 'sealant', 'bonding', 'examinations', 'fissure_seal'],
      form: useForm({
        patient_id: this.patient.id || null,
        cavities: { center: 0, top: 0, bottom: 0, left: 0, right: 0 },
        missing: false,
        filter: false,
        root_canal: false,
        cleaning: false,
        active: 1,
        veener: '',
        graft: '',
        sealant: '',
        bonding: '',
        examinations: '',
        fissure_seal: ''
      }),
        crumbs: [
                { icon: markRaw(UserIcon), label: 'Pacientes', to: route('patients.index') },
                { label: this.patient.first_name + this.patient.last_name, to: route('patients.show',this.patient) },
                 { icon: markRaw(AddIcon), label: 'Crear' }
            ]
    }
  },
  methods: {
    submit() {
      this.form.post(route('odontographs.store'),{
          onSuccess: () => {
                        toast.success('Odontograma registrado correctamente.');
                },
                onError: (error) =>{
                     toast.error('Hubo un error al registrar el Odontograma.');

                }
      })
    }
  }
}
</script>

