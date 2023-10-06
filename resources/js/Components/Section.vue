<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Section from '@/Components/Section.vue';
import { v4 as uuidv4 } from 'uuid';
import { usePage } from '@inertiajs/vue3';

const user = usePage().props.auth.user;
defineProps({
    data: {
        type: String,
    },
    index: {
        type: Number,
    },
    book : {
        type: Object
    }
});
const addSubSection = (section) => {
    // $emit('addSubSection',index,uuid);
    let data = {
        title : '',
        text : '',
        uuid : uuidv4(),
        parent_id: section.uuid
    }
    if(section.sections == undefined){
        section.sections = [data]
    }else{
        section.sections.push(data)
    }
};
</script>

<template>
    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg mt-3"  v-for="(section,si) in data.sections" :key="si">
        <h2 class="font-semibold text-xl text-gray-800">Section</h2>
        <hr class="my-3 border-indigo-600"/>
        <div class="grid grid-cols-3 gap-4">
            <div>
                <InputLabel for="name" value="Title"  class="mb-3 block w-full" />
                <TextInput
                    id="title"
                    type="text"
                    class="block w-full"
                    v-model="section.title"
                    required
                    autofocus
                    autocomplete="title"
                />
            </div>
            <div>
                <InputLabel for="name" value="Text"  class="mb-3 block w-full" />
                <TextInput
                    id="text"
                    type="text"
                    class="block w-full"
                    v-model="section.text"
                    required
                    autofocus
                    autocomplete="text"
                />
            </div>
            <div v-if="(book && user && user.id == book.user_id) || book.id == undefined">
                <button class="float-right py-3 px-3 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-800 mt-6" @click="addSubSection(section)" type="button">+ Add Sub Section </button>
            </div>
        </div>
        <Section :data="section" :index="index" @add-sub-section="addSubSection" :key="section.uuid" :book="book ?? {}"></Section>
    </div>
</template>
