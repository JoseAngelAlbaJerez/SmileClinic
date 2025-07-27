<template>
      <AuthenticatedLayout>
  <div class="py-8">
    <div class="mx-auto max-w-screen-2xl px-4 sm:px-6 lg:px-8 bg-white dark:bg-gray-800 p-5 rounded-xl">
      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Patients Card -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-900/20 rounded-2xl shadow-md p-6 border border-blue-100 dark:border-blue-900/50">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-blue-600 dark:text-blue-300">Total Pacientes</p>
              <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ patients.length }}</h3>
              <div class="mt-2">
                <span class="text-xs text-gray-500 dark:text-gray-400">Registrados en el sistema</span>
              </div>
            </div>
            <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900/50">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
              </svg>
            </div>
          </div>
        </div>

        <!-- Users Card -->
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/30 dark:to-purple-900/20 rounded-2xl shadow-md p-6 border border-purple-100 dark:border-purple-900/50">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-purple-600 dark:text-purple-300">Usuarios</p>
              <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ users.length }}</h3>
              <div class="mt-2">
                <span class="text-xs text-gray-500 dark:text-gray-400">Registrados en el sistema</span>
              </div>
            </div>
            <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900/50">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
          </div>
        </div>

        <!-- Weekly Income Card -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/30 dark:to-green-900/20 rounded-2xl shadow-md p-6 border border-green-100 dark:border-green-900/50">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-green-600 dark:text-green-300">Ingresos Esta Semana</p>
              <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">${{ formatNumber(incomeThisWeek) }}</h3>
              <div class="flex items-center mt-2">
                <span :class="`text-sm flex items-center ${percentageChange >= 0 ? 'text-green-500' : 'text-red-500'}`">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path v-if="percentageChange >= 0" fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    <path v-else fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                  </svg>
                  {{ Math.abs(percentageChange) }}%
                </span>
                <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">vs semana pasada</span>
              </div>
            </div>
            <div class="p-3 rounded-full bg-green-100 dark:bg-green-900/50">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
        </div>

        <!-- Net Profit Card -->
        <div class="bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-900/30 dark:to-amber-900/20 rounded-2xl shadow-md p-6 border border-amber-100 dark:border-amber-900/50">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-amber-600 dark:text-amber-300">Balance Total</p>
              <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">${{ formatNumber(income_sum - expense_sum) }}</h3>
              <div class="mt-2">
                <span class="text-xs text-gray-500 dark:text-gray-400">Ingresos: ${{ formatNumber(income_sum) }}</span>
                <span class="text-xs text-gray-500 dark:text-gray-400 block">Gastos: ${{ formatNumber(expense_sum) }}</span>
              </div>
            </div>
            <div class="p-3 rounded-full bg-amber-100 dark:bg-amber-900/50">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Transactions -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Income -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6 border border-gray-100 dark:border-gray-700">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Ingresos Recientes</h3>
            <Link :href="route('budgets.index')" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Ver todos</Link>
          </div>
          <div class="space-y-3">
            <div v-for="item in income.slice(0, 5)" :key="item.id" class="flex items-center justify-between p-3 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
              <Link :href="route('budgets.show', item.id)" class="flex items-center">
                <DocumentMoney class="w-8 h-8 text-blue-500 mr-3" />
                <div>
                  <p class="text-sm font-medium text-gray-900 dark:text-white">{{ item.patient.first_name }} {{ item.patient.last_name }}</p>
                  <p class="text-xs text-gray-500 dark:text-gray-400">{{ formatDate(item.created_at) }}</p>
                </div>
              </Link>
              <div class="text-right">
                <p class="text-sm font-medium text-emerald-600">+${{ formatNumber(item.total) }}</p>
                <span class="text-xs text-gray-500 dark:text-gray-400">{{ item.type }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Expenses -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6 border border-gray-100 dark:border-gray-700">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Gastos Recientes</h3>
            <Link :href="route('expenses.index')" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Ver todos</Link>
          </div>
          <div class="space-y-3">
            <div v-for="item in expense.slice(0, 5)" :key="item.id" class="flex items-center justify-between p-3 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
              <div class="flex items-center">
                <CartIcon class="w-8 h-8 text-amber-500 mr-3" />
                <div>
                  <p class="text-sm font-medium text-gray-900 dark:text-white">{{ item.description }}</p>
                  <p class="text-xs text-gray-500 dark:text-gray-400">{{ formatDate(item.created_at) }}</p>
                </div>
              </div>
              <div class="text-right">
                <p class="text-sm font-medium text-red-600">-${{ formatNumber(item.amount) }}</p>
                <span class="text-xs text-gray-500 dark:text-gray-400">{{ item.category || 'General' }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Appointments Calendar -->
      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Agenda de Citas</h3>
          <Link :href="route('events.create')" as="button"
              class="flex items-center gap-2 rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 transition-colors">
              <AddIcon class="size-4" />
              Nueva Cita
          </Link>
        </div>
        <div class="rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
          <iframe
              id="open-web-calendar"
              src="https://calendar.google.com/calendar/embed?height=400&wkst=2&ctz=America%2FSanto_Domingo&showPrint=0&mode=AGENDA&title=Citas%20Smile%20Clinic&src=am9zZWFuZ2VsYWxiYTI0QGdtYWlsLmNvbQ&src=NGVlY2JhYzU3ZWNlOGNkODEyZDZiYmNiNzY4ODEyZjYxMWM5M2NlN2VmYTdlYTU4ZjMyYmNkYWIzZmQyMWI4OUBncm91cC5jYWxlbmRhci5nb29nbGUuY29t&src=ZXMtNDE5LmRvI2hvbGlkYXlAZ3JvdXAudi5jYWxlbmRhci5nb29nbGUuY29t&color=%23039BE5&color=%237986CB&color=%230B8043"
              width="100%"
              height="400"
              frameborder="0"
              class="rounded"
              sandbox="allow-scripts allow-same-origin allow-top-navigation"
              allowtransparency="true">
          </iframe>
        </div>
      </div>
    </div>
  </div>
</AuthenticatedLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import DocumentMoney from '@/Components/Icons/DocumentMoney.vue';
import CartIcon from '@/Components/Icons/CartIcon.vue';
import AddIcon from '@/Components/Icons/AddIcon.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
defineProps({
  patients: Array,
  users: Array,
  income_sum: Number,
  expense_sum: Number,
  income: Array,
  expense: Array,
  incomeThisWeek: Number,
  incomeLastWeek: Number,
  percentageChange: Number,
});

const formatNumber = (value) => {
  return new Intl.NumberFormat('en-US').format(value);
};

const formatDate = (dateString) => {
  const options = { year: 'numeric', month: 'short', day: 'numeric' };
  return new Date(dateString).toLocaleDateString('es-DO', options);
};
</script>
