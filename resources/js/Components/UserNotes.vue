<template>
    <div class="bg-gray-100 p-4 border-r h-full w-full md:w-1/3 flex flex-col">
        <div class="flex justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-700">Notes</h2>
            <button @click="createNote" class="text-gray-500 hover:text-gray-700 text-2xl">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                </svg>
            </button>
        </div>

        <!-- Maak de lijst scrollbaar binnen de component -->
        <ul class="space-y-2 flex-1 overflow-y-auto max-h-[calc(100vh-150px)]">
            <li v-for="note in notes" :key="note.id"
                :class="['p-4 bg-white shadow-md border-2 border-transparent rounded-lg cursor-pointer flex justify-between items-center transition', note.id === selectedNoteId ? 'border-blue-300' : 'hover:bg-gray-100']"
                @click="selectNote(note)">
                <div class="flex-1">
                    <h3 class="text-gray-800 font-medium truncate">{{ note.title }}</h3>
                    <!-- Laat de laatst bijgewerkt tekst zien en log de datum om te zien wat je krijgt -->
                    <p class="text-gray-500 text-sm">Last edit: {{ formatDate(note.updated_at) }}</p>
                </div>
                <button @click.stop="deleteNote(note.id)" class="text-red-500 hover:text-red-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                </button>
            </li>
        </ul>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { useNotification } from '@/composables/useNotification';

const { showNotification } = useNotification();
const props = defineProps({ notes: Array, selectedNote: Object });
const emit = defineEmits(['select']);
const selectedNoteId = ref(props.selectedNote?.id || null);

// Definieer title als een ref
const title = ref(props.selectedNote?.title || 'StayNotified: Notes');
document.title = title.value;

// Log de notities om te zien welke data we ontvangen
console.log(props.notes);

// Update de titel wanneer een notitie wordt geselecteerd
const selectNote = (note) => {
    selectedNoteId.value = note.id;
    emit('select', note);
    title.value = "StayNotified: " + note.title;
    document.title = title.value;
};

const createNote = () => {
    router.post('/notes', {title: 'New note', content: ''}, {
        onSuccess: () => showNotification('Succesfully created new note', 'success')
    });
};

const deleteNote = (id) => {
    router.delete(`/notes/${id}`, {
        onSuccess: () => showNotification('Note has been deleted!', 'error')
    });
};

// Functie om de datum te formatteren
const formatDate = (date) => {
    console.log('Received date:', date); // Log de datum die we ontvangen
    if (!date) return 'No date provided';
    const d = new Date(date);
    if (isNaN(d.getTime())) return 'Invalid date';
    return d.toLocaleDateString('en-EN', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};
</script>
