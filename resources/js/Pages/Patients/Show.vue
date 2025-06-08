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
                <div v-if="patient.active" class=" flex ml-auto gap-2 ">
                    <Link :href="route('patients.edit', patient.id)"
                        class="flex justify-center gap-2 rounded-lg bg-green-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-500 sm:px-4">
                    <PrintIcon /> Imprimir
                    </Link>
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
                    <div class="my-2  flex items-center gap-2">
                        <DocumentMoney class="w-6 h-6 text-blue-600 dark:text-blue-400 mb-1" />
                        <h2 class="text-lg font-semibold ">Presupuestos</h2>

                    </div>
                    <div v-for="event in events" :key="event.id"
                        class="bg-white  my-2 border shadow-md dark:bg-gray-700 p-4 rounded-xl shadow-sm hover:shadow-md transition duration-200">
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
                <!-- Citas Pendientes -->
                <div class="bg-gray-100 dark:bg-gray-800 p-6 rounded-2xl shadow-md">
                    <div class="mb-4 flex items-center gap-2">
                        <CalendarIcon class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Citas Pendientes</h2>
                    </div>

                    <div v-for="event in events" :key="event.id"
                        class="bg-white  my-2 border shadow-md dark:bg-gray-700 p-4 rounded-xl shadow-sm hover:shadow-md transition duration-200">
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
            <div class="my-2 mt-5 flex  gap-2 items-center">
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

            <div class="overflow-x-auto bg-gray-100 mt-5 dark:bg-gray-800 dark:text-gray-200  rounded-2xl p-5"
                v-for="item in odontograph" :key="item.id">
                <div class="flex items-center gap-2 font-bold h-12">
                    <h2 class="self-center"># - {{ item.id }}</h2>
                    <div class="flex items-center justify-center text-gray-500 dark:text-gray-200">
                        <DoctorIcon class="size-12 mb-2" />
                    </div>
                    <span class="self-center text-gray-500 dark:text-gray-200">{{ item.doctor.name }} {{
                        item.doctor.last_name }}</span>
                </div>
                    18
                <Molar/>

                <div class=" flex  gap-2 ">
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
import { useToast } from 'vue-toastification';
import DangerButton from '@/Components/DangerButton.vue';
import DoctorIcon from '@/Components/Icons/DoctorIcon.vue';
import UserIcon from '@/Components/Icons/UserIcon.vue';
import DocumentMoney from '@/Components/Icons/DocumentMoney.vue';
import { markRaw } from 'vue';
import RestoreIcon from '@/Components/Icons/RestoreIcon.vue';
import CalendarIcon from '@/Components/Icons/CalendarIcon.vue';
import MedicalHistoryIcon from '@/Components/Icons/MedicalHistoryIcon.vue';
import Molar from '@/Components/Icons/Teeths/Molar.vue';
const toast = useToast();
export default {
    props: {
        patient: Object,
        odontograph: Object,
        filters: Object,
        events: Object
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
        Molar
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
            ]
        };

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
        deletePatient(id) {
            this.$inertia.delete(route('patients.destroy', id),);
        },
        restorePatient() {
            this.$inertia.put(route('patients.update', this.patient.id), {
                active: true
            },
            );
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

    },
};
</script>




<style scoped>
p span:first-child {
    color: #6b7280;
}
</style>
