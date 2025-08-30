<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});
</script>

<template>
    <Head title="Bienvenido a Smile Clinic" />
    <div class="bg-white text-gray-800">
        <!-- Hero Section -->
        <div class="relative bg-gradient-to-r from-pink-50 to-pink-50">
            <div class="relative max-w-7xl mx-auto px-6 lg:px-8 py-20">
                <header class="flex justify-between items-center py-6">
                    <div class="flex items-center">
                        <svg class="h-12 w-auto text-pink-500" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M50 100C77.6142 100 100 77.6142 100 50C100 22.3858 77.6142 0 50 0C22.3858 0 0 22.3858 0 50C0 77.6142 22.3858 100 50 100Z" fill="#3B82F6"/>
                            <path d="M65 40C65 42.7614 62.7614 45 60 45C57.2386 45 55 42.7614 55 40C55 37.2386 57.2386 35 60 35C62.7614 35 65 37.2386 65 40Z" fill="white"/>
                            <path d="M45 40C45 42.7614 42.7614 45 40 45C37.2386 45 35 42.7614 35 40C35 37.2386 37.2386 35 40 35C42.7614 35 45 37.2386 45 40Z" fill="white"/>
                            <path d="M30 60C30 60 35 70 50 70C65 70 70 60 70 60" stroke="white" stroke-width="4" stroke-linecap="round"/>
                        </svg>
                        <span class="ml-3 text-2xl font-bold text-pink-600">Smile Clinic</span>
                    </div>
                    <nav v-if="canLogin" class="flex items-center">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="rounded-md px-4 py-2 bg-pink-500 text-white font-medium hover:bg-pink-700 transition"
                        >
                            Panel de Control
                        </Link>

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="rounded-md px-4 py-2 text-pink-700 font-medium hover:bg-pink-50 transition"
                            >
                                Iniciar Sesión
                            </Link>

                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="ml-4 rounded-md px-4 py-2 bg-pink-500 text-white font-medium hover:bg-pink-700 transition"
                            >
                                Registrarse
                            </Link>
                        </template>
                    </nav>
                </header>

                <div class="mt-16 grid grid-cols-1 gap-12 lg:grid-cols-2 lg:gap-8">
                    <div class="lg:py-12">
                        <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl">
                            <span class="block text-pink-500">Sonrisas saludables</span>
                            <span class="block">para toda la familia</span>
                        </h1>
                        <p class="mt-6 text-xl text-gray-500">
                            En Smile Clinic nos preocupamos por tu salud dental. Ofrecemos tratamientos personalizados con tecnología de última generación.
                        </p>
                        <div class="mt-10">
                            <Link
                                href="#services"
                                class="rounded-md px-6 py-3 bg-pink-500 text-white font-medium hover:bg-pink-700 transition"
                            >
                                Nuestros Servicios
                            </Link>
                            <Link
                                href="#contact"
                                class="ml-4 rounded-md px-6 py-3 border border-pink-500 text-pink-500 font-medium hover:bg-pink-50 transition"
                            >
                                Contacto
                            </Link>
                        </div>
                    </div>
                    <div class="relative">
                        <img
                            src="/images/dentist-hero.jpg"
                            alt="Dentista atendiendo a paciente"
                            class="rounded-lg shadow-xl ring-1 ring-black/10"
                        />
                        <div class="absolute -bottom-6 -right-6 bg-white p-4 rounded-lg shadow-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-pink-100 p-3 rounded-full">
                                    <svg class="h-6 w-6 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Más de 15 años</p>
                                    <p class="text-sm text-gray-500">de experiencia</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Services Section -->
        <div id="services" class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-3xl font-extrabold text-gray-900">Nuestros Servicios</h2>
                    <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">
                        Ofrecemos una amplia gama de tratamientos dentales para cuidar de tu salud bucal.
                    </p>
                </div>

                <div class="mt-16 grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
                        <div class="h-48 bg-pink-100 flex items-center justify-center">
                            <svg class="h-20 w-20 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900">Ortodoncia</h3>
                            <p class="mt-2 text-gray-500">
                                Tratamientos de ortodoncia invisible y brackets metálicos o estéticos para alinear tu sonrisa.
                            </p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
                        <div class="h-48 bg-pink-100 flex items-center justify-center">
                            <svg class="h-20 w-20 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                            </svg>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900">Estética Dental</h3>
                            <p class="mt-2 text-gray-500">
                                Blanqueamiento dental, carillas y coronas para mejorar la apariencia de tus dientes.
                            </p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
                        <div class="h-48 bg-pink-100 flex items-center justify-center">
                            <svg class="h-20 w-20 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900">Implantes Dentales</h3>
                            <p class="mt-2 text-gray-500">
                                Soluciones permanentes para reemplazar dientes perdidos con resultados naturales.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Testimonials -->
        <div class="bg-pink-50 py-16">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <h2 class="text-center text-3xl font-extrabold text-gray-900">Lo que dicen nuestros pacientes</h2>
                <div class="mt-16 grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                    <div class="bg-white p-8 rounded-lg shadow">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <img class="h-12 w-12 rounded-full" src="/images/patient1.jpg" alt="Paciente 1">
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">María González</h3>
                                <p class="text-pink-500">Ortodoncia</p>
                            </div>
                        </div>
                        <p class="mt-4 text-gray-500">
                            "El tratamiento de ortodoncia cambió mi sonrisa por completo. El equipo fue muy profesional y me hizo sentir cómoda en todo momento."
                        </p>
                    </div>

                    <div class="bg-white p-8 rounded-lg shadow">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <img class="h-12 w-12 rounded-full" src="/images/patient2.jpg" alt="Paciente 2">
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Carlos Martínez</h3>
                                <p class="text-pink-500">Implantes</p>
                            </div>
                        </div>
                        <p class="mt-4 text-gray-500">
                            "Después de años con problemas dentales, los implantes me devolvieron la confianza para sonreír. Excelente atención y resultados."
                        </p>
                    </div>

                    <div class="bg-white p-8 rounded-lg shadow">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <img class="h-12 w-12 rounded-full" src="/images/patient3.jpg" alt="Paciente 3">
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Ana López</h3>
                                <p class="text-pink-500">Blanqueamiento</p>
                            </div>
                        </div>
                        <p class="mt-4 text-gray-500">
                            "El blanqueamiento dental superó mis expectativas. Ahora tengo una sonrisa más brillante y me siento mucho más segura."
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div id="contact" class="bg-pink-500">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 py-16">
                <div class="text-center">
                    <h2 class="text-3xl font-extrabold text-white">¿Listo para mejorar tu sonrisa?</h2>
                    <p class="mt-4 max-w-2xl text-xl text-pink-100 mx-auto">
                        Agenda una cita con nuestros especialistas y descubre cómo podemos ayudarte.
                    </p>
                    <div class="mt-10">
                        <Link

                            class="rounded-md px-6 py-3 bg-white text-pink-500 font-medium hover:bg-gray-100 transition"
                        >
                            Contactar Ahora
                        </Link>
                        <Link
                            href="tel:+123456789"
                            class="ml-4 rounded-md px-6 py-3 border border-white text-white font-medium hover:bg-pink-700 transition"
                        >
                            Llamar: (123) 456-789
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-50">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                    <div>
                        <div class="flex items-center">
                            <svg class="h-8 w-auto text-pink-500" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M50 100C77.6142 100 100 77.6142 100 50C100 22.3858 77.6142 0 50 0C22.3858 0 0 22.3858 0 50C0 77.6142 22.3858 100 50 100Z" fill="#06B6D4"/>
                                <path d="M65 40C65 42.7614 62.7614 45 60 45C57.2386 45 55 42.7614 55 40C55 37.2386 57.2386 35 60 35C62.7614 35 65 37.2386 65 40Z" fill="white"/>
                                <path d="M45 40C45 42.7614 42.7614 45 40 45C37.2386 45 35 42.7614 35 40C35 37.2386 37.2386 35 40 35C42.7614 35 45 37.2386 45 40Z" fill="white"/>
                                <path d="M30 60C30 60 35 70 50 70C65 70 70 60 70 60" stroke="white" stroke-width="4" stroke-linecap="round"/>
                            </svg>
                            <span class="ml-3 text-xl font-bold text-pink-600">Smile Clinic</span>
                        </div>
                        <p class="mt-4 text-gray-500">
                            Tu salud dental es nuestra prioridad. Ofrecemos tratamientos personalizados con la más alta tecnología.
                        </p>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Horario</h3>
                        <ul class="mt-4 space-y-2">
                            <li class="text-gray-500">Lunes a Viernes: 9:00 - 19:00</li>
                            <li class="text-gray-500">Sábados: 9:00 - 14:00</li>
                            <li class="text-gray-500">Domingos: Cerrado</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Contacto</h3>
                        <ul class="mt-4 space-y-2">
                            <li class="text-gray-500">Calle Dental 123, Ciudad</li>
                            <li class="text-gray-500">info@smileclinic.com</li>
                            <li class="text-gray-500">(123) 456-789</li>
                        </ul>
                    </div>
                </div>
                <div class="mt-12 border-t border-gray-200 pt-8">
                    <p class="text-base text-gray-500 text-center">
                        &copy; 2023 Smile Clinic. Todos los derechos reservados.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>
