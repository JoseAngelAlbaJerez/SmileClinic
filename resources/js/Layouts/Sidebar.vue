<template>
  <nav
    :class="toggleSidebar ? 'w-[220px]' : 'w-[76px]'"
    class="relative z-[51] flex-shrink-0 bg-blue-500 h-[100vh] isolate transition-all duration-200">
    <div class="h-full pt-4">
      <div class="flex flex-col justify-between h-full">
        <div class="flex-1 flex flex-col gap-4 overflow-y-auto">
          <div class="text-white h-9">
            <a href="/agent/home"
               class="flex items-center pl-5 mt-[5px] font-bold">
              JMS
            </a>
          </div>
          <SidebarMenuNav :toggle-sidebar="toggleSidebar" />
        </div>
        <ul class="text-white pt-9">
          <li class="group relative text-white">
            <div v-for="(item, index) in menuItems" :key="index">
              <div
                :class="
                  isAnySubItemActive(item.subItems)
                    ? 'bg-[rgba(255,255,255,0.35)] group-hover:!bg-[rgba(255,255,255,0.35)]'
                    : ''
                "
                class="cursor-pointer text-white flex items-center h-12 gap-3 pl-6 bg-[rgba(255,255,255,0.1)] hover:bg-[rgba(255,255,255,0.25)] transition-all duration-200">
                <div v-html="item.icon"></div>
                <span v-if="toggleSidebar" class="whitespace-nowrap">
                  {{ item.title }}
                </span>
              </div>
              <div
                :class="toggleSidebar ? 'left-full' : 'left-[76px]'"
                class="bg-[#3e4454] absolute bottom-0 opacity-0 pointer-events-none flex flex-col shadow-[4px_8px_30px_2px_rgb(0_0_0/60%)] min-w-[230px] transition-all duration-300 ease-out group-hover:opacity-100 group-hover:pointer-events-auto">
                <ul>
                  <li
                    v-for="(subItem, subIndex) in item.subItems"
                    :key="subIndex"
                    class="relative"
                    :class="
                      isActive(subItem.link)
                        ? 'bg-[rgba(255,255,255,0.35)]'
                        : 'hover:bg-[rgba(248,250,252,0.20)]'
                    "
                  >
                    <a
                      :href="subItem.link"
                      class="flex flex-wrap text-sm px-3 py-4 transition-all duration-300 hover:text-white hover:bg-[rgba(248,250,252,0.20)]">
                      <span class="block w-full whitespace-nowrap truncate">
                        {{ subItem.title }}
                      </span>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </li>
          <li class="relative h-12 flex items-center justify-end pr-7 cursor-pointer text-white bg-[rgba(255,255,255,0.2)] group/logout"
              @click="handleToggleSidebar">
            <div
              class="transform transition-all duration-300"
              :class="toggleSidebar ? '-rotate-180' : ''"
            >
              <span class="flex items-center">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="16"
                  height="16"
                  fill="currentColor"
                  viewBox="0 0 16 16"
                  class="w-6 h-6"
                >
                  <path
                    d="m8.001 2.666-.94.94 3.72 3.727H2.668v1.333h8.113l-3.72 3.727.94.94 5.334-5.334L8 2.666Z"
                  ></path>
                </svg>
              </span>
            </div>

            <div class="absolute min-w-[88px] -right-[100px] opacity-0 pointer-events-none group-hover/logout:opacity-100 group-hover/logout:pointer-events-auto transition duration-300 text-xs text-center text-white bg-gray-800 shadow-[0_4px_6px_0_rgba(0,0,0,0.25)] rounded p-2">
              <span>Open/Collapse</span>
              <span class="absolute -left-3 w-0 h-0 border-t-[7px] border-t-transparent border-r-[12px] border-r-slate-700 border-b-[7px] border-b-transparent"></span>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref } from 'vue';
import SidebarMenuNav from './SidebarMenuNav.vue';

const toggleSidebar = ref(
  localStorage.getItem('toggleSidebar') === 'true' || false
);
const currentPath = ref(window.location.pathname);
const menuItems = ref([
  {
    id: 1,
    icon: '<span class="flex items-center"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24" class="w-6 h-6"><path d="M19.138 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58a.49.49 0 0 0 .12-.61l-1.92-3.32a.488.488 0 0 0-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54a.484.484 0 0 0-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22l-1.91 3.32c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94 0 .31.02.64.07.94l-2.03 1.58a.49.49 0 0 0-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58Zm-7.14 2.66a3.61 3.61 0 0 1-3.6-3.6c0-1.98 1.62-3.6 3.6-3.6s3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6Z"></path></svg></span>',
    title: 'Settings',
    link: '',
    subItems: [
      {
        id: 'account',
        title: 'Account',
        sub_text: '',
        link: '#',
        secondaryItems: [],
        secondary_open: false,
        state: true,
        count: 0,
      },
      {
        id: 'billing-information',
        title: 'Billing',
        sub_text: '',
        link: '#',
        secondaryItems: [],
        secondary_open: false,
        state: true,
        count: 0,
      },
    ],
    open: false,
    new: false,
    count: 0,
    manuallyToggled: false,
  },
]);

const handleToggleSidebar = () => {
  toggleSidebar.value = !toggleSidebar.value;

  if (toggleSidebar.value) {
    localStorage.setItem('toggleSidebar', 'true');
  } else {
    localStorage.removeItem('toggleSidebar');
  }
};

const isActive = (route) => {
  return currentPath.value === route;
};

const isAnySubItemActive = (item) => {
  return item.some((subItem) => isActive(subItem.link));
};

window.addEventListener('popstate', () => {
  currentPath.value = window.location.pathname;
});
</script>
