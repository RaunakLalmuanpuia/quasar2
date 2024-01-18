<template>
    <QuasarLayout>
        <p>Edit Gallery</p>

        <div class="q-pa-md" style="max-width: 400px">

            <q-form
            @submit="onSubmit"
            @reset="onReset"
            class="q-gutter-md"
            >
            <q-input
                filled
                v-model="form.title"
                label="Title"
                hint="Name and surname"
                lazy-rules
                :rules="[ val => val && val.length > 0 || 'Please type something']"
            />

            <q-input
                filled
                v-model="form.about"
                label="About"
                lazy-rules
                :rules="[ val => val && val.length > 0 || 'Please type something']"
            />
            <q-input
                filled
                v-model="form.descripton"
                label="Description"
                lazy-rules
                :rules="[ val => val && val.length > 0 || 'Please type something']"
            />
            <q-input
                filled
                v-model="form.video_id"
                label="Video Id"
                lazy-rules
                :rules="[ val => val && val.length > 0 || 'Please type something']"
            />
            <q-btn label="Video" color="primary" @click="card5 = true" />
            <div class="q-pa-md q-gutter-sm">                
                <q-dialog v-model="card5">
                <q-card class="my-card" >
                    <q-video  
                        :src="getYouTubeEmbedUrl(props.gallery.video_id)"
                                />
                <q-card-actions align="right">
                <q-btn v-close-popup flat color="primary" label="Close" />
                </q-card-actions>
                </q-card>
                </q-dialog>
            </div>
            
            <q-file
                label="Image Logo"
                outlined
                v-model="form.image_logo">
                <template v-slot:prepend>
                    <q-icon name="attach_file" />
                </template>
            </q-file>
            
            <q-btn label="Image Logo" color="primary" @click="card1 = true" />
            <div class="q-pa-md q-gutter-sm">                
                <q-dialog v-model="card1">
                <q-card class="my-card">
                <q-img :ratio="16/9" fit="cover" height="500px" width="1000px"
                    :src="'/storage/' + props.gallery.image_logo"
                        />
                <q-card-actions align="right">
                <q-btn v-close-popup flat color="primary" label="Close" />
                </q-card-actions>
                </q-card>
                </q-dialog>
            </div>
            
            <q-file
                label="Image 1"
                outlined
                v-model="form.image1">
                <template v-slot:prepend>
                    <q-icon name="attach_file" />
                </template>
            </q-file>
            <q-btn label="Image 1" color="primary" @click="card2 = true" />
            <div class="q-pa-md q-gutter-sm">                
                <q-dialog v-model="card2">
                <q-card class="my-card">
                <q-img fit="cover" height="500px" width="500px"
                    :src="'/storage/' + props.gallery.image1"
                        />
                <q-card-actions align="right">
                <q-btn v-close-popup flat color="primary" label="Close" />
                
                </q-card-actions>
                </q-card>
                </q-dialog>
            </div>
            <q-file
                label="Image 2"
                outlined
                v-model="form.image2">
                <template v-slot:prepend>
                    <q-icon name="attach_file" />
                </template>
            </q-file>
            <q-btn label="Image 2" color="primary" @click="card3 = true" />
            <div class="q-pa-md q-gutter-sm">                
                <q-dialog v-model="card3">
                <q-card class="my-card">
                <q-img height="1000px" width="1000px"
                    :src="'/storage/' + props.gallery.image2"
                        />
                <q-card-actions align="right">
                <q-btn v-close-popup flat color="primary" label="Close" />
                
                </q-card-actions>
                </q-card>
                </q-dialog>
            </div>
            <q-file
                label="Image 3"
                outlined
                v-model="form.image3">
                <template v-slot:prepend>
                    <q-icon name="attach_file" />
                </template>
            </q-file>
            <q-btn label="Image 3" color="primary" @click="card4 = true" />
                <div class="q-pa-md q-gutter-sm">                
                    <q-dialog v-model="card4">
                    <q-card class="my-card">
                    <q-img height="500px" width="500px"
                        :src="'/storage/' + props.gallery.image3"
                            />
                    <q-card-actions align="right">
                    <q-btn v-close-popup flat color="primary" label="Close" />
                    </q-card-actions>
                    </q-card>
                    </q-dialog>
                </div>
            <q-toggle v-model="accept" label="Confirm Edit" />

            <div>
                <q-btn label="Submit" type="submit" color="primary"/>
                <q-btn label="Reset" type="reset" color="primary" flat class="q-ml-sm" />
            </div>
            </q-form>

        </div>
    </QuasarLayout>
    
</template>
<script setup>
import QuasarLayout from "@/Layouts/QuasarLayout.vue";
import { useForm } from "@inertiajs/vue3";
import { useQuasar } from 'quasar'
import { ref } from "vue";

const $q = useQuasar()

const accept = ref(false)
const card1 = ref(false)
const card2 = ref(false)
const card3 = ref(false)
const card4 = ref(false)
const card5 = ref(false)

const getYouTubeEmbedUrl = (videoId) =>
    `https://www.youtube.com/embed/${videoId}?rel=0`;

const props = defineProps({
    gallery : Object,
})

const form = useForm({
    title: props.gallery.title,
    about: props.gallery.about,
    descripton: props.gallery.descripton,
    video_id : props.gallery.video_id,
    image_logo: "",
    image1 : "",
    image2 : "",
    image3 : "",
})
const onSubmit = () => {
    if (accept.value !== true) {
    $q.notify({
      color: 'red-5',
      textColor: 'white',
      icon: 'warning',
      message: 'You need to confirm first'
    })
  }
  else {
    form.post(route("update_gallery", props.gallery.id));
    $q.notify({
      color: 'green-4',
      textColor: 'white',
      icon: 'cloud_done',
      message: 'Submitted'
    })
  }
};

const onReset = () => {
    form.title = "";
    form.about = "";
    form.descripton = "";
    form.image_logo = "";
    form.image1 = "";
    form.image2 = "";
    form.image3 = "";
    form.video_id = "";
};

</script>

