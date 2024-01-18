<template>
    <QuasarLayout>
        <main class="container w-full p-4 mx-auto">
            <div
                class="p-4 border border-gray-200 rounded-md shadow-sm dark:border-gray-800 dark:text-gray-300"
            >
                <div
                    class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3"
                >
                    <div
                        v-for="file in managerPendingFiles.data"
                        :key="file.id"
                    >
                        <div class="p-4 mb-4 border border-gray-300 rounded-md">
                            <Link
                                v-if="file.id"
                                :href="route('report.show', file.id)"
                            >
                                <div class="flex flex-col items-start gap-2">
                                    <span class="font-bold">{{
                                        file.name
                                    }}</span>
                                    <span>{{ file.employee.name }}</span>
                                    <span>{{ file.employer.name }}</span>
                                    <span class="font-bold">{{
                                        file.employer_status
                                    }}</span>
                                    <span>{{ file.movement }}</span>
                                    <span v-html="file.employer_feedback"></span>
                                </div>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-center mt-4">
                <div class="flex gap-1">
                    <Link
                        v-for="(link, index) in managerPendingFiles.links"
                        :key="index"
                        class="px-4 py-2 rounded-md"
                        :href="link.url || ''"
                        :class="{
                            'bg-indigo-500 dark:bg-indigo-800 text-gray-300':
                                link.active,
                        }"
                        v-html="link.label"
                    />
                </div>
            </div>
        </main>
    </QuasarLayout>
</template>

<script setup>
import QuasarLayout from "@/Layouts/QuasarLayout.vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    managerPendingFiles: Object,
});
</script>
