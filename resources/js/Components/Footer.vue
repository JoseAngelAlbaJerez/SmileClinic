<template>
  <footer class="bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 shadow-t py-4 mt-auto">
    <div class="container mx-auto px-4 flex flex-col md:flex-row justify-between items-center gap-2">
      <!-- Left: Copyright -->
      <div class="flex items-center gap-2 text-sm">
        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 2a10 10 0 100 20 10 10 0 000-20zm1 14h-2v-2h2v2zm0-4h-2V7h2v5z"/>
        </svg>
        &copy; {{ currentYear }} <span class="font-semibold">Smile Clinic</span>. All rights reserved.
      </div>

      <!-- Right: Branch Info -->
      <div class="flex items-center gap-2 text-sm">
        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z"/>
        </svg>
        Sucursal Actual: <span class="font-semibold">{{ branchName }}</span>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();

const branchName = computed(() => page.props.auth?.user?.branch?.name || 'N/A');
const currentYear = new Date().getFullYear();
</script>

<style scoped>
footer {
  font-size: 0.875rem;
}
.shadow-t {
  box-shadow: 0 -2px 6px rgba(0, 0, 0, 0.05);
}
</style>
