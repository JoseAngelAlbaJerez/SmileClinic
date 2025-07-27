import { usePage } from '@inertiajs/vue3'

export function can(permission) {
    const user = usePage().props.auth.user
    const permissions = user?.permissions || []

    return permissions.some(p => {
        if (p.endsWith('.*')) {
            return permission.startsWith(p.slice(0, -2))
        }
        return p === permission
    })
}
