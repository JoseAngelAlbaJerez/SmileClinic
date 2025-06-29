<template>
    <div>
        <h2 class="text-lg font-bold mb-4 text-gray-800 dark:text-white">Odontograma</h2>
        <!-- Fila superior -->
        <div class="grid grid-cols-16 gap-2 mb-4">
            <div v-for="tooth in upperTeeth" :key="tooth"
                class="relative border rounded p-6  text-center cursor-pointer dark:text-white hover:bg-blue-100 dark:hover:bg-blue-800"
                :class="{ 'bg-blue-200 dark:bg-blue-700': odontogram[tooth] }" @click="selectTooth(tooth)">
                <div class="font-bold">{{ tooth }}</div>


                <!-- Tooltip -->
                <div v-if="odontogram[tooth]"
                    class="absolute left-1/2 transform -translate-x-1/2  dark:border -top-20 w-40 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 text-xs rounded shadow-lg p-2 opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity">
                    <div v-for="(proc, zone) in odontogram[tooth]" :key="zone" class="flex justify-between">
                        <span class="font-semibold">{{ zone }}:</span>
                        <span>{{ proc }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fila inferior -->
        <div class="grid grid-cols-16 gap-2 mb-4">
            <div v-for="tooth in lowerTeeth" :key="tooth"
                class="relative border rounded p-6 text-center cursor-pointer hover:bg-blue-100 dark:text-white dark:hover:bg-blue-800"
                :class="{ 'bg-blue-200 dark:bg-blue-700': odontogram[tooth] }" @click="selectTooth(tooth)">
                <div class="font-bold">{{ tooth }}</div>


                <!-- Tooltip -->
                <div v-if="odontogram[tooth]"
                    class="absolute left-1/2 transform -translate-x-1/2 dark:border -top-20 w-40 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 text-xs rounded shadow-lg p-2 opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity">
                    <div v-for="(proc, zone) in odontogram[tooth]" :key="zone" class="flex justify-between">
                        <span class="font-semibold">{{ zone }}:</span>
                        <span>{{ proc }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal simple -->
        <div v-if="selectedTooth" class="fixed inset-0  bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 dark:text-white p-6 rounded-xl shadow-md w-full max-w-md">
                <h3 class="text-lg font-semibold mb-4">Procedimiento para diente {{ selectedTooth }}</h3>

                <!-- Zonas -->
                <div class="grid grid-cols-3 gap-2 mb-4 text-center">
                    <div></div>
                    <button type="button" @click="selectZone('Oclusal')" :class="zoneClass('Oclusal')">Oclusal</button>
                    <div></div>

                    <button type="button" @click="selectZone('Mesial')" :class="zoneClass('Mesial')">Mesial</button>

                    <button type="button" @click="applyToAllZones" :class="zoneClass('Mesial')">
                        Todas
                    </button>

                    <button type="button" @click="selectZone('Distal')" :class="zoneClass('Distal')">Distal</button>

                    <div></div>
                    <button type="button" @click="selectZone('Vestibular')"
                        :class="zoneClass('Vestibular')">Vestibular</button>
                    <button type="button" @click="selectZone('Lingual')" :class="zoneClass('Lingual')">Lingual</button>
                </div>

                <!-- Procedimiento -->
                <select v-model="procedure" class="w-full border p-2 dark:bg-gray-800 rounded mb-4">
                    <option disabled value="">Seleccione un procedimiento</option>
                    <option value="Obturación">Obturación</option>
                    <option value="Endodoncia">Endodoncia</option>
                    <option value="Extracción">Extracción</option>
                    <option value="Limpieza">Limpieza</option>
                    <option value="Corona">Corona</option>
                </select>

                <div class="flex justify-end space-x-2">
                    <PrimaryButton @click="applyProcedure"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Guardar
                    </PrimaryButton>
                    <SecondaryButton @click="cancel" class="text-gray-600 hover:text-gray-600">Cancelar
                    </SecondaryButton>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, computed } from 'vue'
import PrimaryButton from './PrimaryButton.vue';
import SecondaryButton from './SecondaryButton.vue';
const props = defineProps({
    modelValue: {
        type: Object,
        default: () => ({})
    }
})

const emit = defineEmits(['update:modelValue'])

const upperTeeth = [
    18, 17, 16, 15, 14, 13, 12, 11,
    21, 22, 23, 24, 25, 26, 27, 28
]
const lowerTeeth = [
    48, 47, 46, 45, 44, 43, 42, 41,
    31, 32, 33, 34, 35, 36, 37, 38
]
function applyToAllZones() {
    if (!selectedTooth.value || !procedure.value) return;

    const updated = JSON.parse(JSON.stringify(odontogram.value));

    updated[selectedTooth.value] = {
        Oclusal: procedure.value,
        Mesial: procedure.value,
        Distal: procedure.value,
        Vestibular: procedure.value,
        Lingual: procedure.value,
    };

    odontogram.value = updated;

    selectedZone.value = '';
    selectedTooth.value = null;
    procedure.value = '';
}




const odontogram = ref({ ...props.modelValue })

const selectedTooth = ref(null)
const procedure = ref('')


watch(() => props.modelValue, (val) => {
    odontogram.value = JSON.parse(JSON.stringify(val || {}))
})


const selectedZone = ref('')
const selectZone = (zone) => {
    selectedZone.value = zone
}
const zoneClass = (zone) => {
    return {
        'border rounded px-2 py-1': true,
        'bg-blue-600 text-white': selectedZone.value === zone,
        'hover:bg-blue-100 dark:hover:bg-blue-800': true
    }
}

// Lógica para selección
function selectTooth(tooth) {
    selectedTooth.value = tooth
    procedure.value = odontogram.value[tooth] || ''
}

function applyProcedure() {
    if (procedure.value && selectedZone.value) {
        if (!odontogram.value[selectedTooth.value]) {
            odontogram.value[selectedTooth.value] = {}
        }
        odontogram.value[selectedTooth.value][selectedZone.value] = procedure.value
    }

    selectedTooth.value = null
    selectedZone.value = ''
    procedure.value = ''
}


function cancel() {
    selectedTooth.value = null
    procedure.value = ''
}
</script>


<style scoped>
.relative:hover>div {
    opacity: 1 !important;
    pointer-events: auto !important;
}
</style>
