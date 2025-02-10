<template>
    <div class="w-full md:w-2/3 p-6">
        <div class="flex justify-between items-center">
            <button @click="goBack" class="md:hidden bg-gray-300 px-3 py-1 rounded">← Back</button>
            <input v-model="title" class="w-full text-xl rounded focus:outline-none p-2" placeholder="Titel">
        </div>

        <div class="border rounded-lg p-4 mt-4 bg-white shadow">
            <div class="flex gap-2 mb-2">
                <button @click="editor.chain().focus().toggleBold().run()" class="bg-gray-200 px-2 py-1 rounded">B</button>
                <input type="color" v-model="textColor" @input="updateTextColor" class="w-8 h-8 border rounded">
                <select v-model="fontSize" @change="updateFontSize" class="border p-1 rounded">
                    <option value="text-sm">Small</option>
                    <option value="text-base">Normal</option>
                    <option value="text-lg">Large</option>
                    <option value="text-xl">Extra Large</option>
                </select>
            </div>
            <EditorContent :editor="editor" class="prose max-w-none" />
        </div>
        <div class="flex gap-2 mt-5">
            <button @click="saveNote" class="bg-green-500 text-white px-4 py-2 rounded">Save</button>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { Editor, EditorContent } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';
import TextStyle from '@tiptap/extension-text-style';
import Color from '@tiptap/extension-color';
import {useNotification} from '@/composables/useNotification';

const {showNotification} = useNotification();
const props = defineProps({note: Object});
const emit = defineEmits(['back']);

const title = ref(props.note?.title || '');
const textColor = ref('#000000');
const fontSize = ref('text-base');

const editor = ref(new Editor({
    extensions: [StarterKit, TextStyle, Color],
    content: props.note?.content || '',
}));

watch(() => props.note, (newNote) => {
    title.value = newNote?.title || '';
    editor.value.commands.setContent(newNote?.content || '');
});

const updateTextColor = () => {
    editor.value.chain().focus().setColor(textColor.value).run();
};

const updateFontSize = () => {
    editor.value.chain().focus().setTextStyle({fontSize: fontSize.value}).run();
};

const saveNote = () => {
    router.put(`/notes/${props.note.id}`, {
        title: title.value,
        content: editor.value.getHTML(),
    }, {
        onSuccess: () => showNotification('Note saved!', 'success')
    });
};


const goBack = () => {
    emit('back');
};
</script>

<style>
.tiptap {
    min-height: 200px;
}
</style>
