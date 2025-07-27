// composables/useGate.js
import { ref } from 'vue'

const permissions = ref(window.USER_PERMISSIONS || [])

const can = (perm) => {
  return permissions.value.includes(perm)
}

const canAny = (perms) => {
  return perms.some(p => permissions.value.includes(p))
}

export default function useGate() {
  return {
    can,
    canAny,
    permissions,
  }
}
