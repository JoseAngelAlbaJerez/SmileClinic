<template>

    <Head title="Citas" />
    <AuthenticatedLayout>
        <template #header>
            <Breadcrumb :crumbs="crumbs" />

        </template>

        <template #default>
            <div
                class="flex items-center justify-center rounded-lg bg-white-500 py-12 dark:bg-gray-900 dark:text-white">
                <div class="container mx-auto w-full px-2">


                    <Calendar :initialEvents="events" :filters="filters" />
                </div>
            </div>


        </template>

    </AuthenticatedLayout>

</template>


<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import ArrowDownTrayIcon from '@/Components/Icons/ArrowDownTrayIcon.vue';
import ArrowLongDownIcon from '@/Components/Icons/ArrowLongDownIcon.vue';
import ArrowLongUpIcon from '@/Components/Icons/ArrowLongUpIcon.vue';
import ChevronDoubleLeftIcon from '@/Components/Icons/ChevronDoubleLeftIcon.vue';
import ChevronDoubleRightIcon from '@/Components/Icons/ChevronDoubleRightIcon.vue';
import ChevronLeftIcon from '@/Components/Icons/ChevronLeftIcon.vue';
import ChevronRightIcon from '@/Components/Icons/ChevronRightIcon.vue';
import DocumentIcon from '@/Components/Icons/DocumentIcon.vue';
import FunnelIcon from '@/Components/Icons/FunnelIcon.vue';
import HandThumbUp from '@/Components/Icons/HandThumbUp.vue';
import HandThumbDown from '@/Components/Icons/HandThumbDown.vue';
import AddIcon from '@/Components/Icons/AddIcon.vue';
import LastDaysFilter from '@/Components/LastDaysFilter.vue';
import Pagination from '@/Components/Pagination.vue';
import SearchIcon from '@/Components/Icons/SearchIcon.vue';
import Breadcrumb from '@/Components/BreadCrumb.vue';
import Modal from '@/Components/Modal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import DeleteIcon from '@/Components/Icons/DeleteIcon.vue';
import CalendarIcon from '@/Components/Icons/CalendarIcon.vue';
import { ref } from 'vue';
import { markRaw } from 'vue'
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TableIcon from '@/Components/Icons/TableIcon.vue';
import EditIcon from '@/Components/Icons/EditIcon.vue';
import RestoreIcon from '@/Components/Icons/RestoreIcon.vue';
import Calendar from '@/Components/Calendar.vue';
export default {

    props: {
        events: Array,
        filters: Object
    },
    components: {
        AuthenticatedLayout,
        Head,
        Pagination,
        HandThumbUp,
        DeleteIcon,
        HandThumbDown,
        DangerButton,
        SecondaryButton,
        Breadcrumb,
        ArrowDownTrayIcon,
        LastDaysFilter,
        ArrowLongUpIcon,
        ArrowLongDownIcon,
        ChevronDoubleLeftIcon,
        ChevronDoubleRightIcon,
        ChevronLeftIcon,
        ChevronRightIcon,
        EditIcon,
        DocumentIcon,
        FunnelIcon,
        SearchIcon,
        Link,
        Modal,
        TableIcon,
        AddIcon,
        RestoreIcon,
        Calendar

    },
    watch: {
        selectedOptions() {
            this.fetchFilteredevents();
        },

    },
    data() {
        return {
            form: {
                search: this.filters?.search || '',
                sortField: this.filters?.sortField || 'events.updated_at',
                sortDirection: this.filters?.sortDirection || 'asc',
                patient_id: this.filters?.patient_id,
                lastDays: this.filters?.lastDays || '1',
                showDeleted: this.filters?.showDeleted || true,
            },
            showModal: ref(false),
            selectedEvent: ref(null),
            timeout: 3000,
            crumbs: [
                { icon: markRaw(CalendarIcon), label: 'Citas', to: route('events.index') },
                { icon: markRaw(TableIcon), label: 'Listado' }
            ]

        }
    },
    mounted() {
        if (this.filters?.activeStates) {
            this.form.activeStates = [...this.filters.activeStates];
        }
    },
    methods: {
        formatDate(date) {
            const d = new Date(date);
            return d.toISOString().split('T')[0];
        },
        openModal(event) {
            this.selectedEvent = event;
            this.showModal = true;
        },
        AttendEvent() {
            this.selectedEvent.attended = !this.selectedEvent.attended;
            this.$inertia.put(route('events.update', this.selectedEvent.id),
                { attended: this.selectedEvent.attended },
            );
        },
        deleteEvent(id) {
            this.$inertia.delete(route('events.destroy', id),);
        },
        restoreEvent(id) {
            this.$inertia.put(route('events.update', id),
                { active: true },
            );
        },


        statusBadgeClasses(status) {
            return {
                1: "bg-green-200 text-green-700 px-2 py-1 rounded",
                0: "bg-red-200 text-red-700 px-2 py-1 rounded",
            }[status] || "bg-gray-200 text-gray-700 px-2 py-1 rounded";
        },

        statusIndicatorClasses(status) {
            return {
                Up: "bg-green-600 size-2 rounded-full animate-pulse",
                Down: "bg-red-600 size-2 rounded-full animate-ping",
            }[status] || "bg-gray-600 size-2 rounded-full";
        },
        submitFilters() {
            if (this.timeout) {
                clearTimeout(this.timeout);
            }

            this.timeout = setTimeout(() => {

                this.form.lastDays = this.filters.lastDays || '1',
                    this.form.search = this.filters.search || '',
                    this.$inertia.get(route('events.index'), this.form, {
                        preserveState: true,
                        preserveScroll: true,
                        replace: true
                    });
            }, 300);
        },
        sort(field) {
            this.form.sortField = field;
            this.form.sortDirection = this.form.sortDirection === 'asc' ? 'desc' : 'asc';
            this.submitFilters();
        },
        toggleShowDeleted() {

            this.form.showDeleted = !this.form.showDeleted;

            this.submitFilters();

        }


    }
};

</script>
<style scoped>
.slide-fade-enter-active,
.slide-fade-leave-active {
    transition: all 0.2s ease;
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    opacity: 0;
    transform: translateY(-5px);
}

.slide-fade-enter-to,
.slide-fade-leave-from {
    opacity: 1;
    transform: translateY(0);
}
</style>
