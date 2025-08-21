<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>

    <Head title="Iniciar Sesión - Smile Clinic" />

    <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-gray-50">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="flex justify-center">
                <svg class="h-16 w-auto text-blue-500" viewBox="0 0 100 100" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M50 100C77.6142 100 100 77.6142 100 50C100 22.3858 77.6142 0 50 0C22.3858 0 0 22.3858 0 50C0 77.6142 22.3858 100 50 100Z"
                        fill="#3B82F6" />
                    <path
                        d="M65 40C65 42.7614 62.7614 45 60 45C57.2386 45 55 42.7614 55 40C55 37.2386 57.2386 35 60 35C62.7614 35 65 37.2386 65 40Z"
                        fill="white" />
                    <path
                        d="M45 40C45 42.7614 42.7614 45 40 45C37.2386 45 35 42.7614 35 40C35 37.2386 37.2386 35 40 35C42.7614 35 45 37.2386 45 40Z"
                        fill="white" />
                    <path d="M30 60C30 60 35 70 50 70C65 70 70 60 70 60" stroke="white" stroke-width="4"
                        stroke-linecap="round" />
                </svg>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Smile Clinic
            </h2>

        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <div v-if="status" class="mb-4 p-3 bg-green-50 text-green-700 rounded text-sm">
                    {{ status }}
                </div>

                <form class="space-y-6" @submit.prevent="submit">
                    <div>
                        <InputLabel for="email" value="Correo electrónico" />
                        <div class="mt-1">
                            <TextInput id="email" type="email"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                v-model="form.email" required autofocus autocomplete="email" />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>
                    </div>

                    <div>
                        <InputLabel for="password" value="Contraseña" />
                        <div class="mt-1">
                            <TextInput id="password" type="password"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                v-model="form.password" required autocomplete="current-password" />
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <Checkbox name="remember" v-model:checked="form.remember" />
                            <label for="remember-me" class="ml-2 block text-sm text-gray-900">
                                Recordar sesión
                            </label>
                        </div>

                        <div class="text-sm">
                            <Link v-if="canResetPassword" :href="route('password.request')"
                                class="font-medium text-blue-500 hover:text-blue-400">
                            ¿Olvidaste tu contraseña?
                            </Link>
                        </div>
                    </div>

                    <div>
                        <PrimaryButton
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Iniciar Sesión
                        </PrimaryButton>
                    </div>
                </form>

                <div class="mt-6">
                    <p class="mt-2 text-center text-sm text-gray-600">
                        O
                        <Link :href="route('register')" class="font-medium text-blue-500 hover:text-blue-400">
                        crear una nueva cuenta
                        </Link>
                    </p>


                </div>
            </div>
        </div>
    </div>

</template>
