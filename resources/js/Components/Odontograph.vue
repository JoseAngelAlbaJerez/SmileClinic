<template>
    <div class="space-y-6">
        <!-- Encabezado -->
        <div class="flex items-center gap-3">
            <div class="p-2 bg-pink-100 dark:bg-pink-900 rounded-lg">
                <TeethIcon class="w-6 h-6 text-pink-600 dark:text-pink-400" />
            </div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Creador de Odontograma</h2>
        </div>
        <div class="space-y-1">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Paciente <span class="text-red-500">*</span>
            </label>
            <button type="button" @click="openPatientModal()"
                class="flex items-center w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg hover:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white transition duration-200">
                <UserIcon class="h-5 w-5 text-gray-400 dark:text-gray-500 mr-2" />
                <span v-if="selected_patient" class="truncate">
                    {{ selected_patient.first_name }} {{ selected_patient.last_name }}
                </span>
                <span v-else class="text-gray-400 dark:text-gray-400">Seleccionar</span>
            </button>

        </div>
        <!-- Odontograma Superior -->
        <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-3">ARCADA SUPERIOR</h3>
        <div
            class="grid grid-cols-2 bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 lg:grid-cols-8 gap-1.5 mr-4">
                <div v-for="tooth in upperLeftTeethTop" :key="tooth" @click="selectTooth(tooth)"
                    class="relative group rounded-md border border-gray-200 dark:border-gray-700 text-center cursor-pointer transition-all hover:shadow-md p-2 sm:p-3"
                    :class="getToothClass(tooth)">
                    <div class="font-bold text-gray-800 dark:text-white text-xs sm:text-sm">{{ tooth }}</div>

                    <!-- Indicadores de zonas -->
                    <div v-if="getStatuses(tooth).length"
                        class="relative flex justify-center items-center w-8 sm:w-10 h-8 sm:h-10 rounded">
                        <!-- Extracción (3 líneas) -->
                        <template v-if="getStatuses(tooth).includes('Extracción')">
                            <div class="absolute inset-0 flex justify-center items-center space-x-1">
                                <div class="w-1/12 h-3/4 bg-red-600"></div>
                                <div class="w-1/12 h-3/4 bg-red-600"></div>
                                <div class="w-1/12 h-3/4 bg-red-600"></div>
                            </div>
                        </template>

                        <!-- Ausencia (X) -->
                        <template v-if="getStatuses(tooth).includes('Ausencia')">
                            <div
                                class="absolute w-full h-full flex items-center justify-center text-red-600 text-sm sm:text-lg font-bold">
                                X</div>
                        </template>

                        <!-- Implante -->
                        <template v-if="getStatuses(tooth).includes('Implante')">
                            <ImplantIcon class="text-gray-800 dark:text-gray-200 w-5 sm:w-6 h-5 sm:h-6" />
                        </template>

                        <!-- Endodoncia -->
                        <template v-if="getStatuses(tooth).includes('Endodoncia')">
                            <div class="absolute w-1.5 sm:w-2 h-1.5 sm:h-2 bg-red-500 rounded-full"></div>
                        </template>
                        <template v-if="getStatuses(tooth).includes('EndodonciaAplicada')">
                            <div class="absolute w-1.5 sm:w-2 h-1.5 sm:h-2 bg-blue-500 rounded-full"></div>
                        </template>

                        <!-- Puente -->
                        <template v-if="getStatuses(tooth).includes('Puente')">
                            <div class="absolute bottom-0 w-full h-0.5 sm:h-1 bg-gray-600"></div>
                        </template>
                    </div>

                    <!-- Tooltip -->
                    <div v-if="odontogram[tooth]"
                        class="absolute z-10 left-1/2 transform -translate-x-1/2 -top-28 w-40 sm:w-48 bg-white dark:bg-gray-800 rounded-lg shadow-xl p-2 sm:p-3 opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity border border-gray-200 dark:border-gray-700">
                        <h4 class="font-semibold text-gray-800 dark:text-white mb-1 sm:mb-2 text-sm sm:text-base">
                            {{ toothNames[tooth] || `Diente ${tooth}` }}
                        </h4>
                        <div class="space-y-1 text-xs sm:text-sm">
                            <div v-for="(proc, zone) in odontogram[tooth]" :key="zone"
                                class="flex items-center gap-1 sm:gap-2">
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ zone }}:</span>
                                <span class="text-gray-600 dark:text-gray-400">{{ proc }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 lg:grid-cols-8 gap-1.5 ">
                <div v-for="tooth in upperRightTeethTop" :key="tooth" @click="selectTooth(tooth)"
                    class="relative group rounded-md border border-gray-200 dark:border-gray-700 text-center cursor-pointer transition-all hover:shadow-md p-2 sm:p-3"
                    :class="getToothClass(tooth)">
                    <div class="font-bold text-gray-800 dark:text-white text-xs sm:text-sm">{{ tooth }}</div>

                    <!-- Indicadores de zonas -->
                    <div v-if="getStatuses(tooth).length"
                        class="relative flex justify-center items-center w-8 sm:w-10 h-8 sm:h-10 rounded">
                        <!-- Extracción (3 líneas) -->
                        <template v-if="getStatuses(tooth).includes('Extracción')">
                            <div class="absolute inset-0 flex justify-center items-center space-x-1">
                                <div class="w-1/12 h-3/4 bg-red-600"></div>
                                <div class="w-1/12 h-3/4 bg-red-600"></div>
                                <div class="w-1/12 h-3/4 bg-red-600"></div>
                            </div>
                        </template>

                        <!-- Ausencia (X) -->
                        <template v-if="getStatuses(tooth).includes('Ausencia')">
                            <div
                                class="absolute w-full h-full flex items-center justify-center text-red-600 text-sm sm:text-lg font-bold">
                                X</div>
                        </template>

                        <!-- Implante -->
                        <template v-if="getStatuses(tooth).includes('Implante')">
                            <ImplantIcon class="text-gray-800 dark:text-gray-200 w-5 sm:w-6 h-5 sm:h-6" />
                        </template>

                        <!-- Endodoncia -->
                        <template v-if="getStatuses(tooth).includes('Endodoncia')">
                            <div class="absolute w-1.5 sm:w-2 h-1.5 sm:h-2 bg-red-500 rounded-full"></div>
                        </template>
                        <template v-if="getStatuses(tooth).includes('EndodonciaAplicada')">
                            <div class="absolute w-1.5 sm:w-2 h-1.5 sm:h-2 bg-blue-500 rounded-full"></div>
                        </template>

                        <!-- Puente -->
                        <template v-if="getStatuses(tooth).includes('Puente')">
                            <div class="absolute bottom-0 w-full h-0.5 sm:h-1 bg-gray-600"></div>
                        </template>
                    </div>

                    <!-- Tooltip -->
                    <div v-if="odontogram[tooth]"
                        class="absolute z-10 left-1/2 transform -translate-x-1/2 -top-28 w-40 sm:w-48 bg-white dark:bg-gray-800 rounded-lg shadow-xl p-2 sm:p-3 opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity border border-gray-200 dark:border-gray-700">
                        <h4 class="font-semibold text-gray-800 dark:text-white mb-1 sm:mb-2 text-sm sm:text-base">
                            {{ toothNames[tooth] || `Diente ${tooth}` }}
                        </h4>
                        <div class="space-y-1 text-xs sm:text-sm">
                            <div v-for="(proc, zone) in odontogram[tooth]" :key="zone"
                                class="flex items-center gap-1 sm:gap-2">
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ zone }}:</span>
                                <span class="text-gray-600 dark:text-gray-400">{{ proc }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-5 mr-4 mt-2 ml-auto gap-1.5">
                <div v-for="tooth in upperLeftTeethBot" :key="tooth" @click="selectTooth(tooth)"
                    class="relative group rounded-md border border-gray-200 dark:border-gray-700 text-center cursor-pointer transition-all hover:shadow-md p-2 sm:p-3"
                    :class="getToothClass(tooth)">
                    <div class="font-bold text-gray-800 dark:text-white text-xs sm:text-sm">{{ tooth }}</div>

                    <!-- Indicadores de zonas -->
                    <div v-if="getStatuses(tooth).length"
                        class="relative flex justify-center items-center w-8 sm:w-10 h-8 sm:h-10 rounded">
                        <!-- Extracción (3 líneas) -->
                        <template v-if="getStatuses(tooth).includes('Extracción')">
                            <div class="absolute inset-0 flex justify-center items-center space-x-1">
                                <div class="w-1/12 h-3/4 bg-red-600"></div>
                                <div class="w-1/12 h-3/4 bg-red-600"></div>
                                <div class="w-1/12 h-3/4 bg-red-600"></div>
                            </div>
                        </template>

                        <!-- Ausencia (X) -->
                        <template v-if="getStatuses(tooth).includes('Ausencia')">
                            <div
                                class="absolute w-full h-full flex items-center justify-center text-red-600 text-sm sm:text-lg font-bold">
                                X</div>
                        </template>

                        <!-- Implante -->
                        <template v-if="getStatuses(tooth).includes('Implante')">
                            <ImplantIcon class="text-gray-800 dark:text-gray-200 w-5 sm:w-6 h-5 sm:h-6" />
                        </template>

                        <!-- Endodoncia -->
                        <template v-if="getStatuses(tooth).includes('Endodoncia')">
                            <div class="absolute w-1.5 sm:w-2 h-1.5 sm:h-2 bg-red-500 rounded-full"></div>
                        </template>
                        <template v-if="getStatuses(tooth).includes('EndodonciaAplicada')">
                            <div class="absolute w-1.5 sm:w-2 h-1.5 sm:h-2 bg-blue-500 rounded-full"></div>
                        </template>

                        <!-- Puente -->
                        <template v-if="getStatuses(tooth).includes('Puente')">
                            <div class="absolute bottom-0 w-full h-0.5 sm:h-1 bg-gray-600"></div>
                        </template>
                    </div>

                    <!-- Tooltip -->
                    <div v-if="odontogram[tooth]"
                        class="absolute z-10 left-1/2 transform -translate-x-1/2 -top-28 w-40 sm:w-48 bg-white dark:bg-gray-800 rounded-lg shadow-xl p-2 sm:p-3 opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity border border-gray-200 dark:border-gray-700">
                        <h4 class="font-semibold text-gray-800 dark:text-white mb-1 sm:mb-2 text-sm sm:text-base">
                            {{ toothNames[tooth] || `Diente ${tooth}` }}
                        </h4>
                        <div class="space-y-1 text-xs sm:text-sm">
                            <div v-for="(proc, zone) in odontogram[tooth]" :key="zone"
                                class="flex items-center gap-1 sm:gap-2">
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ zone }}:</span>
                                <span class="text-gray-600 dark:text-gray-400">{{ proc }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-5  mt-2 mr-auto gap-1.5">
                <div v-for="tooth in upperRightTeethBot" :key="tooth" @click="selectTooth(tooth)"
                    class="relative group rounded-md border border-gray-200 dark:border-gray-700 text-center cursor-pointer transition-all hover:shadow-md p-2 sm:p-3"
                    :class="getToothClass(tooth)">
                    <div class="font-bold text-gray-800 dark:text-white text-xs sm:text-sm">{{ tooth }}</div>

                    <!-- Indicadores de zonas -->
                    <div v-if="getStatuses(tooth).length"
                        class="relative flex justify-center items-center w-8 sm:w-10 h-8 sm:h-10 rounded">
                        <!-- Extracción (3 líneas) -->
                        <template v-if="getStatuses(tooth).includes('Extracción')">
                            <div class="absolute inset-0 flex justify-center items-center space-x-1">
                                <div class="w-1/12 h-3/4 bg-red-600"></div>
                                <div class="w-1/12 h-3/4 bg-red-600"></div>
                                <div class="w-1/12 h-3/4 bg-red-600"></div>
                            </div>
                        </template>

                        <!-- Ausencia (X) -->
                        <template v-if="getStatuses(tooth).includes('Ausencia')">
                            <div
                                class="absolute w-full h-full flex items-center justify-center text-red-600 text-sm sm:text-lg font-bold">
                                X</div>
                        </template>

                        <!-- Implante -->
                        <template v-if="getStatuses(tooth).includes('Implante')">
                            <ImplantIcon class="text-gray-800 dark:text-gray-200 w-5 sm:w-6 h-5 sm:h-6" />
                        </template>

                        <!-- Endodoncia -->
                        <template v-if="getStatuses(tooth).includes('Endodoncia')">
                            <div class="absolute w-1.5 sm:w-2 h-1.5 sm:h-2 bg-red-500 rounded-full"></div>
                        </template>
                        <template v-if="getStatuses(tooth).includes('EndodonciaAplicada')">
                            <div class="absolute w-1.5 sm:w-2 h-1.5 sm:h-2 bg-blue-500 rounded-full"></div>
                        </template>

                        <!-- Puente -->
                        <template v-if="getStatuses(tooth).includes('Puente')">
                            <div class="absolute bottom-0 w-full h-0.5 sm:h-1 bg-gray-600"></div>
                        </template>
                    </div>

                    <!-- Tooltip -->
                    <div v-if="odontogram[tooth]"
                        class="absolute z-10 left-1/2 transform -translate-x-1/2 -top-28 w-40 sm:w-48 bg-white dark:bg-gray-800 rounded-lg shadow-xl p-2 sm:p-3 opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity border border-gray-200 dark:border-gray-700">
                        <h4 class="font-semibold text-gray-800 dark:text-white mb-1 sm:mb-2 text-sm sm:text-base">
                            {{ toothNames[tooth] || `Diente ${tooth}` }}

                        </h4>
                        <div class="space-y-1 text-xs sm:text-sm">
                            <div v-for="(proc, zone) in odontogram[tooth]" :key="zone"
                                class="flex items-center gap-1 sm:gap-2">
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ zone }}:</span>
                                <span class="text-gray-600 dark:text-gray-400">{{ proc }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <!-- Odontograma Inferior -->
        <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-3">ARCADA INFERIOR</h3>
        <div
            class="grid grid-cols-2 bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-5 mr-4 mt-2 ml-auto gap-1.5">
                <div v-for="tooth in LowerLeftTeethTop" :key="tooth" @click="selectTooth(tooth)"
                    class="relative group rounded-md border border-gray-200 dark:border-gray-700 text-center cursor-pointer transition-all hover:shadow-md p-2 sm:p-3"
                    :class="getToothClass(tooth)">
                    <div class="font-bold text-gray-800 dark:text-white text-xs sm:text-sm">{{ tooth }}</div>

                    <!-- Indicadores de zonas -->
                    <div v-if="getStatuses(tooth).length"
                        class="relative flex justify-center items-center w-8 sm:w-10 h-8 sm:h-10 rounded">
                        <!-- Extracción (3 líneas) -->
                        <template v-if="getStatuses(tooth).includes('Extracción')">
                            <div class="absolute inset-0 flex justify-center items-center space-x-1">
                                <div class="w-1/12 h-3/4 bg-red-600"></div>
                                <div class="w-1/12 h-3/4 bg-red-600"></div>
                                <div class="w-1/12 h-3/4 bg-red-600"></div>
                            </div>
                        </template>

                        <!-- Ausencia (X) -->
                        <template v-if="getStatuses(tooth).includes('Ausencia')">
                            <div
                                class="absolute w-full h-full flex items-center justify-center text-red-600 text-sm sm:text-lg font-bold">
                                X</div>
                        </template>

                        <!-- Implante -->
                        <template v-if="getStatuses(tooth).includes('Implante')">
                            <ImplantIcon class="text-gray-800 dark:text-gray-200 w-5 sm:w-6 h-5 sm:h-6" />
                        </template>

                        <!-- Endodoncia -->
                        <template v-if="getStatuses(tooth).includes('Endodoncia')">
                            <div class="absolute w-1.5 sm:w-2 h-1.5 sm:h-2 bg-red-500 rounded-full"></div>
                        </template>
                        <template v-if="getStatuses(tooth).includes('EndodonciaAplicada')">
                            <div class="absolute w-1.5 sm:w-2 h-1.5 sm:h-2 bg-blue-500 rounded-full"></div>
                        </template>

                        <!-- Puente -->
                        <template v-if="getStatuses(tooth).includes('Puente')">
                            <div class="absolute bottom-0 w-full h-0.5 sm:h-1 bg-gray-600"></div>
                        </template>
                    </div>

                    <!-- Tooltip -->
                    <div v-if="odontogram[tooth]"
                        class="absolute z-10 left-1/2 transform -translate-x-1/2 -top-28 w-40 sm:w-48 bg-white dark:bg-gray-800 rounded-lg shadow-xl p-2 sm:p-3 opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity border border-gray-200 dark:border-gray-700">
                        <h4 class="font-semibold text-gray-800 dark:text-white mb-1 sm:mb-2 text-sm sm:text-base">
                            {{ toothNames[tooth] || `Diente ${tooth}` }}
                        </h4>
                        <div class="space-y-1 text-xs sm:text-sm">
                            <div v-for="(proc, zone) in odontogram[tooth]" :key="zone"
                                class="flex items-center gap-1 sm:gap-2">
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ zone }}:</span>
                                <span class="text-gray-600 dark:text-gray-400">{{ proc }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-5  mt-2 mr-auto gap-1.5">
                <div v-for="tooth in LowerRightTeethTop" :key="tooth" @click="selectTooth(tooth)"
                    class="relative group rounded-md border border-gray-200 dark:border-gray-700 text-center cursor-pointer transition-all hover:shadow-md p-2 sm:p-3"
                    :class="getToothClass(tooth)">
                    <div class="font-bold text-gray-800 dark:text-white text-xs sm:text-sm">{{ tooth }}</div>

                    <!-- Indicadores de zonas -->
                    <div v-if="getStatuses(tooth).length"
                        class="relative flex justify-center items-center w-8 sm:w-10 h-8 sm:h-10 rounded">
                        <!-- Extracción (3 líneas) -->
                        <template v-if="getStatuses(tooth).includes('Extracción')">
                            <div class="absolute inset-0 flex justify-center items-center space-x-1">
                                <div class="w-1/12 h-3/4 bg-red-600"></div>
                                <div class="w-1/12 h-3/4 bg-red-600"></div>
                                <div class="w-1/12 h-3/4 bg-red-600"></div>
                            </div>
                        </template>

                        <!-- Ausencia (X) -->
                        <template v-if="getStatuses(tooth).includes('Ausencia')">
                            <div
                                class="absolute w-full h-full flex items-center justify-center text-red-600 text-sm sm:text-lg font-bold">
                                X</div>
                        </template>

                        <!-- Implante -->
                        <template v-if="getStatuses(tooth).includes('Implante')">
                            <ImplantIcon class="text-gray-800 dark:text-gray-200 w-5 sm:w-6 h-5 sm:h-6" />
                        </template>

                        <!-- Endodoncia -->
                        <template v-if="getStatuses(tooth).includes('Endodoncia')">
                            <div class="absolute w-1.5 sm:w-2 h-1.5 sm:h-2 bg-red-500 rounded-full"></div>
                        </template>
                        <template v-if="getStatuses(tooth).includes('EndodonciaAplicada')">
                            <div class="absolute w-1.5 sm:w-2 h-1.5 sm:h-2 bg-blue-500 rounded-full"></div>
                        </template>

                        <!-- Puente -->
                        <template v-if="getStatuses(tooth).includes('Puente')">
                            <div class="absolute bottom-0 w-full h-0.5 sm:h-1 bg-gray-600"></div>
                        </template>
                    </div>

                    <!-- Tooltip -->
                    <div v-if="odontogram[tooth]"
                        class="absolute z-10 left-1/2 transform -translate-x-1/2 -top-28 w-40 sm:w-48 bg-white dark:bg-gray-800 rounded-lg shadow-xl p-2 sm:p-3 opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity border border-gray-200 dark:border-gray-700">
                        <h4 class="font-semibold text-gray-800 dark:text-white mb-1 sm:mb-2 text-sm sm:text-base">
                            {{ toothNames[tooth] || `Diente ${tooth}` }}
                        </h4>
                        <div class="space-y-1 text-xs sm:text-sm">
                            <div v-for="(proc, zone) in odontogram[tooth]" :key="zone"
                                class="flex items-center gap-1 sm:gap-2">
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ zone }}:</span>
                                <span class="text-gray-600 dark:text-gray-400">{{ proc }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 lg:grid-cols-8 mt-2 gap-1.5 mr-4">
                <div v-for="tooth in LowerLeftTeethBot" :key="tooth" @click="selectTooth(tooth)"
                    class="relative group rounded-md border border-gray-200 dark:border-gray-700 text-center cursor-pointer transition-all hover:shadow-md p-2 sm:p-3"
                    :class="getToothClass(tooth)">
                    <div class="font-bold text-gray-800 dark:text-white text-xs sm:text-sm">{{ tooth }}</div>

                    <!-- Indicadores de zonas -->
                    <div v-if="getStatuses(tooth).length"
                        class="relative flex justify-center items-center w-8 sm:w-10 h-8 sm:h-10 rounded">
                        <!-- Extracción (3 líneas) -->
                        <template v-if="getStatuses(tooth).includes('Extracción')">
                            <div class="absolute inset-0 flex justify-center items-center space-x-1">
                                <div class="w-1/12 h-3/4 bg-red-600"></div>
                                <div class="w-1/12 h-3/4 bg-red-600"></div>
                                <div class="w-1/12 h-3/4 bg-red-600"></div>
                            </div>
                        </template>

                        <!-- Ausencia (X) -->
                        <template v-if="getStatuses(tooth).includes('Ausencia')">
                            <div
                                class="absolute w-full h-full flex items-center justify-center text-red-600 text-sm sm:text-lg font-bold">
                                X</div>
                        </template>

                        <!-- Implante -->
                        <template v-if="getStatuses(tooth).includes('Implante')">
                            <ImplantIcon class="text-gray-800 dark:text-gray-200 w-5 sm:w-6 h-5 sm:h-6" />
                        </template>

                        <!-- Endodoncia -->
                        <template v-if="getStatuses(tooth).includes('Endodoncia')">
                            <div class="absolute w-1.5 sm:w-2 h-1.5 sm:h-2 bg-red-500 rounded-full"></div>
                        </template>
                        <template v-if="getStatuses(tooth).includes('EndodonciaAplicada')">
                            <div class="absolute w-1.5 sm:w-2 h-1.5 sm:h-2 bg-blue-500 rounded-full"></div>
                        </template>

                        <!-- Puente -->
                        <template v-if="getStatuses(tooth).includes('Puente')">
                            <div class="absolute bottom-0 w-full h-0.5 sm:h-1 bg-gray-600"></div>
                        </template>
                    </div>

                    <!-- Tooltip -->
                    <div v-if="odontogram[tooth]"
                        class="absolute z-10 left-1/2 transform -translate-x-1/2 -top-28 w-40 sm:w-48 bg-white dark:bg-gray-800 rounded-lg shadow-xl p-2 sm:p-3 opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity border border-gray-200 dark:border-gray-700">
                        <h4 class="font-semibold text-gray-800 dark:text-white mb-1 sm:mb-2 text-sm sm:text-base">
                            {{ toothNames[tooth] || `Diente ${tooth}` }}
                        </h4>
                        <div class="space-y-1 text-xs sm:text-sm">
                            <div v-for="(proc, zone) in odontogram[tooth]" :key="zone"
                                class="flex items-center gap-1 sm:gap-2">
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ zone }}:</span>
                                <span class="text-gray-600 dark:text-gray-400">{{ proc }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 lg:grid-cols-8 mt-2 gap-1.5 ">
                <div v-for="tooth in LowerRightTeethBot" :key="tooth" @click="selectTooth(tooth)"
                    class="relative group rounded-md border border-gray-200 dark:border-gray-700 text-center cursor-pointer transition-all hover:shadow-md p-2 sm:p-3"
                    :class="getToothClass(tooth)">
                    <div class="font-bold text-gray-800 dark:text-white text-xs sm:text-sm">{{ tooth }}</div>

                    <!-- Indicadores de zonas -->
                    <div v-if="getStatuses(tooth).length"
                        class="relative flex justify-center items-center w-8 sm:w-10 h-8 sm:h-10 rounded">
                        <!-- Extracción (3 líneas) -->
                        <template v-if="getStatuses(tooth).includes('Extracción')">
                            <div class="absolute inset-0 flex justify-center items-center space-x-1">
                                <div class="w-1/12 h-3/4 bg-red-600"></div>
                                <div class="w-1/12 h-3/4 bg-red-600"></div>
                                <div class="w-1/12 h-3/4 bg-red-600"></div>
                            </div>
                        </template>

                        <!-- Ausencia (X) -->
                        <template v-if="getStatuses(tooth).includes('Ausencia')">
                            <div
                                class="absolute w-full h-full flex items-center justify-center text-red-600 text-sm sm:text-lg font-bold">
                                X</div>
                        </template>

                        <!-- Implante -->
                        <template v-if="getStatuses(tooth).includes('Implante')">
                            <ImplantIcon class="text-gray-800 dark:text-gray-200 w-5 sm:w-6 h-5 sm:h-6" />
                        </template>

                        <!-- Endodoncia -->
                        <template v-if="getStatuses(tooth).includes('Endodoncia')">
                            <div class="absolute w-1.5 sm:w-2 h-1.5 sm:h-2 bg-red-500 rounded-full"></div>
                        </template>
                        <template v-if="getStatuses(tooth).includes('EndodonciaAplicada')">
                            <div class="absolute w-1.5 sm:w-2 h-1.5 sm:h-2 bg-blue-500 rounded-full"></div>
                        </template>

                        <!-- Puente -->
                        <template v-if="getStatuses(tooth).includes('Puente')">
                            <div class="absolute bottom-0 w-full h-0.5 sm:h-1 bg-gray-600"></div>
                        </template>
                    </div>

                    <!-- Tooltip -->
                    <div v-if="odontogram[tooth]"
                        class="absolute z-10 left-1/2 transform -translate-x-1/2 -top-28 w-40 sm:w-48 bg-white dark:bg-gray-800 rounded-lg shadow-xl p-2 sm:p-3 opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity border border-gray-200 dark:border-gray-700">
                        <h4 class="font-semibold text-gray-800 dark:text-white mb-1 sm:mb-2 text-sm sm:text-base">
                            {{ toothNames[tooth] || `Diente ${tooth}` }}
                        </h4>
                        <div class="space-y-1 text-xs sm:text-sm">
                            <div v-for="(proc, zone) in odontogram[tooth]" :key="zone"
                                class="flex items-center gap-1 sm:gap-2">
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ zone }}:</span>
                                <span class="text-gray-600 dark:text-gray-400">{{ proc }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Modal de Edición -->
        <div v-if="selectedTooth"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md overflow-hidden border border-gray-200 dark:border-gray-700">
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
                            <button type="button" @click="selectZone('Oclusal')"
                                class="p-2 rounded-lg border transition-colors " :class="{
                                    'bg-gray-800 text-white border-gray-800 dark:bg-gray-200 dark:text-gray-800': selectedZone === 'Oclusal',
                                    'border-gray-300 hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-300': selectedZone !== 'Oclusal'
                                }">
                                Oclusal
                            </button>
                            <div></div>

                            <button type="button" @click="selectZone('Mesial')"
                                class="p-2 rounded-lg border transition-colors" :class="{
                                    'bg-green-500 text-white border-green-500': selectedZone === 'Mesial',
                                    'border-gray-300 hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-700': selectedZone !== 'Mesial'
                                }">
                                Mesial
                            </button>

                            <button type="button" @click="applyToAllZones"
                                class="p-2 rounded-lg border transition-colors" :class="{
                                    'bg-pink-500 text-white border-pink-500': selectedZone === 'Todas',
                                    'border-gray-300 hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-700': selectedZone !== 'Todas'
                                }">
                                Todas
                            </button>

                            <button type="button" @click="selectZone('Distal')"
                                class="p-2 rounded-lg border transition-colors" :class="{
                                    'bg-red-500 text-white border-red-500': selectedZone === 'Distal',
                                    'border-gray-300 hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-700': selectedZone !== 'Distal'
                                }">
                                Distal
                            </button>

                            <div></div>
                            <button type="button" @click="selectZone('Vestibular')"
                                class="p-2 rounded-lg border transition-colors" :class="{
                                    'bg-purple-500 text-white border-purple-500': selectedZone === 'Vestibular',
                                    'border-gray-300 hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-700': selectedZone !== 'Vestibular'
                                }">
                                Vestibular
                            </button>

                            <button type="button" @click="selectZone('Lingual')"
                                class="p-2 rounded-lg border transition-colors" :class="{
                                    'bg-yellow-500 text-white border-yellow-500': selectedZone === 'Lingual',
                                    'border-gray-300 hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-700': selectedZone !== 'Lingual'
                                }">
                                Lingual
                            </button>
                        </div>
                    </div>

                    <!-- Selector de Procedimiento -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            PROCEDIMIENTO
                        </label>
                        <select v-model="procedure"
                            class="w-full border dark:text-white border-gray-300 dark:border-gray-600 rounded-lg p-3 bg-white dark:bg-gray-800 focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-colors">
                            <option disabled value="">Seleccione un procedimiento</option>
                            <option value="Ausencia">Ausencia</option>
                            <option value="Endodoncia">Endodoncia</option>
                            <option value="EndodonciaAplicada">Endodoncia Aplicada</option>
                            <option value="Extracción">Extracción</option>
                            <option value="Puente">Puente</option>
                            <option value="Corona">Corona (necesita)</option>
                            <option value="CoronaAplicada">Corona Aplicada</option>
                            <option value="Implante">Implante</option>
                            <option value="Restauración">Restauración</option>
                            <option value="Cariado">Cariado</option>
                        </select>
                    </div>
                </div>

                <!-- Pie del Modal -->
                <div class="p-5 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3">
                    <button type="button" @click="cancel"
                        class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        Cancelar
                    </button>

                    <button type="button" @click="applyProcedure"
                        class="px-4 py-2 rounded-lg bg-pink-600 text-white font-medium hover:bg-pink-700 transition-colors"
                        :disabled="!selectedZone || !procedure"
                        :class="{ 'opacity-50 cursor-not-allowed': !selectedZone || !procedure }">
                        Guardar Procedimiento
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Patient Selection Modal -->
    <Modal :show="showPatientModal" @close="showPatientModal = false" maxWidth="2xl">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white">
                    <UserIcon class="h-6 w-6 inline-block mr-2 text-pink-500" />
                    Seleccionar Paciente
                </h3>
                <button @click="showPatientModal = false"
                    class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                    <XIcon class="h-6 w-6" />
                </button>
            </div>
            <PatientSelector :patients="patients" @selected="selectPatient" />
        </div>
    </Modal>
