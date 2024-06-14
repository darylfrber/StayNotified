<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Calendar from '@/Components/Calendar.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Calendar</h2>
        </template>

        <div class="py-12 h-full">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
                    <Calendar class="h-full"/>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
