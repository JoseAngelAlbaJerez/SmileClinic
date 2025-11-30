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
                    <!-- Patients Card -->
                    <div
                        class="bg-gradient-to-br from-pink-50 to-pink-100 dark:from-pink-900/30 dark:to-pink-900/20 rounded-2xl shadow-md p-6 border border-pink-100 dark:border-pink-900/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-pink-600 dark:text-pink-300">Total Pacientes</p>
                                <h3 class="text-2xl font-bold text-pink-800 dark:text-white mt-1">{{ patients.length }}
                                </h3>
                                <div class="mt-2">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Registrados en el
                                        sistema</span>
                                </div>
                            </div>
                            <div class="p-3 rounded-full bg-pink-100 dark:bg-pink-900/50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-pink-600 dark:text-pink-400"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Users Card -->
                    <div
                        class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/30 dark:to-purple-900/20 rounded-2xl shadow-md p-6 border border-purple-100 dark:border-purple-900/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-purple-600 dark:text-purple-300">Usuarios</p>
                                <h3 class="text-2xl font-bold text-purple-800 dark:text-white mt-1">{{ users.length }}
                                </h3>
                                <div class="mt-2">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Registrados en el
                                        sistema</span>
                                </div>
                            </div>
                            <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900/50">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-8 w-8 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Weekly Income Card -->
                    <div
                        class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/30 dark:to-green-900/20 rounded-2xl shadow-md p-6 border border-green-100 dark:border-green-900/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-green-600 dark:text-green-300">Citas No-show
                                </p>
                                <h3 class="text-2xl font-bold text-green-800 dark:text-white mt-1">{{ no_show?.length || 0 }}</h3>
                                <div class="flex items-center mt-2">

                                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">Citas no atendidas de la semana</span>
                                </div>
                            </div>
                            <div class="p-3 rounded-full bg-green-100 dark:bg-green-900/50">

                                <CalendarIcon class="h-8 w-8 text-green-600 dark:text-green-400" />
                            </div>
                        </div>
                    </div>

                    <!-- Net Profit Card -->
                    <div
                        class="bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-900/30 dark:to-amber-900/20 rounded-2xl shadow-md p-6 border border-amber-100 dark:border-amber-900/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-amber-600 dark:text-amber-300">Balance Total</p>
                                <h3 class="text-2xl font-bold text-amber-800 dark:text-white  mt-1"
                                    :class="income_sum > expense_sum ? 'text-amber-500' : 'text-amber-500'">${{
                                        formatNumber(income_sum - expense_sum) }} {{ income_sum > expense_sum ? '+' : '' }}
                                </h3>
                                <div class="mt-2">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Ingresos: ${{
                                        formatNumber(income_sum) }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 block">Gastos: ${{
                                        formatNumber(expense_sum) }}</span>
                                </div>
                            </div>
                            <div class="p-3 rounded-full bg-amber-100 dark:bg-amber-900/50">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-8 w-8 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Transactions -->
                <div class="grid grid-cols-1 lg:grid-cols-1 gap-6 mb-6">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6 border border-gray-100 dark:border-gray-700 w-full">
                        <div class="flex flex-col md:flex-row items-center justify-between mb-4 gap-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Ingresos por Sucursal</h3>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white md:mx-auto">Ranking
                                Sucursales</h3>
                        </div>


                        <div class="flex flex-col lg:flex-row items-center justify-between gap-6 w-full">
                            <VueApexCharts type="bar" height="300" width="230%" :options="optionsIngresos"
                                :series="seriesIngresos" />
                            <VueApexCharts type="line" height="300" width="230%" :options="optionsTendencia"
                                :series="seriesTendencia" />
                        </div>
                    </div>


                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6 border border-gray-100 dark:border-gray-700 w-full mt-6">
                        <div class="flex flex-col md:flex-row items-center justify-between mb-4 gap-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Citas Atendidas
                            </h3>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white md:mx-auto">Agenda de hoy
                            </h3>
                        </div>


                        <div class="flex flex-col lg:flex-row items-center justify-between gap-6 w-full">
                            <VueApexCharts type="donut" height="300" width="250%" :options="optionsServicios"
                                :series="seriesServicios" />


                            <iframe id="open-web-calendar"
                                src="https://calendar.google.com/calendar/embed?height=400&wkst=2&ctz=America%2FSanto_Domingo&showPrint=0&mode=AGENDA&title=Citas%20Smile%20Clinic&src=am9zZWFuZ2VsYWxiYTI0QGdtYWlsLmNvbQ&src=NGVlY2JhYzU3ZWNlOGNkODEyZDZiYmNiNzY4ODEyZjYxMWM5M2NlN2VmYTdlYTU4ZjMyYmNkYWIzZmQyMWI4OUBncm91cC5jYWxlbmRhci5nb29nbGUuY29t&src=ZXMtNDE5LmRvI2hvbGlkYXlAZ3JvdXAudi5jYWxlbmRhci5nb29nbGUuY29t&color=%23039BE5&color=%237986CB&color=%230B8043"
                                class="rounded w-full h-[350px] border-0"
                                sandbox="allow-scripts allow-same-origin allow-top-navigation"
                                allowtransparency="true"></iframe>
                        </div>
                    </div>


                </div>
            </div>


        </div>
    </AuthenticatedLayout>
</template>

