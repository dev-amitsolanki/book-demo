<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingAddCollaborate = ref(false);
const collaborator_id = ref('');

const form = useForm({
    collaborator_id: '',
    book_id: props.book_id,
});

const props = defineProps({
    users: {
        type: Array
    },
    book_id: {
        type:Number
    }
})

const ConfirmAddCollaborate = () => {
    confirmingAddCollaborate.value = true;
};

const addCollaborate = () => {
    form.post(route('collaborate.add'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingAddCollaborate.value = false;

    form.reset();
};
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="font-semibold text-xl text-indigo-800 leading-tight mb-5">Collaborators
            <button type="button" class="float-right py-2 px-3 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-800" @click="ConfirmAddCollaborate">Add Collaborator</button>
            </h2>
        </header>
        <Modal :show="confirmingAddCollaborate" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Add Collaborate
                </h2>
                <div class="mt-6">
                    <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select an option</label>
                    <select id="countries" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" v-model="form.collaborator_id">
                        <option value="">Choose a Collaborate</option>
                        <option v-for="(data,index) in props.users" :value="data.id" :key="index">{{ data.name }} </option>
                    </select>
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>

                    <PrimaryButton
                        class="ml-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="addCollaborate"
                    >
                        Add
                    </PrimaryButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
