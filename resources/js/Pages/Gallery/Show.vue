<template>
    <QuasarLayout>
        <p>Gallery Show</p>
        <!-- {{ props.gallery.id }}
        {{ props.gallery }} -->
        <div v-if="$page.props.user.roles.includes('admin')">
            <Link :href="route('gallery.edit', gallery.id)">
                <q-btn label="Edit Media" color="primary" />
            </Link>
        </div>

        <div class="text-h6">Title: {{ gallery.title }}</div>
        <div class="text-h6">Description: {{ gallery.descripton }}</div>
        <div class="text-h6">About: {{ gallery.about }}</div>

        <div class="q-pa-md">
            <h4>Images</h4>
            <q-carousel swipeable animated v-model="slide" thumbnails infinite>
                <q-carousel-slide
                    :name="1"
                    :img-src="'/storage/' + gallery.image1"
                />
                <q-carousel-slide
                    :name="2"
                    :img-src="'/storage/' + gallery.image2"
                />
                <q-carousel-slide
                    :name="3"
                    :img-src="'/storage/' + gallery.image3"
                />
            </q-carousel>
        </div>

        <div class="q-pa-md">
            <h4>Video</h4>
            <q-video
                :ratio="16 / 9"
                :src="getYouTubeEmbedUrl(gallery.video_id)"
            />
            <!-- <YouTube :src="gallery.video_id" ref="youtube" /> -->
        </div>
    </QuasarLayout>
</template>

<script setup>
import { ref } from "vue";
import QuasarLayout from "@/Layouts/QuasarLayout.vue";
import YouTube from "vue3-youtube";
import { Link } from "@inertiajs/vue3";
const slide = ref(1);

const props = defineProps({
    gallery: Object,
});
const getYouTubeEmbedUrl = (videoId) =>
    `https://www.youtube.com/embed/${videoId}?rel=0`;
</script>
