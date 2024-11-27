<script setup>
import GuestNav from '@/Layouts/GuestNav.vue';
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
    <GuestNav>
    </GuestNav>
</template>
