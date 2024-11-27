<template>
    <transition name="slide-fade" @before-leave="beforeLeave">
        <div v-if="props.visible"
             :class="['fixed bottom-4 right-4 px-4 py-3 rounded-[10px] shadow-lg text-white cursor-pointer', notificationClass]"
             @click="hideNotification"
             @mouseover="pauseTimer"
             @mouseleave="resumeTimer">
            <div class="flex items-center justify-between gap-3">
                <!-- Dynamisch icoon afhankelijk van props.icon -->
                <svg v-if="props.icon === 'check'" class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                </svg>
                <svg v-else class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM6.707 6.707a1 1 0 0 1 1.414-1.414L10 7.586l1.879-1.879a1 1 0 0 1 1.414 1.414L11.414 9l1.879 1.879a1 1 0 0 1-1.414 1.414L10 10.414l-1.879 1.879a1 1 0 0 1-1.414-1.414L8.586 9 6.707 7.121Z"/>
                </svg>
                <span class="sr-only">Icon</span>
                {{ props.message }}
            </div>
        </div>
    </transition>
</template>


<script setup>
import { computed, watch, onMounted, onUnmounted } from 'vue';
import { useNotification } from '@/composables/useNotification';

const props = defineProps({
    message: String,
    type: {
        type: String,
        default: 'success'
    },
    visible: Boolean,
    icon: {
        type: String,
        default: 'check'
    },
    duration: {
        type: Number,
        default: 3000
    }
});

const { hideNotification, pauseTimer, resumeTimer } = useNotification();


const notificationClass = computed(() => {
    return {
        'bg-green-500': props.type === 'success',
        'bg-red-500': props.type === 'error'
    };
});

function beforeLeave(el) {
    el.style.opacity = 1;
    el.style.transform = 'translateX(0)';
    setTimeout(() => {
        el.style.opacity = 0;
        el.style.transform = 'translateX(100%)';
    }, 0);
}

watch(() => props.visible, (newVisible) => {
    if (newVisible) {
        resumeTimer();
    } else {
        pauseTimer();
    }
});

onMounted(() => {
    if (props.visible) {
        resumeTimer();
    }
});

onUnmounted(() => {
    pauseTimer();
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
