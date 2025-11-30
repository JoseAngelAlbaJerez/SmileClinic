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
import { router } from '@inertiajs/vue3';
import Tooltip from '@/Components/Tooltip.vue';
import DashboardIcon from '@/Components/Icons/DashboardIcon.vue';
import UserIcon from '@/Components/Icons/UserIcon.vue';
import BuildingIcon from '@/Components/Icons/BuildingIcon.vue';
import CalendarIcon from '@/Components/Icons/CalendarIcon.vue';
import AccessGate from '@/Components/AccessGate.vue';
import DocumentMoney from '@/Components/Icons/DocumentMoney.vue';
import CartIcon from '@/Components/Icons/CartIcon.vue';
import RXIcon from '@/Components/Icons/RXIcon.vue';
import SidebarIcon from '@/Components/Icons/SidebarIcon.vue';
import SidebarFilledIcon from '@/Components/Icons/SidebarFilledIcon.vue';
import HomeIcon from '@/Components/Icons/HomeIcon.vue';
import DocumentIcon from '@/Components/Icons/DocumentIcon.vue';
const showingNavigationDropdown = ref(false);
const isDark = ref(false)
const toast = useToast();
const page = usePage();
const shownFlash = ref(null);
const read = ref(false)
const sidebarExpanded = ref(true);

function switchBranch(id) {
    router.post(route('branches.switch'), { branch_id: id }, {
        onSuccess: () => {
            router.reload({ only: ['auth'] })
        }
    })
}
const toggleSidebar = () => {
    sidebarExpanded.value = !sidebarExpanded.value;
    localStorage.setItem('sidebarExpanded', sidebarExpanded.value);
};

const notifications = ref([])
// const checkBillDate = () => {
//     const today = new Date()
//     const day = today.getDate()
//     notifications.value = []

//     const branch = page.props.auth.user.branch.name
//     const isAdmin = page.props.auth.user.roles[0] === 'admin'
//     const dueDates = {
//         "Cutupu": {
//             "⚡ Luz": 7,
//             "💧 Agua": 20,
//             "🏢 Local": 20,
//             "📞 Teléfono": 1,
//         },
//         "Principal": {
//             "⚡ Luz": 20,
//             "💧 Agua": 10,
//             "📞 Teléfono": 7,
//             "🏢 Local": 8,
//         }
//     }
//     const branchesToCheck = isAdmin ? Object.keys(dueDates) : [branch]
//     branchesToCheck.forEach(b => {
//         Object.entries(dueDates[b]).forEach(([label, dueDay]) => {
//             const daysBefore = dueDay - day

