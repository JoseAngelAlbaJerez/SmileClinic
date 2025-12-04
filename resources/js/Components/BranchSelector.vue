<script setup>
import { ref, watch } from 'vue';
import AccessGate from './AccessGate.vue';
import AddIcon from './Icons/AddIcon.vue';
import UserIcon from './Icons/UserIcon.vue';
import { Link } from '@inertiajs/vue3';
import axios from "axios";

const props = defineProps({
    message: String,
    branches: Object,
    filters: Object,
});

const emit = defineEmits(['selected', 'confirm']);

const branches = ref(props.branches);
const searchTerm = ref(props.filters?.name ?? '');

const selectedBranches = ref([]);
const selectedBranchObjects = ref([]);


const selectBranch = (branch) => {
    const exists = selectedBranches.value.includes(branch.id);

    if (exists) {
        selectedBranches.value = selectedBranches.value.filter(id => id !== branch.id);
        selectedBranchObjects.value = selectedBranchObjects.value.filter(b => b.id !== branch.id);
    } else {
        selectedBranches.value.push(branch.id);
        selectedBranchObjects.value.push(branch);
    }

    emit('selected', {
        ids: selectedBranches.value,
        objects: selectedBranchObjects.value
    });
};


const fetchBranches = async (pageUrl = null) => {
    try {
        const url = pageUrl || route('branches.filter');
        const response = await axios.get(url, {
            params: { filters: { name: searchTerm.value } },
        });
        branches.value = response.data.branches;
    } catch (error) {
        console.error(error);
    }
};


watch(searchTerm, () => {
    fetchBranches();
});
</script>


<template>
    <div class="my-4 mx-4 lg:mx-10">
        <!-- Barra de búsqueda -->
        <div class="flex justify-between gap-4">
            <input v-model="searchTerm" type="text" placeholder="Buscar sucursales por nombre..."
                class="mb-4 w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 dark:bg-gray-800 dark:text-white" />

            <AccessGate role="admin">
                <Link :href="route('branches.create')" as="button"
                    class="flex justify-center mb-3 rounded-lg bg-pink-500 px-2 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-pink-600 focus:outline-none focus:ring-2 focus:ring-pink-500 sm:px-4">
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
                        <th class="px-6 py-3">Dirección</th>
                        <th class="px-6 py-3">Número de Teléfono</th>

                    </tr>
                </thead>
                <tbody>
                    <tr v-for="branch in branches.data" :key="branch.id" class="cursor-pointer" :class="{
                        'bg-pink-500 text-white': selectedBranches.includes(branch.id),
                        'hover:bg-gray-200 dark:hover:bg-gray-700': !selectedBranches.includes(branch.id)
                    }" @click="selectBranch(branch)">
                        <td class="p-4 hidden sm:table-cell">{{ branch.id }}</td>
                        <td class="p-4">{{ branch.name }} </td>
                        <td class="p-4">
                            {{ branch.address }}
                        </td>
                        <td class="p-4">{{ branch.phone_number }} </td>
                    </tr>
                </tbody>
            </table>

            <div v-if="!branches.data.length" class="text-center text-gray-500 dark:text-gray-400 py-4 w-full">
                No hay registros disponibles.
            </div>

            <!-- Paginación LOCAL -->
            <div v-if="branches.last_page > 1" class="flex justify-center items-center gap-2 py-3">
                <button :disabled="!branches.prev_page_url" @click="fetchBranches(branches.prev_page_url)"
                    class="px-3 py-1 border rounded disabled:opacity-50">
                    Anterior
                </button>

                <span class="text-sm text-gray-600 dark:text-gray-300">
                    Página {{ branches.current_page }} de {{ branches.last_page }}
                </span>

                <button :disabled="!branches.next_page_url" @click="fetchBranches(branches.next_page_url)"
                    class="px-3 py-1 border rounded disabled:opacity-50">
                    Siguiente
                </button>
            </div>
        </div>
        <div class="flex justify-between">
            <h2 v-if="selectedBranches.length" class="text-sm text-green-500 mt-4">
                <span v-if="selectedBranches.length === 1">
                    Sucursal seleccionada:
                    <strong>{{ selectedBranchObjects[0].name }}</strong>
                </span>

                <span v-else>
                    {{ selectedBranches.length }} sucursales seleccionadas
                </span>
            </h2>
            <button class="mt-2 px-4 py-2 bg-pink-500 text-white rounded-lg" @click="$emit('confirm')">
                Confirmar selección
            </button>

        </div>


    </div>
</template>
