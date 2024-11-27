<script setup>
import { Head } from '@inertiajs/vue3';
import GuestNav from "@/Layouts/GuestNav.vue";
import { Link } from '@inertiajs/vue3';
import { ArrowRightCircleIcon, ClockIcon, CalendarIcon } from "@heroicons/vue/24/outline";
</script>

<template>
    <Head title="Home" />
    <GuestNav>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Home</h2>
        </template>
        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-blue-400 via-blue-500 to-blue-600 text-white py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <!-- Left Text Content -->
                <div>
                    <h1 class="text-4xl font-bold sm:text-5xl leading-tight">
                        Plan slimmer, werk efficiënter
                    </h1>
                    <p class="mt-4 text-lg sm:text-xl">
                        Gebruik onze krachtige tools om je werkdag te stroomlijnen. Notificaties, herinneringen, en meer om je te helpen op tijd te blijven.
                    </p>
                    <div class="mt-6 space-x-4">
                        <Link href="/register" class="px-6 py-3 bg-white text-blue-500 rounded-lg shadow hover:bg-gray-200 transition ease-in-out duration-300 flex items-center space-x-2">
                            <span>Meld je nu aan</span>
                            <ArrowRightCircleIcon class="h-6 w-6 text-blue-500" />
                        </Link>
                        <Link href="/login" class="px-6 py-3 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-600 transition ease-in-out duration-300 flex items-center space-x-2">
                            <span>Log in</span>
                            <ArrowRightCircleIcon class="h-6 w-6 text-white" />
                        </Link>
                    </div>
                </div>

                <!-- Right Image (modern productivity icon) -->
                <div class="flex justify-center">
                    <div class="relative">
                        <img src="/images/calendar.png" alt="Productivity Workspace" class="rounded-lg shadow-lg max-w-lg transform hover:scale-105 transition-all duration-300" />
                        <div class="absolute top-0 left-0 bg-gradient-to-r from-transparent to-blue-600 opacity-50 w-full h-full rounded-lg"></div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Features Section -->
        <div class="bg-white py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-gray-800 text-center mb-12">Wat kun je met onze kalender?</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="text-center group p-6 bg-gray-50 rounded-lg shadow hover:shadow-lg transform hover:scale-105 transition">
                        <CalendarIcon class="mx-auto h-20 w-20 text-blue-400 group-hover:text-blue-600 transition" />
                        <h3 class="mt-4 text-xl font-semibold text-gray-800">Afspraken bekijken</h3>
                        <p class="mt-2 text-gray-600">Bekijk je afspraken in een overzichtelijke, interactieve kalender.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="text-center group p-6 bg-gray-50 rounded-lg shadow hover:shadow-lg transform hover:scale-105 transition">
                        <ClockIcon class="mx-auto h-20 w-20 text-blue-400 group-hover:text-blue-600 transition" />
                        <h3 class="mt-4 text-xl font-semibold text-gray-800">Notificaties</h3>
                        <p class="mt-2 text-gray-600">Ontvang notificaties voor belangrijke afspraken en deadlines.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="text-center group p-6 bg-gray-50 rounded-lg shadow hover:shadow-lg transform hover:scale-105 transition">
                        <img src="https://img.icons8.com/color/96/reminder.png" alt="Reminders" class="mx-auto h-20 w-20 transition transform group-hover:scale-110">
                        <h3 class="mt-4 text-xl font-semibold text-gray-800">Reminders</h3>
                        <p class="mt-2 text-gray-600">Stel herinneringen in om je taken niet te vergeten.</p>
                    </div>

                    <!-- Feature 4 -->
                    <div class="text-center group p-6 bg-gray-50 rounded-lg shadow hover:shadow-lg transform hover:scale-105 transition">
                        <img src="https://img.icons8.com/color/96/search.png" alt="Search Events" class="mx-auto h-20 w-20 transition transform group-hover:scale-110">
                        <h3 class="mt-4 text-xl font-semibold text-gray-800">Zoek naar evenementen</h3>
                        <p class="mt-2 text-gray-600">Vind eenvoudig specifieke afspraken of evenementen.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call-to-Action Section -->
        <!-- Call-to-Action Section -->
        <div class="bg-gradient-to-l from-blue-50 via-blue-100 to-blue-200 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <!-- Left Image (teamwork or collaboration) -->
                <div class="flex justify-center">
                    <img src="https://plus.unsplash.com/premium_vector-1682310688706-3ef962897f3e?q=80&w=2748&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Team Collaboration" class="rounded-lg shadow-lg max-w-lg transform hover:scale-105 transition-all duration-300" />
                </div>

                <!-- Right Text Content -->
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Waarom kiezen voor onze kalender?</h2>
                    <p class="mt-2 text-gray-600">
                        Gebruik onze slimme tools om efficiënter te werken, georganiseerd te blijven en tijd te besparen.
                    </p>
                    <ul class="mt-4 space-y-2">
                        <li class="flex items-center space-x-2">
                            <CalendarIcon class="h-6 w-6 text-blue-500" />
                            <span>Overzichtelijke agenda-weergave</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <ClockIcon class="h-6 w-6 text-blue-500" />
                            <span>Real-time notificaties</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <ArrowRightCircleIcon class="h-6 w-6 text-blue-500" />
                            <span>Gebruiksvriendelijke interface</span>
                        </li>
                    </ul>
                    <div class="mt-6 space-x-4">
                        <Link href="/register" class="px-6 py-3 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-600 transition ease-in-out duration-300">
                            Meld je nu aan
                        </Link>
                        <Link href="/login" class="px-6 py-3 bg-gray-300 text-gray-800 rounded-lg shadow hover:bg-gray-400 transition ease-in-out duration-300">
                            Log in
                        </Link>
                    </div>
                </div>
            </div>
        </div>

    </GuestNav>
</template>
