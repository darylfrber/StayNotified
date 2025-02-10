<script setup>
import AuthenticatedNav from '@/Layouts/AuthenticatedNav.vue';
import Calendar from '@/Components/Calendar.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Calendar" />

    <AuthenticatedNav>
        <div class="p-12 h-full">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full">
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg h-full">
                    <Calendar/>
                </div>
            </div>
        </div>
    </AuthenticatedNav>
</template>
