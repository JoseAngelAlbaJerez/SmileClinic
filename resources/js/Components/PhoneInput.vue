<template>
  <input
    :value="formattedPhone"
    @input="onInput"
    type="text"
    maxlength="12"
    placeholder="809-123-4567"
    class="border p-2 rounded"
  />
</template>

<script setup>
import { ref, watch, defineProps, defineEmits } from 'vue';

const props = defineProps({
  modelValue: String,
});

const emit = defineEmits(['update:modelValue']);

const formattedPhone = ref('');

watch(() => props.modelValue, (newVal) => {
  formattedPhone.value = newVal || '';
}, { immediate: true });

function onInput(e) {
  let digits = e.target.value.replace(/\D/g, '').substring(0, 10);
  let formatted = '';

  if (digits.length > 6) {
    formatted = `${digits.slice(0, 3)}-${digits.slice(3, 6)}-${digits.slice(6)}`;
  } else if (digits.length > 3) {
    formatted = `${digits.slice(0, 3)}-${digits.slice(3)}`;
  } else {
    formatted = digits;
  }

  formattedPhone.value = formatted;
  emit('update:modelValue', formatted);
}
</script>
