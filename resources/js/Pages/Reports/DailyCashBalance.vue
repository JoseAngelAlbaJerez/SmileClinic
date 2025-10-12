<template>
    <div class="container bg-white  mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800  flex gap-1.5">
                    <DocumentMoney class="text-pink-500 h-8 w-8" />
                    Cuadre de Caja Diario
                </h1>
                <p class="text-gray-600  mt-1">
                    Generado el {{ currentDate }}
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <button @click="printReport"
                    class="px-3 sm:px-4 py-2 bg-white  border border-gray-300  rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center text-xs sm:text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z">
                        </path>
                    </svg>
                    Imprimir
                </button>
                <button @click="downloadCSV"
                    class="px-3 sm:px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-lg transition-colors flex items-center text-xs sm:text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Exportar
                </button>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Income -->
            <div class="bg-white   p-6 rounded-xl shadow border border-green-100 ">
                <div class="flex items-center">
                    <div class="rounded-full bg-green-100 p-3 mr-4">
                        <i class="fas fa-arrow-down text-green-600  text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 ">Total Ingresos</p>
                        <p class="text-2xl font-bold text-green-600 ">{{ new
                            Intl.NumberFormat('es-DO', {
                                style:
                                    'currency', currency: 'DOP'
                            }).format(income_total
                                || 0) }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Expenses -->
            <div class="bg-white  p-6 rounded-xl shadow border border-red-100 ">
                <div class="flex items-center">
                    <div class="rounded-full bg-red-100 d p-3 mr-4">
                        <i class="fas fa-arrow-up text-red-600  text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 ">Total Egresos</p>
                        <p class="text-2xl font-bold text-red-600 ">{{ new Intl.NumberFormat('es-DO', {
                            style:
                                'currency', currency: 'DOP'
                        }).format(expenses_total
                            || 0) }}</p>
                    </div>
                </div>
            </div>

            <!-- Net Balance -->
            <div class="bg-white  p-6 rounded-xl shadow border border-pink-100 ">
                <div class="flex items-center">
                    <div class="rounded-full bg-pink-100  p-3 mr-4">
                        <i class="fas fa-scale-balanced text-pink-600  text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 ">Balance Neto</p>
                        <p class="text-2xl font-bold"
                            :class="net_balance >= 0 ? 'text-green-600 ' : 'text-red-600'">
                            {{ new Intl.NumberFormat('es-DO', {
                                style:
                                    'currency', currency: 'DOP'
                            }).format(net_balance
                                || 0) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Income & Expenses Sections -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Income Section -->
            <div class="bg-white  rounded-xl shadow overflow-hidden">
                <div class="bg-green-50 px-6 py-4 border-b border-green-100 ">
                    <h2 class="text-lg font-semibold text-green-800  flex items-center">
                        <i class="fas fa-arrow-down mr-2"></i>
                        Ingresos
                    </h2>
                </div>
                <div class="divide-y divide-gray-200 ">
                    <div v-for="(item, index) in income" :key="'income-' + index" class="px-6 py-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-medium text-gray-800 ">{{ item.description }}</h3>
                                <p class="text-sm text-gray-600">{{ item.date }}</p>
                            </div>
                            <span class="text-green-600  font-semibold">
                                {{ new Intl.NumberFormat('es-DO', {
                                    style:
                                        'currency', currency: 'DOP'
                                }).format(item.amount
                                    || 0) }}
                            </span>
                        </div>
                    </div>
                    <div v-if="!income || income.length === 0" class="px-6 py-8 text-center">
                        <i class="fas fa-receipt text-gray-300  text-4xl mb-3"></i>
                        <p class="text-gray-500 ">No se encontraron registros.</p>
                    </div>
                </div>
            </div>

            <!-- Expenses Section -->
            <div class="bg-white  rounded-xl shadow overflow-hidden">
                <div class="bg-red-50  px-6 py-4 border-b border-red-100 ">
                    <h2 class="text-lg font-semibold text-red-800 dark:text-red-300 flex items-center">
                        <i class="fas fa-arrow-up mr-2"></i>
                        Egresos
                    </h2>
                </div>
                <div class="divide-y divide-gray-200 ">
                    <div v-for="(item, index) in expenses" :key="'expense-' + index" class="px-6 py-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-medium text-gray-800 ">{{ item.description }}</h3>
                                <p class="text-sm text-gray-600 ">{{ item.date }}</p>
                            </div>
                            <span class="text-red-600  font-semibold">{{ new Intl.NumberFormat('es-DO',
                                {
                                    style:
                                        'currency', currency: 'DOP'
                                }).format(item.amount
                                    || 0) }}</span>
                        </div>
                    </div>
                    <div v-if="!expenses || expenses.length === 0" class="px-6 py-8 text-center">
                        <i class="fas fa-money-bill text-gray-300  text-4xl mb-3"></i>
                        <p class="text-gray-500 ">No se encontraron registros.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daily Summary Table -->
        <div class="mt-8 bg-white  rounded-xl shadow overflow-hidden">
            <div class="bg-pink-50  px-6 py-4 border-b border-pink-100 ">
                <h2 class="text-lg font-semibold text-pink-800 flex items-center">
                    <i class="fas fa-table mr-2"></i>
                    Resumen Diario
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 ">
                    <thead class="bg-gray-50 d">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500  uppercase tracking-wider">
                                Fecha</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500  uppercase tracking-wider">
                                Descripción</th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500  uppercase tracking-wider">
                                Ingreso</th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500  uppercase tracking-wider">
                                Egreso</th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500  uppercase tracking-wider">
                                Balance</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white  divide-y divide-gray-200 ">
                        <tr v-for="(day, index) in dailySummary" :key="'day-' + index">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 ">{{ day.date
                            }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800 ">{{ day.description }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-green-600 "
                                v-if="day.income > 0">
                                {{ new Intl.NumberFormat('es-DO',
                                    {
                                        style:
                                            'currency', currency: 'DOP'
                                    }).format(day.income
                                        || 0) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500 "
                                v-else>-</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-red-600 0"
                                v-if="day.expenses > 0">
                                {{ new Intl.NumberFormat('es-DO',
                                    {
                                        style:
                                            'currency', currency: 'DOP'
                                    }).format(day.expenses
                                        || 0) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500 "
                                v-else>-</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right"
                                :class="day.balance >= 0 ? 'text-green-600 ' : 'text-red-600 '">
                                {{ new Intl.NumberFormat('es-DO',
                                    {
                                        style:
                                            'currency', currency: 'DOP'
                                    }).format(day.balance
                                        || 0) }}
                            </td>
                        </tr>
                        <tr v-if="!dailySummary || dailySummary.length === 0">
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500 ">
                                <i class="fas fa-inbox text-gray-300  text-4xl mb-3"></i>
                                <p>No se encontraron registros.</p>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot class="bg-gray-100 ">
                        <tr>
                            <td colspan="2"
                                class="px-6 py-3 text-sm font-semibold text-gray-800  text-left">
                                Total:
                            </td>
                            <td class="px-6 py-3 text-sm font-semibold text-right text-green-600 ">
                                {{ new Intl.NumberFormat('es-DO', { style: 'currency', currency: 'DOP' }).format(
                                    income_total
                                ) }}
                            </td>
                            <td class="px-6 py-3 text-sm font-semibold text-right text-red-600 ">
                                {{ new Intl.NumberFormat('es-DO', { style: 'currency', currency: 'DOP' }).format(
                                    expenses_total
                                ) }}
                            </td>
                            <td class="px-6 py-3 text-sm font-semibold text-right"
                                :class="net_balance >= 0 ? 'text-green-600 ' : 'text-red-600 '">
                                {{ new Intl.NumberFormat('es-DO', { style: 'currency', currency: 'DOP' }).format(
                                    net_balance)
                                }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import DocumentMoney from '@/Components/Icons/DocumentMoney.vue';
export default {

    props: {
        income: Array,
        expenses: Array,
        income_total: Number,
        expenses_total: Number,
        net_balance: Number,
        today: String,
        dailySummary: Array,
    },
    components: {
        DocumentMoney
    },
    computed: {
        currentDate() {
            return new Date().toLocaleDateString('es-ES', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }
    },
    methods: {
        printReport() {
            window.print()
        },
        downloadCSV() {

        }
    }
}
</script>
