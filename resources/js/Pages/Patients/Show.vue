<template>

    <Head :title="patient.first_name + ' ' + patient.last_name" />
    <AuthenticatedLayout>
        <template #header>
            <Breadcrumb :crumbs="crumbs" />
        </template>

        <div
            class="container mx-auto px-6 py-4 mb-5 dark:text-white bg-white dark:bg-gray-700 mt-5  pb-8  rounded-2xl shadow-md">
            <div class="my-2 mt-5 flex  gap-2 items-center">

                <h2 class="text-lg font-semibold ml-1"> Historial Médico de {{ patient.first_name }} {{
                    patient.last_name }}
                </h2>
                <div v-if="patient.active" class=" flex ml-auto gap-2 mb-2 ">
                    <button @click="print()"
                        class="flex justify-center gap-2 rounded-lg bg-green-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-500 sm:px-4">
                        <PrintIcon />
                    </button>
                    <AccessGate permission="patient.update">
                        <Link :href="route('patients.edit', patient.id)"
                            class="flex justify-center gap-2 rounded-lg bg-yellow-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-500 sm:px-4">
                        <EditIcon />
                        </Link>
                    </AccessGate>
                    <AccessGate permission="patient.delete">
                        <DangerButton @click="deletePatient(patient.id)"
                            class="flex justify-center gap-2 rounded-lg bg-red-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 sm:px-4">
                            <DeleteIcon />
                        </DangerButton>
                    </AccessGate>
                </div>

                <div class="flex ml-auto gap-2" v-else>
                    <PrimaryButton @click="restorePatient(patient.id)"
                        class="flex justify-center gap-2 rounded-lg bg-green-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-500 sm:px-4">
                        <RestoreIcon /> Restaurar
                    </PrimaryButton>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">
                <!-- Información personal -->
                <!-- Información del Paciente - Diseño Mejorado -->
                <div
                    class="bg-gradient-to-br from-pink-50 to-indigo-50 dark:from-gray-800 dark:to-gray-900 p-6 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
                    <!-- Encabezado principal -->
                    <div
                        class="mb-6 flex items-center gap-3 backdrop-blur-sm bg-white/30 dark:bg-gray-700/50 p-3 rounded-xl border border-gray-200/50 dark:border-gray-600/50 shadow-sm">
                        <div class="p-2 bg-pink-100 dark:bg-pink-900 rounded-lg">
                            <UserIcon class="w-6 h-6 text-pink-600 dark:text-pink-300" />
                        </div>
                        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">Información del Paciente</h2>
                    </div>

                    <!-- Contenido en dos columnas -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Columna 1: Información Personal -->
                        <div
                            class="bg-white/50 dark:bg-gray-700/50 p-5 rounded-xl border border-gray-200/50 dark:border-gray-600/50 shadow-sm">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="p-2 bg-pink-100 dark:bg-pink-900 rounded-lg">
                                    <CardIcon class="w-5 h-5 text-pink-600 dark:text-pink-300" />
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Datos Personales</h3>
                            </div>

                            <div class="space-y-3 text-sm">
                                <div class="flex items-start">
                                    <span class="font-medium w-32 text-gray-600 dark:text-gray-300 flex-shrink-0">Nombre
                                        completo:</span>
                                    <span class="text-gray-800 dark:text-gray-100">{{ patient.first_name }} {{
                                        patient.last_name
                                        }}</span>
                                </div>
                                <div class="flex items-start">
                                    <span
                                        class="font-medium w-32 text-gray-600 dark:text-gray-300 flex-shrink-0">Cédula/DNI:</span>
                                    <span class="text-gray-800 dark:text-gray-100">{{ patient.DNI }}</span>
                                </div>
                                <div class="flex items-start">
                                    <span
                                        class="font-medium w-32 text-gray-600 dark:text-gray-300 flex-shrink-0">Teléfono:</span>
                                    <span class="text-gray-800 dark:text-gray-100">{{ patient.phone_number }}</span>
                                </div>
                                <div class="flex items-start">
                                    <span
                                        class="font-medium w-32 text-gray-600 dark:text-gray-300 flex-shrink-0">ARS:</span>
                                    <span class="text-gray-800 dark:text-gray-100">{{ patient.ars }}</span>
                                </div>
                                <div class="flex items-start">
                                    <span class="font-medium w-32 text-gray-600 dark:text-gray-300 flex-shrink-0">Fecha
                                        Nacimiento:</span>
                                    <span class="text-gray-800 dark:text-gray-100">{{ formatDate(patient.date_of_birth)
                                        }} ({{ patient.age }} años)</span>
                                </div>
                                <div class="flex items-start">
                                    <span
                                        class="font-medium w-32 text-gray-600 dark:text-gray-300 flex-shrink-0">Dirección:</span>
                                    <span class="text-gray-800 dark:text-gray-100">{{ patient.address }}</span>
                                </div>
                                <div class="flex items-start">
                                    <span class="font-medium w-32 text-gray-600 dark:text-gray-300 flex-shrink-0">Motivo
                                        de
                                        visita:</span>
                                    <span class="text-gray-800 dark:text-gray-100">{{ patient.motive }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Columna 2: Antecedentes Médicos -->
                        <div
                            class="bg-white/50 dark:bg-gray-700/50 p-5 rounded-xl border border-gray-200/50 dark:border-gray-600/50 shadow-sm">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="p-2 bg-pink-100 dark:bg-pink-900 rounded-lg">
                                    <MedicalHistoryIcon class="w-5 h-5 text-pink-600 dark:text-pink-300" />
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Antecedentes Médicos
                                </h3>
                            </div>

                            <div class="space-y-3 text-sm">
                                <!-- Complicaciones -->
                                <div class="bg-pink-50 dark:bg-gray-600/50 p-3 rounded-lg">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="font-medium text-gray-800 dark:text-gray-100">Complicaciones
                                            médicas:</span>
                                        <span
                                            :class="patient.complications ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'"
                                            class="text-xs px-2 py-1 rounded-full">
                                            {{ patient.complications ? 'Sí' : 'No' }}
                                        </span>
                                    </div>
                                    <p v-if="patient.complications_detail"
                                        class="text-gray-700 dark:text-gray-300 mt-1 pl-5 text-xs">
                                        <span class="font-medium">Detalle:</span> {{ patient.complications_detail }}
                                    </p>
                                </div>

                                <!-- Medicamentos -->
                                <div class="bg-pink-50 dark:bg-gray-600/50 p-3 rounded-lg">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="font-medium text-gray-800 dark:text-gray-100">Uso de
                                            medicamentos:</span>
                                        <span
                                            :class="patient.drugs ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'"
                                            class="text-xs px-2 py-1 rounded-full">
                                            {{ patient.drugs ? 'Sí' : 'No' }}
                                        </span>
                                    </div>
                                    <p v-if="patient.drugs_detail"
                                        class="text-gray-700 dark:text-gray-300 mt-1 pl-5 text-xs">
                                        <span class="font-medium">Detalle:</span> {{ patient.drugs_detail }}
                                    </p>
                                </div>

                                <!-- Alergias -->
                                <div class="bg-pink-50 dark:bg-gray-600/50 p-3 rounded-lg">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="font-medium text-gray-800 dark:text-gray-100">Alergias
                                            conocidas:</span>
                                        <span
                                            :class="patient.alergies ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'"
                                            class="text-xs px-2 py-1 rounded-full">
                                            {{ patient.alergies ? 'Sí' : 'No' }}
                                        </span>
                                    </div>
                                    <p v-if="patient.alergies_detail"
                                        class="text-gray-700 dark:text-gray-300 mt-1 pl-5 text-xs">
                                        <span class="font-medium">Detalle:</span> {{ patient.alergies_detail }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


                <!-- Recetas médicas -->
                <!-- Recetas Médicas - Diseño Mejorado -->
                <AccessGate permission="prescription.view">
                    <div
                        class="bg-gradient-to-br from-pink-50 to-indigo-50 dark:from-gray-800 dark:to-gray-900 p-6 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
                        <!-- Encabezado con efecto de vidrio -->
                        <div
                            class="mb-6 flex items-center gap-3 backdrop-blur-sm bg-white/30 dark:bg-gray-700/50 p-3 rounded-xl border border-gray-200/50 dark:border-gray-600/50 shadow-sm">
                            <div class="p-2 bg-pink-100 dark:bg-pink-900 rounded-lg">
                                <MedicalHistoryIcon class="w-6 h-6 text-pink-600 dark:text-pink-300" />
                            </div>
                            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">Recetas Médicas</h2>
                            <div class="ml-auto flex gap-2">
                                <AccessGate permission="prescription.view">
                                    <Link :href="route('prescriptions.index', { patient_id: patient.id })"
                                        class="flex items-center justify-center size-9 rounded-lg bg-pink-500 text-white hover:bg-pink-600 transition-all transform hover:scale-105 shadow-md">
                                    <EyeIcon class="size-5" />
                                    </Link>
                                </AccessGate>
                                <AccessGate permission="prescription.create">
                                    <Link :href="route('prescriptions.create')" as="button"
                                        class="flex items-center justify-center size-9 rounded-lg bg-green-500 text-white hover:bg-green-600 transition-all transform hover:scale-105 shadow-md">
                                    <AddIcon class="size-5" />
                                    </Link>
                                </AccessGate>
                            </div>
                        </div>

                        <!-- Lista de recetas -->
                        <div class="max-h-96 overflow-y-auto pr-2 space-y-3">
                            <div v-for="prescription in prescription" :key="prescription.id"
                                class="group relative bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">


                                <!-- Contenido de la receta -->
                                <div class="p-4">
                                    <div class="flex items-start mb-3">
                                        <div class="flex-1">
                                            <div class="flex justify-between">
                                                <h3
                                                    class="text-lg font-bold text-gray-800 dark:text-gray-100 group-hover:text-pink-600 dark:group-hover:text-pink-300 transition-colors">
                                                    Receta # - {{ prescription.id }}

                                                </h3>
                                                <span class="  text-sm font-medium px-2 py-1 rounded-full"
                                                    :class="prescription.ending_date ? 'bg-green-100 text-green-600 dark:bg-green-800 dark:text-green-300' : 'bg-red-100 text-red-600 dark:bg-red-800 dark:text-red-300'">
                                                    {{ new Date(prescription.ending_date) > new Date() ? 'VIGENTE' :
                                                        'VENCIDA' }}
                                                </span>

                                            </div>


                                            <!-- Info básica -->
                                            <div class="mt-2 grid grid-cols-2 gap-2 text-sm">
                                                <div class="flex items-center text-gray-600 dark:text-gray-300">
                                                    <CalendarDaysIcon class="w-4 h-4 mr-1 text-pink-500" />
                                                    <span>{{ formatDate(prescription.created_at) }}</span>
                                                </div>
                                                <div class="flex items-center text-gray-600 dark:text-gray-300">
                                                    <UserIcon class="w-4 h-4 mr-1 text-pink-500" />
                                                    <span>Dr. {{ prescription.doctor.name }} {{
                                                        prescription.doctor.last_name
                                                    }}</span>
                                                </div>

                                            </div>

                                            <!-- Acordeón para medicamentos -->
                                            <div class="mt-3">
                                                <details class="border-t border-gray-100 dark:border-gray-600 pt-2">
                                                    <summary
                                                        class="flex items-center cursor-pointer text-sm font-medium text-pink-600 dark:text-pink-400">
                                                        <span>Ver medicamentos</span>
                                                        <ChevronDownIcon
                                                            class="w-4 h-4 ml-1 transition-transform duration-200 group-open:rotate-180" />
                                                    </summary>
                                                    <div class="mt-2 space-y-3">
                                                        <div v-for="detail in prescription.prescriptions_details"
                                                            :key="detail.id"
                                                            class="bg-gray-50 dark:bg-gray-600 p-3 rounded-lg border border-gray-200 dark:border-gray-500">
                                                            <div class="flex items-start">
                                                                <div
                                                                    class="p-2 bg-pink-100 dark:bg-pink-900 rounded-lg mr-3">
                                                                    <PillIcon
                                                                        class="w-5 h-5 text-pink-600 dark:text-pink-300" />
                                                                </div>
                                                                <div class="flex-1">
                                                                    <h4
                                                                        class="font-bold text-gray-800 dark:text-gray-100">
                                                                        {{
                                                                            detail.drugs.name }}</h4>
                                                                    <p class="text-sm text-gray-600 dark:text-gray-300">
                                                                        {{
                                                                            detail.drugs.description }}</p>
                                                                    <div class="grid grid-cols-2 gap-2 mt-2 text-xs">


                                                                        <div class="col-span-2">
                                                                            <span class="font-medium">Instrucciones:
                                                                            </span>
                                                                            <span> {{ detail.description }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </details>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Acciones -->
                                    <div
                                        class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100 dark:border-gray-600">
                                        <div class="text-x text-gray-500 dark:text-gray-400">
                                            <span v-if="prescription.active" class="text-green-500">Activa</span>
                                            <span v-else class="text-red-500">Inactiva</span>
                                        </div>

                                        <div class="flex gap-2">
                                            <Link v-if="prescription.active"
                                                :href="route('prescriptions.edit', prescription)"
                                                class="flex items-center justify-center size-8 rounded-lg bg-yellow-500 text-white hover:bg-yellow-600 transition-all transform hover:scale-110 shadow-md">
                                            <EditIcon class="size-4" />
                                            </Link>
                                            <DangerButton v-if="prescription.active"
                                                @click="deletePrescription(prescription)"
                                                class="flex items-center justify-center size-8 rounded-lg bg-red-500 text-white hover:bg-red-600 transition-all transform hover:scale-110 shadow-md">
                                                <DeleteIcon class="size-4" />
                                            </DangerButton>
                                            <button v-else @click="restorePrescription(prescription)"
                                                class="flex items-center justify-center size-8 rounded-lg bg-green-500 text-white hover:bg-green-600 transition-all transform hover:scale-110 shadow-md">
                                                <RestoreIcon class="size-4" />
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mensaje cuando no hay recetas -->
                        <div v-if="!prescription.length" class="text-center py-6 ">
                            <div class="inline-flex flex-col items-center">
                                <DocumentTextIcon class="w-12 h-12 text-gray-400 dark:text-gray-500 mb-2" />
                                <p class="text-gray-500 dark:text-gray-400 font-medium">No hay recetas médicas
                                    registradas
                                </p>
                                <p class="text-sm text-gray-400 dark:text-gray-500">Crea una nueva receta para comenzar
                                </p>
                            </div>
                        </div>
                    </div>
                </AccessGate>

                <AccessGate permission="event.view">
                    <!-- Citas Pendientes - Diseño Mejorado -->
                    <div
                        class="bg-gradient-to-br from-pink-50 to-indigo-50 dark:from-gray-800 dark:to-gray-900 p-6 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
                        <!-- Encabezado con efecto de vidrio -->
                        <div
                            class="mb-6 flex items-center gap-3 backdrop-blur-sm bg-white/30 dark:bg-gray-700/50 p-3 rounded-xl border border-gray-200/50 dark:border-gray-600/50 shadow-sm">
                            <div class="p-2 bg-pink-100 dark:bg-pink-900 rounded-lg">
                                <CalendarIcon class="w-6 h-6 text-pink-600 dark:text-pink-300" />
                            </div>
                            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">Citas</h2>
                            <div class="ml-auto flex gap-2">
                                <Link v-if="events.length" :href="route('events.index', { patient_id: patient.id })"
                                    class="flex items-center justify-center size-9 rounded-lg bg-pink-500 text-white hover:bg-pink-600 transition-all transform hover:scale-105 shadow-md">
                                <EyeIcon class="size-5" />
                                </Link>
                                <AccessGate permission="event.create">
                                    <Link :href="route('events.create')" as="button"
                                        class="flex items-center justify-center size-9 rounded-lg bg-green-500 text-white hover:bg-green-600 transition-all transform hover:scale-105 shadow-md">
                                    <AddIcon class="size-5" />
                                    </Link>
                                </AccessGate>
                            </div>
                        </div>

                        <!-- Lista de citas -->
                        <div class="max-h-96 overflow-y-auto pr-2 space-y-3">
                            <div v-for="event in events" :key="event.id"
                                class="group relative bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">


                                <!-- Contenido de la cita -->
                                <div class="p-4">
                                    <div class="flex items-start mb-3">
                                        <div class="flex-1">
                                            <div class="flex justify-between">
                                                <h3
                                                    class="text-lg font-bold text-gray-800 dark:text-gray-100 group-hover:text-pink-600 dark:group-hover:text-pink-300 transition-colors">
                                                    #{{ event.id }} - {{ event.title }}
                                                </h3>
                                                <span class="  text-sm text-white font-medium px-2 py-1 rounded-full"
                                                    :class="{
                                                        'bg-green-500': event.attended,
                                                        'bg-red-500': !event.attended
                                                    }">
                                                    {{ event.attended ? 'Atendido' : 'No Atendido' }}
                                                </span>
                                            </div>

                                            <div class="mt-2 grid grid-cols-2 gap-2 text-sm">
                                                <div class="flex items-center text-gray-600 dark:text-gray-300">
                                                    <CalendarDaysIcon class="w-4 h-4 mr-1 text-pink-500" />
                                                    <span>{{ event.date }}</span>
                                                </div>
                                                <div class="flex items-center text-gray-600 dark:text-gray-300">
                                                    <ClockIcon class="w-4 h-4 mr-1 text-pink-500" />
                                                    <span>{{ event.starttime }} - {{ event.endtime }}</span>
                                                </div>
                                                <div
                                                    class="col-span-2 flex items-center text-gray-600 dark:text-gray-300">
                                                    <UserIcon class="w-4 h-4 mr-1 text-pink-500" />
                                                    <span>Dr. {{ event.doctor.name }} {{ event.doctor.last_name
                                                    }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Acciones -->
                                    <div
                                        class="flex items-center justify-between mt-4 pt-3 border-t border-gray-100 dark:border-gray-600">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" @click="AttendEvent(event)" :checked="event.attended"
                                                class="sr-only peer">
                                            <div
                                                class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-pink-300 dark:peer-focus:ring-pink-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-500 peer-checked:bg-pink-500 dark:peer-checked:bg-pink-500">
                                            </div>
                                            <span class="ms-3 text-sm font-medium"
                                                :class="event.attended ? 'text-green-500' : 'text-red-500'">
                                                {{ event.attended ? 'Atendido' : 'No Atendido' }}
                                            </span>
                                        </label>

                                        <div class="flex gap-2">
                                            <Link v-if="event.active" :href="route('events.edit', event)"
                                                class="flex items-center justify-center size-8 rounded-lg bg-yellow-500 text-white hover:bg-yellow-600 transition-all transform hover:scale-110 shadow-md">
                                            <EditIcon class="size-4" />
                                            </Link>
                                            <DangerButton v-if="event.active" @click="deleteEvent(event)"
                                                class="flex items-center justify-center size-8 rounded-lg bg-red-500 text-white hover:bg-red-600 transition-all transform hover:scale-110 shadow-md">
                                                <DeleteIcon class="h-5 w-5" />
                                            </DangerButton>
                                            <button v-else @click="restoreEvent(event)"
                                                class="flex items-center justify-center size-8 rounded-lg bg-green-500 text-white hover:bg-green-600 transition-all transform hover:scale-110 shadow-md">
                                                <RestoreIcon class="size-4" />
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mensaje cuando no hay citas -->
                        <div v-if="!events.length" class="text-center py-6">
                            <div class="inline-flex flex-col items-center">
                                <CalendarIcon class="w-12 h-12 text-gray-400 dark:text-gray-500 mb-2" />
                                <p class="text-gray-500 dark:text-gray-400 font-medium">No hay citas programadas</p>
                                <p class="text-sm text-gray-400 dark:text-gray-500">Agrega una nueva cita para comenzar
                                </p>
                            </div>
                        </div>
                    </div>
                </AccessGate>
                <!-- Presupuestos -->
                <!-- Presupuestos - Diseño Mejorado -->
                <AccessGate permission="budget.view">
                    <div
                        class="bg-gradient-to-br from-pink-50 to-indigo-50 dark:from-gray-800 dark:to-gray-900 p-6 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
                        <!-- Encabezado con efecto de vidrio -->
                        <div
                            class="mb-6 flex items-center gap-3 backdrop-blur-sm bg-white/30 dark:bg-gray-700/50 p-3 rounded-xl border border-gray-200/50 dark:border-gray-600/50 shadow-sm">
                            <div class="p-2 bg-pink-100 dark:bg-pink-900 rounded-lg">
                                <DocumentMoney class="w-6 h-6 text-pink-600 dark:text-pink-300" />
                            </div>
                            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">Presupuestos</h2>
                            <div class="ml-auto flex gap-2">

                                <Link v-if="budgets" :href="route('budgets.index', { patient_id: patient.id })"
                                    class="flex items-center justify-center size-9 rounded-lg bg-pink-500 text-white hover:bg-pink-600 transition-all transform hover:scale-105 shadow-md">
                                <EyeIcon class="size-5" />
                                </Link>
                                <AccessGate permission="budget.create">
                                    <Link :href="route('budgets.create')" as="button"
                                        class="flex items-center justify-center size-9 rounded-lg bg-green-500 text-white hover:bg-green-600 transition-all transform hover:scale-105 shadow-md">
                                    <AddIcon class="size-5" />
                                    </Link>
                                </AccessGate>
                            </div>
                        </div>

                        <!-- Lista de presupuestos -->
                        <div class="max-h-96 overflow-y-auto pr-2 space-y-3">
                            <div v-for="budget in budgets" :key="budget.id"
                                class="group relative bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">


                                <!-- Contenido del presupuesto -->
                                <div class="p-4">
                                    <div class="flex items-start mb-3">
                                        <div class="flex-1">
                                            <div class="flex justify-between">
                                                <h3
                                                    class="text-lg font-bold text-gray-800 dark:text-gray-100 group-hover:text-pink-600 dark:group-hover:text-pink-300 transition-colors">
                                                    Presupuesto #{{ budget.id }}
                                                </h3>
                                            </div>


                                            <!-- Info básica en columnas -->
                                            <div class="mt-2 grid grid-cols-2 gap-2 text-sm">
                                                <div class="flex items-center text-gray-600 dark:text-gray-300">
                                                    <CashIcon class="w-4 h-4 mr-1 text-pink-500" />
                                                    <span>{{ budget.type }}</span>
                                                </div>
                                                <div class="flex items-center text-gray-600 dark:text-gray-300">
                                                    <CalendarDaysIcon class="w-4 h-4 mr-1 text-pink-500" />
                                                    <span>{{ formatDate(budget.created_at) }}</span>
                                                </div>
                                                <div class="flex items-center text-gray-600 dark:text-gray-300">
                                                    <UserIcon class="w-4 h-4 mr-1 text-pink-500" />
                                                    <span>Dr. {{ budget.doctor.name }} {{ budget.doctor.last_name
                                                        }}</span>
                                                </div>
                                                <div class="flex items-center text-gray-600 dark:text-gray-300">
                                                    <CurrencyDolarIcon class="w-4 h-4 mr-1 text-pink-500" />
                                                    <span class="font-bold">
                                                        Total: {{ new Intl.NumberFormat('es-DO', {
                                                            style:
                                                                'currency', currency: 'DOP'
                                                        }).format(budget.total
                                                            || 0) }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Acordeón para detalles -->
                                            <div class="mt-3">
                                                <details class="border-t border-gray-100 dark:border-gray-600 pt-2">
                                                    <summary
                                                        class="flex items-center cursor-pointer text-sm font-medium text-pink-600 dark:text-pink-400">
                                                        <span>Ver tratamientos</span>
                                                        <ChevronDownIcon
                                                            class="w-4 h-4 ml-1 transition-transform duration-200 group-open:rotate-180" />
                                                    </summary>
                                                    <div class="mt-2 space-y-2">
                                                        <div v-for="(detail, index) in budget.budgetdetail"
                                                            :key="detail.id"
                                                            class="bg-gray-50 dark:bg-gray-600 p-2 rounded-lg">
                                                            <p class="font-medium">{{ index + 1 }}. {{
                                                                detail.procedure.name
                                                                }}</p>
                                                            <div class="grid grid-cols-2 gap-1 text-xs">
                                                                <span>Cantidad: {{ detail.quantity }}</span>
                                                                <span>
                                                                    Precio: {{ new Intl.NumberFormat('es-DO', {
                                                                        style:
                                                                            'currency', currency: 'DOP'
                                                                    }).format(detail.amount
                                                                        || 0) }}
                                                                </span>

                                                                <span>Descuento: {{ detail.discount }}%</span>
                                                                <span>
                                                                    Total: {{ new Intl.NumberFormat('es-DO', {
                                                                        style:
                                                                            'currency', currency: 'DOP'
                                                                    }).format(detail.total
                                                                        || 0) }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </details>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Acciones -->
                                    <div
                                        class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100 dark:border-gray-600">
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            <span v-if="budget.emission_date">Emitido: {{
                                                formatDate(budget.emission_date)
                                                }}</span>
                                            <span v-if="budget.expiration_date"> | Vence: {{
                                                formatDate(budget.expiration_date)
                                                }}</span>
                                        </div>

                                        <div class="flex gap-2">
                                            <Link v-if="budget.active" :href="route('budgets.edit', budget)"
                                                class="flex items-center justify-center size-8 rounded-lg bg-yellow-500 text-white hover:bg-yellow-600 transition-all transform hover:scale-110 shadow-md">
                                            <EditIcon class="size-4" />
                                            </Link>
                                            <DangerButton v-if="budget.active" @click="deleteBudget(budget)"
                                                class="flex items-center justify-center size-8 rounded-lg bg-red-500 text-white hover:bg-red-600 transition-all transform hover:scale-110 shadow-md">
                                                <DeleteIcon class="size-4" />
                                            </DangerButton>
                                            <button v-else @click="restoreBudget(budget)"
                                                class="flex items-center justify-center size-8 rounded-lg bg-green-500 text-white hover:bg-green-600 transition-all transform hover:scale-110 shadow-md">
                                                <RestoreIcon class="size-4" />
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mensaje cuando no hay presupuestos -->
                        <div v-if="!budgets.length" class="text-center py-6">
                            <div class="inline-flex flex-col items-center">
                                <DocumentTextIcon class="w-12 h-12 text-gray-400 dark:text-gray-500 mb-2" />
                                <p class="text-gray-500 dark:text-gray-400 font-medium">No hay presupuestos registrados
                                </p>
                                <p class="text-sm text-gray-400 dark:text-gray-500">Crea un nuevo presupuesto para
                                    comenzar
                                </p>
                            </div>
                        </div>
                    </div>
                </AccessGate>

                <!-- Recibos -->
                <!-- Recibos - Diseño Mejorado -->
                <AccessGate permission="bill.view">
                    <div
                        class="bg-gradient-to-br from-pink-50 to-indigo-50 dark:from-gray-800 dark:to-gray-900 p-6 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
                        <!-- Encabezado con efecto de vidrio -->
                        <div
                            class="mb-6 flex items-center gap-3 backdrop-blur-sm bg-white/30 dark:bg-gray-700/50 p-3 rounded-xl border border-gray-200/50 dark:border-gray-600/50 shadow-sm">
                            <div class="p-2 bg-pink-100 dark:bg-pink-900 rounded-lg">
                                <DocumentMoney class="w-6 h-6 text-pink-600 dark:text-pink-300" />
                            </div>
                            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">Recibos</h2>
                            <div class="ml-auto flex gap-2">

                                <Link v-if="bills.length" :href="route('bills.index', { patient_id: patient.id })"
                                    class="flex items-center justify-center size-9 rounded-lg bg-pink-500 text-white hover:bg-pink-600 transition-all transform hover:scale-105 shadow-md">
                                <EyeIcon class="size-5" />
                                </Link>
                                <AccessGate permission="bill.create">
                                    <Link :href="route('bills.create')" as="button"
                                        class="flex items-center justify-center size-9 rounded-lg bg-green-500 text-white hover:bg-green-600 transition-all transform hover:scale-105 shadow-md">
                                    <AddIcon class="size-5" />
                                    </Link>
                                </AccessGate>
                            </div>
                        </div>

                        <!-- Lista de Recibos -->
                        <div class="max-h-96 overflow-y-auto pr-2 space-y-3">
                            <div v-for="bill in bills" :key="bill.id"
                                class="group relative bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">


                                <!-- Contenido del Factura -->
                                <div class="p-4">
                                    <div class="flex items-start mb-3">
                                        <div class="flex-1">
                                            <div class="flex justify-between">
                                                <h3
                                                    class="text-lg font-bold text-gray-800 dark:text-gray-100 group-hover:text-pink-600 dark:group-hover:text-pink-300 transition-colors">
                                                    Factura #{{ bill.id }}
                                                </h3>
                                                <span class="  text-sm font-medium px-2 text-white py-1 rounded-full"
                                                    :class="{
                                                        'bg-green-500  ': bill.type === 'Contado' || bill.total === 0,
                                                        'bg-red-500': bill.type === 'Credito' && bill.total > 0
                                                    }">
                                                    {{ bill.type === 'Contado' ? 'PAGADO' : (bill.total === 0 ?
                                                        'PAGADO'
                                                        :
                                                        'PENDIENTE') }}
                                                </span>

                                            </div>


                                            <!-- Info básica en columnas -->
                                            <div class="mt-2 grid grid-cols-2 gap-2 text-sm">
                                                <div class="flex items-center text-gray-600 dark:text-gray-300">
                                                    <CashIcon class="w-4 h-4 mr-1 text-pink-500" />
                                                    <span>{{ bill.type }}</span>
                                                </div>
                                                <div class="flex items-center text-gray-600 dark:text-gray-300">
                                                    <CalendarDaysIcon class="w-4 h-4 mr-1 text-pink-500" />
                                                    <span>{{ formatDate(bill.created_at) }}</span>
                                                </div>
                                                <div class="flex items-center text-gray-600 dark:text-gray-300">
                                                    <UserIcon class="w-4 h-4 mr-1 text-pink-500" />
                                                    <span>Dr. {{ bill.doctor.name }} {{ bill.doctor.last_name
                                                        }}</span>
                                                </div>
                                                <div class="flex items-center text-gray-600 dark:text-gray-300">
                                                    <CurrencyDolarIcon class="w-4 h-4 mr-1 text-pink-500" />
                                                    <span class="font-bold">
                                                        Total: {{ new Intl.NumberFormat('es-DO', {
                                                            style:
                                                                'currency', currency: 'DOP'
                                                        }).format(bill.total
                                                            || 0) }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Acordeón para detalles -->
                                            <div class="mt-3">
                                                <details class="border-t border-gray-100 dark:border-gray-600 pt-2">
                                                    <summary
                                                        class="flex items-center cursor-pointer text-sm font-medium text-pink-600 dark:text-pink-400">
                                                        <span>Ver tratamientos</span>
                                                        <ChevronDownIcon
                                                            class="w-4 h-4 ml-1 transition-transform duration-200 group-open:rotate-180" />
                                                    </summary>
                                                    <div class="mt-2 space-y-2">
                                                        <div v-for="(detail, index) in bill.billdetail" :key="detail.id"
                                                            class="bg-gray-50 dark:bg-gray-600 p-2 rounded-lg">
                                                            <p class="font-medium">{{ index + 1 }}. {{
                                                                detail.procedure.name
                                                                }}</p>
                                                            <div class="grid grid-cols-2 gap-1 text-xs">
                                                                <span>Cantidad: {{ detail.quantity }}</span>
                                                                <span>
                                                                    Precio: {{ new Intl.NumberFormat('es-DO', {
                                                                        style:
                                                                            'currency', currency: 'DOP'
                                                                    }).format(detail.amount
                                                                        || 0) }}
                                                                </span>
                                                                <span>Descuento: {{ detail.discount }}%</span>
                                                                <span>
                                                                    Total: {{ new Intl.NumberFormat('es-DO', {
                                                                        style:
                                                                            'currency', currency: 'DOP'
                                                                    }).format(detail.total
                                                                        || 0) }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </details>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Acciones -->
                                    <div
                                        class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100 dark:border-gray-600">
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            <span v-if="bill.emission_date">Emitido: {{
                                                formatDate(bill.emission_date)
                                                }}</span>
                                            <span v-if="bill.expiration_date"> | Vence: {{
                                                formatDate(bill.expiration_date)
                                                }}</span>
                                        </div>

                                        <div class="flex gap-2">
                                            <Link v-if="bill.active" :href="route('bills.edit', bill)"
                                                class="flex items-center justify-center size-8 rounded-lg bg-yellow-500 text-white hover:bg-yellow-600 transition-all transform hover:scale-110 shadow-md">
                                            <EditIcon class="size-4" />
                                            </Link>
                                            <DangerButton v-if="bill.active" @click="deleteBill(bill)"
                                                class="flex items-center justify-center size-8 rounded-lg bg-red-500 text-white hover:bg-red-600 transition-all transform hover:scale-110 shadow-md">
                                                <DeleteIcon class="size-4" />
                                            </DangerButton>
                                            <button v-else @click="restoreBill(bill)"
                                                class="flex items-center justify-center size-8 rounded-lg bg-green-500 text-white hover:bg-green-600 transition-all transform hover:scale-110 shadow-md">
                                                <RestoreIcon class="size-4" />
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mensaje cuando no hay Recibos -->
                        <div v-if="!bills.length" class="text-center py-6">
                            <div class="inline-flex flex-col items-center">
                                <DocumentTextIcon class="w-12 h-12 text-gray-400 dark:text-gray-500 mb-2" />
                                <p class="text-gray-500 dark:text-gray-400 font-medium">No hay Recibos registrados
                                </p>
                                <p class="text-sm text-gray-400 dark:text-gray-500">Crea un nuevo Factura para
                                    comenzar
                                </p>
                            </div>
                        </div>
                    </div>
                </AccessGate>

            </div>
            <AccessGate permission="odontograph.view">
                <div class="space-y-6">
                    <!-- Encabezado Mejorado -->
                    <div
                        class="flex flex-col mt-4 md:flex-row justify-between items-start md:items-center gap-4 p-4 bg-gradient-to-r from-pink-50 to-indigo-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-pink-100 dark:bg-pink-900 rounded-full">
                                <TeethIcon class="w-6 h-6 text-pink-600 dark:text-pink-400" />
                            </div>
                            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Odontogramas</h1>
                        </div>

                        <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto">

                            <div class="relative flex-grow lg:w-96 sm:w-64">
                                <SearchIcon
                                    class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 sm:top-5 text-gray-400" />
                                <input @input="submitFilters()" v-model="filters.search" type="text"
                                    placeholder="Buscar odontogramas..."
                                    class="w-full pl-10 pr-4 py-2 rounded-lg border-0 bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-pink-500" />
                            </div>

                            <div class="flex gap-2">
                                <AccessGate permission="odontograph.delete">
                                    <button @click="toggleshowDeleted()"
                                        class="flex items-center gap-2 px-4 py-2 rounded-lg bg-red-500 from-rose-500 to-pink-500 text-white font-medium shadow-sm hover:from-rose-600 hover:to-pink-600 transition-all sm:text-hidden">
                                        <DeleteIcon class="w-5 h-5" />
                                        <span class="lg:inline sm:hidden ">{{ !form.showDeleted ? 'Ocultar' : 'Mostrar'
                                            }}
                                            Eliminados</span>
                                    </button>
                                </AccessGate>
                                <AccessGate permission="odontograph.create">
                                    <Link v-if="patient.active" :href="route('odontographs.create', patient)"
                                        class="flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-500 from-blue-500 to-indigo-500 text-white font-medium shadow-sm hover:from-blue-600 hover:to-indigo-600 transition-all">
                                    <AddIcon class="w-5 h-5" />
                                    <span class="lg:inline sm:hidden">Nuevo Odontograma</span>
                                    </Link>
                                </AccessGate>
                            </div>
                        </div>
                    </div>

                    <!-- Lista de Odontogramas -->
                    <div class="space-y-4">
                        <div v-for="item in odontograph" :key="item.id"
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700 transition-all hover:shadow-lg">
                            <!-- Encabezado del Odontograma -->
                            <div
                                class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="p-2 bg-pink-100 dark:bg-pink-900/30 rounded-lg">
                                            <DocumentTextIcon class="w-6 h-6 text-pink-600 dark:text-pink-400" />
                                        </div>
                                        <div>
                                            <h2 class="font-semibold text-gray-800 dark:text-white">Odontograma #{{
                                                item.id
                                                }}</h2>
                                            <div
                                                class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                                <UserIcon class="w-4 h-4" />
                                                <span>Dr. {{ item.doctor.name }} {{ item.doctor.last_name }}</span>
                                                <span class="mx-1">•</span>
                                                <CalendarIcon class="w-4 h-4" />
                                                <span>{{ formatDate(item.created_at) }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex gap-2">
                                        <Link v-if="item.active" :href="route('odontographs.edit', item)"
                                            class="p-2 rounded-lg bg-pink-100 dark:bg-pink-900/30 text-pink-600 dark:text-pink-400 hover:bg-pink-200 dark:hover:bg-pink-800/50 transition-colors"
                                            title="Editar">
                                        <EditIcon class="w-5 h-5" />
                                        </Link>

                                        <DangerButton v-if="item.active" @click="deleteOdontograph(item)"
                                            class="p-2 rounded-lg bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-800/50 transition-colors"
                                            title="Eliminar">
                                            <DeleteIcon class="w-5 h-5" />
                                        </DangerButton>

                                        <button v-else @click="restoreOdontograph(item)"
                                            class="p-2 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-800/50 transition-colors"
                                            title="Restaurar">
                                            <RestoreIcon class="w-5 h-5" />
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Visualización del Odontograma -->
                            <div class="p-4">
                                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-3">PROCEDIMIENTOS
                                    REGISTRADOS
                                </h3>

                                <!-- Odontograma Superior -->
                                <div class="mb-6">
                                    <div class="grid grid-cols-16 gap-1.5 mb-2">
                                        <div v-for="tooth in upperTeeth" :key="tooth"
                                            @click="selectToothInView(item, tooth)"
                                            class="relative p-1 rounded-md border border-gray-200 dark:border-gray-700 text-center cursor-pointer transition-all hover:shadow-md"
                                            :class="toothClass(item.data[tooth])">
                                            <div class="font-bold text-gray-800 dark:text-white text-sm">{{ tooth }}
                                            </div>

                                            <div class="flex justify-center gap-1 mt-1">
                                                <template v-if="item.data[tooth]">
                                                    <span
                                                        v-for="([ proc], index) in Object.entries(item.data[tooth]).slice(0, 3)"
                                                         class="inline-block w-3 h-3 rounded-full "
                                                        :class="procedureStyles[proc]" :title="` ${proc}`">

                                                    </span>
                                                </template>
                                                <template v-else>
                                                    <span class="text-gray-400 text-xs">—</span>
                                                </template>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <!-- Odontograma Inferior -->
                                <div>
                                    <div class="grid grid-cols-16 gap-1.5">
                                        <div v-for="tooth in lowerTeeth" :key="tooth"
                                            @click="selectToothInView(item, tooth)"
                                            class="relative p-1 rounded-md border border-gray-200 dark:border-gray-700 text-center cursor-pointer transition-all hover:shadow-md"
                                            :class="toothClass(item.data[tooth])">
                                            <div class="font-bold text-gray-800 dark:text-white text-sm">{{ tooth }}
                                            </div>

                                            <div class="flex justify-center gap-1 mt-1">
                                                <template v-if="item.data[tooth]">
                                                    <span
                                                        v-for="([ proc], index) in Object.entries(item.data[tooth]).slice(0, 3)"
                                                         class="inline-block w-3 h-3  rounded-full"
                                                        :class="procedureStyles[proc]" :title="` ${proc}`">

                                                    </span>
                                                </template>
                                                <template v-else>
                                                    <span class="text-gray-400 text-xs">—</span>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Estado vacío -->
                        <div v-if="!odontograph.length"
                            class="flex flex-col items-center justify-center p-12 bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-300 dark:border-gray-700 text-center">
                            <SearchIcon class="w-16 h-16 text-gray-400 dark:text-gray-500 mb-4" />
                            <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300">No se encontraron
                                odontogramas
                            </h3>
                            <p class="text-gray-500 dark:text-gray-400 mt-1 max-w-md">
                                No hay registros de odontogramas para este paciente. Crea uno nuevo para comenzar.
                            </p>
                            <AccessGate permission="odontograph.create">
                                <Link v-if="patient.active" :href="route('odontographs.create', patient)"
                                    class="mt-4 flex items-center gap-2 px-4 py-2 rounded-lg bg-pink-500 from-pink-500 to-indigo-500 text-white font-medium shadow-sm hover:from-pink-600 hover:to-indigo-600 transition-all">
                                <AddIcon class="w-5 h-5" />
                                <span>Crear primer odontograma</span>
                                </Link>
                            </AccessGate>
                        </div>
                    </div>


                    <!-- Modal de Detalle del Diente -->
                    <div v-if="selectedToothInfo && selectedOdontograph"
                        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 transition-opacity">
                        <div
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-md transform transition-all">
                            <div
                                class="p-5 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                                    {{ toothNames[selectedToothInfo.tooth] || selectedToothInfo.tooth }}
                                </h2>
                                <button @click="closeToothDetail"
                                    class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                    <XIcon class="w-6 h-6" />
                                </button>
                            </div>

                            <div class="p-5 space-y-3">
                                <div v-for="(proc, zone) in selectedToothInfo.zones" :key="zone"
                                    class="flex items-start gap-4 p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                                    <div class="flex-shrink-0">
                                        <span
                                            class="flex items-center justify-center w-8 h-8 rounded-full text-white font-bold"
                                            :class="{
                                                'bg-gray-800': zone.charAt(0) === 'O',
                                                'bg-red-500': zone.charAt(0) === 'D',
                                                'bg-green-500': zone.charAt(0) === 'M',
                                                'bg-yellow-500': zone.charAt(0) === 'L',
                                                'bg-purple-500': zone.charAt(0) === 'V',
                                            }">
                                            {{ zone.charAt(0) }}
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-800 dark:text-white">{{ zone }}</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ proc }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-5 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                                <button @click="closeToothDetail"
                                    class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    Cerrar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </AccessGate>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Breadcrumb from '@/Components/BreadCrumb.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link } from '@inertiajs/vue3';
import EditIcon from '@/Components/Icons/EditIcon.vue';
import AddIcon from '@/Components/Icons/AddIcon.vue';
import DeleteIcon from '@/Components/Icons/DeleteIcon.vue';
import PrintIcon from '@/Components/Icons/PrintIcon.vue';
import TeethIcon from '@/Components/Icons/TeethIcon.vue';
import DangerButton from '@/Components/DangerButton.vue';
import DoctorIcon from '@/Components/Icons/DoctorIcon.vue';
import UserIcon from '@/Components/Icons/UserIcon.vue';
import DocumentMoney from '@/Components/Icons/DocumentMoney.vue';
import { markRaw } from 'vue';
import RestoreIcon from '@/Components/Icons/RestoreIcon.vue';
import CalendarIcon from '@/Components/Icons/CalendarIcon.vue';
import MedicalHistoryIcon from '@/Components/Icons/MedicalHistoryIcon.vue';
import Molar from '@/Components/Icons/Teeths/Molar.vue';
import ChevronDownIcon from '@/Components/Icons/ChevronDownIcon.vue';
import ChevronUpIcon from '@/Components/Icons/ChevronUpIcon.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import EyeIcon from '@/Components/Icons/EyeIcon.vue';
import DocumentTextIcon from '@/Components/Icons/DocumentTextIcon.vue';
import SearchIcon from '@/Components/Icons/SearchIcon.vue';
import XIcon from '@/Components/Icons/XIcon.vue';
import ClockIcon from '@/Components/Icons/ClockIcon.vue';
import CalendarDaysIcon from '@/Components/Icons/CalendarDaysIcon.vue';
import HandThumbUp from '@/Components/Icons/HandThumbUp.vue';
import HandThumbDown from '@/Components/Icons/HandThumbDown.vue';
import CashIcon from '@/Components/Icons/CashIcon.vue';
import CurrencyDolarIcon from '@/Components/Icons/CurrencyDolarIcon.vue';
import CardIcon from '@/Components/Icons/CardIcon.vue';
import PillIcon from '@/Components/Icons/PillIcon.vue';
import AccessGate from '@/Components/AccessGate.vue';

export default {
    props: {
        patient: Object,
        odontograph: Object,
        bills: Object,
        filters: Object,
        events: Object,
        budgets: Object,
        prescription: Object
    },
    components: {
        AuthenticatedLayout,
        Breadcrumb,
        PrimaryButton,
        DangerButton,
        Link,
        EditIcon,
        AddIcon,
        PillIcon,
        DeleteIcon,
        DoctorIcon,
        PrintIcon,
        TeethIcon,
        UserIcon,
        DocumentMoney,
        RestoreIcon,
        CalendarIcon,
        MedicalHistoryIcon,
        Molar,
        ChevronDownIcon,
        ChevronUpIcon,
        SecondaryButton,
        EyeIcon,
        DocumentTextIcon,
        SearchIcon,
        XIcon,
        ClockIcon,
        CalendarDaysIcon,
        HandThumbUp,
        HandThumbDown,
        CashIcon,
        CurrencyDolarIcon,
        CardIcon,
        AccessGate,
        Head
    },
    data() {
        return {
            form: {
                showDeleted: this.filters?.showDeleted || true,
                search: this.filters?.search || ''
            },
            toothUPOrder: [
                "18", "17", "16", "15", "14", "13", "12", "11",
                "55", "54", "53", "52", "51",
            ],
            toothDownOrder: ["21", "22", "23", "24", "25", "26", "27", "28", "61", "66", "63", "64", "65"],
            timeout: 3000,
            crumbs: [
                { icon: markRaw(UserIcon), label: 'Pacientes', to: route('patients.index') },
                { label: this.patient.first_name + ' ' + this.patient.last_name }
            ],
            activeIndex: null,
            upperTeeth: [
                18, 17, 16, 15, 14, 13, 12, 11,
                21, 22, 23, 24, 25, 26, 27, 28
            ],
            lowerTeeth: [
                48, 47, 46, 45, 44, 43, 42, 41,
                31, 32, 33, 34, 35, 36, 37, 38
            ],
            upperTeethChild: [
                55, 54, 53, 52, 51, 61, 62, 63, 64, 65
            ],
            lowerTeethChild: [
                85, 84, 83, 82, 81, 71, 72, 73, 74, 75
            ],
            selectedOdontograph: null,
            selectedToothInfo: null,
            expandedIndexes: [],
            toothNames: {
                11: "Incisivo central superior derecho",
                12: "Incisivo lateral superior derecho",
                13: "Canino superior derecho",
                14: "1er premolar superior derecho",
                15: "2do premolar superior derecho",
                16: "1er molar superior derecho",
                17: "2do molar superior derecho",
                18: "3er molar superior derecho",
                21: "Incisivo central superior izquierdo",
                22: "Incisivo lateral superior izquierdo",
                23: "Canino superior izquierdo",
                24: "1er premolar superior izquierdo",
                25: "2do premolar superior izquierdo",
                26: "1er molar superior izquierdo",
                27: "2do molar superior izquierdo",
                28: "3er molar superior izquierdo",
                41: "Incisivo central inferior derecho",
                42: "Incisivo lateral inferior derecho",
                43: "Canino inferior derecho",
                44: "1er premolar inferior derecho",
                45: "2do premolar inferior derecho",
                46: "1er molar inferior derecho",
                47: "2do molar inferior derecho",
                48: "3er molar inferior derecho",
                31: "Incisivo central inferior izquierdo",
                32: "Incisivo lateral inferior izquierdo",
                33: "Canino inferior izquierdo",
                34: "1er premolar inferior izquierdo",
                35: "2do premolar inferior izquierdo",
                36: "1er molar inferior izquierdo",
                37: "2do molar inferior izquierdo",
                38: "3er molar inferior izquierdo",
            },
            procedureStyles: {
                Cariado: "bg-red-300",
                Restauración: "bg-blue-300",
                Extracción: "",
                Puente: "border-b-4 border-gray-300",
                Corona: "opacity-75 bg-yellow-300",
                CoronaAplicada: "bg-blue-300",
                Ausencia: "relative after:content-['X'] after:text-red-600 after:font-bold after:absolute after:inset-0 after:flex after:items-center after:justify-center",
                Implante: "bg-[repeating-linear-gradient(135deg,gray,gray_5px,transparent_5px,transparent_10px)]",
                Endodoncia: "relative after:content-['•'] after:text-red-600 after:absolute after:top-1/2 after:left-1/2 after:-translate-x-1/2 after:-translate-y-1/2",
                EndodonciaAplicada: "relative after:content-['•'] after:text-blue-600 after:absolute after:top-1/2 after:left-1/2 after:-translate-x-1/2 after:-translate-y-1/2",
            }

        }
    },



    methods: {
        selectToothInView(item, tooth) {
            this.selectedOdontograph = item
            const zones = item.data[tooth] || null
            if (!zones) {
                this.selectedToothInfo = null
            } else {
                this.selectedToothInfo = { tooth, zones }
            }
        },
        toothClass(procedures) {
            if (!procedures) return "";

            const values = Object.values(procedures);

            if (values.includes("Corona")) return "bg-yellow-300";
            if (values.includes("Cariado")) return "bg-red-300";
            if (values.includes("Restauración")) return "bg-blue-300";
            if (values.includes("CoronaAplicada")) return "bg-blue-300";

            if (values.includes("Ausencia"))
                return "relative after:content-['X'] after:text-red-600 after:font-bold after:absolute after:inset-0 after:flex after:items-center after:justify-center";

            if (values.includes("Endodoncia"))
                return "relative after:content-['•'] after:text-red-600 after:absolute after:top-1/2 after:left-1/2 after:-translate-x-1/2 after:-translate-y-1/2";

            if (values.includes("EndodonciaAplicada"))
                return "relative after:content-['•'] after:text-blue-600 after:absolute after:top-1/2 after:left-1/2 after:-translate-x-1/2 after:-translate-y-1/2";

            if (values.includes("Extracción"))
                return "bg-gray-300";
            if (values.includes("Puente"))
                return "border-b-4 border-red-300";

            return "";
        },

        closeToothDetail() {
            this.selectedToothInfo = null
            this.selectedOdontograph = null
        },
        formatDate(date) {
            if (!date) return '';
            const d = new Date(date);
            return d.toLocaleDateString('es-DO', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
            });
        },
        formatNumber(n) {
            return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
        deletePatient(id) {
            this.$inertia.delete(route('patients.destroy', id),);
        },
        restorePatient() {
            this.$inertia.put(route('patients.update', this.patient.id), {
                active: true
            },
            );
        },
        openAccordion(index) {
            this.activeIndex = this.activeIndex === index ? null : index;
        },

        deleteOdontograph(id) {
            this.$inertia.delete(route('odontographs.destroy', id),);
        },
        restoreOdontograph(id) {
            this.$inertia.put(route('odontographs.update', id), {
                active: true
            },
            );
        },
        toggleDetail(index) {
            const i = this.expandedIndexes.indexOf(index);
            if (i > -1) {
                this.expandedIndexes.splice(i, 1);
            } else {
                this.expandedIndexes.push(index);
            }
        },
        deleteEvent(id) {
            this.$inertia.delete(route('events.destroy', id));
        },
        restoreEvent(id) {
            this.$inertia.put(route('events.update', id),
                { active: true },
            );
        },
        AttendEvent(event) {
            event.attended = !event.attended;
            this.$inertia.put(route('events.update', event.id),
                { attended: event.attended },
            );
        },
        deleteBudget(id) {
            this.$inertia.delete(route('budgets.destroy', id));
        },
        restoreBudget(id) {
            this.$inertia.put(route('budgets.update', id),
                { active: true },
            );
        },
        deleteBill(id) {
            this.$inertia.delete(route('bills.destroy', id));
        },
        restoreBill(id) {
            this.$inertia.put(route('bills.update', id),
                { active: true },
            );
        },
        toggleshowDeleted() {
            this.form.showDeleted = !this.form.showDeleted;
            this.submitFilters();
        },
        submitFilters() {
            if (this.timeout) {
                clearTimeout(this.timeout);
            }
            this.form.search = this.filters.search
            this.timeout = setTimeout(() => {
                this.$inertia.get(route('patients.show', this.patient), this.form, {
                    preserveState: true,
                    preserveScroll: true,
                    replace: true
                });
            }, 300);
        },
        enter(el) {
            el.style.height = '0';
            el.style.opacity = '0';
            const height = el.scrollHeight;
            setTimeout(() => {
                el.style.transition = 'all 0.3s ease';
                el.style.height = height + 'px';
                el.style.opacity = '1';
            });
        },
        leave(el) {
            el.style.height = el.scrollHeight + 'px';
            el.style.opacity = '1';
            setTimeout(() => {
                el.style.height = el.scrollHeight + 'px';
                el.style.height = '0';
                el.style.opacity = '0';
            })
        },
        async print() {
            window.open(route('report.patient', {
                patient: this.patient
            }), '_blank');
        }

    },
};
</script>




<style scoped>
p span:first-child {
    color: #6b7280;
}

.fade-accordion-enter-active,
.fade-accordion-leave-active {
    transition: all 0.3s ease;
    overflow: hidden;
}

.fade-accordion-enter-from,
.fade-accordion-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}

.fade-accordion-enter-to,
.fade-accordion-leave-from {
    opacity: 1;
    transform: translateY(0);
}

.accordion-enter-active,
.accordion-leave-active {
    overflow: hidden;
}

.scroll-beauty::-webkit-scrollbar {
    width: 6px;
}

.scroll-beauty::-webkit-scrollbar-thumb {
    background-color: #3b82f6;
    /* azul */
    border-radius: 6px;
}

.scroll-beauty::-webkit-scrollbar-track {
    background-color: #e5e7eb;
    /* gris claro */
}
</style>
