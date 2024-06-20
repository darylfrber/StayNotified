<template>
    <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center" @click.self="closeModal">
        <div
            ref="modal"
            :style="`box-shadow: 0 24px 38px 3px rgba(0,0,0,.14),0 9px 46px 8px rgba(0,0,0,.12),0 11px 15px -7px rgba(0,0,0,.2); transform: translate3d(${offsetX}px, ${offsetY}px, 0);`"
            class="bg-white rounded-lg w-1/3 relative"
            @click.stop
        >
            <div
                class="flex justify-between px-3 py-3 mb-1 rounded-t-lg items-center cursor-move bg-blue-500 text-white"
                @mousedown="dragStart"
            >
                <h2 class="text-xl font-bold">{{ isEditMode ? 'Edit Event' : 'Add Event' }}</h2>
                <button @click="closeModal" class="p-1 rounded-md hover:bg-blue-400 transition duration-200 ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form class="px-3 py-2" @submit.prevent="submitEvent">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                    <input type="text" v-model="form.title" class="w-full px-3 py-2 border border-gray-300 rounded-md" required />
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                    <input type="date" v-model="form.date" class="w-full px-3 py-2 border border-gray-300 rounded-md" required />
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Time</label>
                    <input type="time" v-model="form.time" class="w-full px-3 py-2 border border-gray-300 rounded-md" required />
                </div>
                <div class="mb-4 flex gap-2 items-center">
                    <input type="checkbox" v-model="form.confirmed" />
                    <label class="block text-sm font-medium text-gray-700">Confirmed</label>
                </div>
                <div class="flex justify-end">
                    <button type="button" class="px-4 py-2 hover:bg-gray-200 transition rounded-md mr-2">More</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 transition hover:bg-blue-400 text-white rounded-md">{{ isEditMode ? 'Save' : 'Add' }}</button>
                </div>
            </form>
            <div v-if="isEditMode" class="flex justify-end mt-4">
                <button @click="deleteEvent" class="px-4 py-2 bg-red-500 text-white rounded-md">Delete</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive, watch, ref, onMounted, onBeforeUnmount } from 'vue';
import { router } from '@inertiajs/vue3';
import { useNotification } from '@/composables/useNotification';

const form = reactive({
    id: null,
    date: '',
    time: '',
    title: '',
    confirmed: false,
    offsetX: 0,
    offsetY: 0,
});

const { showNotification } = useNotification();

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
    if (newEvent) {
        offsetX.value = newEvent.offsetX || 0;
        offsetY.value = newEvent.offsetY || 0;
    }
});

watch(() => props.isOpen, (newIsOpen) => {
    if (!newIsOpen) {
        form.offsetX = offsetX.value;
        form.offsetY = offsetY.value;
    }
});

function closeModal() {
    emit('close');
}

const modal = ref(null);
const offsetX = ref(0);
const offsetY = ref(0);
let initialX = 0;
let initialY = 0;
let isDragging = false;

function dragStart(event) {
    isDragging = true;
    initialX = event.clientX - offsetX.value;
    initialY = event.clientY - offsetY.value;
    document.addEventListener('mousemove', drag);
    document.addEventListener('mouseup', dragEnd);
}

function drag(event) {
    if (!isDragging) return;

    const viewportWidth = window.innerWidth;
    const viewportHeight = window.innerHeight;
    const modalRect = modal.value.getBoundingClientRect();

    let newOffsetX = event.clientX - initialX;
    let newOffsetY = event.clientY - initialY;

    const minX = -modalRect.left;
    const minY = -modalRect.top;
    const maxX = viewportWidth - modalRect.right;
    const maxY = viewportHeight - modalRect.bottom;

    newOffsetX = Math.max(minX, Math.min(newOffsetX, maxX));
    newOffsetY = Math.max(minY, Math.min(newOffsetY, maxY));

    offsetX.value = newOffsetX;
    offsetY.value = newOffsetY;

    setTranslate(newOffsetX, newOffsetY, modal.value);
}

function dragEnd() {
    isDragging = false;
    document.removeEventListener('mousemove', drag);
    document.removeEventListener('mouseup', dragEnd);
}

function setTranslate(xPos, yPos, el) {
    el.style.transform = `translate3d(${xPos}px, ${yPos}px, 0)`;
}

onBeforeUnmount(() => {
    document.removeEventListener('mousemove', drag);
    document.removeEventListener('mouseup', dragEnd);
});
</script>
