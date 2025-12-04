<template>

    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <Breadcrumb :crumbs="crumbs" />

        </template>
        <div class="py-8">
            <div class="mx-auto max-w-screen-2xl px-4 sm:px-6 lg:px-8 bg-white dark:bg-gray-800 p-5 rounded-xl">

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                    <!-- Today's Appointments -->
                    <div
                        class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-900/20 rounded-2xl shadow-md p-6 border border-blue-100 dark:border-blue-900/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-blue-600 dark:text-blue-300">Citas de Hoy</p>
                                <h3 class="text-2xl font-bold text-blue-800 dark:text-white mt-1">{{
                                    todayAppointments.length }}
                                </h3>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Programadas para hoy</span>
                            </div>
                            <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900/50">
                                <CalendarIcon class="h-8 w-8 text-blue-600 dark:text-blue-400" />
                            </div>
                        </div>
                    </div>

                    <!-- Total Revenue -->
                    <div
                        class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/30 dark:to-green-900/20 rounded-2xl shadow-md p-6 border border-green-100 dark:border-green-900/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-green-600 dark:text-green-300">Ingresos Totales</p>
                                <h3 class="text-2xl font-bold text-green-800 dark:text-white mt-1">${{
                                    formatNumber(totalRevenue) }}</h3>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Facturado en total</span>
                            </div>
                            <div class="p-3 rounded-full bg-green-100 dark:bg-green-900/50">
                                <svg class="h-8 w-8 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-10v10m6-5c0 3.314-2.686 6-6 6S6 13.314 6 10s2.686-6 6-6 6 2.686 6 6z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- No Show % -->
                    <div
                        class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/30 dark:to-red-900/20 rounded-2xl shadow-md p-6 border border-red-100 dark:border-red-900/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-red-600 dark:text-red-300">% No-Show</p>
                                <h3 class="text-2xl font-bold text-red-800 dark:text-white mt-1">{{ noShowPercentage }}%
                                </h3>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Pacientes que no
                                    asistieron</span>
                            </div>
                            <div class="p-3 rounded-full bg-red-100 dark:bg-red-900/50">
                                <svg class="h-8 w-8 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18 8a6 6 0 11-12 0 6 6 0 0112 0zm0 0v8m-6-4h6" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Patients -->
                    <div
                        class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/30 dark:to-purple-900/20 rounded-2xl shadow-md p-6 border border-purple-100 dark:border-purple-900/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-purple-600 dark:text-purple-300">Pacientes Nuevos</p>
                                <h3 class="text-2xl font-bold text-purple-800 dark:text-white mt-1">{{
                                    recentPatients.length }}
                                </h3>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Esta semana</span>
                            </div>
                            <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900/50">
                                <UserIcon class="h-8 w-8 text-purple-600 dark:text-purple-400" />
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Charts -->
                <div class="grid grid-cols-1 gap-6 mb-6">

                    <!-- Top procedures + Trend -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6 border border-gray-100 dark:border-gray-700 w-full">

                        <div class="flex flex-col md:flex-row items-center justify-between mb-4 gap-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Procedimientos Más
                                Realizados</h3>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white md:mx-auto pr-2">
                                Tendencia de Citas Atendidas
                            </h3>
                        </div>

                        <div class="flex flex-col lg:flex-row items-center justify-between gap-6 w-full">

                            <!-- Donut -->
                            <div class="w-full lg:w-1/2">
                                <VueApexCharts type="donut" height="300" width="100%" :options="optionsTopProcedures"
                                    :series="seriesTopProcedures" />
                            </div>

                            <!-- Trend line -->
                            <div class="w-full lg:w-1/2">
                                <VueApexCharts type="line" height="300" width="100%" :options="optionsAppointmentsTrend"
                                    :series="[{ name: 'Citas Atendidas', data: appointmentsTrendSeries }]" />
                            </div>

                        </div>
                    </div>

                    <!-- Today Agenda -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6 border border-gray-100 dark:border-gray-700 w-full mt-6">
                        <div class="flex flex-col md:flex-row items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Agenda del Día</h3>
                        </div>

                        <div class="space-y-3">
                            <div v-for="cita in todayAppointments" :key="cita.id"
                                class="p-3 rounded-lg bg-gray-50 dark:bg-gray-700 flex justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-gray-700 dark:text-white">
                                        {{ cita.patient?.name }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ formatDate(cita.date) }}</p>
                                </div>

                                <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-700">
                                    {{ cita.branch?.name }}
                                </span>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </div>


    </AuthenticatedLayout>
</template>