</template>

<script setup>
import { ref, watch } from 'vue'
import PrimaryButton from './PrimaryButton.vue'
import SecondaryButton from './SecondaryButton.vue'
import TeethIcon from './Icons/TeethIcon.vue'
import ImplantIcon from './Icons/ImplantIcon.vue'
import UserIcon from './Icons/UserIcon.vue'
import PatientSelector from './PatientSelector.vue'
import XIcon from './Icons/XIcon.vue'
import Modal from './Modal.vue'




const props = defineProps({
    modelValue: { type: Object, default: () => ({}) },
    patients: { type: Object, default: () => [] },
    patient: { type: Object, default: () => [] }

});
const emit = defineEmits(['update:patient', 'update:modelValue']);


const upperLeftTeethTop = [18, 17, 16, 15, 14, 13, 12, 11,]
const upperLeftTeethBot = [55, 54, 53, 52, 51]
const showPatientModal = ref(false);
const selected_patient = ref(props.patient || null);


const selectPatient = (p) => {
    selected_patient.value = p;
    showPatientModal.value = false;
    emit('update:patient', p);
};


const upperRightTeethTop = [21, 22, 23, 24, 25, 26, 27, 28,]
const upperRightTeethBot = [61, 62, 63, 64, 65]

const LowerLeftTeethTop = [85, 84, 83, 82, 81]
const LowerLeftTeethBot = [48, 47, 46, 45, 44, 43, 42, 41]



