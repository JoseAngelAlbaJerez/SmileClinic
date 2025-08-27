<script setup>
import { ref, onMounted, watchEffect } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import { switchTheme, setThemeOnLoad } from '../theme.js';
import LightIcon from '@/Components/Icons/LightIcon.vue';
import DarkIcon from '@/Components/Icons/DarkIcon.vue';
import { useToast } from 'vue-toastification';
import { usePage } from '@inertiajs/vue3';
import { can } from '@/useAccess.js';
import '@vuepic/vue-datepicker/dist/main.css';
import Footer from '@/Components/Footer.vue';
import BellIcon from '@/Components/Icons/BellIcon.vue';
import InfoCircle from '@/Components/Icons/InfoCircle.vue';
const showingNavigationDropdown = ref(false);
const isDark = ref(false)
const toast = useToast();
const page = usePage();
const shownFlash = ref(null);
const read = ref(false)
const showNotification = ref(false)
const notifications = ref([])
const checkBillDate = () => {
    const today = new Date()
    const day = today.getDate()
    notifications.value = []

    const daysBefore = 15 - day
    if (daysBefore > 0 && daysBefore <= 5) {
        notifications.value.push(`⚡ Luz pendiente: faltan ${daysBefore} día(s)`)
        notifications.value.push(`💧 Agua pendiente: faltan ${daysBefore} día(s)`)
    } else if (day === 15) {
        notifications.value.push("⚡ Apaga la luz")
        notifications.value.push("💧 Paga el agua")
    }
}
 const markAsRead = () => {
        read.value = true
    }
onMounted(() => {
    checkBillDate()
    setInterval(checkBillDate, 1000 * 60 * 60)
    if (
        localStorage.theme === 'dark' ||
        (!('theme' in localStorage) &&
            window.matchMedia('(prefers-color-scheme: dark)').matches)
    ) {
        isDark.value = true
    }
})
watchEffect(() => {
    const flash = page.props.flash;

    if (flash.toast && flash.toast !== shownFlash.value) {
        const style = flash.toastStyle || 'success';

        if (style === 'danger' || style === 'error') {
            toast.error(flash.toast);
        } else if (style === 'info') {
            toast.info(flash.toast);
        } else if (style === 'warning') {
            toast.warning(flash.toast);
        } else {
            toast.success(flash.toast);
        }

        shownFlash.value = flash.toast;
    }
});
</script>

