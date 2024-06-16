<template>
    <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" @click.self="closeModal">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3" @click.stop>
            <h2 class="text-xl font-bold mb-4">{{ isEditMode ? 'Edit Event' : 'Add Event' }}</h2>
            <form @submit.prevent="submitEvent">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                    <input type="date" v-model="form.date" class="w-full px-3 py-2 border border-gray-300 rounded-md" required />
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Time</label>
                    <input type="time" v-model="form.time" class="w-full px-3 py-2 border border-gray-300 rounded-md" required />
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                    <input type="text" v-model="form.title" class="w-full px-3 py-2 border border-gray-300 rounded-md" required />
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirmed</label>
                    <input type="checkbox" v-model="form.confirmed" />
                </div>
                <div class="flex justify-end">
                    <button type="button" @click="closeModal" class="px-4 py-2 bg-gray-500 text-white rounded-md mr-2">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md">{{ isEditMode ? 'Save' : 'Add' }}</button>
                </div>
            </form>
            <div v-if="isEditMode" class="flex justify-end mt-4">
                <button @click="deleteEvent" class="px-4 py-2 bg-red-500 text-white rounded-md">Delete</button>
            </div>
        </div>
    </div>
    <Notification :message="notificationMessage" :type="notificationType" />
</template>

<script setup>
import { reactive, watch, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import Notification from './Notification.vue';

const form = reactive({
    id: null,
    date: '',
    time: '',
    title: '',
    confirmed: false,
});

const notificationMessage = ref('');
const notificationType = ref('success');

function showNotification(message, type) {
    notificationMessage.value = '';
    setTimeout(() => {
        notificationMessage.value = message;
        notificationType.value = type;
    }, 100); // Delay to ensure the transition works
}

function submitEvent() {
    if (form.id) {
        router.put(`/events/${form.id}`, form, {
            onSuccess: (page) => {
                emit('event-updated', page.props.events);
                showNotification('Event updated successfully!', 'success');
                closeModal();
            }
        });
    } else {
        router.post('/events', form, {
            onSuccess: (page) => {
                emit('event-updated', page.props.events);
                showNotification('Event added successfully!', 'success');
                closeModal();
            }
        });
    }
}

function deleteEvent() {
    router.delete(`/events/${form.id}`, {
        onSuccess: (page) => {
            emit('event-updated', page.props.events);
            showNotification('Event deleted successfully!', 'error');
            closeModal();
        }
    });
}

const props = defineProps({
    isOpen: Boolean,
    isEditMode: Boolean,
    event: Object,
});

const emit = defineEmits(['close', 'event-updated']);

watch(() => props.event, (newEvent) => {
    Object.assign(form, newEvent);
});

function closeModal() {
    emit('close');
}
</script>
