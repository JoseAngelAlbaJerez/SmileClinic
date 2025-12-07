<template>

    <Head title="Usuario" />
    <AuthenticatedLayout>
        <template #header>
            <Breadcrumb :crumbs="crumbs" />
        </template>
        <template #default>
            <div class="container mx-auto mb-5 dark:text-white bg-white dark:bg-gray-700 mt-5   rounded-2xl shadow-md">
                <!-- Header Section -->
                <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">

                        <!-- Header -->
                        <div
                            class="p-6 bg-pink-500  dark:bg-pink-600 rounded-t-2xl text-white shadow-inner flex justify-between items-center gap-3">
                            <div>
                                <h2 class="text-2xl font-bold">Perfil de Usuario</h2>
                                <p class="text-pink-100 text-sm mt-0.5">Gestione la información de su perfil y
                                    preferencias</p>
                            </div>


                            <!-- Botones -->
                            <div class="flex justify-end gap-3  ">

                                <template v-if="user.active">

                                    <AccessGate permission="user.delete">
                                        <DangerButton @click="deleteUser(user.id)"
                                            class="flex items-center gap-2 bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg text-white shadow">
                                            <DeleteIcon />
                                        </DangerButton>
                                    </AccessGate>

                                </template>

                                <template v-else>
                                    <PrimaryButton @click="restoreUser(user.id)"
                                        class="flex items-center gap-2 bg-green-500 hover:bg-green-600 px-4 py-2 rounded-lg text-white shadow">
                                        <RestoreIcon /> Restaurar
                                    </PrimaryButton>
                                </template>

                            </div>
                        </div>

                        <!-- Profile Overview Card -->
                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden  border border-gray-200 dark:border-gray-700">


                            <div class="p-6">
                                <div class="flex flex-col items-center gap-6 md:flex-row md:items-start">
                                    <div class="relative">
                                        <div
                                            class="w-24 h-24 overflow-hidden border-4 border-white dark:border-gray-800 rounded-full shadow-lg">
                                            <img :src="user.avatar ? `/storage/${user.avatar}` : 'https://cdn-icons-png.flaticon.com/512/219/219983.png'"
                                                alt="user" class="w-full h-full object-cover">
                                        </div>
                                        <button @click="openEditModal('profile')"
                                            class="absolute bottom-0 right-0 p-1.5 bg-pink-500 rounded-full text-white shadow-md hover:bg-pink-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="flex-1 text-center md:text-left">
                                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90 mb-2">{{
                                            user.first_name
                                        }} {{
                                                user.last_name }}</h2>

                                        <div class="flex flex-col items-center gap-2 md:flex-row md:gap-4">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200">
                                                {{ user.roles[0]?.name || 'Sin rol asignado' }}
                                            </span>

                                            <span v-if="user.specialty"
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                                {{ user.specialty }}
                                            </span>
                                        </div>

                                        <div class="mt-4 flex flex-wrap gap-4 text-sm text-gray-600 dark:text-gray-400">
                                            <span class="flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                                {{ user.email }}
                                            </span>

                                            <span v-if="user.phone_number" class="flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                </svg>
                                                {{ user.phone_number }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">
                                <!-- Personal Information Card -->
                                <div
                                    class="bg-white dark:bg-gray-800  m-5 rounded-xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700">
                                    <div
                                        class="  dark:bg-gray-900 px-6  py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Información
                                            Personal</h3>
                                        <button @click="openEditModal('personal')"
                                            class="p-1.5 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="p-6">
                                        <div class="space-y-4">
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                <div>
                                                    <p
                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                        Nombre</p>
                                                    <p
                                                        class="text-sm font-medium text-gray-800 dark:text-white/90 mt-1">
                                                        {{
                                                            user.first_name }}
                                                    </p>
                                                </div>

                                                <div>
                                                    <p
                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                        Apellido</p>
                                                    <p
                                                        class="text-sm font-medium text-gray-800 dark:text-white/90 mt-1">
                                                        {{
                                                            user.last_name }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div>
                                                <p
                                                    class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                    Correo
                                                    Electrónico</p>
                                                <p class="text-sm font-medium text-gray-800 dark:text-white/90 mt-1">{{
                                                    user.email
                                                    }}
                                                </p>
                                            </div>

                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                <div>
                                                    <p
                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                        DNI</p>
                                                    <p
                                                        class="text-sm font-medium text-gray-800 dark:text-white/90 mt-1">
                                                        {{
                                                            user.DNI ||
                                                            'No proporcionado' }}</p>
                                                </div>

                                                <div>
                                                    <p
                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                        Dirección</p>
                                                    <p
                                                        class="text-sm font-medium text-gray-800 dark:text-white/90 mt-1">
                                                        {{
                                                            user.address || 'No especificado' }}</p>
                                                </div>

                                            </div>

                                            <div>
                                                <p
                                                    class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                    Especialidad</p>
                                                <p class="text-sm font-medium text-gray-800 dark:text-white/90 mt-1">{{
                                                    user.specialty
                                                    || 'No especificada' }}</p>
                                            </div>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                <div>
                                                    <p
                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                        Teléfono</p>
                                                    <p
                                                        class="text-sm font-medium text-gray-800 dark:text-white/90 mt-1">
                                                        {{
                                                            user.phone_number ||
                                                            'No proporcionado' }}</p>
                                                </div>

                                                <div>
                                                    <div class="flex">
                                                        <p
                                                            class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                            Sucursales Disponibles</p><button
                                                            @click="openBranchModal('personal')"
                                                            class="ml-1  text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                    </div>

                                                    <div v-if="user.branches" v-for="branch in user.branches"
                                                        :key="branch.id">
                                                        <li
                                                            class="text-sm ml-2 font-medium text-gray-800 dark:text-white/90 mt-1">
                                                            {{
                                                                branch.name }}</li>
                                                    </div>
                                                    <div v-else>
                                                        <p
                                                            class="text-sm font-medium text-gray-800 dark:text-white/90 mt-1">
                                                            {{
                                                                'No especificado' }}</p>
                                                    </div>

                                                </div>

                                            </div>

                                        </div>
                                    </div>

                                </div>
                                <!-- Password Update Card -->
                                <div
                                    class="bg-white m-5 dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700 mt-6">
                                    <div
                                        class=" bg-white   dark:bg-gray-900 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Seguridad de
                                            la
                                            Cuenta</h3>
                                    </div>

                                    <div class="p-6">
                                        <UpdatePasswordForm class="max-w-xl" />
                                    </div>
                                </div>


                            </div>
                        </div>






                        <!-- Edit Profile Modal -->
                        <Modal :show="showModal" @close="closeModal" maxWidth="2xl">
                            <div class="p-6">
                                <div class="flex justify-between items-center mb-6">
                                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white/90">
                                        {{ modalTitle }}
                                    </h3>
                                    <button @click="closeModal"
                                        class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-lg p-1 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12">
                                            </path>
                                        </svg>
                                    </button>
                                </div>

                                <form @submit.prevent="saveChanges">
                                    <div class="space-y-6">
                                        <!-- Profile Edit Form -->
                                        <div v-if="activeModal === 'profile'" class="space-y-6">


                                            <div class="flex flex-col justify-center items-center space-y-4">
                                                <div
                                                    class="w-40 h-40 rounded-full overflow-hidden border border-gray-300 dark:border-gray-600 shadow-sm">
                                                    <img :src="form.avatar ? `/storage/${form.avatar}` : 'https://cdn-icons-png.flaticon.com/512/219/219983.png'"
                                                        alt="Avatar" class="w-full h-full object-cover">
                                                </div>

                                                <input type="file" @change="handleAvatarChange" class="hidden"
                                                    ref="avatarInput" accept="image/*">

                                                <button type="button" @click="$refs.avatarInput.click()"
                                                    class="px-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                                    Cambiar Imagen
                                                </button>
                                            </div>




                                        </div>

                                        <!-- Personal Info Edit Form -->
                                        <div v-if="activeModal === 'personal'" class="space-y-6">
                                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                                <div>
                                                    <label
                                                        class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">
                                                        Nombre
                                                    </label>
                                                    <input v-model="form.first_name" type="text"
                                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-pink-500 focus:ring-pink-500 dark:focus:ring-pink-400">
                                                </div>

                                                <div>
                                                    <label
                                                        class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">
                                                        Apellido
                                                    </label>
                                                    <input v-model="form.last_name" type="text"
                                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-pink-500 focus:ring-pink-500 dark:focus:ring-pink-400">
                                                </div>
                                            </div>

                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">
                                                    Correo Electrónico
                                                </label>
                                                <input v-model="form.email" type="email"
                                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-pink-500 focus:ring-pink-500 dark:focus:ring-indigo-400">
                                            </div>

                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">
                                                    Teléfono
                                                </label>
                                                <input v-model="form.phone_number" type="tel"
                                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                                            </div>

                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">
                                                    Especialidad
                                                </label>
                                                <input v-model="form.specialty" type="text"
                                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                                            </div>

                                        </div>


                                    </div>

                                    <div class="mt-8 flex justify-end space-x-3">
                                        <button type="button" @click="closeModal"
                                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                            Cancelar
                                        </button>
                                        <button type="submit"
                                            class="px-4 py-2 text-sm font-medium text-white bg-pink-600 border border-transparent rounded-md shadow-sm hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 transition-colors">
                                            Guardar Cambios
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </Modal>
                        <Modal :show="showBranchModal" @close="showBranchModal = false" maxWidth="2xl">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-6">
                                    <h3 class="text-xl font-bold text-gray-800 dark:text-white">
                                        <UserIcon class="h-6 w-6 inline-block mr-2 text-pink-500" />
                                        Seleccionar Sucursales
                                    </h3>
                                    <button @click="showBranchModal = false" class="text-gray-400 hover:text-gray-500">
                                        <XIcon class="h-6 w-6" />
                                    </button>
                                </div>
                                <BranchSelector :branches="branches" @selected="setBranches"
                                    @confirm="showBranchModal = false, saveChanges()" />
                            </div>
                        </Modal>
                        <Modal :show="showRoleModal" @close="showRoleModal = false" maxWidth="2xl">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-6">
                                    <h3 class="text-xl font-bold text-gray-800 dark:text-white">
                                        <UserIcon class="h-6 w-6 inline-block mr-2 text-pink-500" />
                                        Seleccionar Roles
                                    </h3>
                                    <button @click="showRoleModal = false" class="text-gray-400 hover:text-gray-500">
                                        <XIcon class="h-6 w-6" />
                                    </button>
                                </div>
                                <RoleSelector :roles='roles' @selected="setRoles" @confirm="showRoleModal = false" />
                            </div>
                        </Modal>
                    </div>
                </div>
            </div>
        </template>

    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, markRaw } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Inertia } from '@inertiajs/inertia';
