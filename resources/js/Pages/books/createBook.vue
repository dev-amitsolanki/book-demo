<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Section from '@/Components/Section.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head,Link,usePage,useForm } from '@inertiajs/vue3';
import { ref,reactive } from 'vue';
import { v4 as uuidv4 } from 'uuid';

const user = usePage().props.auth.user;

const chapters = reactive([]);
const sections = ref([]);
const book_name = ref('');

const form = useForm({
    book_name: book_name,
    chapters: chapters,
});

const addChapter = () => {
    let data = {
        chapter_name : '',
        chapter_no : '',
        sections : [],
    }
    form.chapters.push(data);
};
const addSection = (index) => {
    let data = {
        title : '',
        text : '',
        uuid : uuidv4(),
        parent_id : null
    }
    form.chapters[index].sections.push(data)
    
};
const addSubSection = (index,uuid) => {
    let data = {
        title : '',
        text : '',
        uuid : uuidv4(),
        parent_id: uuid
    }
    // chapters[index].sections.push(data)
        // console.log(index,uuid,'chapters')
        
    form.chapters[index].sections.map((value) => {
        let obj = find_by_id(uuid,value)
         obj.sections = [data]
         return obj
    })
    // console.log(chapters,'chapters[index].sections')
    
};
const find_by_id = (target, object) => {
  if (object.uuid === target) {
    return object
  }
  for (let c of object.sections) {
    let x = find_by_id(target, c) 
    if (x) {
      return x
    }
  }
}
// const submitData = () => {
//     console.log(chapters,'chapters')
// }
</script>

<template>
    <Head title="Create Book" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800">Create Book
                <Link class="float-right py-2 px-3 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-800" :href="route('books.index')">Back</Link>
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-indigo-50 shadow sm:rounded-lg">
                    <form @submit.prevent="form.post(route('books.store'))" class="">
                        <h2 class="font-semibold text-xl text-gray-800">Add Book</h2>
                        <hr class="my-3 border-indigo-600"/>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <InputLabel for="name" value="Book Name"  class="mb-3 block w-full"  />
                                <TextInput
                                    id="book_name"
                                    type="text"
                                    class="block w-full"
                                    v-model="form.book_name"
                                    required
                                    autofocus
                                    autocomplete="book_name"
                                />
                                <InputError class="block w-full" :message="form.errors.book_name" />
                            </div>
                            <div>
                                <button class="float-right py-3 px-3 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-800 mt-6" type="button" @click="addChapter">+ Add Chapter</button>
                            </div>
                        </div>
                        <div v-for="(data,index) in form.chapters" :key="index"> 
                            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg mt-3">
                                <h2 class="font-semibold text-xl text-gray-800">Add Chapter</h2>
                                <hr class="my-3 border-indigo-600"/>
                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <InputLabel for="name" value="Chapter Name" class="mb-3 block w-full" />
                                        <TextInput
                                            id="chapter_name"
                                            type="text"
                                            class="block w-full"
                                            v-model="data.chapter_name"
                                            required
                                            autofocus
                                            autocomplete="chapter_name"
                                        />
                                    </div>
                                    <div>
                                        <InputLabel for="name" value="Chapter Number" class="mb-3 block w-full" />
                                        <TextInput
                                            id="chapter_no"
                                            type="text"
                                            class="block w-full"
                                            v-model="data.chapter_no"
                                            required
                                            autofocus
                                            autocomplete="chapter_no"
                                        />
                                    </div>
                                    <div>
                                        <button class="float-right py-3 px-3 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-800 mt-6" @click="addSection(index)" type="button">+ Add Section</button>
                                    </div>
                                </div>
                                <Section :data="data" :index="index" @add-sub-section="addSubSection" :book="{}"></Section>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 mt-5">
                            <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                            <Transition
                                enter-active-class="transition ease-in-out"
                                enter-from-class="opacity-0"
                                leave-active-class="transition ease-in-out"
                                leave-to-class="opacity-0"
                            >
                                <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                            </Transition>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
