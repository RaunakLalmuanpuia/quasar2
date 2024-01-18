<template>
    <QuasarLayout>
        <Head title="Gallery" />
        <h5>Gallery</h5>
        <main class="container w-full p-4 mx-auto">
            <div
                class="p-4 border border-gray-200 rounded-md shadow-sm dark:border-gray-800 dark:text-gray-300"
            >
                <div
                    class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3"
                >
                    <div v-for="gallery in gallery.data" :key="gallery.id">
                        <!-- {{ gallery }} -->

                        <Link
                            v-if="gallery.id"
                            :href="route('gallery.show', gallery.id)"
                        >
                            <q-card class="my-card">
                                <q-card-section>
                                    <div class="text-h4">
                                        {{ gallery.title }}
                                    </div>
                                </q-card-section>
                                <q-img
                                    :src="'/storage/' + gallery.image_logo"
                                />
                                <q-card-section>
                                    <div class="text-h6">
                                        {{ gallery.descripton }}
                                    </div>
                                </q-card-section>

                                <!-- <q-video
                                    :src="getYouTubeEmbedUrl(gallery.video_id)"
                                /> -->
                                <!-- <YouTube
                                  :src="gallery.video_id"
                                  ref="youtube"
                              /> -->
                                <q-card-section class="q-pt-none">
                                    {{ gallery.about }}
                                </q-card-section>
                            </q-card>
                        </Link>
                    </div>
                </div>
            </div>

            <div class="flex justify-center mt-4">
                <div class="flex gap-1">
                    <Link
                        v-for="(link, index) in gallery.links"
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
import { Head, Link } from "@inertiajs/vue3";

const getYouTubeEmbedUrl = (videoId) =>
    `https://www.youtube.com/embed/${videoId}?rel=0`;

const props = defineProps({
    gallery: Object,
});
</script>