import Breadcrumb from '@/Components/BreadCrumb.vue';
import Modal from '@/Components/Modal.vue';
import UpdatePasswordForm from '../Profile/Partials/UpdatePasswordForm.vue';
import { useToast } from 'vue-toastification';
import UserIcon from '@/Components/Icons/UserIcon.vue';
import DeleteIcon from '@/Components/Icons/DeleteIcon.vue';
import AccessGate from '@/Components/AccessGate.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import EditIcon from '@/Components/Icons/EditIcon.vue';
import DangerButton from '@/Components/DangerButton.vue';
import RestoreIcon from '@/Components/Icons/RestoreIcon.vue';
import RoleSelector from '@/Components/RoleSelector.vue';
import BranchSelector from '@/Components/BranchSelector.vue';
import XIcon from '@/Components/Icons/XIcon.vue';
const toast = useToast();

const props = defineProps({
    user: {
        type: Object,
        required: true
    },
    branches: {
        type: Object,
        required: true
    },
    roles: {
        type: Object,
        required: true
    },
});
const crumbs = [
    { icon: markRaw(UserIcon), label: 'Usuarios', to: route('users.index') },
    { label: props.user.first_name + ' ' + props.user.last_name }
];
const showModal = ref(false);
const showBranchModal = ref(false);
const showRoleModal = ref(false);

