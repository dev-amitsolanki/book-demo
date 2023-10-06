<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import RemoveBookForm from './RemoveBookForm.vue'
import { usePage,Link } from '@inertiajs/vue3';

const user = usePage().props.auth.user;

defineProps({
    books: {
        type: Array,
    },    
});
</script>

<template>
    <Head title="Books" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800">Books list
                <Link class="float-right py-2 px-3 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-800" :href="route('books.create')">Create Book</Link>
            </h2>
        </template>
        <div class="py-6">
            <div class="max-w-7xl mx-auto px-2">
                <div class="p-6 bg-white shadow sm:rounded-lg">
                    <div class="max-w-full">
                        <Layout title="Books">
                            <table class="w-full whitespace-no-wrapw-full whitespace-no-wrap">
                                <thead class="bg-indigo-100">
                                    <tr class="font-bold bg-indigo-50 text-sm text-left">
                                        <th class="border px-6 py-4">No.</th>
                                        <th class="border px-6 py-4">Name</th>
                                        <th class="border px-6 py-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(data,index) in books" :key="index" class="text-sm">
                                        <td class="border px-6 py-4">{{ data.id }}</td>
                                        <td class="border px-6 py-4">{{ data.name }}</td>
                                        <td class="border px-6 py-4 w-4/12">
                                            <Link type="button" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500" :href="route('collaborators.index',data.id)" v-if="user.id == data.user_id">Collaborators</Link>
                                            <Link type="button" class="rounded-md bg-green-600 px-4 py-2 ml-2 text-sm font-semibold text-white hover:bg-green-500" :href="route('books.edit',data.id)">Edit</Link>
                                            <RemoveBookForm :book_id="data.id" />
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
