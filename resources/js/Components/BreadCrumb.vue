<template>
  <!-- Breadcrumb -->
  <nav class="flex text-sm text-gray-500 dark:text-gray-300" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3 w-full overflow-x-auto scrollbar-hide">

      <!-- Home always visible -->
      <li class="flex items-center">
        <HomeIcon class="w-5 h-5 mr-1 shrink-0" />
        <Link
          href="/dashboard"
          class="font-medium text-pink-600 hover:underline dark:text-pink-400 hidden sm:inline"
        >
          Inicio
        </Link>
      </li>

      <!-- Middle crumbs: hidden on small screens -->
      <li
        v-for="(crumb, index) in crumbs.slice(0, crumbs.length - 1)"
        :key="`crumb-${index}`"
        class="hidden md:flex items-center"
      >
        <ChevronRightIcon class="w-5 h-4 mx-2 text-gray-400 shrink-0" />
        <component
          v-if="crumb.icon"
          :is="crumb.icon"
          class="w-5 h-5 mr-1 shrink-0"
        />
        <Link
          v-if="crumb.to"
          :href="crumb.to"
          class="ml-1 font-medium text-pink-600 hover:underline dark:text-pink-400"
        >
          {{ crumb.label }}
        </Link>
        <span v-else class="ml-1 font-medium text-gray-700 dark:text-gray-300">
          {{ crumb.label }}
        </span>
      </li>

      <!-- Last crumb: always visible -->
      <li v-if="crumbs.length" class="flex items-center">
        <ChevronRightIcon class="w-5 h-4 mx-2 text-gray-400 shrink-0" />
        <component
          v-if="crumbs[crumbs.length - 1].icon"
          :is="crumbs[crumbs.length - 1].icon"
          class="w-5 h-5 mr-1 shrink-0"
        />
        <span class="ml-1 font-medium text-gray-700 dark:text-gray-300 truncate max-w-[150px] sm:max-w-none">
          {{ crumbs[crumbs.length - 1].label }}
        </span>
      </li>
    </ol>
  </nav>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

import ChevronRightIcon from '@/Components/Icons/ChevronRightIcon.vue'
import HomeIcon from '@/Components/Icons/HomeIcon.vue'

const props = defineProps({
  crumbs: {
    type: Array,
    default: () => [],
  },
})
</script>

<style>
.scrollbar-hide::-webkit-scrollbar {
  display: none;
}
.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>
