<template>
    <div class="text-gray-700">
        <div class="flex flex-grow w-screen h-screen overflow-auto">
            <div class="flex flex-col flex-grow">
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
                    <h2 class="ml-2 text-xl font-bold leading-none">{{ currentMonth }} {{ currentYear }}</h2>
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
                <div class="grid flex-grow w-full h-auto grid-cols-7 grid-rows-5 gap-px pt-px mt-1 bg-gray-200">
                    <div v-for="day in daysInMonth" :key="day.date" class="relative flex flex-col bg-white group">
                        <span :class="['mx-2 my-1 text-xs font-bold', { 'border-blue-500 border-2 rounded-full': isToday(day.date) }]">{{ day.date }}</span>
                        <div class="flex flex-col px-1 py-1 overflow-auto">
                            <button v-for="event in day.events" :key="event.time" class="flex items-center flex-shrink-0 h-5 px-1 text-xs hover:bg-gray-200">
                                <span :class="['flex-shrink-0 w-2 h-2 rounded-full', event.confirmed ? 'bg-gray-500' : 'border border-gray-500']"></span>
                                <span class="ml-2 font-light leading-none">{{ event.time }}</span>
                                <span class="ml-2 font-medium leading-none truncate">{{ event.title }}</span>
                            </button>
                        </div>
                        <button @click="addEvent(day.date)" class="absolute bottom-0 right-0 items-center justify-center hidden w-6 h-6 mb-2 mr-2 text-white bg-gray-400 rounded group-hover:flex hover:bg-gray-500">
                            <svg viewBox="0 0 20 20" fill="currentColor" class="w-6 h-6 plus"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            currentYear: new Date().getFullYear(),
            currentMonth: new Date().getMonth(),
            events: [
                { date: '2024-06-01', time: '8:30am', title: 'An unconfirmed event', confirmed: false },
                { date: '2024-06-01', time: '2:15pm', title: 'A confirmed event', confirmed: true },
                // Add more events here
            ],
        };
    },
    computed: {
        daysInMonth() {
            const year = this.currentYear;
            const month = this.currentMonth;
            const days = [];
            const date = new Date(year, month, 1);

            while (date.getMonth() === month) {
                const dayEvents = this.events.filter(event => event.date === this.formatDate(date));
                days.push({ date: this.formatDate(date), events: dayEvents });
                date.setDate(date.getDate() + 1);
            }

            return days;
        },
        currentMonth() {
            return new Date(this.currentYear, this.currentMonth).toLocaleString('default', { month: 'long' });
        },
    },
    methods: {
        formatDate(date) {
            const d = new Date(date);
            const day = d.getDate();
            const month = d.toLocaleString('default', { month: 'long' });
            const year = d.getFullYear();

            return `${day} ${month} ${year}`;
        },
        previousMonth() {
            if (this.currentMonth === 0) {
                this.currentMonth = 11;
                this.currentYear--;
            } else {
                this.currentMonth--;
            }
        },
        nextMonth() {
            if (this.currentMonth === 11) {
                this.currentMonth = 0;
                this.currentYear++;
            } else {
                this.currentMonth++;
            }
        },
        addEvent(date) {
            // Add event logic here
            alert(`Add event to ${date}`);
        },
        isToday(date) {
            const today = new Date();
            const d = new Date(date);
            return today.toDateString() === d.toDateString();
        }
    },
};
</script>

<style scoped>
/* Add your styles here */
</style>