const activeModal = ref('');
const isLoading = ref(false);
const avatarFile = ref(null);

const form = ref({
    first_name: props.user.first_name,
    last_name: props.user.last_name,
    email: props.user.email,
    phone_number: props.user.phone_number,
    position: props.user.position,
    specialty: props.user.specialty,
    avatar: props.user.avatar,
    address: props.user.address,
    roles: props.user.roles,
    branches: props.user.branches
});
const selectedBranchObjects = [];

const form_notes = ref({
    description: props.user.notes?.description,
    created_at: props.user.notes?.created_at,
    updated_at: props.user.notes?.updated_at,
});

const socialLinks = [
    { icon: "M11.6666 11.2503H13.7499L14.5833 7.91699H11.6666V6.25033C11.6666 5.39251 11.6666 4.58366 13.3333 4.58366H14.5833V1.78374C14.3118 1.7477 13.2858 1.66699 12.2023 1.66699C9.94025 1.66699 8.33325 3.04771 8.33325 5.58342V7.91699H5.83325V11.2503H8.33325V18.3337H11.6666V11.2503Z" },
    { icon: "M15.1708 1.875H17.9274L11.9049 8.75833L18.9899 18.125H13.4424L9.09742 12.4442L4.12578 18.125H1.36745L7.80912 10.7625L1.01245 1.875H6.70078L10.6283 7.0675L15.1708 1.875ZM14.2033 16.475H15.7308L5.87078 3.43833H4.23162L14.2033 16.475Z" },
    { icon: "M5.78381 4.16645C5.78351 4.84504 5.37181 5.45569 4.74286 5.71045C4.11391 5.96521 3.39331 5.81321 2.92083 5.32613C2.44836 4.83904 2.31837 4.11413 2.59216 3.49323C2.86596 2.87233 3.48886 2.47942 4.16715 2.49978C5.06804 2.52682 5.78422 3.26515 5.78381 4.16645ZM5.83381 7.06645H2.50048V17.4998H5.83381V7.06645ZM11.1005 7.06645H7.78381V17.4998H11.0672V12.0248C11.0672 8.97475 15.0422 8.69142 15.0422 12.0248V17.4998H18.3338V10.8914C18.3338 5.74978 12.4505 5.94145 11.0672 8.46642L11.1005 7.06645Z" },
    { icon: "M10.8567 1.66699C11.7946 1.66854 12.2698 1.67351 12.6805 1.68573L12.8422 1.69102C13.0291 1.69766 13.2134 1.70599 13.4357 1.71641C14.3224 1.75738 14.9273 1.89766 15.4586 2.10391C16.0078 2.31572 16.4717 2.60183 16.9349 3.06503C17.3974 3.52822 17.6836 3.99349 17.8961 4.54141C18.1016 5.07197 18.2419 5.67753 18.2836 6.56433C18.2935 6.78655 18.3015 6.97088 18.3081 7.15775L18.3133 7.31949C18.3255 7.73011 18.3311 8.20543 18.3328 9.1433L18.3335 9.76463C18.3336 9.84055 18.3336 9.91888 18.3336 9.99972L18.3335 10.2348L18.333 10.8562C18.3314 11.794 18.3265 12.2694 18.3142 12.68L18.3089 12.8417C18.3023 13.0286 18.294 13.213 18.2836 13.4351C18.2426 14.322 18.1016 14.9268 17.8961 15.458C17.6842 16.0074 17.3974 16.4713 16.9349 16.9345C16.4717 17.397 16.0057 17.6831 15.4586 17.8955C14.9273 18.1011 14.3224 18.2414 13.4357 18.2831C13.2134 18.293 13.0291 18.3011 12.8422 18.3076L12.6805 18.3128C12.2698 18.3251 11.7946 18.3306 10.8567 18.3324L10.2353 18.333C10.1594 18.333 10.0811 18.333 10.0002 18.333H9.76516L9.14375 18.3325C8.20591 18.331 7.7306 18.326 7.31997 18.3137L7.15824 18.3085C6.97136 18.3018 6.78703 18.2935 6.56481 18.2831C5.67801 18.2421 5.07384 18.1011 4.5419 17.8955C3.99328 17.6838 3.5287 17.397 3.06551 16.9345C2.60231 16.4713 2.3169 16.0053 2.1044 15.458C1.89815 14.9268 1.75856 14.322 1.7169 13.4351C1.707 13.213 1.69892 13.0286 1.69238 12.8417L1.68714 12.68C1.67495 12.2694 1.66939 11.794 1.66759 10.8562L1.66748 9.1433C1.66903 8.20543 1.67399 7.73011 1.68621 7.31949L1.69151 7.15775C1.69815 6.97088 1.70648 6.78655 1.7169 6.56433C1.75786 5.67683 1.89815 5.07266 2.1044 4.54141C2.3162 3.9928 2.60231 3.52822 3.06551 3.06503C3.5287 2.60183 3.99398 2.31641 4.5419 2.10391C5.07315 1.89766 5.67731 1.75808 6.56481 1.71641C6.78703 1.70652 6.97136 1.69844 7.15824 1.6919L7.31997 1.68666C7.7306 1.67446 8.20591 1.6689 9.14375 1.6671L10.8567 1.66699ZM10.0002 5.83308C7.69781 5.83308 5.83356 7.69935 5.83356 9.99972C5.83356 12.3021 7.69984 14.1664 10.0002 14.1664C12.3027 14.1664 14.1669 12.3001 14.1669 9.99972C14.1669 7.69732 12.3006 5.83308 10.0002 5.83308ZM10.0002 7.49974C11.381 7.49974 12.5002 8.61863 12.5002 9.99972C12.5002 11.3805 11.3813 12.4997 10.0002 12.4997C8.6195 12.4997 7.50023 11.3809 7.50023 9.99972C7.50023 8.61897 8.61908 7.49974 10.0002 7.49974ZM14.3752 4.58308C13.8008 4.58308 13.3336 5.04967 13.3336 5.62403C13.3336 6.19841 13.8002 6.66572 14.3752 6.66572C14.9496 6.66572 15.4169 6.19913 15.4169 5.62403C15.4169 5.04967 14.9488 4.58236 14.3752 4.58308Z" }
];
const formatDate = (date) => {
    return new Date(date).toLocaleDateString('es-DO', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};
const setBranches = (data) => {
    form.value.branches = data.ids;
    selectedBranchObjects.value = data.objects;
};
const openBranchModal = () => {
    showBranchModal.value = true;
};
const setRoles = (roleData) => {
    this.form.roles = roleData.ids
    this.selectedRoleObjects = roleData.objects
};
const openRoleModal = () => {
    showRoleModal = true;
};

const modalTitle = computed(() => {
    switch (activeModal.value) {
        case 'profile': return 'Editar Avatar';
        case 'personal': return 'Editar Información Personal';
        default: return 'Editar';
    }
});

const openEditModal = (type) => {
    activeModal.value = type;
    showModal.value = true;
};
const deleteUser = (id) => {
    Inertia.delete(route('users.destroy', id))
}

const restoreUser = () => {
    Inertia.put(route('users.update', props.user.id), {
        active: true,
    })
}
const submitNotes = () => {
    Inertia.put(route('users.update', props.user.id), {
        description: form_notes.value.description
    })
}


const closeModal = () => {
    showModal.value = false;
    form.value = {
        first_name: props.user.first_name,
        last_name: props.user.last_name,
        email: props.user.email,
        phone_number: props.user.phone_number,
        position: props.user.position,
        specialty: props.user.specialty,
        avatar: props.user.avatar,
        address: props.user.address,
        roles: props.user.roles,
        branches: props.user.branches
    };
    avatarFile.value = null;
};

const handleAvatarChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        if (!file.type.match('image.*')) {
            toast.error('Por favor, selecciona un archivo de imagen válido');
            return;
        }

        if (file.size > 2 * 1024 * 1024) {
            toast.error('La imagen no debe exceder los 2MB');
            return;
        }

        avatarFile.value = file;

        const reader = new FileReader();
        reader.onload = (e) => {
            form.value.avatar = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const saveChanges = async () => {
    isLoading.value = true;
    try {
        const formData = new FormData();

        Object.keys(form.value).forEach(key => {
            if (key !== 'avatar' || key !== 'branches') {
                formData.append(key, form.value[key]);
            }
        });



        if (avatarFile.value) {
            formData.append('avatar', avatarFile.value);
        }

        formData.append('_method', 'PUT');

        await router.post(route('users.update', { user: props.user.id }), formData, {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Usuario actualizado correctamente');
                closeModal();
            },
            onError: (errors) => {
                Object.values(errors).forEach(error => toast.error(error));
            },
        });

    } catch (error) {
        console.error('Error al actualizar el usuario:', error);
        toast.error('Ocurrió un error al actualizar el usuario');
    } finally {
        isLoading.value = false;
    }
};
</script>
