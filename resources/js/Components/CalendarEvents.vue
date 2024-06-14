<template>
    <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" @click.self="closeModal">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3" @click.stop>
            <h2 class="text-xl font-bold mb-4">{{ isEditMode ? 'Edit Event' : 'Add Event' }}</h2>
            <form @submit.prevent="submitEvent">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                    <input type="date" v-model="event.date" class="w-full px-3 py-2 border border-gray-300 rounded-md" required />
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Time</label>
                    <input type="time" v-model="event.time" class="w-full px-3 py-2 border border-gray-300 rounded-md" required />
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                    <input type="text" v-model="event.title" class="w-full px-3 py-2 border border-gray-300 rounded-md" required />
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirmed</label>
                    <input type="checkbox" v-model="event.confirmed" />
                </div>
                <div class="flex justify-end">
                    <button type="button" @click="closeModal" class="px-4 py-2 bg-gray-500 text-white rounded-md mr-2">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md">{{ isEditMode ? 'Save' : 'Add' }}</button>
                </div>
            </form>
            <div v-if="isEditMode" class="flex justify-end mt-4">
                <button @click="deleteEvent" class="px-4 py-2 bg-red-500 text-white rounded-md">Delete</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        isOpen: {
            type: Boolean,
            required: true
        },
        isEditMode: {
            type: Boolean,
            default: false
        },
        event: {
            type: Object,
            default: () => ({
                id: null,
                date: '',
                time: '',
                title: '',
                confirmed: false
            })
        }
    },
    methods: {
        closeModal() {
            this.$emit('close');
        },
        submitEvent() {
            if (this.isEditMode) {
                this.$emit('update-event', {...this.event});
            } else {
                this.$emit('add-event', {...this.event});
            }
        },
        deleteEvent() {
            this.$emit('delete-event', {...this.event});
        }
    }
};
</script>
