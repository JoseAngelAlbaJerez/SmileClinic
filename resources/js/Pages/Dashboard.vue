<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import BreadCrumb from '@/Components/BreadCrumb.vue'
import AddIcon from '@/Components/Icons/AddIcon.vue';
import UserIcon from '@/Components/Icons/UserIcon.vue';
import DocumentMoney from '@/Components/Icons/DocumentMoney.vue';
import CartIcon from '@/Components/Icons/CartIcon.vue';
import CardStats from '@/Components/CardStats.vue';
import PieChartIcon from '@/Components/Icons/PieChartIcon.vue';

export default {
    props: {
        patients: Object,
        users: Object,
        income_sum: Number,
        expense_sum: Number,
        income: [Object, Array],
        expense: [Object, Array],
        incomeThisWeek: Number,
        incomeLastWeek: String,
        percentageChange: Number,
    },
    components: {
        Head,
        BreadCrumb,
        AddIcon,
        Link,
        AuthenticatedLayout,
        UserIcon,
        DocumentMoney,
        CartIcon,
        CardStats,
        PieChartIcon
    },
    methods: {
        formatNumber(n) {
            return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
    }
}
</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <BreadCrumb />
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-screen-2xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <!-- Content -->
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">

                            <CardStats statSubtitle="Nuevos Usuarios" :statTitle="patients.lenght" statArrow="down" statPercent="3.48"
                                statPercentColor="text-red-500" statDescripition="desde la semana pasada"
                                 statIconColor="bg-orange-500" :stat-icon-color="'bg-green-500'"> </CardStats>
                            <CardStats statSubtitle="Ingresos Mensuales" :statDescripition="'del mes pasado'">
                            </CardStats>
                            <CardStats statSubtitle="Egresos Mensuales" :stat-percent="percentageChange"
                                :statDescripition="'del mes pasado'" :stat-icon-color="'bg-yellow-400'"> </CardStats>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                            <div
                                class="p-6 relative flex flex-col min-w-0 mb-4 lg:mb-0 break-words bg-gray-50 dark:bg-gray-700 w-full shadow-lg rounded">
                                <div class="rounded-t mb-0 px-0 border-0">
                                    <div class="flex flex-wrap items-center px-4 py-2">
                                        <div class="relative w-full max-w-full flex-grow flex-1">
                                            <h3 class="font-semibold text-base text-gray-900 dark:text-gray-50">Usuarios
                                            </h3>
                                        </div>
                                    </div>
                                    <div class="block w-full overflow-x-auto">
                                        <table class="items-center w-full bg-transparent border-collapse">
                                            <thead>
                                                <tr>
                                                    <th
                                                        class="px-4 bg-gray-100 dark:bg-gray-600 text-gray-500 dark:text-gray-100 align-middle border border-solid border-gray-200 dark:border-gray-500 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                                        Role</th>
                                                    <th
                                                        class="px-4 bg-gray-100 dark:bg-gray-600 text-gray-500 dark:text-gray-100 align-middle border border-solid border-gray-200 dark:border-gray-500 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                                        Amount</th>
                                                    <th
                                                        class="px-4 bg-gray-100 dark:bg-gray-600 text-gray-500 dark:text-gray-100 align-middle border border-solid border-gray-200 dark:border-gray-500 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left min-w-140-px">
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="text-gray-700 dark:text-gray-100">
                                                    <th
                                                        class="border-t-0 px-4 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left">
                                                        Administrator</th>
                                                    <td
                                                        class="border-t-0 px-4 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                                        1</td>
                                                    <td
                                                        class="border-t-0 px-4 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                                        <div class="flex items-center">
                                                            <span class="mr-2">70%</span>
                                                            <div class="relative w-full">
                                                                <div
                                                                    class="overflow-hidden h-2 text-xs flex rounded bg-blue-200">
                                                                    <div style="width: 70%"
                                                                        class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-600">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="text-gray-700 dark:text-gray-100">
                                                    <th
                                                        class="border-t-0 px-4 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left">
                                                        User</th>
                                                    <td
                                                        class="border-t-0 px-4 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                                        6</td>
                                                    <td
                                                        class="border-t-0 px-4 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                                        <div class="flex items-center">
                                                            <span class="mr-2">40%</span>
                                                            <div class="relative w-full">
                                                                <div
                                                                    class="overflow-hidden h-2 text-xs flex rounded bg-blue-200">
                                                                    <div style="width: 40%"
                                                                        class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-500">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="text-gray-700 dark:text-gray-100">
                                                    <th
                                                        class="border-t-0 px-4 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left">
                                                        User</th>
                                                    <td
                                                        class="border-t-0 px-4 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                                        5</td>
                                                    <td
                                                        class="border-t-0 px-4 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                                        <div class="flex items-center">
                                                            <span class="mr-2">45%</span>
                                                            <div class="relative w-full">
                                                                <div
                                                                    class="overflow-hidden h-2 text-xs flex rounded bg-pink-200">
                                                                    <div style="width: 45%"
                                                                        class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-pink-500">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="text-gray-700 dark:text-gray-100">
                                                    <th
                                                        class="border-t-0 px-4 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left">
                                                        User</th>
                                                    <td
                                                        class="border-t-0 px-4 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                                        4</td>
                                                    <td
                                                        class="border-t-0 px-4 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                                        <div class="flex items-center">
                                                            <span class="mr-2">60%</span>
                                                            <div class="relative w-full">
                                                                <div
                                                                    class="overflow-hidden h-2 text-xs flex rounded bg-red-200">
                                                                    <div style="width: 60%"
                                                                        class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-red-500">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="bg-gray-50  shadow-md dark:bg-gray-700 dark:text-white shadow-black/5 p-6 rounded-md">
                                <div class="flex justify-between mb-4 items-start">
                                    <div class="font-medium">Activities</div>
                                </div>
                                <div class="overflow-hidden">
                                    <table class="w-full min-w-[540px]">
                                        <tbody>
                                            <tr>
                                                <td class="py-2 px-4 border-b border-b-gray-50">
                                                    <div class="flex items-center">
                                                        <a href="#"
                                                            class="text-gray-500 text-sm font-medium hover:text-blue-500 ml-2 truncate">Lorem
                                                            Ipsum</a>
                                                    </div>
                                                </td>
                                                <td class="py-2 px-4 border-b border-b-gray-50">
                                                    <span
                                                        class="text-[13px] font-medium text-gray-400">02-02-2024</span>
                                                </td>
                                                <td class="py-2 px-4 border-b border-b-gray-50">
                                                    <span class="text-[13px] font-medium text-gray-400">17.45</span>
                                                </td>
                                                <td class="py-2 px-4 border-b border-b-gray-50">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="py-2 px-4 border-b border-b-gray-50">
                                                    <div class="flex items-center">
                                                        <a href="#"
                                                            class="text-gray-500 text-sm font-medium hover:text-blue-500 ml-2 truncate">Lorem
                                                            Ipsum</a>
                                                    </div>
                                                </td>
                                                <td class="py-2 px-4 border-b border-b-gray-50">
                                                    <span
                                                        class="text-[13px] font-medium text-gray-400">02-02-2024</span>
                                                </td>
                                                <td class="py-2 px-4 border-b border-b-gray-50">
                                                    <span class="text-[13px] font-medium text-gray-400">17.45</span>
                                                </td>
                                                <td class="py-2 px-4 border-b border-b-gray-50">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                            <div
                                class="bg-gray-50  shadow-md  dark:bg-gray-700 dark:text-white shadow-black/5 p-6 rounded-md lg:col-span-2">
                                <div class="flex justify-between mb-4 items-start">
                                    <div class="font-medium">Agenda de Citas</div>
                                    <Link :href="route('events.create')" as="button"
                                        class="flex justify-center  rounded-lg bg-blue-500 px-1 py-1 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500 sm:px-4">
                                    <AddIcon class="size-6 mr-2" />
                                    Nueva Cita
                                    </Link>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-1 gap-4 mb-4">

                                    <iframe id="open-web-calendar"
                                        src="https://calendar.google.com/calendar/embed?height=600&wkst=2&ctz=America%2FSanto_Domingo&showPrint=0&mode=AGENDA&title=Citas%20Smile%20Clinic&src=am9zZWFuZ2VsYWxiYTI0QGdtYWlsLmNvbQ&src=NGVlY2JhYzU3ZWNlOGNkODEyZDZiYmNiNzY4ODEyZjYxMWM5M2NlN2VmYTdlYTU4ZjMyYmNkYWIzZmQyMWI4OUBncm91cC5jYWxlbmRhci5nb29nbGUuY29t&src=ZXMtNDE5LmRvI2hvbGlkYXlAZ3JvdXAudi5jYWxlbmRhci5nb29nbGUuY29t&color=%23039BE5&color=%237986CB&color=%230B8043"
                                        width="100%" height="600" frameborder="0" scrolling="no"
                                        class="rounded overflow-x-auto"
                                        sandbox="allow-scripts allow-same-origin allow-top-navigation"
                                        allowtransparency="true">
                                    </iframe>

                                </div>

                            </div>
                            <div
                                class="bg-gray-50  shadow-md  dark:bg-gray-700 dark:text-white shadow-black/5 p-6 rounded-md lg:col-span-2">
                                <div class="flex justify-between mb-4 items-start">
                                    <div class="font-medium">Ganancias y Pérdidas </div>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="w-full min-w-[460px]">
                                        <thead>
                                            <tr>
                                                <th
                                                    class="text-[12px] uppercase tracking-wide font-medium text-gray-400 dark:text-gray-700 py-2 px-4 bg-gray-50 text-left rounded-tl-md rounded-bl-md">
                                                    Servicio</th>
                                                <th
                                                    class="text-[12px] uppercase tracking-wide font-medium text-gray-400 dark:text-gray-700 py-2 px-4 bg-gray-50 text-left">
                                                    Ganancia</th>
                                                <th
                                                    class="text-[12px] uppercase tracking-wide font-medium text-gray-400 dark:text-gray-700 py-2 px-4 bg-gray-50 text-left rounded-tr-md rounded-br-md">
                                                    Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="incomes in income" :key="incomes.id">
                                                <td class="py-2 px-4 border-b border-b-gray-50">
                                                    <div class="flex items-center">
                                                        <DocumentMoney
                                                            class="w-8 h-8 rounded text-blue-500 object-cover block" />
                                                        <Link :href="route('budgets.show', incomes.id)"
                                                            class="text-gray-600 dark:text-gray-200 text-sm font-medium hover:text-blue-500 ml-2 truncate">
                                                        {{ incomes.patient.first_name }} {{ incomes.patient.last_name }}
                                                        </Link>
                                                    </div>
                                                </td>
                                                <td class="py-2 px-4 border-b border-b-gray-50">
                                                    <span class="text-[13px] font-medium text-emerald-500">+{{
                                                        formatNumber(incomes.total) }}</span>
                                                </td>
                                                <td class="py-2 px-4 border-b border-b-gray-50">
                                                    <span
                                                        :class="incomes.active
                                                            ? 'inline-block p-1 rounded bg-emerald-500/10 text-emerald-500 font-medium text-[12px] leading-none'
                                                            : 'inline-block p-1 rounded bg-rose-500/10 text-rose-500 font-medium text-[12px] leading-none'">
                                                        {{ incomes.active ? 'Activo' : 'Inactivo' }}
                                                    </span>

                                                </td>
                                            </tr>
                                            <tr v-for="expenses in expense" :key="expenses.id">
                                                <td class="py-2 px-4 border-b border-b-gray-50">
                                                    <div class="flex items-center">
                                                        <CartIcon
                                                            class="w-8 h-8 rounded text-blue-500 object-cover block" />
                                                        <Link :href="route('expenses.index')"
                                                            class="text-gray-600 dark:text-gray-200 text-sm font-medium hover:text-blue-500 ml-2 truncate">
                                                        {{ expenses.description }}</Link>
                                                    </div>
                                                </td>
                                                <td class="py-2 px-4 border-b border-b-gray-50">
                                                    <span class="text-[13px] font-medium text-rose-500">-${{
                                                        formatNumber(expenses.amount) }}</span>
                                                </td>
                                                <td class="py-2 px-4 border-b border-b-gray-50">
                                                    <span
                                                        :class="expenses.active
                                                            ? 'inline-block p-1 rounded bg-emerald-500/10 text-emerald-500 font-medium text-[12px] leading-none'
                                                            : 'inline-block p-1 rounded bg-rose-500/10 text-rose-500 font-medium text-[12px] leading-none'">
                                                        {{ expenses.active ? 'Activo' : 'Inactivo' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Content -->
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
