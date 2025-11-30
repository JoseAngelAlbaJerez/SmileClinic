<template>

    <Head title="Presupuestos" />
    <AuthenticatedLayout>
        <template #header>
            <BreadCrumb :crumbs="crumbs" />
        </template>
        <template #default>
            <div
                class="container mx-auto px-6 py-4  dark:text-white bg-white dark:bg-gray-700 mt-5  pb-8  rounded-2xl shadow-md">
                <div
                    class="bg-pink-50 dark:bg-gray-800  p-6 rounded-xl shadow-md  border border-gray-200 dark:border-gray-700 mb-2">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between  gap-4">
                        <div>
                            <h1 class="text-lg font-bold text-gray-800 dark:text-white/90"> {{
                                branch.name }} <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <p><span class="font-semibold">Fecha de Creación: </span>{{ formatDate(branch.created_at) }}
                                    </p>
                                </div>

                            </h1>

                        </div>
                        <div v-if="branch.active" class=" flex ml-auto gap-2 mb-2 ">

                            <Link :href="route('branches.edit', branch)"
                                class="flex justify-center gap-2 rounded-lg bg-yellow-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-500 sm:px-4">
                            <EditIcon />
                            </Link>
                            <DangerButton @click="deleteBranch(branch.id)"
                                class="flex justify-center gap-2 rounded-lg bg-red-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 sm:px-4">
                                <DeleteIcon />
                            </DangerButton>
                        </div>
                        <div class="flex ml-auto gap-2" v-else>
                            <PrimaryButton @click="restoreBranch(branch.id)"
                                class="flex justify-center gap-2 rounded-lg bg-green-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-500 sm:px-4">
                                <RestoreIcon />
                            </PrimaryButton>
                        </div>
                    </div>
                </div>



            </div>

        </template>

    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import { Head, Link } from '@inertiajs/vue3';
import EditIcon from '@/Components/Icons/EditIcon.vue';
import DeleteIcon from '@/Components/Icons/DeleteIcon.vue';
import PrintIcon from '@/Components/Icons/PrintIcon.vue';
import UserIcon from '@/Components/Icons/UserIcon.vue';
import DoctorIcon from '@/Components/Icons/DoctorIcon.vue';
import BuildingIcon from '@/Components/Icons/BuildingIcon.vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import DocumentIcon from '@/Components/Icons/DocumentIcon.vue';
import { markRaw } from 'vue';
import BreadCrumb from '@/Components/BreadCrumb.vue';
import RestoreIcon from '@/Components/Icons/RestoreIcon.vue';



export default {
    props: {
        branch: Object
    },
    components: {
        AuthenticatedLayout,
        BreadCrumb,
        PrimaryButton,
        DangerButton,
        Link,
        EditIcon,
        DeleteIcon,
        PrintIcon,
        UserIcon,
        DoctorIcon,
        BuildingIcon,
        VueDatePicker,
        DocumentIcon,
        RestoreIcon,
        Head
    },
    data() {
        return {
            crumbs: [
                { icon: markRaw(BuildingIcon), label: 'Sucursales', to: route('branches.index') },
                { icon: markRaw(UserIcon), label: this.branch.name, to: route('branches.show', this.branch) },
                { icon: markRaw(DocumentIcon), label: this.branch.id },

            ],
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
        formatNumber(n) {
            return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
        deleteBranch(id) {
            this.$inertia.delete(route('branches.destroy', id),);
        },
        restoreBranch(id) {
            this.$inertia.put(route('branches.update', id), {
                active: true
            },
            );
        },

    }
}
</script>
