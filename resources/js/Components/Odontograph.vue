<template>
    <div>
        <h2 class="text-lg font-bold mb-4 text-gray-800 dark:text-white">Odontograma</h2>

        <!-- Fila superior -->
        <div class="grid grid-cols-16 gap-2 mb-4">
            <div v-for="tooth in upperTeeth" :key="tooth"
                class="relative group border rounded p-6 text-center cursor-pointer dark:text-white hover:bg-blue-100 dark:hover:bg-blue-800"
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

        <!-- Fila inferior -->
        <div class="grid grid-cols-16 gap-2 mb-4">
            <div v-for="tooth in lowerTeeth" :key="tooth"
                class="relative group border rounded p-6 text-center cursor-pointer hover:bg-blue-100 dark:text-white dark:hover:bg-blue-800"
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

        <!-- Modal -->
        <div v-if="selectedTooth" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 dark:text-white p-6 rounded-xl shadow-md w-full max-w-md">
                <h3 class="text-lg font-semibold mb-4">
                    Procedimiento para {{ toothNames[selectedTooth] || selectedTooth }}
                </h3>

                <!-- Zonas -->
                <div class="grid grid-cols-3 gap-2 mb-4 text-center">
                    <div></div>
                    <button type="button" @click="selectZone('Oclusal')" :class="zoneClass('Oclusal')">Oclusal</button>
                    <div></div>

                    <button type="button" @click="selectZone('Mesial')" :class="zoneClass('Mesial')">Mesial</button>
                    <button type="button" @click="applyToAllZones" :class="zoneClass('Zona')">Todas</button>
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
                    <PrimaryButton type="button" @click="applyProcedure"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Guardar</PrimaryButton>
                    <SecondaryButton type="button" @click="cancel" class="text-gray-600 hover:text-gray-600">Cancelar
                    </SecondaryButton>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import PrimaryButton from './PrimaryButton.vue'
import SecondaryButton from './SecondaryButton.vue'

const props = defineProps({
    modelValue: {
        type: Object,
        default: () => ({})
    }
})
const emit = defineEmits(['update:modelValue'])

const odontogram = ref({ ...props.modelValue })
const selectedTooth = ref(null)
const selectedZone = ref('')
const procedure = ref('')

const upperTeeth = [18, 17, 16, 15, 14, 13, 12, 11, 21, 22, 23, 24, 25, 26, 27, 28]
const lowerTeeth = [48, 47, 46, 45, 44, 43, 42, 41, 31, 32, 33, 34, 35, 36, 37, 38]

const toothNames = {
    11: "Incisivo central sup. derecho", 12: "Incisivo lateral sup. derecho", 13: "Canino sup. derecho",
    14: "1er premolar sup. derecho", 15: "2do premolar sup. derecho", 16: "1er molar sup. derecho",
    17: "2do molar sup. derecho", 18: "3er molar sup. derecho",
    21: "Incisivo central sup. izquierdo", 22: "Incisivo lateral sup. izquierdo", 23: "Canino sup. izquierdo",
    24: "1er premolar sup. izquierdo", 25: "2do premolar sup. izquierdo", 26: "1er molar sup. izquierdo",
    27: "2do molar sup. izquierdo", 28: "3er molar sup. izquierdo",
    41: "Incisivo central inf. derecho", 42: "Incisivo lateral inf. derecho", 43: "Canino inf. derecho",
    44: "1er premolar inf. derecho", 45: "2do premolar inf. derecho", 46: "1er molar inf. derecho",
    47: "2do molar inf. derecho", 48: "3er molar inf. derecho",
    31: "Incisivo central inf. izquierdo", 32: "Incisivo lateral inf. izquierdo", 33: "Canino inf. izquierdo",
    34: "1er premolar inf. izquierdo", 35: "2do premolar inf. izquierdo", 36: "1er molar inf. izquierdo",
    37: "2do molar inf. izquierdo", 38: "3er molar inf. izquierdo"
}

function selectTooth(tooth) {
    selectedTooth.value = tooth
    selectedZone.value = ''
    procedure.value = ''
}

function selectZone(zone) {
    selectedZone.value = zone
}

function zoneClass(zone) {
    return {
        'border rounded px-2 py-1': true,
        'bg-blue-600 text-white': selectedZone.value === zone,
        'hover:bg-blue-100 dark:hover:bg-blue-800': true
    }
}

function applyProcedure() {
    if (procedure.value && selectedZone.value) {
        if (!odontogram.value[selectedTooth.value]) {
            odontogram.value[selectedTooth.value] = {}
        }
        odontogram.value[selectedTooth.value][selectedZone.value] = procedure.value


        emit('update:modelValue', { ...odontogram.value })
    }

    selectedTooth.value = null
    selectedZone.value = ''
    procedure.value = ''
}

function applyToAllZones() {
    if (!selectedTooth.value || !procedure.value) return;

    odontogram.value[selectedTooth.value] = {
        Oclusal: procedure.value,
        Mesial: procedure.value,
        Distal: procedure.value,
        Vestibular: procedure.value,
        Lingual: procedure.value,
    };

    emit('update:modelValue', { ...odontogram.value })

    selectedZone.value = ''
    selectedTooth.value = null
    procedure.value = ''
}

function cancel() {
    selectedTooth.value = null
    selectedZone.value = ''
    procedure.value = ''
}

watch(() => props.modelValue, (val) => {
    odontogram.value = JSON.parse(JSON.stringify(val || {}))
})


</script>

<style scoped>
.relative:hover>.group-hover\\:opacity-100 {
    opacity: 1 !important;
    pointer-events: auto !important;
}
</style>
