<script setup>
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingRemoveBook = ref(false);

const form = useForm({
    book_id: props.book_id,
});

const props = defineProps({
    book_id: {
        type:Number
    },
})

const ConfirmRemoveBook = () => {
    confirmingRemoveBook.value = true;
};

const removeBook = () => {
    form.delete(route('books.destroy',form.book_id), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingRemoveBook.value = false;

    form.reset();
};
</script>

<template>
    <div class="w-auto" style="display:inline-block;">
        <button type="button" class="rounded-md bg-red-600 px-4 py-2 ml-2 text-sm font-semibold text-white hover:bg-red-500" @click="ConfirmRemoveBook">Delete</button>
        <Modal :show="confirmingRemoveBook" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Are you sur e you want to Delete Book?
                </h2>
                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>
                    <DangerButton
                        class="ml-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="removeBook"
                    >
                        Delete
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </div>
</template>