<script>
import { Head, Link, useForm } from '@inertiajs/vue3';
import DocumentMoney from '@/Components/Icons/DocumentMoney.vue';
import CartIcon from '@/Components/Icons/CartIcon.vue';
import AddIcon from '@/Components/Icons/AddIcon.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AccessGate from '@/Components/AccessGate.vue';
import SearchIcon from '@/Components/Icons/SearchIcon.vue';
import { markRaw } from 'vue';
import PrintIcon from '@/Components/Icons/PrintIcon.vue';
import { router } from "@inertiajs/vue3"
import DashboardIcon from '@/Components/Icons/DashboardIcon.vue';
import Breadcrumb from '@/Components/BreadCrumb.vue';
import VueApexCharts from 'vue3-apexcharts';
import CalendarIcon from '@/Components/Icons/CalendarIcon.vue';

export default {
    props: {
        patients: Array,
        branches: Array,
        users: Array,
        income_sum: Number,
        expense_sum: Number,
        income: Array,
        expense: Array,
        incomeThisWeek: Number,
        incomeLastWeek: Number,
        percentageChange: Number,
        total: Number,
        user: Object,
        branch: Object,
        chart: Object,
        no_show: Object
    },
    components: {
        Head,
        CartIcon,
        Link,
        DocumentMoney,
        SearchIcon,
        AccessGate,
        AuthenticatedLayout,
        AddIcon,
        PrintIcon,
        Breadcrumb,
        VueApexCharts,
        CalendarIcon

    },
    methods: {
        handleBranchChange() {
            this.form.get(route('branches.index'));
        },
        formatDate(date) {
            const d = new Date(date);
            return d.toISOString().split('T')[0];
        },
        formatNumber(n) {
            return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
        print() {
            router.visit(route("report.dailycashbalance"));
        },

    },
    data() {
        return {
            crumbs: [
                { icon: markRaw(DashboardIcon), label: 'Dashboard', to: route('dashboard') },
            ],
            optionsIngresos: {
                colors: ["#3b82f6", "#10b981", "#f59e0b"],
                chart: { foreColor: '#1f2937' },
            },
            optionsTendencia: {
                colors: ["#6366f1"],
                chart: { foreColor: '#1f2937' },
            },
            optionsServicios: {
                colors: ["#ef4444", "#3b82f6", "#22c55e", "#eab308"],
                chart: { foreColor: '#1f2937' },
            },
            seriesIngresos: [],
            seriesTendencia: [],
            seriesServicios: [],


        }

    },
    mounted() {
        const ingresosSeries = this.chart?.ingresos?.series ?? [];
        const ingresosLabels = this.chart?.ingresos?.labels ?? [];

        const tendenciaSeries = this.chart?.tendencia?.series ?? [];
        const tendenciaCats = this.chart?.tendencia?.categories ?? [];

        const serviciosSeries = this.chart?.procedimientos?.series ?? [];
        const serviciosLabels = this.chart?.procedimientos?.labels ?? [];

        this.seriesIngresos = [{ name: 'Ingresos', data: ingresosSeries }];
        this.optionsIngresos.xaxis = { categories: ingresosLabels };
        this.optionsIngresos.yaxis = {
            labels: {
                formatter: (value) => `$${value.toLocaleString()}`
            }
        };
         this.optionsIngresos.tooltip = {
            labels: {
                formatter: (value) => `$${value.toLocaleString()}`
            }
        };
        this.seriesTendencia = [{ name: 'Tendencia', data: tendenciaSeries }];
        this.optionsTendencia.xaxis = { categories: tendenciaCats };
        this.optionsTendencia.yaxis = {
            labels: {
                formatter: (value) => `$${value.toLocaleString()}`
            }
        }
        this.optionsTendencia.tooltip = {
            y: {
                formatter: (value) => `$${value.toLocaleString()}`
            }
        }
        this.seriesServicios = serviciosSeries;
        this.optionsServicios.labels = serviciosLabels;
        this.optionsServicios = { ...this.optionsServicios };

        const applyTheme = (isDark) => {
            const textColor = isDark ? '#f9fafb' : '#1f2937';
            const paletteLight = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#6366f1'];
            const paletteDark = ['#60a5fa', '#34d399', '#fbbf24', '#f87171', '#818cf8'];
            const palette = isDark ? paletteDark : paletteLight;
            const themeMode = isDark ? 'dark' : 'light';

            this.optionsIngresos.colors = [palette[0], palette[1], palette[2]];
            this.optionsIngresos.chart.foreColor = textColor;
            this.optionsIngresos.theme = { mode: themeMode };

            this.optionsTendencia.colors = [palette[4]];
            this.optionsTendencia.chart.foreColor = textColor;
            this.optionsTendencia.theme = { mode: themeMode };

            this.optionsServicios.colors = [palette[3], palette[0], palette[1], palette[2]];
            this.optionsServicios.chart.foreColor = textColor;
            this.optionsServicios.theme = { mode: themeMode };

            if (this.$refs.ingresosChart?.updateOptions) {
                this.$refs.ingresosChart.updateOptions(this.optionsIngresos, false, true);
            }
            if (this.$refs.tendenciaChart?.updateOptions) {
                this.$refs.tendenciaChart.updateOptions(this.optionsTendencia, false, true);
            }
            if (this.$refs.serviciosChart?.updateOptions) {
                this.$refs.serviciosChart.updateOptions(this.optionsServicios, false, true);
            }
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
    watch: {
        branches: {
            handler(newBranches) {
                this.updateBranchesChart(newBranches);
            },
            deep: true
        },
        income: {
            handler(newIncome) {
                this.updateIncomeChart(newIncome);
            },
            deep: true
        }
    },

}


</script>
