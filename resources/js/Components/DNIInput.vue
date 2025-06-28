<template>
  <input
    :value="formattedDni"
    @input="onInput"
    type="text"
    maxlength="13"
    placeholder="213-3332643-2"
    class="border p-2 rounded"
  />
</template>

<script setup>
import { ref, watch, defineProps, defineEmits } from 'vue';

const props = defineProps({
  modelValue: String
});
const emit = defineEmits(['update:modelValue']);

const formattedDni = ref('');

watch(() => props.modelValue, (newVal) => {
  formattedDni.value = newVal || '';
}, { immediate: true });

function onInput(e) {
  let digits = e.target.value.replace(/\D/g, '').substring(0, 11); // max 11 digits
  let formatted = '';

  if (digits.length > 10) {
    formatted = `${digits.slice(0, 3)}-${digits.slice(3, 10)}-${digits.slice(10)}`;
  } else if (digits.length > 3) {
    formatted = `${digits.slice(0, 3)}-${digits.slice(3)}`;
  } else {
    formatted = digits;
  }

  formattedDni.value = formatted;
  emit('update:modelValue', formatted);
}
</script>
