<script setup>
import { ref, watch } from 'vue';
import AccessGate from './AccessGate.vue';
import AddIcon from './Icons/AddIcon.vue';
import UserIcon from './Icons/UserIcon.vue';
import { Link } from '@inertiajs/vue3';
import axios from "axios";

const props = defineProps({
    message: String,
    roles: Object,
    filters: Object,
});

const emit = defineEmits(['selected', 'confirm']);

const roles = ref(props.roles);
const searchTerm = ref(props.filters?.name ?? '');

const selectedRoles = ref([]);
const selectedRoleObjects = ref([]);


const selectRole = (role) => {
    const exists = selectedRoles.value.includes(role.id);

    if (exists) {
        selectedRoles.value = selectedRoles.value.filter(id => id !== role.id);
        selectedRoleObjects.value = selectedRoleObjects.value.filter(b => b.id !== role.id);
    } else {
        selectedRoles.value.push(role.id);
        selectedRoleObjects.value.push(role);
    }

    emit('selected', {
        ids: selectedRoles.value,
        objects: selectedRoleObjects.value
    });
};


const fetchRoles = async (pageUrl = null) => {
    try {
        const url = pageUrl || route('roles.filter');
        const response = await axios.get(url, {
            params: { filters: { name: searchTerm.value } },
        });
        roles.value = response.data.roles;
    } catch (error) {
        console.error(error);
    }
};


watch(searchTerm, () => {
    fetchRoles(route('roles.filter'));
});
</script>


<template>
    <div class="my-4 mx-4 lg:mx-10">
        <!-- Barra de búsqueda -->
        <div class="flex justify-between gap-4">
            <input v-model="searchTerm" type="text" placeholder="Buscar sucursales por nombre..."
                class="mb-4 w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 dark:bg-gray-800 dark:text-white" />


        </div>

        <!-- Tabla -->
        <div class="relative overflow-x-auto border border-gray-200 dark:border-gray-700/60 rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-100">
                    <tr>
                        <th class="px-4 py-3 hidden sm:table-cell">#</th>
                        <th class="px-6 py-3">Nombre</th>


                    </tr>
                </thead>
                <tbody>
                    <tr v-for="role in roles.data" :key="role.id" class="cursor-pointer" :class="{
                        'bg-pink-500 text-white': selectedRoles.includes(role.id),
                        'hover:bg-gray-200 dark:hover:bg-gray-700': !selectedRoles.includes(role.id)
                    }" @click="selectRole(role)">
                        <td class="p-4 hidden sm:table-cell">{{ role.id }}</td>
                        <td class="p-4">{{ role.name.toUpperCase() }} </td>

                    </tr>
                </tbody>
            </table>

            <div v-if="!roles.data.length" class="text-center text-gray-500 dark:text-gray-400 py-4 w-full">
                No hay registros disponibles.
            </div>

            <!-- Paginación LOCAL -->
            <div v-if="roles.last_page > 1" class="flex justify-center items-center gap-2 py-3">
                <button :disabled="!roles.prev_page_url" @click="fetchRoles(roles.prev_page_url)"
                    class="px-3 py-1 border rounded disabled:opacity-50">
                    Anterior
                </button>

                <span class="text-sm text-gray-600 dark:text-gray-300">
                    Página {{ roles.current_page }} de {{ roles.last_page }}
                </span>

                <button :disabled="!roles.next_page_url" @click="fetchRoles(roles.next_page_url)"
                    class="px-3 py-1 border rounded disabled:opacity-50">
                    Siguiente
                </button>
            </div>
        </div>
        <div class="flex justify-between">
            <h2 v-if="selectedRoles.length" class="text-sm text-green-500 mt-4">
                <span v-if="selectedRoles.length === 1">
                    Sucursal seleccionada:
                    <strong>{{ selectedRoleObjects[0].name }}</strong>
                </span>

                <span v-else>
                    {{ selectedRoles.length }} sucursales seleccionadas
                </span>
            </h2>
            <button class="mt-2 px-4 py-2 bg-pink-500 text-white rounded-lg" @click="$emit('confirm')">
                Confirmar selección
            </button>

        </div>


    </div>
</template>
