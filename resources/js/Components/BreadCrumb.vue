<template>
    <nav class="flex text-sm text-gray-500 dark:text-gray-300" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li>
                <div class="flex items-center">
                    <HomeIcon class="w-5 h-5 mr-1" />
                    <Link href="/dashboard" class=" font-medium text-blue-600 hover:underline dark:text-blue-400">
                    Inicio
                    </Link>

                </div>
            </li>
            <li v-for="(crumb, index) in crumbs" :key="index">
                <div class="flex items-center">
                    <ChevronRightIcon class="w-5 h-4 mx-2 text-gray-400" />

                    <!-- Icono dinámico, si existe -->
                    <component v-if="crumb.icon" :is="crumb.icon" class="w-5 h-5 mr-1 text-gray-500" />

                    <!-- Texto -->
                    <span v-if="!crumb.to" class="ml-1 font-medium text-gray-700 dark:text-gray-300">
                        {{ crumb.label }}
                    </span>
                    <Link v-else :href="crumb.to"
                        class="ml-1 font-medium text-blue-600 hover:underline dark:text-blue-400">
                    {{ crumb.label }}
                    </Link>
                </div>
            </li>

        </ol>
    </nav>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';


import ChevronRightIcon from '@/Components/Icons/ChevronRightIcon.vue';
import HomeIcon from '@/Components/Icons/HomeIcon.vue';
const props = defineProps({
    crumbs: {
        type: Array,
        default: () => []
    }
})
</script>
