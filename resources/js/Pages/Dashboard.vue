<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800">Dashboard</h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto px-2">
                <div class="bg-indigo-100 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-10">
                        <h4 class="text-3xl font-bold text-indigo-600 mb-3">Welcome <strong>{{ $page.props.auth.user.name }}</strong></h4>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
