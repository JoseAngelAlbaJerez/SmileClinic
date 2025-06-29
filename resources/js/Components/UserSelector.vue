<script setup>
import { ref } from 'vue';
import Pagination from './Pagination.vue';

const props = defineProps({
    message: String,
    users: Object,
});

const emit = defineEmits(['selected']);

const selectedUser = ref(null);

const selecteUser = (user) => {
    if (selectedUser.value === user.id) {
        selectedUser.value = null;
        emit('selected', null);
    } else {
        selectedUser.value = user.id;
        emit('selected', user);
    }
};
</script>

<template>
    <div class="relative overflow-x-auto border border-gray-200 dark:border-gray-700/60 rounded-lg my-4 mx-4 lg:mx-10 ">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-100">
                <tr>
                    <th scope="col" class="px-4 py-3 cursor-pointer whitespace-nowrap hidden sm:table-cell">#</th>
                    <th scope="col" class="px-6 py-3 cursor-pointer">Nombre</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="user in users.data"
                    :key="user.id"
                    class="cursor-pointer"
                    :class="{
                        'bg-blue-500 text-gray-900 dark:bg-blue-500 dark:text-white': selectedUser === user.id,
                        'hover:bg-gray-200 hover:text-gray-800': selectedUser !== user.id
                    }"
                    @click="selecteUser(user)"
                >
                    <td class="p-4 items-center">{{ user.id }}</td>
                    <td class="p-4 items-center">{{ user.name }}</td>
                </tr>
            </tbody>
        </table>

        <div v-if="!users.data.length" class="text-center text-gray-500 dark:text-gray-400 py-4 w-full">
            No hay registros disponibles.
        </div>

        <Pagination :pagination="users" />
    </div>
    <h2 v-if="selectedUser" class="text-sm text-green-500 lg:mx-10 mx-4">
        Usuario Seleccionado
    </h2>
</template>
