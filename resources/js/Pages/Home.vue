<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import {Head, Link} from '@inertiajs/vue3';

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
    <Head title="Home" />
    <GuestLayout>
    </GuestLayout>
</template>
