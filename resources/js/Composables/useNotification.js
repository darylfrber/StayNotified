// src/composables/useNotification.js
import { ref } from 'vue';

const message = ref('');
const type = ref('success');
const duration = ref(3000);
const visible = ref(false);
let timer = null;

export function useNotification() {
    function showNotification(newMessage, newType = 'success', newDuration = 3000) {
        message.value = newMessage;
        type.value = newType;
        duration.value = newDuration;
        visible.value = true;

        if (timer) clearTimeout(timer);

        timer = setTimeout(() => {
            visible.value = false;
        }, duration.value);
    }

    function hideNotification() {
        visible.value = false;
        if (timer) clearTimeout(timer);
    }

    function pauseTimer() {
        if (timer) clearTimeout(timer);
    }

    function resumeTimer() {
        timer = setTimeout(() => {
            hideNotification();
        }, duration.value);
    }

    return {
        message,
        type,
        duration,
        visible,
        showNotification,
        hideNotification,
        pauseTimer,
        resumeTimer
    };
}
