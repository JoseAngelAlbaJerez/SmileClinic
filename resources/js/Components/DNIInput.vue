<template>
  <input
    v-model="formattedDni"
    @input="onInput"
    type="text"
    maxlength="13"
    placeholder="213-3332643-2"
    class="border p-2 rounded"
  />
</template>

<script setup>
import { ref } from 'vue';

const formattedDni = ref('');

function onInput(e) {
  let digits = e.target.value.replace(/\D/g, '').substring(0, 11); // 11 digits
  let formatted = '';

  if (digits.length > 10) {
    formatted = `${digits.slice(0, 3)}-${digits.slice(3, 10)}-${digits.slice(10)}`;
  } else if (digits.length > 3) {
    formatted = `${digits.slice(0, 3)}-${digits.slice(3)}`;
  } else {
    formatted = digits;
  }

  formattedDni.value = formatted;
}
</script>
