<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedNav from '@/Layouts/AuthenticatedNav.vue';
import UserNotes from '@/Components/UserNotes.vue';
import NoteEditor from '@/Components/NoteEditor.vue';
import Calendar from "@/Pages/Calendar.vue";

defineProps({ notes: Array});

const selectedNote = ref(null);
const isMobile = ref(window.innerWidth < 768);

const updateScreenSize = () => {
    isMobile.value = window.innerWidth < 768;
};

onMounted(() => {
    window.addEventListener('resize', updateScreenSize);
});

onUnmounted(() => {
    window.removeEventListener('resize', updateScreenSize);
});

const selectNote = (note) => {
    selectedNote.value = note;
};

const backToList = () => {
    selectedNote.value = null;
};
</script>

<template>
    <Head title="Notes"/>

    <AuthenticatedNav>
        <div class="h-full">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full">
                <div class="overflow-hidden h-full">
                    <div class="flex flex-col md:flex-row h-screen">
                        <UserNotes v-if="!selectedNote || !isMobile" :notes="notes" @select="selectNote"/>
                        <NoteEditor v-if="selectedNote" :note="selectedNote" @back="backToList"/>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedNav>
</template>
