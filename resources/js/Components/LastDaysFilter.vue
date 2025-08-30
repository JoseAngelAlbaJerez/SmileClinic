<template>
  <div class="flex relative">
    <button
      @click="toggleDropdown"
      type="button"
      class="inline-flex items-center text-gray-500 bg-white dark:bg-gray-500 dark:text-white dark:border-gray-500 border border-gray-300 rounded-lg px-3 py-1.5"
    >
      <svg class="w-3 h-3 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        <path
          d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm3.982 13.982a1 1 0 0 1-1.414 0l-3.274-3.274A1.012 1.012 0 0 1 9 10V6a1 1 0 0 1 2 0v3.586l2.982 2.982a1 1 0 0 1 0 1.414Z"
        />
      </svg>
      {{ labelText }}
      <svg
        class="w-2.5 h-2.5 ms-2.5"
        fill="none"
        viewBox="0 0 10 6"
        xmlns="http://www.w3.org/2000/svg"
        aria-hidden="true"
      >
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4" />
      </svg>
    </button>

    <div
      v-if="dropdownOpen"
      class="absolute z-10 mt-1 w-48 bg-white divide-y divide-gray-100 rounded-lg shadow-sm dark:bg-gray-800"
    >
      <ul class="p-3 space-y-1 text-sm text-gray-700 dark:text-gray-200">
        <li v-for="option in options" :key="option.value">
          <label class="flex items-center p-2 rounded-sm hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer">
            <input
              type="radio"
              class="w-4 h-4 text-pink-600 bg-gray-100 border-gray-300 focus:ring-pink-500"
              :value="option.value"
              name="lastDays"
              :checked="option.value === modelValue"
              @change="onChange(option.value)"
            />
            <span class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ option.label }}</span>
          </label>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  name: "LastDaysFilter",
  props: {
    modelValue: {
      type: [String, Number],
      default: "30",
    },
  },
  data() {
    return {
      dropdownOpen: false,
      options: [
        { label: "Hoy", value: "1" },
        { label: "Últimos 7 días", value: "7" },
        { label: "Últimos 30 días", value: "30" },
        { label: "Último mes", value: "month" },
        { label: "Último año", value: "year" },
      ],
    };
  },
  computed: {
    labelText() {
      const found = this.options.find((o) => o.value === this.modelValue);
      return found ? found.label : "Select";
    },
  },
  methods: {
    toggleDropdown() {
      this.dropdownOpen = !this.dropdownOpen;
    },
    onChange(value) {
      this.$emit("update:modelValue", value);
      this.$emit("change", value);
      this.dropdownOpen = false;
    },
  },
};
</script>
