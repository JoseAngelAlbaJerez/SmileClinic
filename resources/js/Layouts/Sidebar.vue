<template>
  <div class="w-72 flex-col w-full md:flex md:flex-row md:min-h-screen">
    <div
      class="flex flex-col flex-shrink-0 w-full text-gray-700 bg-white md:w-72 md:min-h-screen dark:text-gray-200 dark:bg-gray-800"
    >
      <!-- Header -->
      <div class="flex flex-row items-center justify-between px-10 py-6">
        <TeethIcon class="h-6 w-6 text-blue-300 dark:text-white"></TeethIcon>
        <a
          href="#"
          class="text-2xl font-bold tracking-widest text-blue-300 uppercase rounded-lg dark:text-white"
        >
          SmileClinic
        </a>
        <button class="rounded-lg md:hidden" @click="open = !open">
          <svg fill="currentColor" viewBox="0 0 20 20" class="w-7 h-7">
            <path
              v-if="!open"
              fill-rule="evenodd"
              d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM9 15a1 1 0 011-1h6a1 1 0 110 2h-6a1 1 0 01-1-1z"
              clip-rule="evenodd"
            ></path>
            <path
              v-else
              fill-rule="evenodd"
              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
              clip-rule="evenodd"
            ></path>
          </svg>
        </button>
      </div>

      <!-- Navigation -->
      <nav
        class="flex-grow px-5 pb-6 md:block md:pb-0 md:overflow-y-auto"
        :class="{ hidden: !open && isMobile }"
      >
        <NavLink class="sidebar-link" :href="route('dashboard')" :active="route().current('dashboard')">
          Dashboard
        </NavLink>
        <NavLink class="sidebar-link" :href="route('patients.index')" :active="route().current('patients.index')">
          Pacientes
        </NavLink>
        <NavLink class="sidebar-link" :href="route('events.index')" :active="route().current('events.index')">
          Citas
        </NavLink>
        <NavLink class="sidebar-link" :href="route('budgets.index')" :active="route().current('budgets.index')">
          Presupuestos
        </NavLink>
        <NavLink class="sidebar-link" :href="route('CXC.index')" :active="route().current('CXC.index')">
          Cuentas por Cobrar
        </NavLink>
        <NavLink class="sidebar-link" :href="route('expenses.index')" :active="route().current('expenses.index')">
          Egresos
        </NavLink>
        <NavLink class="sidebar-link" :href="route('prescriptions.index')" :active="route().current('prescriptions.index')">
          Recetas
        </NavLink>
      </nav>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import NavLink from '@/Components/NavLink.vue'
import TeethIcon from '@/Components/Icons/TeethIcon.vue'
const open = ref(false)
const isMobile = ref(false)

onMounted(() => {
  isMobile.value = window.innerWidth < 768
  window.addEventListener('resize', () => {
    isMobile.value = window.innerWidth < 768
  })
})
</script>

<style scoped>
.sidebar-link {
  @apply block px-6 py-4 mt-2 text-lg font-semibold rounded-xl text-gray-700 dark:text-gray-200 hover:text-gray-700 dark:hover:text-white hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:bg-gray-200 dark:focus:bg-gray-600;
}
</style>
