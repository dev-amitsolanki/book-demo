<script setup>
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingRemoveCollaborate = ref(false);
const collaborator_id = ref(props.collaborator_id);

const form = useForm({
    collaborator_id: props.collaborator_id,
    book_id: props.book_id,
});

const props = defineProps({
    users: {
        type: Array
    },
    book_id: {
        type:Number
    },
    collaborator_id: {
        type:Number
    },
})

const ConfirmRemoveCollaborate = () => {
    confirmingRemoveCollaborate.value = true;
};

const removeCollaborate = () => {
    form.post(route('collaborate.remove'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingRemoveCollaborate.value = false;

    form.reset();
};
</script>

<template>
    <div class="w-auto" style="display:inline-block;">
        <button type="button" class="rounded-md bg-red-600 px-4 py-2 ml-2 text-sm font-semibold text-white hover:bg-red-500" @click="ConfirmRemoveCollaborate">Remove</button>
        <Modal :show="confirmingRemoveCollaborate" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Are you sure you want to remove collaborate?
                </h2>
                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>

                    <DangerButton
                        class="ml-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="removeCollaborate"
                    >
                        Remove
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </div>
</template>