<template>
    <div>
        <div class="min-h-screen  bg-gray-100 dark:bg-gray-900">
            <nav class="border-b border-gray-100 bg-white dark:border-gray-700 dark:bg-gray-800">
                <!-- Primary Navigation Menu -->
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex shrink-0 items-center">
                                <Link :href="route('dashboard')">
                                <div class="flex justify-center ">
                                    <svg class="h-8  mr-2 w-auto text-pink-500" viewBox="0 0 100 100" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M50 100C77.6142 100 100 77.6142 100 50C100 22.3858 77.6142 0 50 0C22.3858 0 0 22.3858 0 50C0 77.6142 22.3858 100 50 100Z"
                                            fill="#3B82F6" />
                                        <path
                                            d="M65 40C65 42.7614 62.7614 45 60 45C57.2386 45 55 42.7614 55 40C55 37.2386 57.2386 35 60 35C62.7614 35 65 37.2386 65 40Z"
                                            fill="white" />
                                        <path
                                            d="M45 40C45 42.7614 42.7614 45 40 45C37.2386 45 35 42.7614 35 40C35 37.2386 37.2386 35 40 35C42.7614 35 45 37.2386 45 40Z"
                                            fill="white" />
                                        <path d="M30 60C30 60 35 70 50 70C65 70 70 60 70 60" stroke="white"
                                            stroke-width="4" stroke-linecap="round" />
                                    </svg>
                                    <h1 class="mt-2 text-pink-500    font-thin">Smile Clinic</h1>

                                </div>

                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    Dashboard
                                </NavLink>
                                <NavLink v-if="can('patient.view')" :href="route('patients.index')"
                                    :active="route().current('patients.index') || route().current('patients.show') || route().current('patients.edit')">
                                    Pacientes
                                </NavLink>
                                <NavLink v-if="can('event.view')" :href="route('events.index')"
                                    :active="route().current('events.index') || route().current('events.show') || route().current('events.edit')">
                                    Citas
                                </NavLink>
                                <NavLink v-if="can('budget.view')" :href="route('budgets.index')"
                                    :active="route().current('budgets.index') || route().current('budgets.show') || route().current('budgets.edit')">
                                    Presupuestos
                                </NavLink>
                                <NavLink v-if="can('CXC.view')" :href="route('CXC.index')"
                                    :active="route().current('CXC.index') || route().current('CXC.show') || route().current('CXC.edit')">
                                    Cuentas por Cobrar
                                </NavLink>
                                <NavLink v-if="can('expense.view')" :href="route('expenses.index')"
                                    :active="route().current('expenses.index') || route().current('expenses.edit')">
                                    Egresos
                                </NavLink>
                                <NavLink v-if="can('prescription.view')" :href="route('prescriptions.index')"
                                    :active="route().current('prescriptions.index') || route().current('prescriptions.show') || route().current('prescriptions.edit')">
                                    Recetas
                                </NavLink>
                                <NavLink v-if="can('user.view')" :href="route('users.index')"
                                    :active="route().current('users.index') || route().current('users.show') || route().current('users.edit')">
                                    Usuarios
                                </NavLink>
                                <NavLink v-if="can('bill.view')" :href="route('bills.index')"
                                    :active="route().current('bills.index') || route().current('bills.show') || route().current('bills.edit')">
                                    Facturas
                                </NavLink>

                            </div>
                        </div>

                        <div class="hidden sm:ms-6 sm:flex sm:items-center">
                            <button @click="() => { switchTheme(); isDark = !isDark }"
                                class="text-gray-500 hover:text-gray-900 dark:text-gray-100 dark:hover:text-gray-500">
                                <component :is="isDark ? DarkIcon : LightIcon" class="w-5 h-5" />
                            </button>
                            <!-- Notification Dropdown -->
                            <div class="relative ml-2">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button @click="notificationOpen = !notificationOpen; markAsRead()"
                                                class="relative inline-flex items-center p-2 rounded-md text-gray-500 hover:text-gray-900 dark:text-gray-100 dark:hover:text-gray-300">
                                                <BellIcon class="h-5 w-5" />
                                                <span v-if="notifications.length && !read"
                                                    class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-600 text-white text-xs font-bold">
                                                    {{ notifications.length }}
                                                </span>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <div v-if="notifications.length">
                                            <p v-for="(note, index) in notifications" :key="index"
                                                class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none dark:text-gray-300 dark:hover:bg-gray-800 dark:focus:bg-gray-800">
                                                {{ note }}
                                            </p>
                                        </div>
                                        <div v-else class="flex justify-center">

                                            <p
                                                class="block w-full px-4 py-4 text-start text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none dark:text-gray-300 dark:hover:bg-gray-800 dark:focus:bg-gray-800">
                                               <InfoCircle class="mx-auto mb-2 dark:text-white w-7 h-7"/>
                                                No tiene notificaciones pendientes.
                                            </p>
                                        </div>
                                    </template>
                                </Dropdown>
                            </div>
                            <!-- Settings Dropdown -->
                            <div class="relative ms-1">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button"
                                                class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none dark:bg-gray-800 dark:text-gray-400 dark:hover:text-gray-300">
                                                {{ $page.props.auth.user.name }}

                                                <svg class="-me-0.5 ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('profile.edit')">
                                            Profile
                                        </DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button">
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button @click="
                                showingNavigationDropdown =
                                !showingNavigationDropdown
                                "
                                class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none dark:text-gray-500 dark:hover:bg-gray-900 dark:hover:text-gray-400 dark:focus:bg-gray-900 dark:focus:text-gray-400">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{
                                        hidden: showingNavigationDropdown,
                                        'inline-flex':
                                            !showingNavigationDropdown,
                                    }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{
                                        hidden: !showingNavigationDropdown,
                                        'inline-flex':
                                            showingNavigationDropdown,
                                    }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{
                    block: showingNavigationDropdown,
                    hidden: !showingNavigationDropdown,
                }" class="sm:hidden">
                    <div class="space-y-1 pb-3 pt-2">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Dashboard
                        </ResponsiveNavLink>
                        <ResponsiveNavLink v-if="can('patient.view')" :href="route('patients.index')"
                            :active="route().current('patients.index')">
                            Pacientes
                        </ResponsiveNavLink>
                        <ResponsiveNavLink v-if="can('event.view')" :href="route('events.index')"
                            :active="route().current('events.index')">
                            Citas
                        </ResponsiveNavLink>
                        <ResponsiveNavLink v-if="can('budget.view')" :href="route('budgets.index')"
                            :active="route().current('budgets.index')">
                            Presupuestos
                        </ResponsiveNavLink>
                        <ResponsiveNavLink v-if="can('CXC.view')" :href="route('CXC.index')"
                            :active="route().current('CXC.index')">
                            Cuentas por Cobrar
                        </ResponsiveNavLink>
                        <ResponsiveNavLink v-if="can('expense.view')" :href="route('expenses.index')"
                            :active="route().current('expenses.index')">
                            Egresos
                        </ResponsiveNavLink>
                        <ResponsiveNavLink v-if="can('prescription.view')" :href="route('prescriptions.index')"
                            :active="route().current('prescriptions.index')">
                            Recetas
                        </ResponsiveNavLink>
                        <ResponsiveNavLink v-if="can('user.view')" :href="route('users.index')"
                            :active="route().current('users.index')">
                            Usuarios
                        </ResponsiveNavLink>
                        <ResponsiveNavLink v-if="can('bill.view')" :href="route('bills.index')"
                            :active="route().current('bills.index')">
                            Facturas
                        </ResponsiveNavLink>

                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="border-t border-gray-200 pb-1 pt-4 dark:border-gray-600">
                        <div class="px-4">
                            <div class="text-base font-medium text-gray-800 dark:text-gray-200">
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="text-sm font-medium text-gray-500">
                                {{ $page.props.auth.user.email }}
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')">
                                Profile
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white shadow dark:bg-gray-800" v-if="$slots.header">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
            <Footer class="sticky top-[100vh]" />
        </div>
    </div>
</template>
