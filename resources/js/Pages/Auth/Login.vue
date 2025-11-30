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
    <div>
        <div class="flex ">
            <div class="min-h-screen flex-1">
                <div class="flex h-full items-center py-10 px-5">
                    <div class="mx-auto flex w-full max-w-lg flex-col">

                        <h1 class="mt-8 text-3xl font-bold text-gray-700 lg:mt-0">
                            Bienvenido
                        </h1>

                        <p class="mt-2 text-md text-gray-500">Por favor inicie sesión debajo</p>

                        <form @submit.prevent="submit" class="mt-6">

                            <p class="text-sm font-semibold text-gray-600">Correo Electrónico</p>
                            <TextInput class="mt-1 rounded border py-3 px-4 text-base shadow w-full"
                                v-model="form.email" required autofocus />
                            <InputError class="mt-2" :message="form.errors.email" />

                            <p class="mt-6 text-sm font-semibold text-gray-600">Contraseña</p>
                            <TextInput type="password" class="mt-1 rounded border py-3 px-4 text-base shadow w-full"
                                v-model="form.password" required />
                            <InputError class="mt-2" :message="form.errors.password" />

                            <div class="mt-5 flex items-center">
                                <Checkbox class="mr-2" v-model:checked="form.remember" />
                                <p class="text-sm font-semibold text-gray-600">Recuérdame</p>
                                <span class="flex-1"></span>
                                <Link v-if="canResetPassword" :href="route('password.request')"
                                    class="text-sm font-semibold text-pink-500 hover:text-pink-600">Forgot password?
                                </Link>
                            </div>

                            <button
                                class="mt-6 w-full rounded-lg bg-pink-500 py-3 text-base font-semibold text-white shadow hover:bg-pink-600"
                                :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Iniciar Sesión
                            </button>

                            <div class="mt-8 flex items-center space-x-1 justify-center">
                                <p class="text-sm font-semibold text-gray-600">¿No tienes una cuenta?</p>
                                <a href="#" class="text-sm font-semibold text-pink-500 hover:text-pink-600">
                                    Regístrate
                                </a>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
            <div class="hidden min-h-screen flex-1 bg-pink-500 px-5 py-5 lg:flex">
                <div class="mx-auto flex h-full flex-col items-center justify-center">

                    <img src="/img/LOGO.PNG" class="h-100 w-auto drop-shadow-xl" />

                    <h1 class=" text-5xl font-extrabold text-white tracking-wide drop-shadow-lg mb-20">
                        Smile Clinic
                    </h1>

                </div>
            </div>


        </div>
    </div>
</template>
