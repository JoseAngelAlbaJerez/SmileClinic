<template>
    <AuthenticatedLayout>
        <template #header>
            <Breadcrumb :crumbs="crumbs" />
        </template>

        <div
            class="container mx-auto px-6 py-4  dark:text-white bg-white dark:bg-gray-700 mt-5  pb-8  rounded-2xl shadow-md">
            <div class="my-2 mt-5 flex  gap-2 items-center">
                <h2 class="text-lg font-semibold ml-1"> Historial Médico de {{ patient.first_name }} {{
                    patient.last_name }}
                </h2>
                <div v-if="patient.active" class=" flex ml-auto gap-2 mb-2 ">
                    <button @click="print()"
                        class="flex justify-center gap-2 rounded-lg bg-green-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-500 sm:px-4">
                        <PrintIcon /> Imprimir
                    </button>
                    <Link :href="route('patients.edit', patient.id)"
                        class="flex justify-center gap-2 rounded-lg bg-yellow-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-500 sm:px-4">
                    <EditIcon /> Editar
                    </Link>
                    <DangerButton @click="deletePatient(patient.id)"
                        class="flex justify-center gap-2 rounded-lg bg-red-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 sm:px-4">
                        <DeleteIcon /> Eliminar
                    </DangerButton>
                </div>
                <div class="flex ml-auto gap-2" v-else>
                    <PrimaryButton @click="restorePatient(patient.id)"
                        class="flex justify-center gap-2 rounded-lg bg-green-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-500 sm:px-4">
                        <RestoreIcon /> Restaurar
                    </PrimaryButton>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Información personal -->
                <div class="bg-gray-100 dark:bg-gray-800 p-6   rounded-2xl shadow-md">
                    <div class="my-2  flex items-center gap-2">
                        <UserIcon class="w-6 h-6 text-blue-600 dark:text-blue-400 mb-1" />
                        <h2 class="text-lg font-semibold ">Información Personal</h2>

                    </div>

                    <div class="space-y-2 text-sm">
                        <p><span class="font-medium">Nombres:</span> {{ patient.first_name }} {{ patient.last_name }}
                        </p>
                        <p><span class="font-medium">Cédula / DNI:</span> {{ patient.DNI }}</p>
                        <p><span class="font-medium">Teléfono:</span> {{ patient.phone_number }}</p>
                        <p><span class="font-medium">ARS:</span> {{ patient.ars }}</p>
                        <p><span class="font-medium">Fecha de Nacimiento:</span> {{ formatDate(patient.date_of_birth) }}
                        </p>
                        <p><span class="font-medium">Dirección:</span> {{ patient.address }}</p>
                        <p><span class="font-medium">Motivo de visita:</span> {{ patient.motive }}</p>
                    </div>
                </div>

                <!-- Información médica -->
                <div class="bg-gray-100 dark:bg-gray-800 p-6 rounded-2xl shadow-md">
                    <div class="my-2  flex items-center gap-2">
                        <MedicalHistoryIcon class="w-6 h-6 text-blue-600 dark:text-blue-400 mb-4" />
                        <h2 class="text-lg font-semibold mb-4">Antecedentes Médicos</h2>

                    </div>
                    <div class="space-y-2 text-sm">
                        <p>
                            <span class="font-medium text-gray-100">¿Complicaciones?</span>
                            <span>{{ patient.complications ? ': Sí' : ': No' }}</span>
                        </p>
                        <p v-if="patient.complications_detail">
                            <span class="font-medium">Detalle:</span> {{ patient.complications_detail }}
                        </p>

                        <p>
                            <span class="font-medium">¿Usa Medicamentos?</span>
                            <span>{{ patient.drugs ? ': Sí' : ': No' }}</span>
                        </p>
                        <p v-if="patient.drugs_detail">
                            <span class="font-medium">Detalle:</span> {{ patient.drugs_detail }}
                        </p>

                        <p>
                            <span class="font-medium">¿Tiene Alergias?</span>
                            <span>{{ patient.alergies ? ': Sí' : ': No' }}</span>
                        </p>
                        <p v-if="patient.alergies_detail">
                            <span class="font-medium">Detalle:</span> {{ patient.alergies_detail }}
                        </p>
                    </div>
                </div>
                <!-- Presupuestos -->
                <div class="bg-gray-100 dark:bg-gray-800 p-6 rounded-2xl shadow-md">
                    <div class=" flex items-center gap-2">
                        <DocumentMoney class="w-6 h-6 text-blue-600 dark:text-blue-400 mb-1" />
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Presupuestos</h2>
                    </div>

                    <div class=" rounded-2xl ">

                        <div v-for="(budget, index) in budgets" :key="index"
                            class="text-sm p-4 cursor-pointer  bg-gray-300  rounded-lg dark:bg-gray-700 text-gray-700 dark:text-gray-300 ml-1 my-2 space-y-1">

                            <div @click="openAccordion(index)" class="flex items-center gap-4">
                                <p>
                                    <strong># - </strong> {{ budget.id }}
                                </p>
                                <p>
                                    <strong>Tipo:</strong> {{ budget.type }}
                                </p>
                                <p v-if="budget.type === 'Contado'"> <strong>Fecha de Emisión:</strong> {{
                                    budget.emission_date }}</p>
                                <template v-if="budget.type === 'Crédito'">
                                    <p> <strong>Fecha de Expiración:</strong> {{ budget.expiration_date }}</p>
                                </template>
                                <p> <strong>Total:</strong> {{ formatNumber(budget.total) }}</p>
                                <ChevronDownIcon class="flex  ml-auto " v-if="activeIndex === index" />
                                <ChevronUpIcon class="flex  ml-auto " v-else />
                            </div>

                            <transition name="accordion" @enter="enter" @leave="leave">
                                <div v-if="activeIndex === index">
                                    <div v-for="details in budget.budgetdetail" :key="details.id"
                                        class="bg-white  cursor-default hover:bg-blue-500 my-4 border shadow-md dark:bg-gray-800 p-4 rounded-xl transition-all duration-200 ease-linear">
                                        <div class="flex justify-between items-center mb-2">
                                            <h3 class="text-md font-semibold text-gray-800 dark:text-gray-100"># -{{
                                                details.id
                                            }} {{ details.procedure.name }}</h3>
                                            <span class="text-sm font-medium px-2 py-1 rounded-full"
                                                :class="details.procedure.coberture ? 'bg-green-100 text-green-600 dark:bg-green-800 dark:text-green-300' : 'bg-red-100 text-red-600 dark:bg-red-800 dark:text-red-300'">
                                                {{ details.procedure.coberture ? 'Asegurado' : 'No Asegurado' }}
                                            </span>
                                        </div>

                                        <div class="text-sm text-gray-700 dark:text-gray-300 space-y-2">
                                            <div>
                                                <label class="block text-sm font-medium">Monto: ${{
                                                    formatNumber(details.amount)
                                                }}</label>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium">Descuento: {{ details.discount
                                                }} %</label>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium">Cantidad: {{ details.quantity
                                                }}</label>
                                            </div>
                                        </div>


                                        <div class="flex items-center gap-2 mt-4">
                                            <h3 class="text-md font-semibold">Subtotal: ${{ formatNumber(details.total)
                                            }}
                                            </h3>
                                            <DangerButton v-if="budget.active && details.active"
                                                @click="deleteBudgetDetail(details.id)"
                                                class="ml-auto mt-2 gap-2 bg-red-500 px-2 py-2 text-sm text-white hover:bg-red-600 rounded-lg">
                                                <DeleteIcon />
                                            </DangerButton>
                                            <button v-else @click="restoreBudgetDetail(details.id)"
                                                class="ml-auto gap-2 bg-green-500 px-2 py-2 text-sm text-white hover:bg-green-600 rounded-lg">
                                                <RestoreIcon />
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </transition>

                        </div>

                    </div>

                </div>
                <!-- Citas Pendientes -->
                <div class="bg-gray-100 dark:bg-gray-800 p-6 rounded-2xl shadow-md">
                    <div class="mb-4 flex items-center gap-2">
                        <CalendarIcon class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Citas Pendientes</h2>
                    </div>

                    <div v-for="event in events" :key="event.id"
                        class="bg-white hover:bg-blue-300 dark:hover:bg-blue-500  my-2 border shadow-md dark:bg-gray-700 p-4 rounded-xl shadow-sm hover:shadow-md transition duration-200">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="text-md font-semibold text-gray-800 dark:text-gray-100">#{{ event.id }} - {{
                                event.title
                            }}</h3>
                            <span class="text-sm font-medium px-2 py-1 rounded-full"
                                :class="event.attended ? 'bg-green-100 text-green-600 dark:bg-green-800 dark:text-green-300' : 'bg-red-100 text-red-600 dark:bg-red-800 dark:text-red-300'">
                                {{ event.attended ? 'Atendido' : 'No Atendido' }}
                            </span>
                        </div>
                        <div class="text-sm text-gray-700 dark:text-gray-300 space-y-1">
                            <p><strong>Fecha:</strong> {{ event.date }}</p>
                            <p><strong>Hora:</strong> {{ event.starttime }} - {{ event.endtime }}</p>
                            <p><strong>Doctor:</strong> {{ event.doctor.name }} {{ event.last_name }}</p>
                        </div>
                        <div v-if="event.active" class="flex items-start gap-2">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" @click="AttendEvent(event)" :checked="event.attended"
                                    class="sr-only peer">
                                <div
                                    class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-500 peer-checked:bg-blue-500 dark:peer-checked:bg-blue-500">
                                </div>
                                <span v-if="event.attended"
                                    class="ms-3 text-sm font-medium text-green-300  ">Atendido</span>
                                <span v-else class="ms-3 text-sm font-medium text-red-300  ">No Atendido</span>
                            </label>
                        </div>
                        <div class=" flex  gap-2 ">
                            <Link v-if="event.active" :href="route('odontographs.edit', event)"
                                class="flex  ml-auto mt-2  gap-2 rounded-lg bg-yellow-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 sm:px-4">
                            <EditIcon />
                            </Link>
                            <DangerButton v-if="event.active" @click="deleteEvent(event)"
                                class="flex  mt-2  gap-2 rounded-lg bg-red-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 sm:px-4">
                                <DeleteIcon />
                            </DangerButton>
                            <button v-else @click="restoreEvent(event)"
                                class="flex  ml-auto mt-2  gap-2 rounded-lg bg-green-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 sm:px-4">
                                <RestoreIcon />
                            </button>

                        </div>
                    </div>
                </div>

            </div>
            <div class="my-2 mt-5 flex   gap-2 items-center">
                <div class="my-2 mt-5 flex items-center gap-2 ml-1">
                    <TeethIcon />
                    <h1 class="font-bold text-2xl">Odontogramas</h1>

                </div>
                <div class=" flex ml-auto gap-2 ">
                    <input @input="submitFilters()" v-model="filters.search" type="text" placeholder="Buscar..."
                        class="rounded-lg border-0 p-1.5 px-3 py-2 shadow-sm ring-1 ring-slate-300 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 lg:w-96 dark:bg-gray-800 dark:ring-slate-600" />
                    <button @click="toggleshowDeleted()"
                        class="flex justify-center gap-2 rounded-lg bg-red-600 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 sm:px-4">
                        <DeleteIcon /> Mostrar Eliminados
                    </button>
                    <Link :href="route('odontographs.create', patient)" v-if="patient.active"
                        class="flex justify-center gap-2 rounded-lg bg-blue-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500 sm:px-4">
                    <AddIcon /> Crear Odontograma
                    </Link>
                </div>
            </div>

            <div class="overflow-x-auto bg-gray-100 mt-5 dark:bg-gray-800 dark:text-gray-200 rounded-2xl p-5"
                v-for="item in odontograph" :key="item.id">

                <div class="flex items-center gap-2 font-bold h-12">
                    <h2 class="self-center mt-1"># - {{ item.id }}</h2>
                    <div class="flex items-center justify-center text-gray-500 dark:text-gray-200">
                        <DoctorIcon class="size-12 mb-3 text-blue-500" />
                    </div>
                    <span class="self-center text-gray-500 dark:text-gray-200 mt-1">
                        {{ item.doctor.name }} {{ item.doctor.last_name }}
                    </span>
                </div>

                <div class="mt-4 space-y-6">

                    <h3 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Procedimientos guardados:</h3>

                    <!-- Fila superior -->
                    <div class="grid grid-cols-16 gap-2 mb-4">
                        <div v-for="tooth in upperTeeth" :key="tooth"
                            class="border rounded p-2 text-center cursor-default" :class="{
                                'bg-blue-200 dark:bg-blue-700': item.data[tooth],
                                'hover:bg-blue-100 dark:hover:bg-blue-800': item.data[tooth]
                            }" @click="selectToothInView(item, tooth)" title="Click para ver detalles">
                            <div class="font-bold">{{ tooth }}</div>
                            <div class="text-xs text-gray-600 dark:text-gray-300 truncate">
                                <!-- Muestra zonas con procedimiento abreviados -->
                                <template v-if="item.data[tooth]">
                                    <span v-for="(proc, zone) in item.data[tooth]" :key="zone"
                                        class="mr-1 px-1 rounded text-white text-[10px] font-semibold" :class="{
                                            'bg-gray-900': zone.charAt(0) === 'O',
                                            'bg-red-500': zone.charAt(0) === 'D',
                                            'bg-green-500': zone.charAt(0) === 'M',
                                            'bg-yellow-500': zone.charAt(0) === 'L',
                                            'bg-purple-500': zone.charAt(0) === 'V',
                                        }">
                                        {{ zone.charAt(0) }}
                                    </span>
                                </template>
                                <template v-else>
                                    —
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Fila inferior -->
                    <div class="grid grid-cols-16 gap-2">
                        <div v-for="tooth in lowerTeeth" :key="tooth"
                            class="border rounded p-2 text-center cursor-default" :class="{
                                'bg-blue-200 dark:bg-blue-700': item.data[tooth],
                                'hover:bg-blue-100 dark:hover:bg-blue-800': item.data[tooth]
                            }" @click="selectToothInView(item, tooth)" title="Click para ver detalles">
                            <div class="font-bold">{{ tooth }}</div>
                            <div class="text-xs text-gray-600 dark:text-gray-300 truncate">
                                <template v-if="item.data[tooth]">
                                    <span v-for="(proc, zone) in item.data[tooth]" :key="zone"
                                        class="mr-1 px-4 rounded text-white bg-blue-600 dark:bg-blue-400 text-[10px] font-semibold"
                                        :class="{
                                            'bg-gray-900': zone.charAt(0) === 'O',
                                            'bg-red-500': zone.charAt(0) === 'D',
                                            'bg-green-500': zone.charAt(0) === 'M',
                                            'bg-yellow-500': zone.charAt(0) === 'L',
                                            'bg-purple-500': zone.charAt(0) === 'V',
                                        }">
                                        {{ zone.charAt(0) }}
                                    </span>
                                </template>
                                <template v-else>
                                    —
                                </template>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Modal o cuadro para mostrar detalles del diente seleccionado -->
                <div v-if="selectedToothInfo && selectedOdontograph && selectedOdontograph.id === item.id"
                    class="fixed inset-0  bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md max-w-lg  w-full">
                        <h2 class="text-2xl font-semibold mb-4">
                            {{ toothNames[selectedToothInfo.tooth] || selectedToothInfo.tooth }}
                        </h2>

                        <div class="space-y-2 text-gray-700 dark:text-gray-300 text-sm">
                            <div v-for="(proc, zone) in selectedToothInfo.zones" :key="zone"
                                class="flex justify-between">
                                <span class="font-bold flex gap-2 text-white p-2 px-4 rounded" :class="{
                                    'bg-gray-900': zone.charAt(0) === 'O',
                                    'bg-red-500': zone.charAt(0) === 'D',
                                    'bg-green-500': zone.charAt(0) === 'M',
                                    'bg-yellow-500': zone.charAt(0) === 'L',
                                    'bg-purple-500': zone.charAt(0) === 'V',
                                }">
                                    <TeethIcon /> {{ zone }}
                                </span>
                                <span>{{ proc }}</span>

                            </div>
                        </div>
                        <div class="flex justify-end">
                            <SecondaryButton class="mt-6  dark:text-white font-semibold py-2 px-4 rounded"
                                @click="closeToothDetail">
                                Cerrar
                            </SecondaryButton>
                        </div>

                    </div>
                </div>





                <div class=" flex  gap-2 mt-4 ">
                    <h2 class="text-sm text-gray-400 my-2">Fecha de Creación - {{ formatDate(item.created_at)
                    }} </h2>
                    <Link v-if="item.active" :href="route('odontographs.edit', item)"
                        class="flex  ml-auto gap-2 rounded-lg bg-yellow-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-500 sm:px-4">
                    <EditIcon />
                    </Link>
                    <DangerButton v-if="item.active" @click="deleteOdontograph(item)"
                        class="flex   gap-2 rounded-lg bg-red-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 sm:px-4">
                        <DeleteIcon />
                    </DangerButton>
                    <button v-else @click="restoreOdontograph(item)"
                        class="flex  ml-auto  gap-2 rounded-lg bg-green-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-500 sm:px-4">
                        <RestoreIcon />
                    </button>
                </div>

            </div>
            <div v-if="!odontograph.length" class="text-center text-gray-500 dark:text-gray-400 p-2 py-8 w-full">
                No hay registros disponibles.
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Breadcrumb from '@/Components/BreadCrumb.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Link } from '@inertiajs/vue3';
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

