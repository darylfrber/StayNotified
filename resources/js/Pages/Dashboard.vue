<script setup>
import AuthenticatedNav from '@/Layouts/AuthenticatedNav.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const { props } = usePage();

const sortEventsByTime = (events) => {
    return [...events].sort((a, b) =>
        new Date(a.date + ' ' + (a.time || '00:00')) - new Date(b.date + ' ' + (b.time || '00:00'))
    );
};

const today = new Date();
const currentMonth = today.toLocaleString('en-US', { month: 'long' });
const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);

// Seizoens-emoji bij maand
const seasonEmoji = computed(() => {
    const month = today.getMonth();
    if ([2, 3, 4].includes(month)) return '🌸'; // lente
    if ([5, 6, 7].includes(month)) return '☀️'; // zomer
    if ([8, 9, 10].includes(month)) return '🍂'; // herfst
    return '❄️'; // winter
});

const upcomingEvents = computed(() => {
    const todayStr = new Date().toISOString().split('T')[0];
    return sortEventsByTime(props.events.filter(event => new Date(event.date) >= new Date(todayStr)));
});

const todayEvents = computed(() => {
    const todayStr = new Date().toISOString().split('T')[0];
    return sortEventsByTime(props.events.filter(event => event.date === todayStr));
});

const recentNotes = computed(() => [...props.notes].sort((a, b) => new Date(b.updated_at) - new Date(a.updated_at)).slice(0, 3));
const mostViewedNotes = computed(() => [...props.notes].sort((a, b) => b.views - a.views).slice(0, 3));

const daysInMonth = computed(() => {
    const days = [];
    const startDay = firstDay.getDay();
    const totalDays = lastDay.getDate();

    const prevMonthLastDay = new Date(today.getFullYear(), today.getMonth(), 0).getDate();
    for (let i = startDay - 1; i >= 0; i--) {
        days.push({ day: prevMonthLastDay - i, disabled: true });
    }

    for (let i = 1; i <= totalDays; i++) {
        days.push({
            day: i,
            current: i === today.getDate(),
            hasEvent: props.events.some(e => {
                const d = new Date(e.date);
                return (
                    d.getDate() === i &&
                    d.getMonth() === today.getMonth() &&
                    d.getFullYear() === today.getFullYear()
                );
            })
        });
    }

    while (days.length % 7 !== 0) {
        days.push({ day: days.length - totalDays - startDay + 1, disabled: true });
    }

    return days;
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedNav>
        <div class="py-12">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 items-start [grid-auto-flow:row_dense]">

                <!-- 📝 Recent Notes -->
                <div class="bg-white shadow-lg rounded-2xl p-6 self-start w-full col-span-1">
                    <h2 class="text-xl font-semibold mb-4">📝 Recent Notes</h2>
                    <ul v-if="recentNotes.length" class="space-y-2">
                        <li class="cursor-pointer" v-for="note in recentNotes" :key="note.id">
                            <a @click="$inertia.visit('/notes')" class="block bg-gray-50 hover:bg-blue-50 p-3 rounded-lg transition text-gray-800 font-medium truncate">
                                {{ note.title }}
                            </a>
                        </li>
                    </ul>
                    <p v-else class="text-gray-500">No notes found.</p>
                </div>

                <!-- 🔥 Most Viewed Notes -->
                <div class="bg-white shadow-lg rounded-2xl p-6 self-start w-full col-span-1">
                    <h2 class="text-xl font-semibold mb-4">🔥 Most Viewed Notes</h2>
                    <ul v-if="mostViewedNotes.length" class="space-y-2">
                        <li class="cursor-pointer" v-for="note in mostViewedNotes" :key="note.id">
                            <a @click="$inertia.visit('/notes')" class="block bg-gray-50 hover:bg-blue-50 p-3 rounded-lg transition text-gray-800 font-medium truncate">
                                {{ note.title }}
                            </a>
                        </li>
                    </ul>
                    <p v-else class="text-gray-500">No notes found.</p>
                </div>

                <!-- 📅 Mini Calendar -->
                <div class="bg-white shadow-lg rounded-2xl p-6 w-full col-span-1 lg:row-span-2">
                    <h2 class="text-xl font-semibold">{{ seasonEmoji }} {{ currentMonth }}</h2>
                    <div class="grid grid-cols-7 gap-1 text-center mt-4 text-gray-700">
                        <span v-for="day in ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']" :key="day" class="font-bold">
                            {{ day }}
                        </span>
                        <span
                            v-for="date in daysInMonth"
                            :key="`${date.day}-${date.disabled}`"
                            :class="[
                                'p-3 aspect-w-1 aspect-h-1 rounded-full relative flex items-center justify-center transition',
                                date.current ? 'bg-blue-500 text-white' : 'hover:bg-gray-200',
                                date.disabled ? 'text-gray-400' : 'text-black'
                            ]">
                            <button @click="$inertia.visit('/calendar')" class="w-full h-full flex items-center justify-center">
                                {{ date.day }}
                                <span v-if="date.hasEvent"
                                      class="block w-2 h-2 bg-blue-500 rounded-full absolute bottom-1 left-1/2 transform -translate-x-1/2"></span>
                            </button>
                        </span>
                    </div>
                    <button @click="$inertia.visit('/calendar')"
                            class="mt-4 w-full py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                        View Full Calendar
                    </button>
                </div>

                <!-- 📆 Upcoming Events -->
                <div class="bg-white shadow-lg rounded-2xl p-6 w-full col-span-1">
                    <h2 class="text-xl font-semibold mb-3">📆 Upcoming Events</h2>
                    <div v-if="upcomingEvents.length" class="space-y-4">
                        <div v-for="event in upcomingEvents" :key="event.id"
                             class="bg-gray-100 p-4 rounded-lg shadow-md flex items-center">
                            <div class="bg-blue-500 text-white p-3 rounded-lg text-center">
                                <p class="text-xl font-bold">{{ new Date(event.date).getDate() }}</p>
                                <p class="text-sm">{{ new Date(event.date).toLocaleString('en-US', { month: 'short' }) }}</p>
                            </div>
                            <div class="ml-4">
                                <h3 class="font-semibold text-lg">{{ event.title }}</h3>
                                <p class="text-gray-500">{{ event.time || 'No time specified' }}</p>
                            </div>
                        </div>
                    </div>
                    <p v-else class="text-gray-500">No upcoming events.</p>
                </div>

                <!-- 🎯 Today's Events -->
                <div class="bg-white shadow-md rounded-2xl p-6 w-full col-span-1">
                    <h2 class="text-xl font-semibold mb-3">🎯 Today's Events</h2>
                    <div v-if="todayEvents.length" class="space-y-2">
                        <div v-for="event in todayEvents" :key="event.id"
                             class="bg-gray-50 p-4 rounded-lg shadow-sm flex items-center">
                            <div class="text-gray-700 text-center">
                                <p class="text-lg font-bold">📍</p>
                            </div>
                            <div class="ml-4">
                                <h3 class="font-semibold text-lg">{{ event.title }}</h3>
                                <p class="text-gray-500">{{ event.time || 'No time specified' }}</p>
                            </div>
                        </div>
                    </div>
                    <p v-else class="text-gray-500">No events for today.</p>
                </div>

            </div>
        </div>
    </AuthenticatedNav>
</template>
