<template>
  <slot v-if="canAccess" />
</template>

<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
  roles: Array,      // e.g., ['admin', 'supervisor']
  permissions: Array // e.g., ['view patients', 'edit orders']
});

const page = usePage();

const user = computed(() => page.props.auth.user); // asegura que tengas auth.user en props
const canAccess = computed(() => {
  const userRoles = user.value?.roles || [];
  const userPermissions = user.value?.permissions || [];

  const hasRole = props.roles?.some(role => userRoles.includes(role));
  const hasPermission = props.permissions?.some(perm => userPermissions.includes(perm));

  return hasRole || hasPermission;
});
</script>
