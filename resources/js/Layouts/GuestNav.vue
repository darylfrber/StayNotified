<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import Notification from "@/Components/Notification.vue";
import {useNotification} from "@/composables/useNotification.js";

const { notificationMessage, notificationType, notificationIcon, notificationVisible, hideNotification, pauseTimer, resumeTimer } = useNotification();


const showingNavigationDropdown = ref(false);
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <nav class="bg-white border-b border-gray-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('home')">
                                    <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800" />
                                </Link>
                            </div>
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink :href="route('home')" :active="route().current('home')">Home</NavLink>
<!--                                <NavLink :href="route('about')" :active="route().current('about')">About</NavLink>-->
<!--                                <NavLink :href="route('contact')" :active="route().current('contact')">Contact</NavLink>-->
                            </div>
                        </div>
                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            <NavLink :href="route('login')" :active="route().current('login')">Log In</NavLink>
                            <NavLink :href="route('register')" :active="route().current('register')">Register</NavLink>
                        </div>
                        <div class="-me-2 flex items-center sm:hidden">
                            <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{ 'hidden': showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                    <path :class="{ 'hidden': !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div :class="{ 'block': showingNavigationDropdown, 'hidden': !showingNavigationDropdown }" class="sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('home')" :active="route().current('home')">Home</ResponsiveNavLink>
<!--                        <ResponsiveNavLink :href="route('about')" :active="route().current('about')">About</ResponsiveNavLink>-->
<!--                        <ResponsiveNavLink :href="route('contact')" :active="route().current('contact')">Contact</ResponsiveNavLink>-->
                    </div>
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('login')">Log In</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('register')">Register</ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    StayNotified
                </div>
            </header>
            <main>
                <slot />
            </main>
            <Notification
                :message="notificationMessage"
                :type="notificationType"
                :icon="notificationIcon"
                :visible="notificationVisible"
                @hide="hideNotification"
                :duration="3000"
            />
        </div>
    </div>
</template>

