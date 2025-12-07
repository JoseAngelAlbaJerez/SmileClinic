<template>

    <Head :title="patient.first_name + ' ' + patient.last_name" />
    <AuthenticatedLayout>
        <template #header>
            <Breadcrumb :crumbs="crumbs" />
        </template>

        <div class="container mx-auto mb-5 dark:text-white bg-white dark:bg-gray-700 mt-5   rounded-2xl shadow-md">

            <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">

                    <!-- Header -->
                    <div
                        class="p-6 bg-pink-500  dark:bg-pink-600 rounded-t-2xl text-white shadow-inner flex justify-between items-center gap-3">
                        <div>
                            <h2 class="text-2xl font-bold">Información del Paciente</h2>
                            <p class="text-pink-100 text-sm mt-0.5">Detalles completos del paciente</p>
                        </div>
                        <!-- Botones -->
                        <div class="flex justify-end gap-3  ">

                            <template v-if="patient.active">

                                <button @click="print()"
                                    class="flex items-center gap-2 bg-green-500 hover:bg-green-600 px-4 py-2 rounded-lg text-white shadow">
                                    <PrintIcon />
                                </button>

                                <AccessGate permission="patient.update">
                                    <Link :href="route('patients.edit', patient.id)"
                                        class="flex items-center gap-2 bg-yellow-500 hover:bg-yellow-600 px-4 py-2 rounded-lg text-white shadow">
                                    <EditIcon />
                                    </Link>
                                </AccessGate>

                                <AccessGate permission="patient.delete">
                                    <DangerButton @click="deletePatient(patient.id)"
                                        class="flex items-center gap-2 bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg text-white shadow">
                                        <DeleteIcon />
                                    </DangerButton>
                                </AccessGate>

                            </template>

                            <template v-else>
                                <PrimaryButton @click="restorePatient(patient.id)"
                                    class="flex items-center gap-2 bg-green-500 hover:bg-green-600 px-4 py-2 rounded-lg text-white shadow">
                                    <RestoreIcon /> Restaurar
                                </PrimaryButton>
                            </template>

                        </div>

                    </div>

                    <!-- Contenido en Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">

                        <!-- Información Personal -->
                        <div
                            class="bg-white dark:bg-gray-700/40 p-5 rounded-xl shadow-sm border border-gray-100 dark:border-gray-600/40">

                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-pink-500"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                        clip-rule="evenodd" />
                                </svg>
                                Información Personal
                            </h3>

                            <div class="space-y-3 text-sm">

                                <div class="flex">
                                    <span class="w-32 font-medium text-gray-600 dark:text-gray-300">Nombre
                                        completo:</span>
                                    <span class="text-gray-800 dark:text-gray-100">{{ patient.first_name }} {{
                                        patient.last_name
                                        }}</span>
                                </div>

                                <div class="flex">
                                    <span class="w-32 font-medium text-gray-600 dark:text-gray-300">Cédula/DNI:</span>
                                    <span class="text-gray-800 dark:text-gray-100">{{ patient.DNI }}</span>
                                </div>

                                <div class="flex">
                                    <span class="w-32 font-medium text-gray-600 dark:text-gray-300">Teléfono:</span>
                                    <span class="text-gray-800 dark:text-gray-100">{{ patient.phone_number }}</span>
                                </div>

                                <div class="flex">
                                    <span class="w-32 font-medium text-gray-600 dark:text-gray-300">Fecha
                                        Nacimiento:</span>
                                    <span class="text-gray-800 dark:text-gray-100">
                                        {{ formatDate(patient.date_of_birth) }} ({{ patient.age }} años)
                                    </span>
                                </div>

                                <div class="flex">
                                    <span class="w-32 font-medium text-gray-600 dark:text-gray-300">Dirección:</span>
                                    <span class="text-gray-800 dark:text-gray-100">{{ patient.address }}</span>
                                </div>

                                <div class="flex">
                                    <span class="w-32 font-medium text-gray-600 dark:text-gray-300">Última
                                        visita:</span>
                                    <span class="text-gray-800 dark:text-gray-100">
                                        {{ latest_event?.title || "El paciente no ha agendado citas." }}
                                    </span>
                                </div>

                            </div>
                        </div>


                        <!-- Información Médica -->
                        <div
                            class="bg-white dark:bg-gray-700/40 p-5 rounded-xl shadow-sm border border-gray-100 dark:border-gray-600/40">

                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-pink-500"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M7 2a1 1 0 00-.707 1.707L7 4.414v3.758a1 1 0 01-.293.707l-4 4C.817 14.769 2.156 18 4.828 18h10.343c2.673 0 4.012-3.231 2.122-5.121l-4-4A1 1 0 0113 8.172V4.414l.707-.707A1 1 0 0013 2H7zm2 6.172V4h2v4.172a3 3 0 00.879 2.12l1.027 1.028a4 4 0 00-2.171.102l-.47.156a4 4 0 01-2.53 0l-.563-.187a1.993 1.993 0 00-.114-.035l1.063-1.063A3 3 0 009 8.172z"
                                        clip-rule="evenodd" />
                                </svg>
                                Información Médica
                            </h3>

                            <div class="space-y-4 text-sm">

                                <!-- Bloque reusable -->
                                <template v-for="item in [
                                    { label: 'Complicaciones médicas', key: 'complications', detail: 'complications_detail' },
                                    { label: 'Uso de medicamentos', key: 'drugs', detail: 'drugs_detail' },
                                    { label: 'Alergias conocidas', key: 'alergies', detail: 'alergies_detail' }
                                ]">
                                    <div
                                        class="bg-gray-100 dark:bg-gray-600/40 p-3 rounded-lg border border-gray-200 dark:border-gray-500/40">
                                        <div class="flex items-center gap-2">
                                            <span class="font-medium text-gray-800 dark:text-gray-100">{{ item.label
                                                }}:</span>
                                            <span :class="medicalHistory[item.key] ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                                : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'"
                                                class="text-xs px-2 py-1 rounded-full">
                                                {{ medicalHistory[item.key] ? 'Sí' : 'No' }}
                                            </span>
                                        </div>

                                        <p v-if="medicalHistory[item.detail]"
                                            class="text-gray-700 dark:text-gray-300 mt-1 pl-5 text-xs">
                                            <span class="font-medium">Detalle:</span> {{ medicalHistory[item.detail] }}
                                        </p>
                                    </div>
                                </template>

                            </div>
                        </div>

                    </div>


                    <!-- Recetas médicas -->
                    <AccessGate permission="prescription.view" class="p-5">
                        <div
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">

                            <!-- ENCABEZADO -->
                            <div
                                class="mb-6 flex items-center gap-3 backdrop-blur-sm bg-white/30 dark:bg-gray-700/50 p-4 rounded-xl border border-gray-200/50 dark:border-gray-600/50 shadow-sm">

                                <div class="p-2 bg-pink-100 dark:bg-pink-900 rounded-lg">
                                    <MedicalHistoryIcon class="w-6 h-6 text-pink-600 dark:text-cyan-300" />
                                </div>

                                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">
                                    Recetas Médicas
                                </h2>

                                <div class="ml-auto flex gap-2">
                                    <AccessGate permission="prescription.view">
                                        <Link :href="route('prescriptions.index', { patient_id: patient.id })"
                                            class="flex items-center justify-center size-9 rounded-xl bg-blue-500 text-white hover:bg-blue-600 transition-all transform hover:scale-105 shadow-md">
                                        <EyeIcon class="size-5" />
                                        </Link>
                                    </AccessGate>

                                    <AccessGate permission="prescription.create">
                                        <Link :href="route('prescriptions.create', { patient_id: patient.id })"
                                            as="button"
                                            class="flex items-center justify-center size-9 rounded-xl bg-pink-500 text-white hover:bg-pink-600 transition-all transform hover:scale-105 shadow-md">
                                        <AddIcon class="size-5" />
                                        </Link>
                                    </AccessGate>
                                </div>
                            </div>

                            <!-- LISTA -->
                            <div class="max-h-96 overflow-y-auto pr-2 space-y-3">
                                <div v-for="prescription in prescription" :key="prescription.id"
                                    class="group relative bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm hover:shadow-md transition-all duration-300">

                                    <div class="p-4">

                                        <!-- Encabezado → título y estado -->
                                        <div class="flex justify-between items-start mb-3">
                                            <h3
                                                class="text-lg font-bold text-gray-800 dark:text-gray-100 group-hover:text-pink-600 transition">
                                                Receta #{{ prescription.id }}
                                            </h3>


                                        </div>

                                        <!-- Info básica -->
                                        <div class="grid grid-cols-2 gap-3 text-sm text-gray-600 dark:text-gray-300">
                                            <div class="flex items-center">
                                                <CalendarDaysIcon class="w-4 h-4 mr-1 text-pink-500" />
                                                {{ formatDate(prescription.created_at) }}
                                            </div>

                                            <div class="flex items-center">
                                                <UserIcon class="w-4 h-4 mr-1 text-pink-500" />
                                                {{ prescription.doctor.first_name }} {{
                                                    prescription.doctor.last_name }}
                                            </div>
                                        </div>

                                        <!-- MEDICAMENTOS  -->
                                        <div class="mt-3">
                                            <button @click="toggleAccordion(prescription.id)"
                                                class="flex items-center cursor-pointer text-sm font-medium text-pink-600 dark:text-pink-400 select-none">
                                                Ver medicamentos

                                                <ChevronDownIcon class="w-4 h-4 ml-1 transition-transform duration-300"
                                                    :class="{ 'rotate-180': openPrescriptions[prescription.id] }" />
                                            </button>

                                            <!-- TRANSITION REAL -->
                                            <Transition @before-enter="beforeEnter" @enter="enter"
                                                @before-leave="beforeLeave" @leave="leave">
                                                <div v-show="openPrescriptions[prescription.id]"
                                                    class="overflow-hidden mt-2 space-y-3">
                                                    <div v-for="detail in prescription.prescriptions_details"
                                                        :key="detail.id"
                                                        class="bg-gray-50 dark:bg-gray-600 p-3 rounded-xl border border-gray-200 dark:border-gray-500 flex gap-3">

                                                        <div class="p-3 bg-pink-100 dark:bg-pink-900 rounded-lg">
                                                            <PillIcon
                                                                class="w-5 h-5 text-pink-600 dark:text-pink-300" />
                                                        </div>

                                                        <div class="flex-1">
                                                            <h4 class="font-bold text-gray-800 dark:text-gray-100">
                                                                {{ detail.drugs?.name }}
                                                            </h4>
                                                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                                                {{ detail.description }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </Transition>
                                        </div>



                                        <!-- FOOTER -->
                                        <div
                                            class="flex justify-between items-center border-t border-gray-100 dark:border-gray-600 mt-4 pt-3">
                                            <span :class="prescription.active ? 'text-green-500' : 'text-red-500'">
                                                {{ prescription.active ? 'Activa' : 'Inactiva' }}
                                            </span>

                                            <button @click="printPrescription(prescription)"
                                                class="flex items-center justify-center size-10 rounded-xl bg-green-500 text-white hover:bg-green-600 transition-all transform hover:scale-110 shadow-md">
                                                <PrintIcon class="size-4" />
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- SIN RECETAS -->
                            <div v-if="!prescription.length" class="text-center py-6 opacity-70">
                                <DocumentTextIcon class="w-12 h-12 text-gray-400 dark:text-gray-500 mx-auto mb-2" />
                                <p class="text-gray-500 dark:text-gray-400 font-semibold">No hay recetas médicas
                                    registradas
                                </p>
                                <p class="text-sm text-gray-400 dark:text-gray-500">Crea una nueva receta para comenzar
                                </p>
                            </div>

                        </div>
                    </AccessGate>


                    <AccessGate permission="event.view" class="p-5">
                        <div
                            class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">

                            <!-- Encabezado con Efecto de Vidrio -->
                            <div
                                class="mb-6 flex items-center gap-3 backdrop-blur-sm bg-white/30 dark:bg-gray-700/50 p-3 rounded-xl border border-gray-200/50 dark:border-gray-600/50 shadow-sm">
                                <div class="p-2 bg-pink-100 dark:bg-pink-900 rounded-lg">
                                    <CalendarIcon class="w-6 h-6 text-pink-600 dark:text-pink-300" />
                                </div>

                                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">Citas</h2>

                                <div class="ml-auto flex gap-2">
                                    <Link v-if="events.length" :href="route('events.index', { patient_id: patient.id })"
                                        class="flex items-center justify-center size-9 rounded-lg bg-blue-500 text-white hover:bg-blue-600 transition-all transform hover:scale-105 shadow-md">
                                    <EyeIcon class="size-5" />
                                    </Link>

                                    <AccessGate permission="event.create">
                                        <Link :href="route('events.create', { patient_id: patient.id })" as="button"
                                            class="flex items-center justify-center size-9 rounded-lg bg-pink-500 text-white hover:bg-pink-600 transition-all transform hover:scale-105 shadow-md">
                                        <AddIcon class="size-5" />
                                        </Link>
                                    </AccessGate>
                                </div>
                            </div>

                            <!-- Lista de Citas -->
                            <div class="max-h-96 overflow-y-auto pr-2 space-y-3">
                                <div v-for="event in events" :key="event.id"
                                    class="group relative bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">

                                    <div class="p-4">
                                        <div class="flex items-start mb-3">
                                            <div class="flex-1">
                                                <div class="flex justify-between">
                                                    <h3
                                                        class="text-lg font-bold text-gray-800 dark:text-gray-100 group-hover:text-pink-600 dark:group-hover:text-pink-300 transition-colors">
                                                        #{{ event.id }} - {{ event.title }}
                                                    </h3>

                                                    <!-- Estado -->
                                                    <span class="text-sm font-medium px-2 py-1 rounded-full text-white"
                                                        :class="event.attended ? 'bg-green-500' : 'bg-red-500'">
                                                        {{ event.attended ? 'Atendido' : 'No Atendido' }}
                                                    </span>
                                                </div>

                                                <!-- Información -->
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
                                                        <span> {{ event.doctor.first_name }} {{
                                                            event.doctor.last_name
                                                            }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Acciones -->
                                        <div
                                            class="flex items-center justify-between mt-4 pt-3 border-t border-gray-100 dark:border-gray-600">

                                            <!-- Switch de asistencia -->
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="checkbox" @click="AttendEvent(event)"
                                                    :checked="event.attended" class="sr-only peer">
                                                <div
                                                    class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-pink-300 dark:peer-focus:ring-pink-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:bg-pink-500 after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-500">
                                                </div>
                                                <span class="ms-3 text-sm font-medium"
                                                    :class="event.attended ? 'text-green-500' : 'text-red-500'">
                                                    {{ event.attended ? 'Atendido' : 'No Atendido' }}
                                                </span>
                                            </label>


                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Mensaje cuando no hay citas -->
                            <div v-if="!events.length" class="text-center py-6">
                                <div class="inline-flex flex-col items-center">
                                    <CalendarIcon class="w-12 h-12 text-gray-400 dark:text-gray-500 mb-2" />
                                    <p class="text-gray-500 dark:text-gray-400 font-medium">No hay citas programadas</p>
                                    <p class="text-sm text-gray-400 dark:text-gray-500">Agrega una nueva cita para
                                        comenzar
                                    </p>
                                </div>
                            </div>
                        </div>
                    </AccessGate>

                    <!-- Presupuestos -->
                    <AccessGate permission="budget.view" class="p-5">
                        <div
                            class="bg-white-50 dark:bg-gray-800 p-6 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">

                            <!-- Encabezado con efecto vidrio -->
                            <div
                                class="mb-6 flex items-center gap-3 backdrop-blur-sm bg-white/30 dark:bg-gray-700/50 p-3 rounded-xl border border-gray-200/50 dark:border-gray-600/50 shadow-sm">

                                <div class="p-2 bg-pink-100 dark:bg-pink-900 rounded-lg">
                                    <DocumentMoney class="w-6 h-6 text-pink-600 dark:text-pink-300" />
                                </div>

                                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">Presupuestos</h2>

                                <div class="ml-auto flex gap-2">
                                    <Link v-if="budgets.length"
                                        :href="route('budgets.index', { patient_id: patient.id })"
                                        class="flex items-center justify-center size-9 rounded-lg bg-blue-500 text-white hover:bg-blue-600 transition-all transform hover:scale-105 shadow-md">
                                    <EyeIcon class="size-5" />
                                    </Link>

                                    <AccessGate permission="budget.create">
                                        <Link :href="route('budgets.create', { patient_id: patient.id })" as="button"
                                            class="flex items-center justify-center size-9 rounded-lg bg-pink-500 text-white hover:bg-pink-600 transition-all transform hover:scale-105 shadow-md">
                                        <AddIcon class="size-5" />
                                        </Link>
                                    </AccessGate>
                                </div>
                            </div>

                            <!-- Lista -->
                            <div class="max-h-96 overflow-y-auto pr-2 space-y-3">

                                <div v-for="budget in budgets" :key="budget.id"
                                    class="group relative bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">

                                    <div
                                        class="bg-white dark:bg-gray-700/40 rounded-xl shadow-sm border border-gray-100 dark:border-gray-600/40 ">

                                        <div @click="toggleSection(budget.id, 'budgets')"
                                            class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 cursor-pointer items-center">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-3">
                                                    <div class="p-2 bg-pink-100 dark:bg-pink-900/30 rounded-lg">
                                                        <DocumentMoney
                                                            class="w-6 h-6 text-pink-600 dark:text-pink-400" />
                                                    </div>
                                                    <div>
                                                        <h2 class="font-semibold text-gray-800 dark:text-white">
                                                            Presupuesto # {{ budget.id }}
                                                        </h2>
                                                        <div
                                                            class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                                            <BuildingIcon class="w-4 h-4" />
                                                            <span>{{ budget.branch.name }}</span>
                                                            <UserIcon class="w-4 h-4" />
                                                            <span>{{ budget.doctor.first_name }} {{
                                                                budget.doctor.last_name
                                                                }}</span>
                                                            <span class="mx-1">•</span>
                                                            <CalendarIcon class="w-4 h-4" />
                                                            <span>{{ formatDate(budget.created_at) }}</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Icono chevron que rota -->
                                                <svg class="w-5 h-5 text-gray-600 dark:text-gray-300 transform transition-transform duration-300"
                                                    :class="isOpen(budget.id, 'budgets') ? 'rotate-180' : ''"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </div>

                                        </div>
                                        <Transition
                                            enter-active-class="transition-all duration-600 ease-[cubic-bezier(0.34,1.56,0.64,1)]"
                                            enter-from-class="max-h-0 opacity-0 -translate-y-3"
                                            enter-to-class="max-h-[2000px] opacity-100 translate-y-0"
                                            leave-active-class="transition-all duration-500 ease-[cubic-bezier(0.4,0,0.2,1)]"
                                            leave-from-class="max-h-[2000px] opacity-100 translate-y-0"
                                            leave-to-class="max-h-0 opacity-0 -translate-y-2">
                                            <div v-show="isOpen(budget.id, 'budgets')" class="overflow-hidden">
                                                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                                                    <div v-for="details in budget.budgetdetail" :key="details.id"
                                                        class="p-5  dark:hover:bg-gray-900 transition-colors duration-200">
                                                        <div
                                                            class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-3">
                                                            <h4
                                                                class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                                                                {{ details.procedure.name }}
                                                            </h4>

                                                        </div>

                                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                                            <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                                    Monto</p>
                                                                <p class="font-medium">{{ new Intl.NumberFormat('es-DO',
                                                                    {
                                                                        style:
                                                                            'currency', currency: 'DOP'
                                                                    }).format(details.amount
                                                                        || 0) }}</p>
                                                            </div>
                                                            <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                                    Descuento
                                                                </p>
                                                                <p class="font-medium">{{ details.discount }}%</p>
                                                            </div>
                                                            <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                                    Cantidad</p>
                                                                <p class="font-medium">{{ details.quantity }}</p>
                                                            </div>
                                                        </div>

                                                        <div
                                                            class="flex items-center justify-between border-t border-gray-200 dark:border-gray-700 pt-4">
                                                            <p class="font-semibold text-lg">Subtotal: {{ new
                                                                Intl.NumberFormat('es-DO',
                                                                    {
                                                                        style:
                                                                            'currency', currency: 'DOP'
                                                                    }).format(details.total
                                                                        || 0) }}
                                                            </p>


                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-6 border-t">
                                                    <div
                                                        class=" rounded-b bg-white dark:bg-gray-800  border-gray-200 dark:border-gray-700 p-5">
                                                        <div class="flex items-center justify-between">

                                                            <p
                                                                class="text-xl font-bold text-gray-700 dark:text-gray-200">
                                                                Total:
                                                            </p>

                                                            <p
                                                                class="text-3xl font-extrabold text-green-600 dark:text-green-400">
                                                                {{ new
                                                                    Intl.NumberFormat('es-DO',
                                                                        {
                                                                            style:
                                                                                'currency', currency: 'DOP'
                                                                        }).format(budget.total
                                                                            || 0) }}
                                                            </p>
                                                        </div>


                                                    </div>
                                                </div>



                                            </div>
                                        </Transition>
                                    </div>
                                </div>
                            </div>

                            <!-- Cuando no hay presupuestos -->
                            <div v-if="!budgets.length" class="text-center py-6">
                                <div class="inline-flex flex-col items-center">
                                    <DocumentTextIcon class="w-12 h-12 text-gray-400 dark:text-gray-500 mb-2" />
                                    <p class="text-gray-500 dark:text-gray-400 font-medium">No hay presupuestos
                                        registrados</p>
                                    <p class="text-sm text-gray-400 dark:text-gray-500">Crea un nuevo presupuesto para
                                        comenzar
                                    </p>
                                </div>
                            </div>

                        </div>
                    </AccessGate>

                    <AccessGate permission="bill.view" class="p-5">
                        <div
                            class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">

                            <!-- Encabezado -->
                            <div
                                class="mb-6 flex items-center gap-3 backdrop-blur-sm bg-white/30 dark:bg-gray-700/50 p-3 rounded-xl border border-gray-200/50 dark:border-gray-600/50 shadow-sm">
                                <div class="p-2 bg-pink-100 dark:bg-pink-900 rounded-lg">
                                    <CashIcon class="w-6 h-6 text-pink-600 dark:text-pink-300" />
                                </div>
                                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">Facturas</h2>

                                <div class="ml-auto flex gap-2">
                                    <!-- Ver todos -->
                                    <Link v-if="bills.length" :href="route('bills.index', { patient_id: patient.id })"
                                        class="flex items-center justify-center size-9 rounded-lg bg-blue-500 text-white hover:bg-blue-600
                             transition-all transform hover:scale-105 shadow-md">
                                    <EyeIcon class="size-5" />
                                    </Link>

                                    <!-- Crear -->
                                    <AccessGate permission="bill.create">
                                        <Link :href="route('bills.create', { patient_id: patient.id })" as="button"
                                            class="flex items-center justify-center size-9 rounded-lg bg-pink-500 text-white hover:bg-pink-600
                                 transition-all transform hover:scale-105 shadow-md">
                                        <AddIcon class="size-5" />
                                        </Link>
                                    </AccessGate>
                                </div>
                            </div>

                            <!-- Lista -->
                            <div class="max-h-96 overflow-y-auto pr-2 space-y-3">

                                <div v-for="bill in bills" :key="bill.id"
                                    class="group relative bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">

                                    <div
                                        class="bg-white dark:bg-gray-700/40 rounded-xl shadow-sm border border-gray-100 dark:border-gray-600/40 ">

                                        <div @click="toggleSection(bill.id, 'bills')"
                                            class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 cursor-pointer items-center">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-3">
                                                    <div class="p-2 bg-pink-100 dark:bg-pink-900/30 rounded-lg">
                                                        <CashIcon class="w-6 h-6 text-pink-600 dark:text-pink-400" />
                                                    </div>
                                                    <div>
                                                        <h2 class="font-semibold text-gray-800 dark:text-white">
                                                            Factura # {{ bill.id }}
                                                        </h2>
                                                        <div
                                                            class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                                            <BuildingIcon class="w-4 h-4" />
                                                            <span>{{ bill.branch.name }}</span>
                                                            <UserIcon class="w-4 h-4" />
                                                            <span>{{ bill.doctor.first_name }} {{
                                                                bill.doctor.last_name
                                                                }}</span>
                                                            <span class="mx-1">•</span>
                                                            <CalendarIcon class="w-4 h-4" />
                                                            <span>{{ formatDate(bill.created_at) }}</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Icono chevron que rota -->
                                                <svg class="w-5 h-5 text-gray-600 dark:text-gray-300 transform transition-transform duration-300"
                                                    :class="isOpen(bill.id, 'bills') ? 'rotate-180' : ''" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </div>

                                        </div>
                                        <Transition
                                            enter-active-class="transition-all duration-600 ease-[cubic-bezier(0.34,1.56,0.64,1)]"
                                            enter-from-class="max-h-0 opacity-0 -translate-y-3"
                                            enter-to-class="max-h-[2000px] opacity-100 translate-y-0"
                                            leave-active-class="transition-all duration-500 ease-[cubic-bezier(0.4,0,0.2,1)]"
                                            leave-from-class="max-h-[2000px] opacity-100 translate-y-0"
                                            leave-to-class="max-h-0 opacity-0 -translate-y-2">
                                            <div v-show="isOpen(bill.id, 'bills')" class="overflow-hidden">
                                                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                                                    <div v-for="details in bill.billdetail" :key="details.id"
                                                        class="p-5  dark:hover:bg-gray-900 transition-colors duration-200">
                                                        <div
                                                            class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-3">
                                                            <h4
                                                                class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                                                                {{ details.procedure.name }}
                                                            </h4>

                                                        </div>

                                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                                            <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                                    Monto</p>
                                                                <p class="font-medium">{{ new Intl.NumberFormat('es-DO',
                                                                    {
                                                                        style:
                                                                            'currency', currency: 'DOP'
                                                                    }).format(details.amount
                                                                        || 0) }}</p>
                                                            </div>
                                                            <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                                    Descuento
                                                                </p>
                                                                <p class="font-medium">{{ details.discount }}%</p>
                                                            </div>
                                                            <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                                    Cantidad</p>
                                                                <p class="font-medium">{{ details.quantity }}</p>
                                                            </div>
                                                        </div>

                                                        <div
                                                            class="flex items-center justify-between border-t border-gray-200 dark:border-gray-700 pt-4">
                                                            <p class="font-semibold text-lg">Subtotal: {{ new
                                                                Intl.NumberFormat('es-DO',
                                                                    {
                                                                        style:
                                                                            'currency', currency: 'DOP'
                                                                    }).format(details.total
                                                                        || 0) }}
                                                            </p>


                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-6 border-t">
                                                    <div
                                                        class=" rounded-b bg-white dark:bg-gray-800  border-gray-200 dark:border-gray-700 p-5">
                                                        <div class="flex items-center justify-between">

                                                            <p
                                                                class="text-xl font-bold text-gray-700 dark:text-gray-200">
                                                                Total:
                                                            </p>

                                                            <p
                                                                class="text-3xl font-extrabold text-green-600 dark:text-green-400">
                                                                {{ new
                                                                    Intl.NumberFormat('es-DO',
                                                                        {
                                                                            style:
                                                                                'currency', currency: 'DOP'
                                                                        }).format(bill.total
                                                                            || 0) }}
                                                            </p>
                                                        </div>


                                                    </div>
                                                </div>



                                            </div>
                                        </Transition>
                                    </div>
                                </div>
                            </div>

                            <!-- Cuando no hay presupuestos -->
                            <div v-if="!bills.length" class="text-center py-6">
                                <div class="inline-flex flex-col items-center">
                                    <DocumentTextIcon class="w-12 h-12 text-gray-400 dark:text-gray-500 mb-2" />
                                    <p class="text-gray-500 dark:text-gray-400 font-medium">No hay presupuestos
                                        registrados</p>
                                    <p class="text-sm text-gray-400 dark:text-gray-500">Crea un nuevo presupuesto para
                                        comenzar
                                    </p>
                                </div>
                            </div>

                        </div>
                    </AccessGate>

                    <AccessGate permission="CXC.view" class="p-5">
                        <div
                            class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">

                            <!-- Encabezado -->
                            <div
                                class="mb-6 flex items-center gap-3 backdrop-blur-sm bg-white/30 dark:bg-gray-700/50 p-3 rounded-xl border border-gray-200/50 dark:border-gray-600/50 shadow-sm">
                                <div class="p-2 bg-pink-100 dark:bg-pink-900 rounded-lg">
                                    <CreditCardIcon class="w-6 h-6 text-pink-600 dark:text-pink-300" />
                                </div>
                                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">Recibos</h2>

                                <div class="ml-auto flex gap-2">

                                    <!-- Ver más -->
                                    <Link v-if="CXCS.length" :href="route('CXC.index', { patient_id: patient.id })"
                                        class="flex items-center justify-center size-9 rounded-lg bg-blue-500 text-white hover:bg-blue-600 transition-all transform hover:scale-105 shadow-md">
                                    <EyeIcon class="size-5" />
                                    </Link>

                                    <!-- Crear -->
                                    <AccessGate permission="CXC.create">
                                        <Link :href="route('payments.create', { patient_id: patient.id })" as="button"
                                            class="flex items-center justify-center size-9 rounded-lg bg-pink-500 text-white hover:bg-pink-600 transition-all transform hover:scale-105 shadow-md">
                                        <AddIcon class="size-5" />
                                        </Link>
                                    </AccessGate>

                                </div>
                            </div>

                            <!-- Lista de Recibos -->
                            <div class="max-h-96 overflow-y-auto pr-2 space-y-3">

                                <div v-for="CXC in CXCS" :key="CXC.id"
                                    class="group relative bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">

                                    <div class="p-4">
                                        <div class="flex items-start mb-3">
                                            <div class="flex-1">

                                                <div class="flex justify-between">
                                                    <h3
                                                        class="text-lg font-bold text-gray-800 dark:text-gray-100 group-hover:text-pink-600 dark:group-hover:text-pink-300 transition-colors">
                                                        Cuenta #{{ CXC.id }}
                                                    </h3>

                                                    <!-- Estado -->
                                                    <span class="text-sm font-medium px-2 py-1 rounded-full text-white"
                                                        :class="{
                                                            'bg-green-500': CXC.balance === 0,
                                                            'bg-red-500': CXC.balance > 0
                                                        }">
                                                        {{ CXC.balance === 0 ? 'PAGADO' : 'PENDIENTE' }}
                                                    </span>
                                                </div>

                                                <!-- Info básica -->
                                                <div class="mt-2 grid grid-cols-2 gap-2 text-sm">
                                                    <div class="flex items-center text-gray-600 dark:text-gray-300">
                                                        <CalendarDaysIcon class="w-4 h-4 mr-1 text-pink-500" />
                                                        <span>{{ formatDate(CXC.created_at) }}</span>
                                                    </div>

                                                    <div class="flex items-center text-gray-600 dark:text-gray-300">
                                                        <CurrencyDolarIcon class="w-4 h-4 mr-1 text-pink-500" />
                                                        <span class="font-bold">
                                                            Balance:
                                                            {{
                                                                new Intl.NumberFormat('es-DO', {
                                                                    style: 'currency',
                                                                    currency: 'DOP'
                                                                }).format(CXC.balance || 0)
                                                            }}
                                                        </span>
                                                    </div>
                                                </div>

                                                <!-- Acordeón -->
                                                <div class="mt-3">
                                                    <details class="border-t border-gray-100 dark:border-gray-600 pt-2">
                                                        <summary
                                                            class="flex items-center cursor-pointer text-sm font-medium text-pink-600 dark:text-pink-400">
                                                            <span>Ver recibos realizados</span>
                                                            <ChevronDownIcon
                                                                class="w-4 h-4 ml-1 transition-transform duration-200 group-open:rotate-180" />
                                                        </summary>

                                                        <div class="mt-2 space-y-2">

                                                            <div v-for="(payment, index) in CXC.payment"
                                                                :key="payment.id"
                                                                class="bg-gray-50 dark:bg-gray-600 p-3 rounded-xl border border-gray-200 dark:border-gray-700">

                                                                <p
                                                                    class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                                    Recibo #{{ index + 1 }}
                                                                </p>

                                                                <div class="flex items-center justify-between">
                                                                    <span class="text-gray-600 dark:text-gray-300">Monto
                                                                        pagado</span>
                                                                    <span
                                                                        class="font-semibold text-green-600 dark:text-green-400">
                                                                        {{
                                                                            new Intl.NumberFormat('es-DO', {
                                                                                style: 'currency',
                                                                                currency: 'DOP'
                                                                            }).format(payment.amount_paid || 0)
                                                                        }}
                                                                    </span>
                                                                </div>

                                                                <div class="flex items-center justify-between mt-1">
                                                                    <span
                                                                        class="text-gray-600 dark:text-gray-300">Restante</span>
                                                                    <span
                                                                        class="font-semibold text-pink-600 dark:text-pink-400">
                                                                        {{
                                                                            new Intl.NumberFormat('es-DO', {
                                                                                style: 'currency',
                                                                                currency: 'DOP'
                                                                            }).format(
                                                                                (payment.total ?? 0) - (payment.amount_paid ??
                                                                                    0)
                                                                            )
                                                                        }}
                                                                    </span>
                                                                </div>

                                                            </div>

                                                        </div>
                                                    </details>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>

                            <!-- Sin Recibos -->
                            <div v-if="!CXCS.length" class="text-center py-6">
                                <div class="inline-flex flex-col items-center">
                                    <DocumentTextIcon class="w-12 h-12 text-gray-400 dark:text-gray-500 mb-2" />
                                    <p class="text-gray-500 dark:text-gray-400 font-medium">No hay recibos registrados
                                    </p>
                                    <p class="text-sm text-gray-400 dark:text-gray-500">Aún no se han realizado recibos
                                        en
                                        esta
                                        cuenta</p>
                                </div>
                            </div>

                        </div>
                    </AccessGate>


                    <AccessGate permission="odontograph.view" class="p-5">
                        <div
                            class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">

                            <div
                                class="mb-6 flex items-center gap-3 backdrop-blur-sm bg-white/30 dark:bg-gray-700/50 p-3 rounded-xl border border-gray-200/50 dark:border-gray-600/50 shadow-sm">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-pink-100 dark:bg-pink-900 rounded-lg">
                                        <TeethIcon class="w-6 h-6 text-pink-600 dark:text-pink-400" />
                                    </div>
                                    <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">Odontogramas</h2>
                                </div>

                                <div class="ml-auto flex gap-2">
                                    <Link v-if="odontograph.length"
                                        :href="route('odontographs.index', { patient_id: patient.id })"
                                        class="flex items-center justify-center size-9 rounded-lg bg-blue-500 text-white hover:bg-blue-600 transition-all transform hover:scale-105 shadow-md">
                                    <EyeIcon class="size-5" />
                                    </Link>

                                    <AccessGate permission="odontograph.create">
                                        <Link :href="route('odontographs.create', { patient_id: patient.id })"
                                            as="button"
                                            class="flex items-center justify-center size-9 rounded-lg bg-pink-500 text-white hover:bg-pink-600 transition-all transform hover:scale-105 shadow-md">
                                        <AddIcon class="size-5" />
                                        </Link>
                                    </AccessGate>
                                </div>
                            </div>


                            <!-- Lista de Odontogramas -->
                            <div class="space-y-4">
                                <div v-for="item in odontograph" :key="item.id"
                                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700 transition-all hover:shadow-lg">
                                    <!-- Encabezado del Odontograma -->
                                    <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 cursor-pointer"
                                        @click="toggleSection(item.id, 'odontographs')">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <div class="p-2 bg-pink-100 dark:bg-pink-900/30 rounded-lg">
                                                    <DocumentTextIcon
                                                        class="w-6 h-6 text-pink-600 dark:text-pink-400" />
                                                </div>
                                                <div>
                                                    <h2 class="font-semibold text-gray-800 dark:text-white">
                                                        Odontograma #{{ item.id }}
                                                    </h2>
                                                    <div
                                                        class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                                        <BuildingIcon class="w-4 h-4" />
                                                        <span>{{ item.branch.name }}</span>
                                                        <UserIcon class="w-4 h-4" />
                                                        <span>{{ item.doctor.first_name }} {{ item.doctor.last_name
                                                        }}</span>
                                                        <span class="mx-1">•</span>
                                                        <CalendarIcon class="w-4 h-4" />
                                                        <span>{{ formatDate(item.created_at) }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Icono chevron que rota -->
                                            <button type="button"
                                                class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-300 transform transition-transform duration-200"
                                                    :class="isOpen(item.id, 'odontographs') ? 'rotate-180' : ''"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>


                                    <!-- Visualización del Odontograma -->
                                    <Transition
                                        enter-active-class="transition-all duration-600 ease-[cubic-bezier(0.34,1.56,0.64,1)]"
                                        enter-from-class="max-h-0 opacity-0 -translate-y-3"
                                        enter-to-class="max-h-[2000px] opacity-100 translate-y-0"
                                        leave-active-class="transition-all duration-500 ease-[cubic-bezier(0.4,0,0.2,1)]"
                                        leave-from-class="max-h-[2000px] opacity-100 translate-y-0"
                                        leave-to-class="max-h-0 opacity-0 -translate-y-2">
                                        <div v-show="isOpen(item.id, 'odontographs')" class="overflow-hidden">

                                            <div class="p-4">
                                                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-3">
                                                    PROCEDIMIENTOS
                                                    REGISTRADOS
                                                </h3>

                                                <!-- Odontograma Superior -->
                                                <div class="mb-6 grid grid-cols-2">
                                                    <div
                                                        class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 lg:grid-cols-8 gap-1.5 mr-4">
                                                        <div v-for="tooth in upperLeftTeethTop" :key="tooth"
                                                            @click="selectToothInView(item, tooth)"
                                                            class="relative p-1 rounded-md border border-gray-200 dark:border-gray-700 text-center cursor-pointer transition-all hover:shadow-md"
                                                            :class="toothClass(item.data[tooth])">

                                                            <div
                                                                class="font-bold text-gray-800 dark:text-white text-sm">
                                                                {{
                                                                    tooth }}
                                                            </div>

                                                            <div class="flex justify-center gap-1 mt-1">
                                                                <div v-if="item.data[tooth]"
                                                                    class="relative flex justify-center items-center w-8 sm:w-10 h-8 sm:h-10 rounded">
                                                                    <!-- Extracción (3 líneas) -->
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('Extracción')">
                                                                        <div
                                                                            class="absolute inset-0 flex justify-center items-center space-x-1">
                                                                            <div class="w-1/12 h-3/4 bg-red-600"></div>
                                                                            <div class="w-1/12 h-3/4 bg-red-600"></div>
                                                                            <div class="w-1/12 h-3/4 bg-red-600"></div>
                                                                        </div>
                                                                    </template>

                                                                    <!-- Ausencia (X) -->
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('Ausencia')">
                                                                        <div
                                                                            class="absolute w-full h-full flex items-center justify-center text-red-600 text-sm sm:text-lg font-bold">
                                                                            X</div>
                                                                    </template>

                                                                    <!-- Implante -->
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('Implante')">
                                                                        <ImplantIcon
                                                                            class="text-gray-800 dark:text-gray-200 w-5 sm:w-6 h-5 sm:h-6" />
                                                                    </template>

                                                                    <!-- Endodoncia -->
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('Endodoncia')">
                                                                        <div
                                                                            class="absolute w-1.5 sm:w-2 h-1.5 sm:h-2 bg-red-500 rounded-full">
                                                                        </div>
                                                                    </template>
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('EndodonciaAplicada')">
                                                                        <div
                                                                            class="absolute w-1.5 sm:w-2 h-1.5 sm:h-2 bg-blue-500 rounded-full">
                                                                        </div>
                                                                    </template>

                                                                    <!-- Puente -->
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('Puente')">
                                                                        <div
                                                                            class="absolute bottom-0 w-full h-0.5 sm:h-1 bg-gray-600">
                                                                        </div>
                                                                    </template>
                                                                </div>

                                                                <template v-else>
                                                                    <span class="text-gray-400 text-xs">—</span>
                                                                </template>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 lg:grid-cols-8 gap-1.5 mr-4">
                                                        <div v-for="tooth in upperRightTeethTop" :key="tooth"
                                                            @click="selectToothInView(item, tooth)"
                                                            class="relative p-1 rounded-md border border-gray-200 dark:border-gray-700 text-center cursor-pointer transition-all hover:shadow-md"
                                                            :class="toothClass(item.data[tooth])">

                                                            <div
                                                                class="font-bold text-gray-800 dark:text-white text-sm">
                                                                {{
                                                                    tooth }}
                                                            </div>

                                                            <div class="flex justify-center gap-1 mt-1">
                                                                <div v-if="item.data[tooth]"
                                                                    class="relative flex justify-center items-center w-8 sm:w-10 h-8 sm:h-10 rounded">
                                                                    <!-- Extracción (3 líneas) -->
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('Extracción')">
                                                                        <div
                                                                            class="absolute inset-0 flex justify-center items-center space-x-1">
                                                                            <div class="w-1/12 h-3/4 bg-red-600"></div>
                                                                            <div class="w-1/12 h-3/4 bg-red-600"></div>
                                                                            <div class="w-1/12 h-3/4 bg-red-600"></div>
                                                                        </div>
                                                                    </template>

                                                                    <!-- Ausencia (X) -->
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('Ausencia')">
                                                                        <div
                                                                            class="absolute w-full h-full flex items-center justify-center text-red-600 text-sm sm:text-lg font-bold">
                                                                            X</div>
                                                                    </template>

                                                                    <!-- Implante -->
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('Implante')">
                                                                        <ImplantIcon
                                                                            class="text-gray-800 dark:text-gray-200 w-5 sm:w-6 h-5 sm:h-6" />
                                                                    </template>

                                                                    <!-- Endodoncia -->
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('Endodoncia')">
                                                                        <div
                                                                            class="absolute w-1.5 sm:w-2 h-1.5 sm:h-2 bg-red-500 rounded-full">
                                                                        </div>
                                                                    </template>
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('EndodonciaAplicada')">
                                                                        <div
                                                                            class="absolute w-1.5 sm:w-2 h-1.5 sm:h-2 bg-blue-500 rounded-full">
                                                                        </div>
                                                                    </template>

                                                                    <!-- Puente -->
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('Puente')">
                                                                        <div
                                                                            class="absolute bottom-0 w-full h-0.5 sm:h-1 bg-gray-600">
                                                                        </div>
                                                                    </template>
                                                                </div>


                                                                <template v-else>
                                                                    <span class="text-gray-400 text-xs">—</span>
                                                                </template>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div
                                                        class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-5 mr-4  mt-2 ml-auto gap-1.5">
                                                        <div v-for="tooth in upperLeftTeethBot" :key="tooth"
                                                            @click="selectToothInView(item, tooth)"
                                                            class="relative p-1 rounded-md border border-gray-200 dark:border-gray-700 text-center cursor-pointer transition-all hover:shadow-md"
                                                            :class="toothClass(item.data[tooth])">

                                                            <div
                                                                class="font-bold text-gray-800 dark:text-white text-sm">
                                                                {{
                                                                    tooth }}
                                                            </div>

                                                            <div class="flex justify-center gap-1 mt-1">
                                                                <div v-if="item.data[tooth]"
                                                                    class="relative flex justify-center items-center w-8 sm:w-10 h-8 sm:h-10 rounded">
                                                                    <!-- Extracción (3 líneas) -->
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('Extracción')">
                                                                        <div
                                                                            class="absolute inset-0 flex justify-center items-center space-x-1">
                                                                            <div class="w-1/12 h-3/4 bg-red-600"></div>
                                                                            <div class="w-1/12 h-3/4 bg-red-600"></div>
                                                                            <div class="w-1/12 h-3/4 bg-red-600"></div>
                                                                        </div>
                                                                    </template>

                                                                    <!-- Ausencia (X) -->
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('Ausencia')">
                                                                        <div
                                                                            class="absolute w-full h-full flex items-center justify-center text-red-600 text-sm sm:text-lg font-bold">
                                                                            X</div>
                                                                    </template>

                                                                    <!-- Implante -->
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('Implante')">
                                                                        <ImplantIcon
                                                                            class="text-gray-800 dark:text-gray-200 w-5 sm:w-6 h-5 sm:h-6" />
                                                                    </template>

                                                                    <!-- Endodoncia -->
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('Endodoncia')">
                                                                        <div
                                                                            class="absolute w-1.5 sm:w-2 h-1.5 sm:h-2 bg-red-500 rounded-full">
                                                                        </div>
                                                                    </template>
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('EndodonciaAplicada')">
                                                                        <div
                                                                            class="absolute w-1.5 sm:w-2 h-1.5 sm:h-2 bg-blue-500 rounded-full">
                                                                        </div>
                                                                    </template>

                                                                    <!-- Puente -->
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('Puente')">
                                                                        <div
                                                                            class="absolute bottom-0 w-full h-0.5 sm:h-1 bg-gray-600">
                                                                        </div>
                                                                    </template>
                                                                </div>


                                                                <template v-else>
                                                                    <span class="text-gray-400 text-xs">—</span>
                                                                </template>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div
                                                        class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-5  mt-2 mr-auto gap-1.5">
                                                        <div v-for="tooth in upperRightTeethBot" :key="tooth"
                                                            @click="selectToothInView(item, tooth)"
                                                            class="relative p-1 rounded-md border border-gray-200 dark:border-gray-700 text-center cursor-pointer transition-all hover:shadow-md"
                                                            :class="toothClass(item.data[tooth])">

                                                            <div
                                                                class="font-bold text-gray-800 dark:text-white text-sm">
                                                                {{
                                                                    tooth }}
                                                            </div>

                                                            <div class="flex justify-center gap-1 mt-1">
                                                                <div v-if="item.data[tooth]"
                                                                    class="relative flex justify-center items-center w-8 sm:w-10 h-8 sm:h-10 rounded">
                                                                    <!-- Extracción (3 líneas) -->
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('Extracción')">
                                                                        <div
                                                                            class="absolute inset-0 flex justify-center items-center space-x-1">
                                                                            <div class="w-1/12 h-3/4 bg-red-600"></div>
                                                                            <div class="w-1/12 h-3/4 bg-red-600"></div>
                                                                            <div class="w-1/12 h-3/4 bg-red-600"></div>
                                                                        </div>
                                                                    </template>

                                                                    <!-- Ausencia (X) -->
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('Ausencia')">
                                                                        <div
                                                                            class="absolute w-full h-full flex items-center justify-center text-red-600 text-sm sm:text-lg font-bold">
                                                                            X</div>
                                                                    </template>

                                                                    <!-- Implante -->
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('Implante')">
                                                                        <ImplantIcon
                                                                            class="text-gray-800 dark:text-gray-200 w-5 sm:w-6 h-5 sm:h-6" />
                                                                    </template>

                                                                    <!-- Endodoncia -->
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('Endodoncia')">
                                                                        <div
                                                                            class="absolute w-1.5 sm:w-2 h-1.5 sm:h-2 bg-red-500 rounded-full">
                                                                        </div>
                                                                    </template>
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('EndodonciaAplicada')">
                                                                        <div
                                                                            class="absolute w-1.5 sm:w-2 h-1.5 sm:h-2 bg-blue-500 rounded-full">
                                                                        </div>
                                                                    </template>

                                                                    <!-- Puente -->
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('Puente')">
                                                                        <div
                                                                            class="absolute bottom-0 w-full h-0.5 sm:h-1 bg-gray-600">
                                                                        </div>
                                                                    </template>
                                                                </div>


                                                                <template v-else>
                                                                    <span class="text-gray-400 text-xs">—</span>
                                                                </template>
                                                            </div>
                                                        </div>


                                                    </div>


                                                </div>

                                                <!-- Odontograma Inferior -->
                                                <div class=" grid grid-cols-2">
                                                    <div
                                                        class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-5 mr-4  mt-2 ml-auto gap-1.5">
                                                        <div v-for="tooth in LowerLeftTeethTop" :key="tooth"
                                                            @click="selectToothInView(item, tooth)"
                                                            class="relative p-1 rounded-md border border-gray-200 dark:border-gray-700 text-center cursor-pointer transition-all hover:shadow-md"
                                                            :class="toothClass(item.data[tooth])">

                                                            <div
                                                                class="font-bold text-gray-800 dark:text-white text-sm">
                                                                {{
                                                                    tooth }}
                                                            </div>

                                                            <div class="flex justify-center gap-1 mt-1">
                                                                <div v-if="item.data[tooth]"
                                                                    class="relative flex justify-center items-center w-8 sm:w-10 h-8 sm:h-10 rounded">
                                                                    <!-- Extracción (3 líneas) -->
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('Extracción')">
                                                                        <div
                                                                            class="absolute inset-0 flex justify-center items-center space-x-1">
                                                                            <div class="w-1/12 h-3/4 bg-red-600"></div>
                                                                            <div class="w-1/12 h-3/4 bg-red-600"></div>
                                                                            <div class="w-1/12 h-3/4 bg-red-600"></div>
                                                                        </div>
                                                                    </template>

                                                                    <!-- Ausencia (X) -->
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('Ausencia')">
                                                                        <div
                                                                            class="absolute w-full h-full flex items-center justify-center text-red-600 text-sm sm:text-lg font-bold">
                                                                            X</div>
                                                                    </template>

                                                                    <!-- Implante -->
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('Implante')">
                                                                        <ImplantIcon
                                                                            class="text-gray-800 dark:text-gray-200 w-5 sm:w-6 h-5 sm:h-6" />
                                                                    </template>

                                                                    <!-- Endodoncia -->
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('Endodoncia')">
                                                                        <div
                                                                            class="absolute w-1.5 sm:w-2 h-1.5 sm:h-2 bg-red-500 rounded-full">
                                                                        </div>
                                                                    </template>
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('EndodonciaAplicada')">
                                                                        <div
                                                                            class="absolute w-1.5 sm:w-2 h-1.5 sm:h-2 bg-blue-500 rounded-full">
                                                                        </div>
                                                                    </template>

                                                                    <!-- Puente -->
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('Puente')">
                                                                        <div
                                                                            class="absolute bottom-0 w-full h-0.5 sm:h-1 bg-gray-600">
                                                                        </div>
                                                                    </template>
                                                                </div>


                                                                <template v-else>
                                                                    <span class="text-gray-400 text-xs">—</span>
                                                                </template>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div
                                                        class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-5  mt-2 mr-auto gap-1.5">
                                                        <div v-for="tooth in LowerRightTeethTop" :key="tooth"
                                                            @click="selectToothInView(item, tooth)"
                                                            class="relative p-1 rounded-md border border-gray-200 dark:border-gray-700 text-center cursor-pointer transition-all hover:shadow-md"
                                                            :class="toothClass(item.data[tooth])">

                                                            <div
                                                                class="font-bold text-gray-800 dark:text-white text-sm">
                                                                {{
                                                                    tooth }}
                                                            </div>

                                                            <div class="flex justify-center gap-1 mt-1">
                                                                <div v-if="item.data[tooth]"
                                                                    class="relative flex justify-center items-center w-8 sm:w-10 h-8 sm:h-10 rounded">
                                                                    <!-- Extracción (3 líneas) -->
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('Extracción')">
                                                                        <div
                                                                            class="absolute inset-0 flex justify-center items-center space-x-1">
                                                                            <div class="w-1/12 h-3/4 bg-red-600"></div>
                                                                            <div class="w-1/12 h-3/4 bg-red-600"></div>
                                                                            <div class="w-1/12 h-3/4 bg-red-600"></div>
                                                                        </div>
                                                                    </template>

                                                                    <!-- Ausencia (X) -->
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('Ausencia')">
                                                                        <div
                                                                            class="absolute w-full h-full flex items-center justify-center text-red-600 text-sm sm:text-lg font-bold">
                                                                            X</div>
                                                                    </template>

                                                                    <!-- Implante -->
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('Implante')">
                                                                        <ImplantIcon
                                                                            class="text-gray-800 dark:text-gray-200 w-5 sm:w-6 h-5 sm:h-6" />
                                                                    </template>

                                                                    <!-- Endodoncia -->
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('Endodoncia')">
                                                                        <div
                                                                            class="absolute w-1.5 sm:w-2 h-1.5 sm:h-2 bg-red-500 rounded-full">
                                                                        </div>
                                                                    </template>
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('EndodonciaAplicada')">
                                                                        <div
                                                                            class="absolute w-1.5 sm:w-2 h-1.5 sm:h-2 bg-blue-500 rounded-full">
                                                                        </div>
                                                                    </template>

                                                                    <!-- Puente -->
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('Puente')">
                                                                        <div
                                                                            class="absolute bottom-0 w-full h-0.5 sm:h-1 bg-gray-600">
                                                                        </div>
                                                                    </template>
                                                                </div>


                                                                <template v-else>
                                                                    <span class="text-gray-400 text-xs">—</span>
                                                                </template>
                                                            </div>
                                                        </div>


                                                    </div>

                                                    <div
                                                        class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 mt-2 lg:grid-cols-8 gap-1.5 mr-4">
                                                        <div v-for="tooth in LowerLeftTeethBot" :key="tooth"
                                                            @click="selectToothInView(item, tooth)"
                                                            class="relative p-1 rounded-md border border-gray-200 dark:border-gray-700 text-center cursor-pointer transition-all hover:shadow-md"
                                                            :class="toothClass(item.data[tooth])">

                                                            <div
                                                                class="font-bold text-gray-800 dark:text-white text-sm">
                                                                {{
                                                                    tooth }}
                                                            </div>

                                                            <div class="flex justify-center gap-1 mt-1">
                                                                <div v-if="item.data[tooth]"
                                                                    class="relative flex justify-center items-center w-8 sm:w-10 h-8 sm:h-10 rounded">
                                                                    <!-- Extracción (3 líneas) -->
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('Extracción')">
                                                                        <div
                                                                            class="absolute inset-0 flex justify-center items-center space-x-1">
                                                                            <div class="w-1/12 h-3/4 bg-red-600"></div>
                                                                            <div class="w-1/12 h-3/4 bg-red-600"></div>
                                                                            <div class="w-1/12 h-3/4 bg-red-600"></div>
                                                                        </div>
                                                                    </template>

                                                                    <!-- Ausencia (X) -->
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('Ausencia')">
                                                                        <div
                                                                            class="absolute w-full h-full flex items-center justify-center text-red-600 text-sm sm:text-lg font-bold">
                                                                            X</div>
                                                                    </template>

                                                                    <!-- Implante -->
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('Implante')">
                                                                        <ImplantIcon
                                                                            class="text-gray-800 dark:text-gray-200 w-5 sm:w-6 h-5 sm:h-6" />
                                                                    </template>

                                                                    <!-- Endodoncia -->
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('Endodoncia')">
                                                                        <div
                                                                            class="absolute w-1.5 sm:w-2 h-1.5 sm:h-2 bg-red-500 rounded-full">
                                                                        </div>
                                                                    </template>
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('EndodonciaAplicada')">
                                                                        <div
                                                                            class="absolute w-1.5 sm:w-2 h-1.5 sm:h-2 bg-blue-500 rounded-full">
                                                                        </div>
                                                                    </template>

                                                                    <!-- Puente -->
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('Puente')">
                                                                        <div
                                                                            class="absolute bottom-0 w-full h-0.5 sm:h-1 bg-gray-600">
                                                                        </div>
                                                                    </template>
                                                                </div>

                                                                <template v-else>
                                                                    <span class="text-gray-400 text-xs">—</span>
                                                                </template>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 lg:grid-cols-8 mt-2 gap-1.5 mr-4">
                                                        <div v-for="tooth in LowerRightTeethBot" :key="tooth"
                                                            @click="selectToothInView(item, tooth)"
                                                            class="relative p-1 rounded-md border border-gray-200 dark:border-gray-700 text-center cursor-pointer transition-all hover:shadow-md"
                                                            :class="toothClass(item.data[tooth])">

                                                            <div
                                                                class="font-bold text-gray-800 dark:text-white text-sm">
                                                                {{
                                                                    tooth }}
                                                            </div>

                                                            <div class="flex justify-center gap-1 mt-1">
                                                                <div v-if="item.data[tooth]"
                                                                    class="relative flex justify-center items-center w-8 sm:w-10 h-8 sm:h-10 rounded">
                                                                    <!-- Extracción (3 líneas) -->
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('Extracción')">
                                                                        <div
                                                                            class="absolute inset-0 flex justify-center items-center space-x-1">
                                                                            <div class="w-1/12 h-3/4 bg-red-600"></div>
                                                                            <div class="w-1/12 h-3/4 bg-red-600"></div>
                                                                            <div class="w-1/12 h-3/4 bg-red-600"></div>
                                                                        </div>
                                                                    </template>

                                                                    <!-- Ausencia (X) -->
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('Ausencia')">
                                                                        <div
                                                                            class="absolute w-full h-full flex items-center justify-center text-red-600 text-sm sm:text-lg font-bold">
                                                                            X</div>
                                                                    </template>

                                                                    <!-- Implante -->
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('Implante')">
                                                                        <ImplantIcon
                                                                            class="text-gray-800 dark:text-gray-200 w-5 sm:w-6 h-5 sm:h-6" />
                                                                    </template>

                                                                    <!-- Endodoncia -->
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('Endodoncia')">
                                                                        <div
                                                                            class="absolute w-1.5 sm:w-2 h-1.5 sm:h-2 bg-red-500 rounded-full">
                                                                        </div>
                                                                    </template>
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('EndodonciaAplicada')">
                                                                        <div
                                                                            class="absolute w-1.5 sm:w-2 h-1.5 sm:h-2 bg-blue-500 rounded-full">
                                                                        </div>
                                                                    </template>

                                                                    <!-- Puente -->
                                                                    <template
                                                                        v-if="getStatusesForItem(item.data[tooth]).includes('Puente')">
                                                                        <div
                                                                            class="absolute bottom-0 w-full h-0.5 sm:h-1 bg-gray-600">
                                                                        </div>
                                                                    </template>
                                                                </div>

                                                                <template v-else>
                                                                    <span class="text-gray-400 text-xs">—</span>
                                                                </template>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </Transition>

                                </div>

                                <!-- Estado vacío -->
                                <div v-if="!odontograph.length"
                                    class="flex flex-col items-center justify-center p-12 bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-300 dark:border-gray-700 text-center">
                                    <DocumentTextIcon class="w-12 h-12 text-gray-400 dark:text-gray-500 mb-2" />

                                    <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300">No se encontraron
                                        odontogramas
                                    </h3>
                                    <p class="text-gray-500 dark:text-gray-400 mt-1 max-w-md">
                                        No hay registros de odontogramas para este paciente. Crea uno nuevo para
                                        comenzar.
                                    </p>

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
                                                    class="flex items-center justify-center w-8 h-8 rounded-full text-white bg-gray-800  font-bold">
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





            </div>

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
import ImplantIcon from '@/Components/Icons/ImplantIcon.vue';
import BuildingIcon from '@/Components/Icons/BuildingIcon.vue';
import CreditCardIcon from '@/Components/Icons/CreditCardIcon.vue';
import { ref } from 'vue';
export default {
    props: {
        patient: Object,
        odontograph: Object,
        bills: Object,
        CXCS: Object,
        filters: Object,
        events: Object,
        budgets: Object,
        prescription: Object,
        medicalHistory: Object,
        latest_event: Object
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
        Head,
        ImplantIcon,
        BuildingIcon,
        CreditCardIcon
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
            upperLeftTeethTop: [18, 17, 16, 15, 14, 13, 12, 11,],
            upperLeftTeethBot: [55, 54, 53, 52, 51],

            upperRightTeethTop: [21, 22, 23, 24, 25, 26, 27, 28,],
            upperRightTeethBot: [61, 62, 63, 64, 65],

            LowerLeftTeethTop: [85, 84, 83, 82, 81],
            LowerLeftTeethBot: [48, 47, 46, 45, 44, 43, 42, 41],



            LowerRightTeethBot: [31, 32, 33, 34, 35, 36, 37, 38,],
            LowerRightTeethTop: [71, 72, 73, 74, 75],
            openBudgetId: ref(null),
            openBillId: ref(null),
            openOdontographId: ref(null),
            openEventId: ref(null),
            openPrescriptionId: ref(null),
            openPaymentId: ref(null),

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
                51: "Incisivo central superior derecho (temporal)",
                52: "Incisivo lateral superior derecho (temporal)",
                53: "Canino superior derecho (temporal)",
                54: "1er molar superior derecho (temporal)",
                55: "2do molar superior derecho (temporal)",
                61: "Incisivo central superior izquierdo (temporal)",
                62: "Incisivo lateral superior izquierdo (temporal)",
                63: "Canino superior izquierdo (temporal)",
                64: "1er molar superior izquierdo (temporal)",
                65: "2do molar superior izquierdo (temporal)",
                71: "Incisivo central inferior izquierdo (temporal)",
                72: "Incisivo lateral inferior izquierdo (temporal)",
                73: "Canino inferior izquierdo (temporal)",
                74: "1er molar inferior izquierdo (temporal)",
                75: "2do molar inferior izquierdo (temporal)",
                81: "Incisivo central inferior derecho (temporal)",
                82: "Incisivo lateral inferior derecho (temporal)",
                83: "Canino inferior derecho (temporal)",
                84: "1er molar inferior derecho (temporal)",
                85: "2do molar inferior derecho (temporal)"
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
            },
            openPrescriptions: {}

        }
    },

    methods: {
        toggleAccordion(id) {
            if (this.openPrescriptions[id]) {
                this.openPrescriptions = {};
                return;
            }

            this.openPrescriptions = { [id]: true };
        }
        ,
        toggleSection(id, seccion) {

            switch (seccion) {
                case 'budgets':
                    this.openBudgetId = this.openBudgetId === id ? null : id;
                    break;
                case 'bills':
                    this.openBillId = this.openBillId === id ? null : id;
                    break;
                case 'odontographs':
                    this.openOdontographId = this.openOdontographId === id ? null : id;
                    break;
                case 'events':
                    this.openEventId = this.openEventId === id ? null : id;
                    break;
                case 'prescriptions':
                    this.openPrescriptionId = this.openPrescriptionId === id ? null : id;
                    break;
                case 'payments':
                    this.openPaymentId = this.openPaymentId === id ? null : id;
                    break;


                default:
                    break;
            }
        },

        isOpen(id, seccion) {
            switch (seccion) {
                case 'budgets':
                    return this.openBudgetId === id;
                case 'bills':
                    return this.openBillId === id;
                case 'odontographs':
                    return this.openOdontographId === id;
                case 'events':
                    return this.openEventId === id;
                case 'prescriptions':
                    return this.openPrescriptionId === id;
                case 'payments':
                    return this.openPaymentId === id;
                default:
                    break;
            }

        },

        formatPhoneNumber(number) {
            if (!number) return "";
            return number.slice(0, 3) + '-' + number.slice(3, 6) + '-' + number.slice(6, 10)
        },
        formatDNI(number) {
            if (!number) return "";
            return number.slice(0, 3) + '-' + number.slice(3, 10) + '-' + number.slice(10);
        },
        selectToothInView(item, tooth) {
            this.selectedOdontograph = item
            const zones = item.data[tooth] || null
            if (!zones) {
                this.selectedToothInfo = null
            } else {
                this.selectedToothInfo = { tooth, zones }
            }
        },
        getStatusesForItem(toothData) {
            if (!toothData) return [];
            return Object.values(toothData);
        },
        toothClass(procedures) {
            if (!procedures) return "";

            const values = Object.values(procedures);

            if (values.includes("Corona")) return "bg-yellow-100 dark:bg-yellow-900/30 border-yellow-300 dark:border-yellow-700";
            if (values.includes("Cariado")) return "bg-red-100 dark:bg-red-900/30 border-red-300 dark:border-red-700";
            if (values.includes("Restauración")) return "bg-blue-100 dark:bg-blue-800/30 border-blue-300 dark:border-blue-700";
            if (values.includes("CoronaAplicada")) return "bg-blue-200 dark:bg-blue-900/30 border-blue-500 dark:border-blue-800";

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
        beforeEnter(el) {
            el.style.opacity = 0;
            el.style.maxHeight = "0px";
        },
        enter(el) {
            el.style.transition = "all 0.35s cubic-bezier(0.25, 0.1, 0.25, 1.2)";
            el.style.maxHeight = el.scrollHeight + "px";
            el.style.opacity = 1;
        },

        beforeLeave(el) {
            el.style.opacity = 1;
            el.style.maxHeight = el.scrollHeight + "px";
        },

        leave(el) {
            requestAnimationFrame(() => {
                el.style.transition = "all 0.35s cubic-bezier(0.25, 0.1, 0.25, 1.2)";
                el.style.maxHeight = "0px";
                el.style.opacity = 0;
            });
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
    background-color: #ec4899;
    /* azul */
    border-radius: 6px;
}

.scroll-beauty::-webkit-scrollbar-track {
    background-color: #e5e7eb;
    /* gris claro */
}
</style>
