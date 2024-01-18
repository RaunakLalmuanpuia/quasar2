<template>
     <Head title="Upload Report"/>
    <QuasarLayout>
                    
        <div class="q-pa-md" style="max-width: 400px">
            
            <q-form @submit="onSubmit" @reset="onReset" class="q-gutter-md">
                <q-input
                    filled
                    v-model="form.filename"
                    label="File Name *"
                    hint="Name of file"
                    lazy-rules
                    :rules="[
                        (val) =>
                            (val && val.length > 0) || 'Please type something',
                    ]"
                />
                <q-select
                    rounded
                    outlined
                    v-model="form.employer"
                    :options="employerOptions"
                    label="Employer"
                />
                <q-file rounded
                 outlined
                  bottom-slots
                   v-model="form.filepath"
                    label="File" 
                    counter max-files="1">
                    <template v-slot:before>
                    <q-icon name="attachment" />
                    </template>

                    <template v-slot:append>
                    <q-icon v-if="form.filepath !== null" name="close" @click.stop.prevent="form.filepath = null" class="cursor-pointer" />
                    <q-icon name="search" @click.stop.prevent />
                    </template>
                    
                </q-file>
                <div>
                    <q-btn label="Submit" type="submit" color="primary" />
                    <q-btn
                        label="Reset"
                        type="reset"
                        color="primary"
                        flat
                        class="q-ml-sm"
                    />
                </div>
            </q-form>
        </div>
    </QuasarLayout>
</template>
<script setup>
import QuasarLayout from "@/Layouts/QuasarLayout.vue";


import { useForm, Head } from "@inertiajs/vue3";

const props = defineProps({
    employer: Object,
});

const form = useForm({
    filename: "",
    filepath: [],
    employer: "",
});

// Compute options based on employer properties
const employerOptions = Object.keys(props.employer).map((key) => ({
  label: props.employer[key].name,
  value: props.employer[key].id,
}));


const onSubmit = () => {
    form.post(route("report.store"));
};

const onReset = () => {
    form.filename = "";
    form.filepath = [];
    form.employer = "";
};
</script>
