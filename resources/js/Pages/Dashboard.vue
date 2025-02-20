<script setup>
import AuthenticatedNav from '@/Layouts/AuthenticatedNav.vue';
import {Head, usePage} from '@inertiajs/vue3';
import {computed} from 'vue';

const {props} = usePage();

// Get sorted upcoming & today's events
const sortEventsByTime = (events) => {
    return [...events].sort((a, b) => new Date(a.date + ' ' + (a.time || '00:00')) - new Date(b.date + ' ' + (b.time || '00:00')));
};

const upcomingEvents = computed(() => {
    const today = new Date().toISOString().split('T')[0];
    return sortEventsByTime(props.events.filter(event => new Date(event.date) >= new Date(today)));
});

const todayEvents = computed(() => {
    const today = new Date().toISOString().split('T')[0];
    return sortEventsByTime(props.events.filter(event => event.date === today));
});

// Get recent and most viewed notes
const recentNotes = computed(() => [...props.notes].sort((a, b) => new Date(b.updated_at) - new Date(a.updated_at)).slice(0, 3));
const mostViewedNotes = computed(() => [...props.notes].sort((a, b) => b.views - a.views).slice(0, 3));

// Get current month for mini calendar
const currentMonth = new Date().toLocaleString('en-US', {month: 'long'});
</script>

<template>
    <Head title="Dashboard"/>

    <AuthenticatedNav>
        <div class="py-12">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- 📌 Recent Notes -->
                <div class="bg-white shadow-lg rounded-2xl p-6">
                    <h2 class="text-xl font-semibold">Recent Notes</h2>
                    <ul v-if="recentNotes.length">
                        <li v-for="note in recentNotes" :key="note.id" class="mt-2">
                            <a :href="`/notes/${note.id}`" class="text-blue-500 hover:underline">{{ note.title }}</a>
                        </li>
                    </ul>
                    <p v-else class="text-gray-500">No notes found.</p>
                </div>

                <!-- 📊 Most Viewed Notes -->
                <div class="bg-white shadow-lg rounded-2xl p-6">
                    <h2 class="text-xl font-semibold">Most Viewed Notes</h2>
                    <ul v-if="mostViewedNotes.length">
                        <li v-for="note in mostViewedNotes" :key="note.id" class="mt-2">
                            <a :href="`/notes/${note.id}`" class="text-blue-500 hover:underline">{{ note.title }}</a>
                            <p class="text-gray-500 text-sm">Views: {{ note.views }}</p>
                        </li>
                    </ul>
                    <p v-else class="text-gray-500">No notes found.</p>
                </div>

                <!-- 📅 Mini Calendar -->
                <div class="bg-white shadow-lg rounded-2xl p-6">
                    <h2 class="text-xl font-semibold text-center">{{ currentMonth }}</h2>
                    <div class="grid grid-cols-7 gap-1 text-center mt-4 text-gray-700">
                        <span v-for="day in ['S', 'M', 'T', 'W', 'T', 'F', 'S']" :key="day" class="font-bold">{{
                                day
                            }}</span>
                        <span v-for="n in 30" :key="n" class="p-2">{{ n }}</span>
                    </div>
                    <button @click="$inertia.visit('/calendar')"
                            class="mt-4 w-full py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                        View Full Calendar
                    </button>
                </div>

                <!-- 📆 Upcoming Events -->
                <div class="bg-white shadow-lg rounded-2xl p-6">
                    <h2 class="text-xl font-semibold mb-3">Upcoming Events</h2>
                    <div v-if="upcomingEvents.length" class="space-y-4">
                        <div v-for="event in upcomingEvents" :key="event.id"
                             class="bg-gray-100 p-4 rounded-lg shadow-md flex items-center">
                            <div class="bg-blue-500 text-white p-3 rounded-lg text-center">
                                <p class="text-xl font-bold">{{ new Date(event.date).getDate() }}</p>
                                <p class="text-sm">{{
                                        new Date(event.date).toLocaleString('en-US', {month: 'short'})
                                    }}</p>
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
                <div class="bg-white shadow-md rounded-2xl p-6">
                    <h2 class="text-xl font-semibold mb-3">Today's Events</h2>
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