//             if (daysBefore > 0 && daysBefore <= 5) {
//                 notifications.value.push(`${label} (${b}): faltan ${daysBefore} día(s)`)
//             } else if (day === dueDay) {
//                 notifications.value.push(`${label} (${b}): ¡HOY debes pagar!`)
//             }
//         })
//     })
// }
const markAsRead = () => {
    read.value = true
}
onMounted(() => {
    // checkBillDate()
    // setInterval(checkBillDate, 1000 * 60 * 60)
    const savedSidebarState = localStorage.getItem('sidebarExpanded');
    if (savedSidebarState !== null) {
        sidebarExpanded.value = savedSidebarState === 'true';
    }
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

            <aside
                class="hidden sm:flex flex-col h-screen fixed left-0 top-0 bottom-0 bg-white dark:bg-gray-800 border-r border-gray-100 dark:border-gray-700 shadow-md transition-all duration-300 ease-in-out z-20"
                :class="{ 'w-64': sidebarExpanded, 'w-20': !sidebarExpanded }">

                <div class="bg-pink-500" :class="[
                    'flex items-center h-16 border-b border-gray-100 dark:border-gray-700',
                    { 'justify-between': sidebarExpanded, 'justify-center': !sidebarExpanded }
                ]">
                    <Link :href="route('dashboard')" class="flex items-center">
                    <img src="/img/LOGO.PNG" class="drop-shadow-xl"
                        :class="{ 'h-14 ml-4': sidebarExpanded, 'h-12 w-full': !sidebarExpanded }" />


                    <h1 class="mt-1 text-white font-bold ml-2 whitespace-nowrap overflow-hidden
           transition-all duration-300" :class="{
            'max-w-[200px] opacity-100 translate-x-0': sidebarExpanded,
            'max-w-0 opacity-0 -translate-x-3': !sidebarExpanded
        }">
                        Smile Clinic
                    </h1>

                    </Link>
                </div>

                <!-- Navigation Links -->
                <div class="flex-1 overflow-y-auto py-6 space-y-1">
                    <div class="px-3">
                        <Tooltip class="w-full" text="Inicio" position="right">
                            <Link :href="route('dashboard')" :class="[
                                route().current('dashboard') ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700',
                                'group flex items-center px-3 py-3 text-sm font-medium rounded-md transition-all',
                                { 'justify-center': !sidebarExpanded }
                            ]">
                            <HomeIcon
                                class="h-5 w-5 text-gray-500 group-hover:text-gray-500 dark:text-gray-400 dark:group-hover:text-gray-300" />
                            <span v-if="sidebarExpanded" class="truncate ml-3">Inicio</span>
                            </Link>
                        </Tooltip>
                    </div>

                    <AccessGate :role="['admin']">
                        <div class="px-3">
                            <Tooltip class="w-full" text="Sucursales" position="right">
                                <Link :href="route('branches.index')" :class="[
                                    route().current('branches.index') || route().current('branches.create') || route().current('branches.edit')  || route().current('branches.show') ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700',
                                    'group flex items-center px-3 py-3 text-sm font-medium rounded-md transition-all',
                                    { 'justify-center': !sidebarExpanded }
                                ]">
                                <BuildingIcon
                                    class="h-5 w-5 text-gray-500 group-hover:text-gray-500 dark:text-gray-400 dark:group-hover:text-gray-300" />
                                <span v-if="sidebarExpanded" class="truncate ml-3">Sucursales</span>
                                </Link>
                            </Tooltip>
                        </div>
                    </AccessGate>
                    <AccessGate :permission="['patient.view']">
                        <div class="px-3">
                            <Tooltip class="w-full" text="Pacientes" position="right">
                                <Link :href="route('patients.index')" :class="[
                                    route().current('patients.index') || route().current('patients.create') || route().current('patients.edit')  || route().current('patients.show') ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700',
                                    'group flex items-center px-3 py-3 text-sm font-medium rounded-md transition-all',
                                    { 'justify-center': !sidebarExpanded }
                                ]">
                                <UserIcon
                                    class="h-5 w-5 text-gray-500 group-hover:text-gray-500 dark:text-gray-400 dark:group-hover:text-gray-300" />
                                <span v-if="sidebarExpanded" class="truncate ml-3">Pacientes</span>
                                </Link>
                            </Tooltip>
                        </div>
                    </AccessGate>

                    <div class="px-3">
                        <AccessGate :permission="['event.view']">
                            <Tooltip class="w-full" text="Citas" position="right">
                                <Link :href="route('events.index')" :class="[
                                    route().current('events.index') || route().current('events.create') || route().current('events.edit')  || route().current('events.show') ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700',
                                    'group flex items-center px-3 py-3 text-sm font-medium rounded-md transition-all',
                                    { 'justify-center': !sidebarExpanded }
                                ]">
                                <CalendarIcon
                                    class="h-5 w-5 text-gray-500 group-hover:text-gray-500 dark:text-gray-400 dark:group-hover:text-gray-300" />
                                <span v-if="sidebarExpanded" class="truncate ml-3">Citas</span>
                                </Link>
                            </Tooltip>
                        </AccessGate>
                    </div>

                    <div class="px-3">
                        <AccessGate :permission="['budget.view']">
                            <Tooltip class="w-full" text="Presupuestos" position="right">
                                <Link :href="route('budgets.index')" :class="[
                                    route().current('budgets.index') || route().current('budgets.create') || route().current('budgets.edit')  || route().current('budgets.show') ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700',
                                    'group flex items-center px-3 py-3 text-sm font-medium rounded-md transition-all',
                                    { 'justify-center': !sidebarExpanded }
                                ]">
                                <DocumentMoney
                                    class="h-5 w-5 text-gray-500 group-hover:text-gray-500 dark:text-gray-400 dark:group-hover:text-gray-300" />
                                <span v-if="sidebarExpanded" class="truncate ml-3">Presupuestos</span>
                                </Link>
                            </Tooltip>
                        </AccessGate>
                    </div>

                    <AccessGate :permission="['bill.view']" class="px-3">
                        <Tooltip class="w-full" text="Recibos" position="right">
                            <Link :href="route('bills.index')" :class="[
                                route().current('bills.index') || route().current('bills.create') || route().current('bills.edit')  || route().current('bills.show') ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700',
                                'group flex items-center px-3 py-3 text-sm font-medium rounded-md transition-all',
                                { 'justify-center': !sidebarExpanded }
                            ]">
                            <DocumentIcon
                                class="h-5 w-5 text-gray-500 group-hover:text-gray-500 dark:text-gray-400 dark:group-hover:text-gray-300" />
                            <span v-if="sidebarExpanded" class="truncate ml-3">Recibos</span>
                            </Link>
                        </Tooltip>
                    </AccessGate>

                    <AccessGate :permission="['expense.view']" class="px-3">
                        <Tooltip class="w-full" text="Egresos" position="right">
                            <Link :href="route('expenses.index')" :class="[
                                route().current('expenses.index') || route().current('expenses.create') || route().current('expenses.edit')  || route().current('expenses.show') ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700',
                                'group flex items-center px-3 py-3 text-sm font-medium rounded-md transition-all',
                                { 'justify-center': !sidebarExpanded }
                            ]">
                            <CartIcon
                                class="h-5 w-5 text-gray-500 group-hover:text-gray-500 dark:text-gray-400 dark:group-hover:text-gray-300" />
                            <span v-if="sidebarExpanded" class="truncate ml-3">Egresos</span>
                            </Link>
                        </Tooltip>
                    </AccessGate>

                    <div class="px-3">
                        <AccessGate :permission="['prescription.view']">
                            <Tooltip class="w-full" text="Recetas" position="right">
                                <Link :href="route('prescriptions.index')" :class="[
                                    route().current('prescriptions.index') || route().current('prescription.create') || route().current('prescription.edit')  || route().current('prescription.show') ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700',
                                    'group flex items-center px-3 py-3 text-sm font-medium rounded-md transition-all',
                                    { 'justify-center': !sidebarExpanded }
                                ]">
                                <RXIcon
                                    class="h-5 w-5 text-gray-500 group-hover:text-gray-500 dark:text-gray-400 dark:group-hover:text-gray-300" />
                                <span v-if="sidebarExpanded" class="truncate ml-3">Recetas</span>
                                </Link>
                            </Tooltip>
                        </AccessGate>
                    </div>



                    <AccessGate :permission="['user.view']" class="px-3">
                        <Tooltip class="w-full" text="Usuarios" position="right">
                            <Link :href="route('users.index')" :class="[
                                route().current('users.index') || route().current('users.create') || route().current('users.edit')  || route().current('users.show') ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700',
                                'group flex items-center px-3 py-3 text-sm font-medium rounded-md transition-all',
                                { 'justify-center': !sidebarExpanded }
                            ]">
                            <UserIcon
                                class="h-5 w-5 text-gray-500 group-hover:text-gray-500 dark:text-gray-400 dark:group-hover:text-gray-300" />
                            <span v-if="sidebarExpanded" class="truncate ml-3">Usuarios</span>
                            </Link>
                        </Tooltip>
                    </AccessGate>


                </div>

            </aside>

            <!-- Contenido principal - ajustado para acomodar el sidebar -->
            <div class="flex flex-col flex-1 transition-all duration-300"
                :class="{ 'sm:ml-64': sidebarExpanded, 'sm:ml-20': !sidebarExpanded }">

                <!-- Top bar (solo para móviles) - mantiene el menú hamburguesa existente -->
                <nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 sm:hidden">

                    <!-- Primary Navigation Menu -->
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex justify-between h-16 ">
                            <div class="flex">
                                <!-- Logo -->
                                <div class="shrink-0 flex items-center">
                                    <Link :href="route('dashboard')">

                                    </Link>
                                </div>
                            </div>

                            <div class="hidden sm:flex sm:items-center sm:ms-6">
                                <!-- Componentes del menú superior que ya no usamos en escritorio pero mantenemos para móvil -->
                            </div>

                            <!-- Hamburger -->
                            <div class="-me-2 flex items-center sm:hidden">
                                <button
                                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out"
                                    @click="showingNavigationDropdown = !showingNavigationDropdown">
                                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                        <path
                                            :class="{ 'hidden': showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }"
                                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6h16M4 12h16M4 18h16" />
                                        <path
                                            :class="{ 'hidden': !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Responsive Navigation Menu - mantener igual que el original -->
                    <div :class="{ 'block': showingNavigationDropdown, 'hidden': !showingNavigationDropdown }"
                        class="sm:hidden">
                        <div class="pt-2 pb-3 space-y-1">
                            <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                Dashboard
                            </ResponsiveNavLink>
                            <ResponsiveNavLink v-if="can('patient.view')" :href="route('patients.index')"
                                :active="route().current('patients.index')">
                                Pacientes
                            </ResponsiveNavLink>
                            <ResponsiveNavLink v-if="can('branch.view')" :href="route('branches.index')"
                                :active="route().current('branches.index')">
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
                                Recibos
                            </ResponsiveNavLink>

                        </div>
                        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">


                            <div class="mt-3 space-y-1">
                                <ResponsiveNavLink :href="route('profile.edit')"
                                    :active="route().current('profile.edit')">
                                    Perfil
                                </ResponsiveNavLink>



                                <button @click="() => { switchTheme(); isDark = !isDark }" class="flex items-center w-full px-4 py-2 text-sm font-medium rounded-md transition
                   text-gray-600 hover:bg-gray-100 hover:text-gray-900
                   dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-white">
                                    <component :is="isDark ? DarkIcon : LightIcon" class="w-5 h-5 mr-2" />
                                    <span>{{ isDark ? 'Modo Oscuro' : 'Modo Claro' }}</span>
                                </button>


                                <ResponsiveNavLink :href="route('logout')">
                                    Cerrar sesión
                                </ResponsiveNavLink>



                            </div>
                        </div>
                    </div>
                </nav>

                <nav
                    class="border-b flex h-16 justify-between px-5 border-gray-100 bg-white dark:border-gray-700 dark:bg-gray-800">


                    <!-- IZQUIERDA -->
                    <button @click="toggleSidebar"
                        class="text-gray-500 hover:text-gray-700 bg-white dark:bg-gray-800 dark:text-gray-400  dark:hover:text-gray-300">
                        <SidebarIcon v-if="sidebarExpanded" class="h-5 w-5" />
                        <SidebarFilledIcon v-else class="h-5 w-5" />
                    </button>

                    <!-- DERECHA -->
                    <div class="hidden lg:ms-6 lg:flex lg:items-center ml-auto">

                        <!-- Modo oscuro -->
                        <button @click="() => { switchTheme(); isDark = !isDark }"
                            class="text-gray-500 hover:text-gray-700 bg-white dark:bg-gray-800 dark:text-gray-400 dark:hover:text-gray-300">
                            <component :is="isDark ? DarkIcon : LightIcon" class="w-5 h-5" />
                        </button>

                        <!-- Notificaciones -->
                        <div class="relative ml-2">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button @click="notificationOpen = !notificationOpen; markAsRead()"
                                            class="relative inline-flex items-center p-2 rounded-md text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
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
                                        <Link :href="route('expenses.index')" v-for="(note, index) in notifications"
                                            :key="index"
                                            class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">
                                        {{ note }}
                                        </Link>
                                    </div>

                                    <div v-else class="flex justify-center">
                                        <p
                                            class="block w-full px-4 py-4 text-center text-sm text-gray-700 dark:text-gray-300">
                                            <InfoCircle class="mx-auto mb-2 dark:text-white w-7 h-7" />
                                            No tiene notificaciones pendientes.
                                        </p>
                                    </div>
                                </template>
                            </Dropdown>
                        </div>

                        <!-- Usuario -->
                        <div class="relative ms-1">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button type="button"
                                            class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 dark:bg-gray-800 dark:text-gray-400">
                                            {{ $page.props.auth.user.first_name }}
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
                                    <div v-for="branch in page.props.auth.user.branches" :key="branch.id">
                                        <button :disabled="branch.id === page.props.auth.user.active_branch_id"
                                            @click="switchBranch(branch.id)" :class="{
                                                'bg-pink-500 text-white dark:hover:bg-pink-800 hover:bg-pink-400 ': branch.id === page.props.auth.user.active_branch_id,
                                            }"
                                            class="block w-full px-4 py-2 text-start text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">
                                            {{ branch.name }}
                                        </button>
                                    </div>

                                    <DropdownLink :href="route('profile.edit')">
                                        Perfil
                                    </DropdownLink>

                                    <DropdownLink :href="route('logout')" method="post" as="button">
                                        Cerrar Sesión
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>



                    </div>
                </nav>

                <!-- Page Heading -->
                <header v-if="$slots.header" class="bg-white dark:bg-gray-800 shadow flex items-center justify-between">
                    <div class="max-w-7xl py-6 px-4 sm:px-6 lg:px-8 flex items-center">

                        <slot name="header" />


                    </div>


                </header>


            </div>



            <main class="flex-1 transition-all duration-300" :class="{
                'sm:ml-64': sidebarExpanded,
                'sm:ml-20': !sidebarExpanded
            }">
                <slot />
            </main>

            <Footer class="flex-1 transition-all duration-300 sticky top-[100vh]" :class="{
                'sm:ml-64': sidebarExpanded,
                'sm:ml-20': !sidebarExpanded
            }" />
        </div>
    </div>
</template>
