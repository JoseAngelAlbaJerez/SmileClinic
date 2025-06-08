<template>
  <input
    v-model="formattedPhone"
    @input="onInput"
    type="text"
    maxlength="12"
    placeholder="809-123-4567"
    class="border p-2 rounded"
  />
</template>

<script setup>
import { ref } from 'vue';

const formattedPhone = ref('');

function onInput(e) {
  let digits = e.target.value.replace(/\D/g, '').substring(0, 10); // max 10 digits
  let formatted = '';

  if (digits.length > 6) {
    formatted = `${digits.slice(0, 3)}-${digits.slice(3, 6)}-${digits.slice(6)}`;
  } else if (digits.length > 3) {
    formatted = `${digits.slice(0, 3)}-${digits.slice(3)}`;
  } else {
    formatted = digits;
  }

  formattedPhone.value = formatted;
}
</script>