const LowerRightTeethBot = [31, 32, 33, 34, 35, 36, 37, 38,]
const LowerRightTeethTop = [71, 72, 73, 74, 75]


const allTeeth = [
    ...upperLeftTeethTop,
    ...upperLeftTeethBot,
    ...upperRightTeethTop,
    ...upperRightTeethBot,
    ...LowerLeftTeethTop,
    ...LowerLeftTeethBot,
    ...LowerRightTeethBot,
    ...LowerRightTeethTop,
]

const odontogram = ref({ ...props.modelValue })
allTeeth.forEach(tooth => {
    odontogram.value[tooth] = {}
})
const selectedTooth = ref(null)
const selectedZone = ref('')
const procedure = ref('')


const toothNames = {
    11: "Incisivo central superior derecho", 12: "Incisivo lateral superior derecho", 13: "Canino superior derecho",
    14: "1er premolar superior derecho", 15: "2do premolar superior derecho", 16: "1er molar superior derecho",
    17: "2do molar superior derecho", 18: "3er molar superior derecho",

    21: "Incisivo central superior izquierdo", 22: "Incisivo lateral superior izquierdo", 23: "Canino superior izquierdo",
    24: "1er premolar superior izquierdo", 25: "2do premolar superior izquierdo", 26: "1er molar superior izquierdo",
    27: "2do molar superior izquierdo", 28: "3er molar superior izquierdo",

    41: "Incisivo central inferior derecho", 42: "Incisivo lateral inferior derecho", 43: "Canino inferior derecho",
    44: "1er premolar inferior derecho", 45: "2do premolar inferior derecho", 46: "1er molar inferior derecho",
    47: "2do molar inferior derecho", 48: "3er molar inferior derecho",

    31: "Incisivo central inferior izquierdo", 32: "Incisivo lateral inferior izquierdo", 33: "Canino inferior izquierdo",
    34: "1er premolar inferior izquierdo", 35: "2do premolar inferior izquierdo", 36: "1er molar inferior izquierdo",
    37: "2do molar inferior izquierdo", 38: "3er molar inferior izquierdo",

    51: "Incisivo central superior derecho (temporal)", 52: "Incisivo lateral superior derecho (temporal)",
    53: "Canino superior derecho (temporal)", 54: "1er molar superior derecho (temporal)",
    55: "2do molar superior derecho (temporal)",

    61: "Incisivo central superior izquierdo (temporal)", 62: "Incisivo lateral superior izquierdo (temporal)",
    63: "Canino superior izquierdo (temporal)", 64: "1er molar superior izquierdo (temporal)",
    65: "2do molar superior izquierdo (temporal)",

    71: "Incisivo central inferior izquierdo (temporal)", 72: "Incisivo lateral inferior izquierdo (temporal)",
    73: "Canino inferior izquierdo (temporal)", 74: "1er molar inferior izquierdo (temporal)",
    75: "2do molar inferior izquierdo (temporal)",

    81: "Incisivo central inferior derecho (temporal)", 82: "Incisivo lateral inferior derecho (temporal)",
    83: "Canino inferior derecho (temporal)", 84: "1er molar inferior derecho (temporal)",
    85: "2do molar inferior derecho (temporal)"
};