export default {
    props: {
        patient: Object,
        odontograph: Object,
        filters: Object,
        events: Object,
        budgets: Object
    },
    components: {
        AuthenticatedLayout,
        Breadcrumb,
        PrimaryButton,
        DangerButton,
        Link,
        EditIcon,
        AddIcon,
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
        SecondaryButton
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
            selectedOdontograph: null,
            selectedToothInfo: null,
            toothNames: {
                11: "Incisivo central sup. derecho",
                12: "Incisivo lateral sup. derecho",
                13: "Canino sup. derecho",
                14: "1er premolar sup. derecho",
                15: "2do premolar sup. derecho",
                16: "1er molar sup. derecho",
                17: "2do molar sup. derecho",
                18: "3er molar sup. derecho",
                21: "Incisivo central sup. izquierdo",
                22: "Incisivo lateral sup. izquierdo",
                23: "Canino sup. izquierdo",
                24: "1er premolar sup. izquierdo",
                25: "2do premolar sup. izquierdo",
                26: "1er molar sup. izquierdo",
                27: "2do molar sup. izquierdo",
                28: "3er molar sup. izquierdo",
                41: "Incisivo central inf. derecho",
                42: "Incisivo lateral inf. derecho",
                43: "Canino inf. derecho",
                44: "1er premolar inf. derecho",
                45: "2do premolar inf. derecho",
                46: "1er molar inf. derecho",
                47: "2do molar inf. derecho",
                48: "3er molar inf. derecho",
                31: "Incisivo central inf. izquierdo",
                32: "Incisivo lateral inf. izquierdo",
                33: "Canino inf. izquierdo",
                34: "1er premolar inf. izquierdo",
                35: "2do premolar inf. izquierdo",
                36: "1er molar inf. izquierdo",
                37: "2do molar inf. izquierdo",
                38: "3er molar inf. izquierdo",
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
        deleteBudgetDetail(id) {
            this.$inertia.delete(route('budgetDetails.destroy', id));
        },
        restoreBudgetDetail(id) {
            this.$inertia.put(route('budgetDetails.update', id),
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
</style>
