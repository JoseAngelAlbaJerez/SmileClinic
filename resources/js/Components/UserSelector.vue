<script setup>
import { ref, watch } from 'vue';
import AccessGate from './AccessGate.vue';
import AddIcon from './Icons/AddIcon.vue';
import UserIcon from './Icons/UserIcon.vue';
import { Link } from '@inertiajs/vue3';
import axios from "axios";

const props = defineProps({
    message: String,
    users: Object,   // listado inicial desde backend
    filters: Object, // filtros actuales
});

const emit = defineEmits(['selected']);

// Estado local
const users = ref(props.users);
const searchTerm = ref(props.filters?.name ?? '');
const selectedUserId = ref(null);

const selectUser = (user) => {
    if (selectedUserId.value === user.id) {
        selectedUserId.value = null;
        emit('selected', null);
    } else {
        selectedUserId.value = user.id;
        emit('selected', user);
    }
};

// Llamada al backend
const fetchUsers = async (pageUrl = null) => {
    try {
        const url = pageUrl || route('users.filter');
        const response = await axios.get(url, {
            params: { filters: { name: searchTerm.value } },
        });
        users.value = response.data.users;
    } catch (error) {
        console.error(error);
    }
};

// Resetear a página 1 cuando cambie búsqueda
watch(searchTerm, () => {
    fetchUsers(route('users.filter'));
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
                    class="flex justify-center mb-3 rounded-lg bg-green-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 sm:px-4">
                    <AddIcon class="size-6" />
                </Link>
            </AccessGate>
        </div>

        <!-- Tabla -->
        <div class="relative overflow-x-auto border border-gray-200 dark:border-gray-700/60 rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-100">
                    <tr>
                        <th class="px-4 py-3 hidden sm:table-cell">#</th>
                        <th class="px-6 py-3">Nombre</th>
                        <th class="px-6 py-3">Rol</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users.data" :key="user.id" class="cursor-pointer" :class="{
                        'bg-blue-500 text-white': selectedUserId === user.id,
                        'hover:bg-gray-200 dark:hover:bg-gray-700': selectedUserId !== user.id
                    }" @click="selectUser(user)">
                        <td class="p-4 hidden sm:table-cell">{{ user.id }}</td>
                        <td class="p-4">{{ user.name }} {{ user.last_name }}</td>
                        <td class="p-4">
                            <span class="inline-flex items-center gap-1 bg-blue-200 text-blue-800 text-xs font-semibold px-3 py-1 rounded-xl">
                                <UserIcon class="w-4 h-4" />
                                {{ user.roles.length > 0 ? user.roles[0].name : 'Sin rol' }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div v-if="!users.data.length" class="text-center text-gray-500 dark:text-gray-400 py-4 w-full">
                No hay registros disponibles.
            </div>

            <!-- Paginación LOCAL -->
            <div v-if="users.last_page > 1" class="flex justify-center items-center gap-2 py-3">
                <button
                    :disabled="!users.prev_page_url"
                    @click="fetchUsers(users.prev_page_url)"
                    class="px-3 py-1 border rounded disabled:opacity-50"
                >
                    Anterior
                </button>

                <span class="text-sm text-gray-600 dark:text-gray-300">
                    Página {{ users.current_page }} de {{ users.last_page }}
                </span>

                <button
                    :disabled="!users.next_page_url"
                    @click="fetchUsers(users.next_page_url)"
                    class="px-3 py-1 border rounded disabled:opacity-50"
                >
                    Siguiente
                </button>
            </div>
        </div>

        <!-- Indicador -->
        <h2 v-if="selectedUserId" class="text-sm text-green-500 mt-2">
            Usuario Seleccionado
        </h2>
    </div>
</template>
