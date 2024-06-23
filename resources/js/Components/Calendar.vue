<template>
    <div class="text-gray-700 h-full" ref="calendarContainer">
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
                <div v-for="day in daysInMonth" :key="day.date" :class="['relative flex flex-col group h-20', { 'bg-white': !day.outOfMonth, 'bg-gray-100': day.outOfMonth }]">
                    <div class="relative self-start flex justify-center items-center">
                        <span :class="['m-1 text-xs font-bold flex items-center justify-center', { 'bg-gray-500 text-white border-gray-500 border-2 rounded-full': isToday(day.date) }]" style="width: 24px; height: 24px;">
                            {{ new Date(day.date).getDate() }}
                        </span>
                        <span v-if="isFirstDayOfMonth(day.date)" class="text-xs font-light">{{ getMonthName(day.date) }}</span>
                    </div>
                    <div class="flex flex-col overflow-auto -mt-0.5">
                        <button
                            v-for="event in day.events.filter(e => e).sort((a, b) => (a.time || '').localeCompare(b.time || ''))"
                            :key="event.id"
                            @click="editEvent(event)"
                            :class="[
                                'flex items-center mx-1 my-0.5 rounded-md flex-shrink-0 h-5 px-1 text-xs transition ease-in-out',
                                event.time ? 'hover:bg-gray-200' : 'bg-blue-400 hover:bg-blue-500 text-white'
                            ]"
                        >
                            <span
                              v-if="event.time"
                              :class="['flex-shrink-0 w-2 h-2 rounded-full', event.confirmed ? 'bg-blue-400' : 'border border-blue-400']">
                            </span>
                            <span v-if="event.time" class="ml-1 font-light leading-none">{{ event.time }}</span>
                            <span :class="['font-medium leading-none truncate', { 'ml-1.5': event.time }]">{{ event.title }}</span>

                        </button>
                    </div>
                    <button @click="openAddEventModal(day.date)" class="absolute top-1.5 right-0 items-center justify-center hidden w-5 h-5 mr-1 text-white bg-blue-400 transition ease-in-out rounded-md group-hover:flex hover:bg-blue-500">
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
            @close="closeModal"
            @event-updated="refreshEvents"
        />
    </div>
</template>

<script setup>
import { computed, ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
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
    saveScrollPosition();
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

function editEvent(event) {
    saveScrollPosition();
    currentEvent.value = { ...event };
    isEditMode.value = true;
    isModalOpen.value = true;
}

function saveScrollPosition() {
    sessionStorage.setItem('scrollPosition', window.scrollY);
}

function restoreScrollPosition() {
    const scrollPosition = sessionStorage.getItem('scrollPosition');
    if (scrollPosition !== null) {
        window.scrollTo(0, parseInt(scrollPosition));
    }
}

function isToday(date) {
    const today = new Date();
    const d = new Date(date);
    return today.toDateString() === d.toDateString();
}

function isFirstDayOfMonth(date) {
    return new Date(date).getDate() === 1;
}

function getMonthName(date) {
    return new Date(date).toLocaleString('default', { month: 'short' });
}

const daysInMonth = computed(() => {
    const year = currentYear.value;
    const month = currentMonth.value;
    const days = [];
    const date = new Date(year, month, 1);

    // Get the first day of the month (0 = Sunday, 1 = Monday, ..., 6 = Saturday)
    const firstDay = new Date(year, month, 1).getDay();

    // Fill in the days before the start of the month
    const previousMonthDays = firstDay === 0 ? 6 : firstDay - 1;
    for (let i = previousMonthDays; i > 0; i--) {
        const prevDate = new Date(year, month, 1);
        prevDate.setDate(prevDate.getDate() - i);
        const dayEvents = localEvents.value.filter(event => event.date === formatDate(prevDate));
        days.push({ date: formatDate(prevDate), events: dayEvents, outOfMonth: true });
    }

    // Fill in the days of the current month
    while (date.getMonth() === month) {
        const dayEvents = localEvents.value.filter(event => event.date === formatDate(date));
        days.push({ date: formatDate(date), events: dayEvents });
        date.setDate(date.getDate() + 1);
    }

    // Fill in the days after the end of the month
    const remainingDays = 42 - days.length;
    for (let i = 0; i < remainingDays; i++) {
        const nextDate = new Date(year, month + 1, 1);
        nextDate.setDate(nextDate.getDate() + i);
        const dayEvents = localEvents.value.filter(event => event.date === formatDate(nextDate));
        days.push({ date: formatDate(nextDate), events: dayEvents, outOfMonth: true });
    }

    return days;
});

const currentMonthName = computed(() => {
    return new Date(currentYear.value, currentMonth.value).toLocaleString('default', { month: 'long' });
});

function refreshEvents(updatedEvents) {
    localEvents.value = updatedEvents;
    restoreScrollPosition();
}

watch(isModalOpen, (newVal) => {
    if (!newVal) {
        restoreScrollPosition();
    }
});

</script>

