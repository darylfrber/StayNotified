<template>
    <transition name="slide-fade">
        <div v-show="visible"
             :class="['fixed bottom-4 right-4 px-4 py-3 rounded-[10px] shadow-lg text-white cursor-pointer', notificationClass]"
             @click="hideNotification"
             @mouseover="pauseTimer"
             @mouseleave="resumeTimer">
            <div class="flex items-center justify-between gap-3">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                     viewBox="0 0 20 20">
                    <path
                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                </svg>
                <span class="sr-only">Check icon</span>
                {{ message }}
            </div>
        </div>
    </transition>
</template>

<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
    message: String,
    type: {
        type: String,
        default: 'success',
    },
    duration: {
        type: Number,
        default: 3000,
    }
});

const visible = ref(false);
let timer = null;
let remainingTime = props.duration;
let startTime = null;
let isPaused = false;

const notificationClass = computed(() => {
    return {
        'bg-green-500': props.type === 'success',
        'bg-red-500': props.type === 'error'
    };
});

function showNotification() {
    visible.value = true;
    startTime = Date.now();
    isPaused = false;
    timer = setTimeout(() => {
        hideNotification();
    }, remainingTime);
}

function hideNotification() {
    visible.value = false;
    clearTimeout(timer);
}

function pauseTimer() {
    if (!isPaused) {
        clearTimeout(timer);
        remainingTime -= Date.now() - startTime;
        isPaused = true;
    }
}

function resumeTimer() {
    if (isPaused) {
        startTime = Date.now();
        isPaused = false;
        timer = setTimeout(() => {
            hideNotification();
        }, remainingTime);
    }
}

watch(() => props.message, (newMessage) => {
    if (newMessage) {
        clearTimeout(timer); // Clear any existing timer
        remainingTime = props.duration; // Reset remaining time
        showNotification();
    }
});
</script>

<style>
.slide-fade-enter-active, .slide-fade-leave-active {
    transition: transform 0.5s ease, opacity 0.5s ease;
}

.slide-fade-enter-from, .slide-fade-leave-to {
    transform: translateX(100%);
    opacity: 0;
}

.slide-fade-enter-to, .slide-fade-leave-from {
    transform: translateX(0);
    opacity: 1;
}
</style>
