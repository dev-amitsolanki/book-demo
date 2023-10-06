<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AddCollaboratorForm from './AddCollaboratorForm.vue'
import RemoveCollaboratorForm from './RemoveCollaboratorForm.vue'
import { Head,Link } from '@inertiajs/vue3';

defineProps({
    collaborators: {
        type: Array,
    },    
    users: {
        type: Array,
    },    
    id: {
        type: Number,
    },    
});
</script>

<template>
    <Head title="Collaborators" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800">
                <AddCollaboratorForm class="w-full" :users="users" :book_id="id" />
            </h2>
        </template>
        <div class="py-6">
            <div class="max-w-7xl mx-auto px-2">
                <div class="p-6 bg-white shadow sm:rounded-lg">
                    <div class="max-w-full">
                    <Layout title="Collaborators">
                        <table class="w-full whitespace-no-wrapw-full whitespace-no-wrap">
                            <thead class="bg-indigo-100">
                                <tr class="font-bold bg-indigo-50 text-sm text-left">
                                    <th class="border px-6 py-4">No.</th>
                                    <th class="border px-6 py-4">Name</th>
                                    <th class="border px-6 py-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(data,index) in collaborators" :key="index">
                                    <td class="border px-6 py-4">{{ data.id }}</td>
                                    <td class="border px-6 py-4">{{ data.name }}</td>
                                    <td class="border px-6 py-4 w-3/12">
                                        <RemoveCollaboratorForm :users="users" :collaborator_id="data.id" :book_id="id" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </Layout>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
