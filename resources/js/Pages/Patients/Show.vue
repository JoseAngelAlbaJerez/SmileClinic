<template>
    <AuthenticatedLayout>
        <template #header>
            <Breadcrumb :crumbs="crumbs" />
        </template>

        <div class="container mx-auto px-6 py-4  dark:text-white bg-white dark:bg-gray-800 mt-5  pb-8  rounded-2xl shadow-md">
            <div class="my-2 mt-5 flex  gap-2 items-center">
                  <h2 class="text-lg font-semibold ml-1"> Historial Médico de {{ patient.first_name }} {{ patient.last_name }}</h2>
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
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Información personal -->
                <div class="bg-white brightness-95 dark:bg-gray-700 p-6   rounded-2xl shadow-md">
                    <div class="my-2  flex items-center gap-2">
                        <UserIcon />
                        <h2 class="text-lg font-semibold ">Información Personal</h2>

                    </div>

                    <div class="space-y-2 text-sm">
                        <p><span class="font-medium">Nombres:</span> {{ patient.first_name }} {{ patient.last_name }}</p>
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
                <div class="bg-white brightness-95 dark:bg-gray-700 p-6 rounded-2xl shadow-md">
                    <h2 class="text-lg font-semibold mb-4">Antecedentes Médico</h2>
                    <div class="space-y-2 text-sm">
                        <p>
                            <span class="font-medium">¿Complicaciones?</span>
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
                <div class="bg-white brightness-95 dark:bg-gray-700 p-6 rounded-2xl shadow-md">
                    <h2 class="text-lg font-semibold mb-4">Presupuestos</h2>
                    <div class="space-y-2 text-sm">
                        <p>
                            <span class="font-medium">¿Complicaciones?</span>
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
                <!-- Información Financiera -->
                <div class="bg-white brightness-95 dark:bg-gray-700 p-6   rounded-2xl shadow-md">
                    <div class="my-2  flex items-center gap-2">
                        <DocumentMoney />
                        <h2 class="text-lg font-semibold ">Información Personal</h2>

                    </div>
                    <div class="space-y-2 text-sm">
                        <p>
                            <span class="font-medium">¿Complicaciones?</span>
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
            </div>
            <div class="my-2 mt-5 flex  gap-2 items-center">
                <div class="my-2 mt-5 flex items-center gap-2 ml-1">
                    <TeethIcon />
                    <h1 class="font-bold text-2xl">Odontogramas</h1>

                </div>
                <div class=" flex ml-auto gap-2 ">
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

            <div class="overflow-x-auto bg-white brightness-95 mt-5 dark:bg-gray-700 dark:text-gray-200  rounded-2xl p-5"
                v-for="odontographs in odontograph">
                <div class="flex items-center gap-2 font-bold h-12">
                    <h2 class="self-center"># - {{ odontographs.id }}</h2>
                    <div class="flex items-center justify-center text-gray-500 dark:text-gray-200">
                        <DoctorIcon class="size-12 mb-2" />
                    </div>
                    <span class="self-center text-gray-500 dark:text-gray-200">{{ odontographs.doctor.first_name }} {{
                        odontographs.doctor.last_name }}</span>
                </div>

                <div class="flex justify-center space-x-4 py-2 w-full max-w-3xl mx-auto">

                    <!-- Fila Superior -->
                    <div v-for="toothId in toothUPOrder" :key="toothId"
                        class="flex flex-col items-center justify-center bg-white  dark:text-gray-500 p-3 border rounded-md w-20">
                        <div class="text-xs font-bold p-2">{{ toothId }}</div>
                        <div class="flex flex-wrap justify-center items-center text-[10px] gap-1 mt-1">
                            <!-- estados -->
                            <span v-if="hasCavities(odontographs.Cavities)"
                                class="bg-red-100 text-red-700 px-1 rounded">C</span>
                            <span v-if="data[toothId]?.Crown"
                                class="bg-yellow-100 text-yellow-700 px-1 rounded">Cr</span>
                            <span v-if="data[toothId]?.Extract"
                                class="bg-purple-100 text-purple-700 px-1 rounded">Ex</span>
                            <span v-if="data[toothId]?.Filter" class="bg-blue-100 text-blue-700 px-1 rounded">F</span>
                            <span v-if="data[toothId]?.Fracture"
                                class="bg-pink-100 text-pink-700 px-1 rounded">Fr</span>
                            <span v-if="data[toothId]?.Missing" class="bg-gray-300 text-gray-900 px-1 rounded">A</span>
                        </div>
                    </div>
                </div>
                <hr class="bg-gray-500 ">
                <div class="flex justify-center space-x-4 py-2 w-full max-w-3xl mx-auto">
                    <!-- Fila Inferior -->
                    <div v-for="toothId in toothDownOrder" :key="toothId"
                        class="flex flex-col items-center justify-center bg-white  dark:text-gray-500 p-3 border rounded-md w-20">
                        <div class="text-xs font-bold p-2">{{ toothId }}</div>
                        <div class="flex flex-wrap justify-center items-center text-[10px] gap-1 mt-1">
                            <!-- estados -->
                            <span v-if="hasCavities(toothId)" class="bg-red-100 text-red-700 px-1 rounded">C</span>
                            <span v-if="data[toothId]?.Crown"
                                class="bg-yellow-100 text-yellow-700 px-1 rounded">Cr</span>
                            <span v-if="data[toothId]?.Extract"
                                class="bg-purple-100 text-purple-700 px-1 rounded">Ex</span>
                            <span v-if="data[toothId]?.Filter" class="bg-blue-100 text-blue-700 px-1 rounded">F</span>
                            <span v-if="data[toothId]?.Fracture"
                                class="bg-pink-100 text-pink-700 px-1 rounded">Fr</span>
                            <span v-if="data[toothId]?.Missing" class="bg-gray-300 text-gray-900 px-1 rounded">A</span>
                        </div>
                    </div>
                </div>
                <div class=" flex  gap-2 " v-if="patient.active">
                    <h2 class="text-sm text-gray-400 my-2">Fecha de Creación - {{ formatDate(odontographs.created_at)
                        }}</h2>
                    <Link :href="route('odontographs.edit', odontograph)"
                        class="flex  ml-auto gap-2 rounded-lg bg-yellow-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-500 sm:px-4">
                    <EditIcon />
                    </Link>
                    <DangerButton @click="deleteOdontograph(odontograph)"
                        class="flex   gap-2 rounded-lg bg-red-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 sm:px-4">
                    <DeleteIcon />
                    </DangerButton>
                </div>

            </div>
                <div v-if="!odontograph.length"
                                class="text-center text-gray-500 dark:text-gray-400 p-2 py-8 w-full">
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
const toast = useToast();
export default {
    props: {
        patient: Object,
        odontograph: Object,
        filters: Object,
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
        DocumentMoney
    },
    data() {
        return {
            form: {
                showDeleted: this.filters?.showDeleted || true,

            },
            toothUPOrder: [
                "18", "17", "16", "15", "14", "13", "12", "11",
                "55", "54", "53", "52", "51",
            ],
            toothDownOrder: ["21", "22", "23", "24", "25", "26", "27", "28", "61", "66", "63", "64", "65"],
            timeout: 3000,
             crumbs: [
                { icon: markRaw(UserIcon), label: 'Pacientes', to: route('patients.index') },
                { label: this.patient.first_name + this.patient.last_name }
            ]
        };

    },
    computed: {
        data() {
            return this.odontograph?.data || {};

        }
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
        hasCavities(toothId) {
            const cav = this.data[toothId]?.Cavities;
            if (!cav) return false;
            return cav.center || cav.top || cav.bottom || cav.left || cav.right;
        },
        deletePatient(id) {
            this.$inertia.delete(route('patients.destroy', id), {
                onSuccess: () => {
                    toast.success('Paciente desactivado correctamente.');
                },
                onError: () => {
                    toast.error('Hubo un error al desactivar el paciente.');
                }
            });
        },
        deleteOdontograph(id){
            this.$inertia.delete(route('odontographs.destroy', id), {
                onSuccess: () => {
                    toast.success('Odontograma desactivado correctamente.');
                },
                onError: () => {
                    toast.error('Hubo un error al desactivar el Odontograma.');
                }
            });
        },
        toggleshowDeleted() {

            this.form.showDeleted = !this.form.showDeleted;

            this.submitFilters();

        },
        submitFilters() {
            if (this.timeout) {
                clearTimeout(this.timeout);
            }

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
