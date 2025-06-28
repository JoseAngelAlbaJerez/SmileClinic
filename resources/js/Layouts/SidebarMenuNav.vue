<template>
  <ul class="gap-3 overflow-y-auto overflow-x-hidden text-white transition-all duration-200 space-y-3">
    <li class="group transition-all duration-200">
      <a
        href="/"
        :class="toggleSidebar ? 'flex flex-row items-start' : ''"
        class="font-medium cursor-pointer relative hover:text-white"
      >
        <span
          :class="[
            !toggleSidebar ? 'rounded-[8px] ml-5 w-8 h-8' : 'pl-5 h-10 w-10',
            isActive('/')
              ? 'bg-[rgba(255,255,255,0.35)]'
              : 'group-hover:bg-[rgba(255,255,255,0.2)]',
          ]"
          class="flex items-center flex-shrink-0 justify-center flex-shrink-0 group-hover:text-white hover:!text-white transition duration-300 relative">
          <span class="flex items-center">
               <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="18"
                  height="18"
                  fill="currentColor"
                  viewBox="0 0 18 18"
                  class="w-5 h-5"
                >
                  <path
                    d="M7.5 15.375v-4.5h3v4.5h3.75v-6h2.25L9 2.625l-7.5 6.75h2.25v6H7.5Z"
                  ></path>
                </svg>
          </span>
        </span>

        <span
          class="whitespace-nowrap"
          :class="[
            isActive('/') && toggleSidebar
              ? 'bg-[rgba(255,255,255,0.35)] group-hover:!bg-[rgba(255,255,255,0.35)]'
              : '',
            toggleSidebar
              ? 'flex-1 text-xsm transition duration-300 h-10 flex items-center pl-2 group-hover:bg-[rgba(255,255,255,0.2)]'
              : 'block mx-auto mt-1 text-center text-[10px]',
          ]"
        >
          Home
        </span>
      </a>
    </li>
    <li
      v-for="(item, index) in menuItems"
      :key="index"
      class="group transition-all duration-200"
    >
      <div
        :class="toggleSidebar ? 'flex flex-row items-start' : ''"
        class="font-medium cursor-pointer relative"
        @click="toggleAccordion(index)"
      >
        <a
          :href="item.link"
          :class="[
            !toggleSidebar ? 'rounded-[8px] ml-5 w-8 h-8' : 'pl-5 h-10 w-10',
            isAnySubItemActive(item.subItems)
              ? 'bg-[rgba(255,255,255,0.35)]'
              : 'group-hover:bg-[rgba(255,255,255,0.2)]',
          ]"
          class="flex items-center justify-center flex-shrink-0 group-hover:text-white hover:!text-white transition duration-300 relative">
          <div v-html="item.icon"></div>
          <span
            v-if="!toggleSidebar && item.new"
            class="absolute -top-1 -right-4 h-[14px] w-[30px] leading-[11px] rounded bg-red-500 text-[10px] uppercase font-bold text-white flex items-center justify-center">
            New
          </span>

          <span
            v-if="!toggleSidebar && item.count"
            class="absolute top-3 -left-0.5 w-1.5 h-1.5 rounded-full bg-red-500 flex items-center justify-center"></span>
        </a>

        <span
          class="whitespace-nowrap"
          :class="[
            isAnySubItemActive(item.subItems) && toggleSidebar
              ? 'bg-[rgba(255,255,255,0.35)] group-hover:!bg-[rgba(255,255,255,0.35)]'
              : '',
            toggleSidebar
              ? 'flex-1 text-xsm transition duration-300 h-10 flex items-center pl-2 group-hover:bg-[rgba(255,255,255,0.2)]'
              : 'block mx-auto mt-1 text-center text-[10px]',
          ]"
        >
          {{ item.title }}
          <span
            v-if="toggleSidebar && item.new"
            class="w-9 h-[14px] leading-[11px] ml-3 uppercase rounded-full bg-red-500 text-white font-bold text-[10px] flex items-center justify-center">
            New
          </span>
        </span>

        <Transition name="fade-in">
          <span
            v-if="toggleSidebar && item.count"
            class="absolute top-4 right-10 w-1.5 h-1.5 rounded-full bg-red-500 flex items-center justify-center"></span>
        </Transition>
        <Transition name="fade-in">
          <span
            v-if="toggleSidebar && item.subItems.length > 0"
            class="flex items-center"
            ><svg
              xmlns="http://www.w3.org/2000/svg"
              width="16"
              height="16"
              fill="currentColor"
              viewBox="0 0 16 16"
              class="w-6 h-6"
              :class="
                item.open
                  ? 'absolute right-3 top-2 transform duration-200 transition'
                  : 'absolute right-3 top-2 rotate-90 duration-200 transition'
              "
            >
              <path
                d="M4.94 5.53 8 8.583l3.06-3.053.94.94-4 4-4-4 .94-.94Z"
              ></path>
            </svg>
          </span>
        </Transition>
      </div>
      <Transition name="height-slide-in" mode="out-in">
        <div
          v-if="item.subItems.length > 0 && (!toggleSidebar || item.open)"
          :class="`${
            !toggleSidebar
              ? 'bg-[#3e4454] absolute opacity-0 left-[76px] pointer-events-none flex flex-col space-y-2 shadow-[4px_8px_30px_2px_rgb(0_0_0/60%)] -mt-[50px] min-w-[230px] transition-all duration-300 ease-out group-hover:opacity-100 group-hover:pointer-events-auto'
              : 'max-h-[350px] bg-[rgba(255,255,255,0.1)] transition-all duration-200 overflow-y-auto'
          }`"
        >
          <ul>
            <template v-for="subItem in item.subItems">
              <li
                v-if="subItem.state"
                :key="subItem.id"
                class="relative space-y-2"
                :class="
                  toggleSidebar ? 'rounded py-[2.5] relative' : 'group/list'
                "
              >
                <div
                  v-if="subItem.secondaryItems.length > 0"
                  class="flex flex-1"
                >
                  <div
                    class="w-full cursor-pointer"
                    @click.prevent="toggleSecondaryAccordion(subItem.id)"
                  >
                    <span class="flex space-x-2 text-xsm/4 p-3 transition-all duration-300 hover:bg-[rgba(248,250,252,0.20)] hover:text-white">
                      <span class="block whitespace-nowrap truncate">
                        {{ subItem.title }}
                      </span>
                    </span>
                  </div>
                </div>

                <template v-else>
                  <a
                    :href="subItem.link"
                    class="flex flex-wrap text-sm p-3 transition-all duration-300 hover:text-white relative"
                    :class="[
                      toggleSidebar ? 'pl-11' : '',
                      isActive(subItem.link)
                        ? 'bg-[rgba(255,255,255,0.35)]'
                        : 'hover:bg-[rgba(248,250,252,0.20)]',
                    ]"
                  >
                    <span class="block w-full whitespace-nowrap truncate">
                      {{ subItem.title }}
                    </span>
                    <span class="text-[10px]">
                      {{ subItem.sub_text }}
                    </span>
                    <span
                      v-if="subItem.count"
                      class="h-[18px] w-7 absolute top-3 right-3 bg-red-500 rounded flex items-center justify-center text-white font-bold text-[10px]">
                      {{ subItem.count }}
                    </span>
                  </a>
                </template>

                <div
                  v-if="
                    subItem.secondaryItems.length > 0 &&
                    (!toggleSidebar || subItem.secondary_open)
                  "
                  class="p-2"
                  :class="
                    !toggleSidebar
                      ? 'absolute left-[230px] -top-2 z-10 rounded-r bg-[#2c3a50] w-[230px] opacity-0 pointer-events-none group-hover/list:opacity-100 group-hover/list:pointer-events-auto transition-all duration-300'
                      : 'bg-[rgba(255,255,255,0.1)] rounded'
                  "
                >
                  <ul class="space-y-2">
                    <li
                      v-for="secondaryItem in subItem.secondaryItems"
                      :key="secondaryItem.id"
                    >
                      <a
                        :href="secondaryItem.link"
                        class="flex space-x-2 text-xsm/4 p-2 transition-all duration-300 hover:bg-[rgba(248,250,252,0.20)] rounded hover:text-white">
                        <span class="block whitespace-nowrap truncate">
                          {{ secondaryItem.title }}
                        </span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
            </template>
          </ul>
        </div>
      </Transition>
    </li>
  </ul>
</template>

<script setup>
import { onMounted, ref, watch } from 'vue';

const props = defineProps({
  toggleSidebar: {
    type: [Boolean, null],
    required: false,
    default: false,
  },
});

const currentPath = ref(window.location.pathname);
const menuItems = ref([
  {
    id: 1,
    icon: '<span class="flex items-center"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 18 18" class="w-4 h-4"><path d="M14 12a3 3 0 1 0-4.5 2.595V18l1.5-1.5 1.5 1.5v-3.405A3.01 3.01 0 0 0 14 12Zm-3 1.5c-.825 0-1.5-.675-1.5-1.5s.675-1.5 1.5-1.5 1.5.675 1.5 1.5-.675 1.5-1.5 1.5ZM11.307 0H4.25A2.257 2.257 0 0 0 2 2.25V18h6v-1.5H3.5V2.25c0-.413.337-.75.75-.75h6v5.25h5.25v9.75H14V18h3V5.692L11.307 0Zm.443 2.558 2.693 2.692H11.75V2.558Z"></path></svg></span>',
    title: 'Job',
    link: '',
    subItems: [
      {
        id: 'job-search',
        title: 'Job Search',
        sub_text: '',
        link: '#',
        secondaryItems: [],
        secondary_open: false,
        state: true,
        count: 0,
      },
      {
        id: 'job-list',
        title: 'Job List',
        sub_text: '',
        link: '#',
        secondaryItems: [],
        secondary_open: false,
        state: true,
        count: 0,
      },
    ],
    open: false,
    new: true,
    count: 0,
    manuallyToggled: false,
  },
]);

const isActive = (route) => {
  return currentPath.value === route;
};

const openParentAccordionIfActiveSubItem = () => {
  menuItems.value.forEach((item) => {
    if (!item.manuallyToggled) {
      item.open = item.subItems.some((subItem) => isActive(subItem.link));
    }
  });
};

const toggleAccordion = (index) => {
  menuItems.value[index].open = !menuItems.value[index].open;
  menuItems.value[index].manuallyToggled = true;

  if (menuItems.value[index].open) {
    menuItems.value.forEach((item, i) => {
      if (i !== index) {
        item.open = false;
        item.manuallyToggled = false;
      }
    });
  }
};

const toggleSecondaryAccordion = (id) => {
  const item = menuItems.value.find((item) =>
    item.subItems.some((subItem) => subItem.id === id)
  );
  const subItem = item.subItems.find((subItem) => subItem.id === id);
  subItem.secondary_open = !subItem.secondary_open;
  if (subItem.secondary_open) {
    item.subItems.forEach((subItem) => {
      if (subItem.id !== id) {
        subItem.secondary_open = false;
      }
    });
  }
};

const isAnySubItemActive = (item) => {
  return item.some((subItem) => isActive(subItem.link));
};

window.addEventListener('popstate', () => {
  currentPath.value = window.location.pathname;
  openParentAccordionIfActiveSubItem();
});

watch( menuItems, () => { openParentAccordionIfActiveSubItem()},
  { deep: true }
);

onMounted(() => {
  openParentAccordionIfActiveSubItem();
});
</script>