<script>
import { Head, Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { markRaw } from 'vue';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AccessGate from '@/Components/AccessGate.vue';
import Breadcrumb from '@/Components/BreadCrumb.vue';

import VueApexCharts from 'vue3-apexcharts';

import DashboardIcon from '@/Components/Icons/DashboardIcon.vue';
import DocumentMoney from '@/Components/Icons/DocumentMoney.vue';
import CartIcon from '@/Components/Icons/CartIcon.vue';
import AddIcon from '@/Components/Icons/AddIcon.vue';
import SearchIcon from '@/Components/Icons/SearchIcon.vue';
import PrintIcon from '@/Components/Icons/PrintIcon.vue';
import CalendarIcon from '@/Components/Icons/CalendarIcon.vue';
import UserIcon from '@/Components/Icons/UserIcon.vue';

export default {
    props: {
        todayAppointments: Array,
        totalRevenue: Number,
        totalAppointments: Number,
        noShowCount: Number,
        noShowPercentage: Number,
        recentPatients: Array,
        topProcedures: [Object, Array, null],
        appointmentsTrendSeries: Array,
        appointmentsTrendCategories: Array,
        user: Object,
    },

    components: {
        Head,
        Link,
        AuthenticatedLayout,
        AccessGate,
        Breadcrumb,
        VueApexCharts,
        DashboardIcon,
        DocumentMoney,
        CartIcon,
        AddIcon,
        SearchIcon,
        PrintIcon,
        CalendarIcon,
        UserIcon,
    },

    data() {
        return {
            crumbs: [
                { icon: markRaw(DashboardIcon), label: 'Dashboard', to: route('dashboard') },
            ],

            optionsTopProcedures: {
                chart: { type: 'donut', foreColor: '#1f2937' },
                labels: [],
                legend: { position: 'bottom' },
                theme: { mode: 'light' },
            },
            seriesTopProcedures: [],

            optionsAppointmentsTrend: {
                chart: { type: 'area', foreColor: '#1f2937' },
                xaxis: { categories: [] },
                yaxis: { labels: { formatter: (v) => v.toLocaleString() } },
                stroke: { curve: 'smooth' },
                theme: { mode: 'light' },
            },

            isLoadingPrint: false,
        };
    },

    mounted() {

        const tp = this.topProcedures ?? [];

        if (tp && Array.isArray(tp)) {
            if (tp.length && typeof tp[0] === 'object' && (tp[0].name || tp[0].label)) {
                const labels = tp.map(x => x.name ?? x.label ?? '—');
                const series = tp.map(x => Number(x.value ?? x.count ?? x.total ?? 0));
                this.optionsTopProcedures.labels = labels;
                this.seriesTopProcedures = series;
            } else {
                this.optionsTopProcedures.labels = tp.map(String);
                this.seriesTopProcedures = tp.map(() => 0);
            }
        } else if (tp && typeof tp === 'object' && (tp.labels || tp.series)) {
            this.optionsTopProcedures.labels = tp.labels ?? [];
            this.seriesTopProcedures = tp.series ?? [];
        } else {
            this.optionsTopProcedures.labels = [];
            this.seriesTopProcedures = [];
        }


        const ats = this.appointmentsTrendSeries ?? [];

        this.seriesAppointmentsTrend = [{
            name: 'Citas Atendidas',
            data: ats
        }];

        if (this.$props.appointmentsTrendCategories) {
            this.optionsAppointmentsTrend.xaxis.categories = this.$props.appointmentsTrendCategories;
        }
        else if (Array.isArray(ats) && ats.every(v => typeof v === 'number')) {
            this.seriesAppointmentsTrend = [{ name: 'Citas', data: ats }];
            this.optionsAppointmentsTrend.xaxis.categories = ats.map((_, i) => i + 1);
        } else {
            this.seriesAppointmentsTrend = [];
            this.optionsAppointmentsTrend.xaxis.categories = [];
        }

        const applyTheme = (isDark) => {
            const textColor = isDark ? '#f9fafb' : '#1f2937';
            const paletteLight = ['#EC4899', '#10b981', '#f59e0b', '#ef4444', '#6366f1'];
            const paletteDark = ['#EC4899', '#34d399', '#fbbf24', '#f87171', '#818cf8'];
            const palette = isDark ? paletteDark : paletteLight;
            const themeMode = isDark ? 'dark' : 'light';

            this.optionsTopProcedures.colors = [palette[0], palette[1], palette[2], palette[3], palette[4]];
            this.optionsTopProcedures.chart.foreColor = textColor;
            this.optionsTopProcedures.theme = { mode: themeMode };

            this.optionsAppointmentsTrend.colors = [palette[4]];
            this.optionsAppointmentsTrend.chart.foreColor = textColor;
            this.optionsAppointmentsTrend.theme = { mode: themeMode };

        };

        applyTheme(document.documentElement.classList.contains('dark'));
        this._darkObserver = new MutationObserver(() => {
            applyTheme(document.documentElement.classList.contains('dark'));
        });
        this._darkObserver.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
    },

    beforeUnmount() {
        if (this._darkObserver) {
            this._darkObserver.disconnect();
            this._darkObserver = null;
        }
    },

    methods: {
        formatDate(date) {
            if (!date) return '';
            const d = new Date(date);
            return d.toISOString().split('T')[0];
        },
        formatNumber(n) {
            if (n === null || n === undefined) return '0';
            return Number(n).toLocaleString('es-DO');
        },
        formatCurrency(n) {
            return new Intl.NumberFormat('es-DO', { style: 'currency', currency: 'DOP' }).format(n ?? 0);
        },
        print() {
            this.isLoadingPrint = true;
            router.visit(route('report.dailycashbalance')).finally(() => (this.isLoadingPrint = false));
        },
    }
};
</script>
