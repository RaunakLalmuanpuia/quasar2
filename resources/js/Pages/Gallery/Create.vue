<template>
    <QuasarLayout>
        <Head title="Media" />
        <h3>Upload Media for gallerys</h3>
        <div
            v-if="$page.props.flash.message"
            class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
            role="alert">
            <span class="font-medium">
                {{ $page.props.flash.message }}
            </span>
        </div>
        
        <div class="q-pa-md q-gutter-sm">
            <q-btn label="Add Media" color="primary" @click="prompt = true" />
            <q-dialog v-model="prompt" persistent>
                <q-card style="min-width: 350px">
                    <q-card-section>
                        <div class="text-h6">New Media</div>
                    </q-card-section>

                    <q-card-section class="q-pt-none">
                        <q-input
                            label="Title"
                            dense
                            v-model="form.title"
                            autofocus
                            @keyup.enter="prompt = false"
                        />
                    </q-card-section>

                    <q-card-section class="q-pt-none">
                        <q-input
                            label="About"
                            dense
                            v-model="form.about"
                            autofocus
                            @keyup.enter="prompt = false"
                        />
                    </q-card-section>
                    <q-card-section class="q-pt-none">
                        <q-input
                            label="Description"
                            dense
                            v-model="form.descripton"
                            autofocus
                            @keyup.enter="prompt = false"
                        />
                    </q-card-section>

                    <q-card-section class="q-pt-none">
                        <q-file
                            label="Image Logo"
                            outlined
                            v-model="form.image_logo"
                        >
                            <template v-slot:prepend>
                                <q-icon name="attach_file" />
                            </template>
                        </q-file>
                        <!-- <q-input label="Image" dense v-model="form.about" autofocus @keyup.enter="prompt = false" /> -->
                    </q-card-section>

                    <q-card-section class="q-pt-none">
                        <q-file label="Image" outlined v-model="form.image1">
                            <template v-slot:prepend>
                                <q-icon name="attach_file" />
                            </template>
                        </q-file>
                    </q-card-section>
                    <q-card-section class="q-pt-none">
                        <q-file label="Image" outlined v-model="form.image2">
                            <template v-slot:prepend>
                                <q-icon name="attach_file" />
                            </template>
                        </q-file>
                    </q-card-section>
                    <q-card-section class="q-pt-none">
                        <q-file label="Image" outlined v-model="form.image3">
                            <template v-slot:prepend>
                                <q-icon name="attach_file" />
                            </template>
                        </q-file>
                    </q-card-section>

                    <q-card-section class="q-pt-none">
                        <q-input
                            label="Video id"
                            dense
                            v-model="form.video_id"
                            autofocus
                            @keyup.enter="prompt = false"
                        />
                    </q-card-section>

                    <q-card-actions align="right" class="text-primary">
                        <q-btn
                            flat
                            label="Cancel"
                            v-close-popup
                            @click="resetform"
                        />
                        <q-btn
                            flat
                            label="Add Media"
                            v-close-popup
                            @click="onSubmit"
                        />
                    </q-card-actions>
                </q-card>
            </q-dialog>
        </div>

        <main class="container w-full p-4 mx-auto">
            <div
                class="p-4 border border-gray-200 rounded-md shadow-sm dark:border-gray-800 dark:text-gray-300"
            >
                <div
                    class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3"
                >
                    <div v-for="gallery in gallery.data" :key="gallery.id">
                        <!-- {{ gallery }} -->
                        <div style="display: flex;">
                            <q-btn 
                                round
                                color="primary"
                                icon="delete"
                                @click="destroy(gallery.id)"
                            />
                            <Link :href="route('gallery.edit', gallery.id)">
                                <q-btn
                                class="ml-5"
                                round
                                color="primary"
                                icon="edit"
                                
                            />
                            </Link>
                           
                        </div>
                        <Link
                            v-if="gallery.id"
                            :href="route('gallery.show', gallery.id)"
                        >
                            <q-card class="my-card">
                                <q-img
                                    :src="'/storage/' + gallery.image_logo"
                                />
                                <q-card-section>
                                    <div class="text-h4">
                                       Title: {{ gallery.title }}
                                    </div>
                                </q-card-section>
                               
                                <q-card-section>
                                    <div class="text-h6">
                                       Description: {{ gallery.descripton }}
                                    </div>
                                </q-card-section>
                               
                                <!-- <YouTube
                                    :src="gallery.video_id"
                                    ref="youtube"
                                /> -->
                                <q-card-section class="q-pt-none">
                                    About: {{ gallery.about }}
                                </q-card-section>
                                
                                <q-video
                                    :src="getYouTubeEmbedUrl(gallery.video_id)"
                                />
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
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import YouTube from "vue3-youtube";

// const counterLabelFn = ({ totalSize, filesNumber, maxFiles }) => {
//     return `${filesNumber} files of ${maxFiles} | ${totalSize}`;
// };
const getYouTubeEmbedUrl = (videoId) =>
    `https://www.youtube.com/embed/${videoId}?rel=0`;

const prompt = ref(false);
const form = useForm({
    title: "",
    about: "",
    descripton: "",
    image_logo: "",
    image1: "",
    image2: "",
    image3: "",
    video_id: "",
});
const props = defineProps({
    gallery: Object,
});

const onSubmit = () => {
    form.post(route("gallery.store"));
    form.title = "";
    form.about = "";
    form.descripton = "";
    form.image_logo = "";
    form.image1 = "";
    form.image2 = "";
    form.image3 = "";
    form.video_id = "";
};

const resetform = () => {
    form.title = "";
    form.about = "";
    form.descripton = "";
    form.image_logo = "";
    form.image1 = "";
    form.image2 = "";
    form.image3 = "";
    form.video_id = "";
};
function destroy(id) {
    if (confirm("Delete Media")) {
        form.delete(route("gallery.destroy", id));
    }
}
</script>