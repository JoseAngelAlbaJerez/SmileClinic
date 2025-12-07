<script setup>
import { ref, computed } from 'vue';
import AccessGate from './AccessGate.vue';
import AddIcon from './Icons/AddIcon.vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
    message: String,
    branches: Object, // viene paginado
});

const emit = defineEmits(['selected', 'confirm']);

// --- ESTADO ---
const searchTerm = ref('');
const selectedBranches = ref([]);
const selectedBranchObjects = ref([]);

// --- FILTRO EN FRONTEND (solo página actual) ---
const filtered = computed(() => {
    if (!searchTerm.value) return props.branches.data;

    return props.branches.data.filter(b =>
        (b.name + " " + b.address + " " + b.phone_number)
            .toLowerCase()
            .includes(searchTerm.value.toLowerCase())
    );
});

// --- SELECCIÓN MÚLTIPLE ---
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

// --- PAGINACIÓN HACIA BACKEND ---
const goToPage = (url) => {
    if (!url) return;

    router.visit(url, {
        preserveScroll: true,
        preserveState: true,
    });
};
</script>

<template>
    <div class="my-4 mx-4 lg:mx-10">

        <!-- Búsqueda (Frontend ONLY) -->
        <div class="flex justify-between gap-4">
            <input v-model="searchTerm" type="text" placeholder="Buscar sucursales..."
                class="mb-4 w-full px-4 py-2 border rounded-md dark:bg-gray-800 dark:text-white" />

            <AccessGate role="admin">
                <Link :href="route('branches.create')" as="button"
                    class="flex justify-center mb-3 rounded-lg bg-pink-500 px-2 py-2 text-sm text-white shadow-sm hover:bg-pink-600 sm:px-4">
                    <AddIcon class="size-6" />
                </Link>
            </AccessGate>
        </div>

        <!-- Tabla -->
        <div class="relative overflow-x-auto border rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-300">
                <thead class="text-xs bg-gray-50 dark:bg-gray-700 dark:text-gray-100">
                    <tr>
                        <th class="px-4 py-3 hidden sm:table-cell">#</th>
                        <th class="px-6 py-3">Nombre</th>
                        <th class="px-6 py-3">Dirección</th>
                        <th class="px-6 py-3">Teléfono</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="branch in filtered" :key="branch.id"
                        class="cursor-pointer"
                        :class="{
                            'bg-pink-500 text-white': selectedBranches.includes(branch.id),
                            'hover:bg-gray-200 dark:hover:bg-gray-700': !selectedBranches.includes(branch.id)
                        }"
                        @click="selectBranch(branch)"
                    >
                        <td class="p-4 hidden sm:table-cell">{{ branch.id }}</td>
                        <td class="p-4">{{ branch.name }}</td>
                        <td class="p-4">{{ branch.address }}</td>
                        <td class="p-4">{{ branch.phone_number }}</td>
                    </tr>
                </tbody>
            </table>

            <div v-if="!filtered.length" class="text-center py-4">
                No hay registros disponibles.
            </div>
        </div>

        <!-- PAGINACIÓN BACKEND -->
        <div class="flex justify-center items-center gap-2 mt-4">
            <button v-for="link in props.branches.links"
                :key="link.label"
                v-html="link.label"
                @click="goToPage(link.url)"
                :class="[
                    'px-3 py-1 rounded',
                    link.active
                        ? 'bg-pink-500 text-white'
                        : 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white hover:bg-gray-300'
                ]"
                :disabled="!link.url"
            />
        </div>

        <!-- Selección -->
        <div class="flex justify-between mt-4">
            <h2 v-if="selectedBranches.length" class="text-sm text-green-500">
                <span v-if="selectedBranches.length === 1">
                    Sucursal seleccionada:
                    <strong>{{ selectedBranchObjects[0].name }}</strong>
                </span>
                <span v-else>
                    {{ selectedBranches.length }} sucursales seleccionadas
                </span>
            </h2>

            <button class="px-4 py-2 bg-pink-500 text-white rounded-lg"
                    @click="$emit('confirm')">
                Confirmar selección
            </button>
        </div>

    </div>
</template>
