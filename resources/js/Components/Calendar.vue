<template>
    <div class="text-gray-700 h-full">
        <div class="flex flex-col h-full">
            <div class="flex items-center mt-4">
                <div class="flex ml-6">
                    <button @click="previousMonth">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button @click="nextMonth">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
                <h2 class="ml-2 text-xl font-bold leading-none">{{ currentMonthName }} {{ currentYear }}</h2>
            </div>
            <div class="grid grid-cols-7 mt-4">
                <div class="pl-1 text-sm">Mon</div>
                <div class="pl-1 text-sm">Tue</div>
                <div class="pl-1 text-sm">Wed</div>
                <div class="pl-1 text-sm">Thu</div>
                <div class="pl-1 text-sm">Fri</div>
                <div class="pl-1 text-sm">Sat</div>
                <div class="pl-1 text-sm">Sun</div>
            </div>
            <div class="grid flex-grow w-full h-full grid-cols-7 grid-rows-6 gap-px pt-px mt-1 bg-gray-200">
                <div v-for="day in daysInMonth" :key="day.date" class="relative flex flex-col bg-white group h-20">
                    <div class="relative self-start flex justify-center items-center">
                        <span :class="['m-1 text-xs font-bold flex items-center justify-center', { 'bg-blue-500 text-white border-blue-500 border-2 rounded-full': isToday(day.date) }]" style="width: 24px; height: 24px;">
                            {{ new Date(day.date).getDate() }}
                        </span>
                    </div>
                    <div class="flex flex-col overflow-auto">
                        <button v-for="event in day.events" :key="event.id" @click="editEvent(event)" class="flex items-center flex-shrink-0 h-5 px-1 text-xs hover:bg-gray-200">
                            <span :class="['flex-shrink-0 w-2 h-2 rounded-full', event.confirmed ? 'bg-blue-500' : 'border border-blue-500']"></span>
                            <span class="ml-2 font-light leading-none">{{ event.time }}</span>
                            <span class="ml-2 font-medium leading-none truncate">{{ event.title }}</span>
                        </button>
                    </div>
                    <button @click="openAddEventModal(day.date)" class="absolute top-1 right-0 items-center justify-center hidden w-6 h-6 mb-2 mr-2 text-white bg-blue-500 rounded group-hover:flex hover:bg-blue-400">
                        <svg viewBox="0 0 20 20" fill="currentColor" class="w-6 h-6 plus">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <CalendarEvents
            :isOpen="isModalOpen"
            :isEditMode="isEditMode"
            :event="currentEvent"
            @add-event="addEvent"
            @update-event="updateEvent"
            @delete-event="deleteEvent"
            @close="closeModal"
        />
    </div>
</template>
<script setup>
import { computed, ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import CalendarEvents from './CalendarEvents.vue';

const { props } = usePage();

const events = ref(props.events || []);
const localEvents = ref([...events.value]);
const currentYear = ref(new Date().getFullYear());
const currentMonth = ref(new Date().getMonth());
const isModalOpen = ref(false);
const isEditMode = ref(false);
const currentEvent = ref({
    id: null,
    date: '',
    time: '',
    title: '',
    confirmed: false,
});

function formatDate(date) {
    const d = new Date(date);
    const day = String(d.getDate()).padStart(2, '0');
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const year = d.getFullYear();
    return `${year}-${month}-${day}`;
}

function previousMonth() {
    if (currentMonth.value === 0) {
        currentMonth.value = 11;
        currentYear.value--;
    } else {
        currentMonth.value--;
    }
}

function nextMonth() {
    if (currentMonth.value === 11) {
        currentMonth.value = 0;
        currentYear.value++;
    } else {
        currentMonth.value++;
    }
}

function openAddEventModal(date) {
    currentEvent.value = {
        id: null,
        date: date,
        time: '',
        title: '',
        confirmed: false,
    };
    isEditMode.value = false;
    isModalOpen.value = true;
}

function closeModal() {
    isModalOpen.value = false;
}

function addEvent(event) {
    Inertia.post('/events', event, {
        onSuccess: () => {
            localEvents.value.push(event);
            closeModal();
        }
    });
}

function updateEvent(event) {
    Inertia.put(`/events/${event.id}`, event, {
        onSuccess: () => {
            const index = localEvents.value.findIndex(e => e.id === event.id);
            if (index !== -1) {
                localEvents.value.splice(index, 1, event);
            }
            closeModal();
        }
    });
}

function deleteEvent(event) {
    Inertia.delete(`/events/${event.id}`, {
        onSuccess: () => {
            localEvents.value = localEvents.value.filter(e => e.id !== event.id);
            closeModal();
        }
    });
}

function editEvent(event) {
    currentEvent.value = { ...event };
    isEditMode.value = true;
    isModalOpen.value = true;
}

function isToday(date) {
    const today = new Date();
    const d = new Date(date);
    return today.toDateString() === d.toDateString();
}

const daysInMonth = computed(() => {
    const year = currentYear.value;
    const month = currentMonth.value;
    const days = [];
    const date = new Date(year, month, 1);

    while (date.getMonth() === month) {
        const dayEvents = localEvents.value.filter(event => event.date === formatDate(date));
        days.push({ date: formatDate(date), events: dayEvents });
        date.setDate(date.getDate() + 1);
    }

    return days;
});

const currentMonthName = computed(() => {
    return new Date(currentYear.value, currentMonth.value).toLocaleString('default', { month: 'long' });
});
</script>