const openPatientModal = () => {
    showPatientModal.value = true;
};

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
        'bg-pink-600 text-white': selectedZone.value === zone,
        'hover:bg-pink-100 dark:hover:bg-pink-800': true
    }
}
const getStatuses = (tooth) => {
    const value = odontogram.value[tooth];
    if (!value) return [];

    if (Array.isArray(value)) {
        return [...value];
    }

    return Object.values({ ...value });
};

const getToothClass = (tooth) => {
    const statuses = getStatuses(tooth);

    if (statuses.includes("Cariado")) {
        return "bg-red-100 dark:bg-red-900/30 border-red-300 dark:border-red-700";
    }

    if (statuses.includes("Restauración")) {
        return "bg-blue-100 dark:bg-blue-800/30 border-blue-300 dark:border-blue-700";
    }

    if (statuses.includes("Corona")) {
        return "bg-yellow-100 dark:bg-yellow-900/30 border-yellow-300 dark:border-yellow-700";
    }

    if (statuses.includes("CoronaAplicada")) {
        return "bg-blue-200 dark:bg-blue-900/30 border-blue-500 dark:border-blue-800";
    }

    if (statuses.length > 0) {
        return "bg-pink-100 dark:bg-pink-900/30 border-pink-300 dark:border-pink-700";
    }

    return "hover:bg-gray-50 dark:hover:bg-gray-700";
};


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
watch(
    () => props.modelValue,
    (val) => (odontogram.value = val)
);

watch(
    () => odontogram.value,
    (val) => emit("update:modelValue", val),
    { deep: true }
);

</script>

<style scoped>
.relative:hover>.group-hover\\:opacity-100 {
    opacity: 1 !important;
    pointer-events: auto !important;
}
</style>
