<script setup>
import { ref, computed } from 'vue';
import Pagination from './Pagination.vue';
import UserIcon from './Icons/UserIcon.vue';
import AddIcon from './Icons/AddIcon.vue';
import { Link } from '@inertiajs/vue3';
import AccessGate from './AccessGate.vue';

const props = defineProps({
    message: String,
    users: Object,
});

const emit = defineEmits(['selected']);

const selectedUser = ref(null);
const searchTerm = ref('');

const selecteUser = (user) => {
    if (selectedUser.value === user.id) {
        selectedUser.value = null;
        emit('selected', null);
    } else {
        selectedUser.value = user.id;
        emit('selected', user);
    }
};


const filteredUsers = computed(() => {
    if (!searchTerm.value.trim()) return props.users.data;
    return props.users.data.filter((user) =>
        `${user.name} ${user.last_name}`.toLowerCase().includes(searchTerm.value.toLowerCase())
    );
});
</script>

<template>
    <div class="my-4 mx-4 lg:mx-10">
        <!-- Barra de búsqueda -->
        <div class="flex justify-between gap-4">
            <input v-model="searchTerm" type="text" placeholder="Buscar usuario por nombre..."
                class="mb-4 w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white" />
            <AccessGate role="admin">
                <Link :href="route('users.create')" as="button"
                    class="flex justify-center mb-3 rounded-lg bg-green-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-500 sm:px-4">
                <AddIcon class="size-6" />

                </Link>
            </AccessGate>
        </div>
        <!-- Tabla de usuarios -->
        <div class="relative overflow-x-auto border border-gray-200 dark:border-gray-700/60 rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-100">
                    <tr>
                        <th scope="col" class="px-4 py-3 hidden sm:table-cell">#</th>
                        <th scope="col" class="px-6 py-3">Nombre</th>
                        <th scope="col" class="px-6 py-3">Rol</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in filteredUsers" :key="user.id" class="cursor-pointer" :class="{
                        'bg-blue-500 text-gray-900 dark:bg-blue-500 dark:text-white': selectedUser === user.id,
                        'hover:bg-gray-200 hover:text-gray-800': selectedUser !== user.id
                    }" @click="selecteUser(user)">
                        <td class="p-4 hidden sm:table-cell">{{ user.id }}</td>
                        <td class="p-4">{{ user.name }} {{ user.last_name }}</td>
                        <td class="p-4">
                            <span
                                class="inline-flex items-center gap-1 bg-blue-200 text-blue-800 text-xs font-semibold px-5 py-3.5 rounded-xl">
                                <UserIcon class="w-4 h-4" />
                                {{ user.roles.length > 0 ? user.roles[0].name : 'Sin rol' }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div v-if="!filteredUsers.length" class="text-center text-gray-500 dark:text-gray-400 py-4 w-full">
                No hay registros disponibles.
            </div>

            <Pagination :pagination="users" />
        </div>

        <!-- Indicador de usuario seleccionado -->
        <h2 v-if="selectedUser" class="text-sm text-green-500 mt-2">
            Usuario Seleccionado
        </h2>
    </div>
</template>
