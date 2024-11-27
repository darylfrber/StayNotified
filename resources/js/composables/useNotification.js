import { ref } from 'vue';

const notificationMessage = ref('');
const notificationType = ref('success');
const notificationIcon = ref('check'); // Voeg een icon-ref toe
const notificationVisible = ref(false);
let timer = null;
let remainingTime = 3000;
let startTime = null;

export function useNotification() {
    function showNotification(message, type = 'success', icon = 'check', duration = 3000) {
        notificationVisible.value = false;
        clearTimeout(timer);
        remainingTime = duration;

        setTimeout(() => {
            notificationMessage.value = message;
            notificationType.value = type;
            notificationIcon.value = icon; // Stel het icoon in
            notificationVisible.value = true;
            startTime = Date.now();
            timer = setTimeout(() => {
                hideNotification();
            }, remainingTime);
        }, 100); // Delay to ensure the transition works
    }

    function hideNotification() {
        notificationVisible.value = false;
        clearTimeout(timer);
    }

    function pauseTimer() {
        if (timer) {
            clearTimeout(timer);
            remainingTime -= Date.now() - startTime;
        }
    }

    function resumeTimer() {
        if (remainingTime > 0) {
            startTime = Date.now();
            timer = setTimeout(() => {
                hideNotification();
            }, remainingTime);
        }
    }

    return {
        notificationMessage,
        notificationType,
        notificationIcon, // Exporteer de icon-ref
        notificationVisible,
        showNotification,
        hideNotification,
        pauseTimer,
        resumeTimer
    };
}
