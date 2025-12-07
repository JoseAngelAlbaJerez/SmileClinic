<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    roles: Object,
});

const emit = defineEmits(['selected', 'confirm']);

// --- ESTADO ---
const searchTerm = ref('');
const selectedRoles = ref([]);
const selectedRoleObjects = ref([]);

// --- FILTRAR EN FRONTEND (solo datos de ESTA página) ---
const filtered = computed(() => {
    if (!searchTerm.value) return props.roles.data;

    return props.roles.data.filter(r =>
        r.name.toLowerCase().includes(searchTerm.value.toLowerCase())
    );
});

// --- SELECCIÓN ---
const selectRole = (role) => {
    const exists = selectedRoles.value.includes(role.id);

    if (exists) {
        selectedRoles.value = selectedRoles.value.filter(id => id !== role.id);
        selectedRoleObjects.value = selectedRoleObjects.value.filter(r => r.id !== role.id);
    } else {
        selectedRoles.value.push(role.id);
        selectedRoleObjects.value.push(role);
    }

    emit('selected', {
        ids: selectedRoles.value,
        objects: selectedRoleObjects.value
    });
};

// --- PAGINACIÓN BACKEND ---
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

        <!-- Búsqueda frontend -->
        <div class="flex justify-between gap-4">
            <input v-model="searchTerm" type="text" placeholder="Buscar rol..."
                class="mb-4 w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md
                       focus:outline-none focus:ring-2 focus:ring-pink-500 dark:bg-gray-800 dark:text-white" />
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
                    <tr v-for="role in filtered" :key="role.id"
                        class="cursor-pointer"
                        :class="{
                            'bg-pink-500 text-white': selectedRoles.includes(role.id),
                            'hover:bg-gray-200 dark:hover:bg-gray-700': !selectedRoles.includes(role.id)
                        }"
                        @click="selectRole(role)"
                    >
                        <td class="p-4 hidden sm:table-cell">{{ role.id }}</td>
                        <td class="p-4">{{ role.name.toUpperCase() }}</td>
                    </tr>
                </tbody>
            </table>

            <div v-if="!filtered.length" class="text-center py-4">
                No hay registros disponibles.
            </div>

        </div>

        <!-- PAGINACIÓN DEL BACKEND -->
        <div v-if="props.roles.last_page > 1"
             class="flex justify-center items-center gap-2 py-3">

            <button
                :disabled="!props.roles.prev_page_url"
                @click="goToPage(props.roles.prev_page_url)"
                class="px-3 py-1 border rounded disabled:opacity-50">
                Anterior
            </button>

            <span class="text-sm text-gray-700 dark:text-gray-300">
                Página {{ props.roles.current_page }} de {{ props.roles.last_page }}
            </span>

            <button
                :disabled="!props.roles.next_page_url"
                @click="goToPage(props.roles.next_page_url)"
                class="px-3 py-1 border rounded disabled:opacity-50">
                Siguiente
            </button>
        </div>

        <!-- INDICADOR DE SELECCIÓN -->
        <div class="flex justify-between mt-4">
            <h2 v-if="selectedRoles.length" class="text-sm text-green-500">
                <span v-if="selectedRoles.length === 1">
                    Rol seleccionado:
                    <strong>{{ selectedRoleObjects[0].name }}</strong>
                </span>
                <span v-else>
                    {{ selectedRoles.length }} roles seleccionados
                </span>
            </h2>

            <button class="px-4 py-2 bg-pink-500 text-white rounded-lg"
                    @click="$emit('confirm')">
                Confirmar selección
            </button>
        </div>

    </div>
</template>
