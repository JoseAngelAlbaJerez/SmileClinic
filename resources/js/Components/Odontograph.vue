<template>
    <div class="space-y-6">
        <!-- Encabezado -->
        <div class="flex items-center gap-3">
            <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                <TeethIcon class="w-6 h-6 text-blue-600 dark:text-blue-400" />
            </div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Creador de Odontograma</h2>
        </div>

        <!-- Odontograma Superior -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-3">ARCADA SUPERIOR</h3>
            <div class="grid grid-cols-16 gap-1.5">
                <div
                    v-for="tooth in upperTeeth"
                    :key="tooth"
                    @click="selectTooth(tooth)"
                    class="relative group p-2 rounded-md border border-gray-200 dark:border-gray-700 text-center cursor-pointer transition-all hover:shadow-md"
                    :class="{
                        'bg-blue-100 dark:bg-blue-900/30 border-blue-300 dark:border-blue-700': odontogram[tooth],
                        'hover:bg-gray-50 dark:hover:bg-gray-700': !odontogram[tooth]
                    }"
                >
                    <div class="font-bold text-gray-800 dark:text-white text-sm">{{ tooth }}</div>

                    <!-- Indicadores de zonas -->
                    <div v-if="odontogram[tooth]" class="flex justify-center gap-1 mt-1">
                        <span
                            v-for="(proc, zone) in odontogram[tooth]"
                            :key="zone"
                            class="inline-block w-2 h-2 rounded-full"
                            :class="{
                                'bg-gray-800 dark:bg-gray-200': zone === 'Oclusal',
                                'bg-red-500': zone === 'Distal',
                                'bg-green-500': zone === 'Mesial',
                                'bg-yellow-500': zone === 'Lingual',
                                'bg-purple-500': zone === 'Vestibular'
                            }"
                        ></span>
                    </div>

                    <!-- Tooltip mejorado -->
                    <div
                        v-if="odontogram[tooth]"
                        class="absolute z-10 left-1/2 transform -translate-x-1/2 -top-28 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-xl p-3 opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity border border-gray-200 dark:border-gray-700"
                    >
                        <h4 class="font-semibold text-gray-800 dark:text-white mb-2">
                            {{ toothNames[tooth] || `Diente ${tooth}` }}
                        </h4>
                        <div class="space-y-1 text-sm">
                            <div
                                v-for="(proc, zone) in odontogram[tooth]"
                                :key="zone"
                                class="flex items-center gap-2"
                            >
                                <span
                                    class="w-3 h-3 rounded-full flex-shrink-0"
                                    :class="{
                                        'bg-gray-800 dark:bg-gray-200': zone === 'Oclusal',
                                        'bg-red-500': zone === 'Distal',
                                        'bg-green-500': zone === 'Mesial',
                                        'bg-yellow-500': zone === 'Lingual',
                                        'bg-purple-500': zone === 'Vestibular'
                                    }"
                                ></span>
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ zone }}:</span>
                                <span class="text-gray-600 dark:text-gray-400">{{ proc }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Odontograma Inferior -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-3">ARCADA INFERIOR</h3>
            <div class="grid grid-cols-16 gap-1.5">
                <div
                    v-for="tooth in lowerTeeth"
                    :key="tooth"
                    @click="selectTooth(tooth)"
                    class="relative group p-2 rounded-md border border-gray-200 dark:border-gray-700 text-center cursor-pointer transition-all hover:shadow-md"
                    :class="{
                        'bg-blue-100 dark:bg-blue-900/30 border-blue-300 dark:border-blue-700': odontogram[tooth],
                        'hover:bg-gray-50 dark:hover:bg-gray-700': !odontogram[tooth]
                    }"
                >
                    <div class="font-bold text-gray-800 dark:text-white text-sm">{{ tooth }}</div>

                    <!-- Indicadores de zonas -->
                    <div v-if="odontogram[tooth]" class="flex justify-center gap-1 mt-1">
                        <span
                            v-for="(proc, zone) in odontogram[tooth]"
                            :key="zone"
                            class="inline-block w-2 h-2 rounded-full"
                            :class="{
                                'bg-gray-800 dark:bg-gray-200': zone === 'Oclusal',
                                'bg-red-500': zone === 'Distal',
                                'bg-green-500': zone === 'Mesial',
                                'bg-yellow-500': zone === 'Lingual',
                                'bg-purple-500': zone === 'Vestibular'
                            }"
                        ></span>
                    </div>

                    <!-- Tooltip mejorado -->
                    <div
                        v-if="odontogram[tooth]"
                        class="absolute z-10 left-1/2 transform -translate-x-1/2 -top-28 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-xl p-3 opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity border border-gray-200 dark:border-gray-700"
                    >
                        <h4 class="font-semibold text-gray-800 dark:text-white mb-2">
                            {{ toothNames[tooth] || `Diente ${tooth}` }}
                        </h4>
                        <div class="space-y-1 text-sm">
                            <div
                                v-for="(proc, zone) in odontogram[tooth]"
                                :key="zone"
                                class="flex items-center gap-2"
                            >
                                <span
                                    class="w-3 h-3 rounded-full flex-shrink-0"
                                    :class="{
                                        'bg-gray-800 dark:bg-gray-200': zone === 'Oclusal',
                                        'bg-red-500': zone === 'Distal',
                                        'bg-green-500': zone === 'Mesial',
                                        'bg-yellow-500': zone === 'Lingual',
                                        'bg-purple-500': zone === 'Vestibular'
                                    }"
                                ></span>
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ zone }}:</span>
                                <span class="text-gray-600 dark:text-gray-400">{{ proc }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Edición -->
        <div
            v-if="selectedTooth"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
        >
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md overflow-hidden border border-gray-200 dark:border-gray-700">
                <!-- Encabezado del Modal -->
                <div class="p-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white">
                        {{ toothNames[selectedTooth] || `Diente ${selectedTooth}` }}
                    </h3>
                </div>

                <!-- Contenido del Modal -->
                <div class="p-5">
                    <!-- Selector de Zonas -->
                    <div class="mb-6">
                        <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-3">SELECCIONAR ZONA</h4>

                        <div class="grid grid-cols-3 gap-3 text-center dark:text-white">
                            <div></div>
                            <button
                                type="button"
                                @click="selectZone('Oclusal')"
                                class="p-2 rounded-lg border transition-colors "
                                :class="{
                                    'bg-gray-800 text-white border-gray-800 dark:bg-gray-200 dark:text-gray-800': selectedZone === 'Oclusal',
                                    'border-gray-300 hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-300': selectedZone !== 'Oclusal'
                                }"
                            >
                                Oclusal
                            </button>
                            <div></div>

                            <button
                                type="button"
                                @click="selectZone('Mesial')"
                                class="p-2 rounded-lg border transition-colors"
                                :class="{
                                    'bg-green-500 text-white border-green-500': selectedZone === 'Mesial',
                                    'border-gray-300 hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-700': selectedZone !== 'Mesial'
                                }"
                            >
                                Mesial
                            </button>

                            <button
                                type="button"
                                @click="applyToAllZones"
                                class="p-2 rounded-lg border transition-colors"
                                :class="{
                                    'bg-blue-500 text-white border-blue-500': selectedZone === 'Todas',
                                    'border-gray-300 hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-700': selectedZone !== 'Todas'
                                }"
                            >
                                Todas
                            </button>

                            <button
                                type="button"
                                @click="selectZone('Distal')"
                                class="p-2 rounded-lg border transition-colors"
                                :class="{
                                    'bg-red-500 text-white border-red-500': selectedZone === 'Distal',
                                    'border-gray-300 hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-700': selectedZone !== 'Distal'
                                }"
                            >
                                Distal
                            </button>

                            <div></div>
                            <button
                                type="button"
                                @click="selectZone('Vestibular')"
                                class="p-2 rounded-lg border transition-colors"
                                :class="{
                                    'bg-purple-500 text-white border-purple-500': selectedZone === 'Vestibular',
                                    'border-gray-300 hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-700': selectedZone !== 'Vestibular'
                                }"
                            >
                                Vestibular
                            </button>

                            <button
                                type="button"
                                @click="selectZone('Lingual')"
                                class="p-2 rounded-lg border transition-colors"
                                :class="{
                                    'bg-yellow-500 text-white border-yellow-500': selectedZone === 'Lingual',
                                    'border-gray-300 hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-700': selectedZone !== 'Lingual'
                                }"
                            >
                                Lingual
                            </button>
                        </div>
                    </div>

                    <!-- Selector de Procedimiento -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            PROCEDIMIENTO
                        </label>
                        <select
                            v-model="procedure"
                            class="w-full border dark:text-white border-gray-300 dark:border-gray-600 rounded-lg p-3 bg-white dark:bg-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        >
                            <option disabled value="">Seleccione un procedimiento</option>
                            <option value="Obturación">Obturación</option>
                            <option value="Endodoncia">Endodoncia</option>
                            <option value="Extracción">Extracción</option>
                            <option value="Limpieza">Limpieza</option>
                            <option value="Corona">Corona</option>
                            <option value="Implante">Implante</option>
                            <option value="Sellante">Sellante</option>
                            <option value="Cariado">Cariado</option>
                        </select>
                    </div>
                </div>

                <!-- Pie del Modal -->
                <div class="p-5 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3">
                    <button
                        type="button"
                        @click="cancel"
                        class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                    >
                        Cancelar
                    </button>

                    <button
                        type="button"
                        @click="applyProcedure"
                        class="px-4 py-2 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 transition-colors"
                        :disabled="!selectedZone || !procedure"
                        :class="{ 'opacity-50 cursor-not-allowed': !selectedZone || !procedure }"
                    >
                        Guardar Procedimiento
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import PrimaryButton from './PrimaryButton.vue'
import SecondaryButton from './SecondaryButton.vue'
import TeethIcon from './Icons/TeethIcon.vue'
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
