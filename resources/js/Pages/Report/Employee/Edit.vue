<template>
    <QuasarLayout>
        <p>Employee edit</p>
        <q-form @submit="onSubmit" @reset="onReset" class="q-gutter-md">
            <q-input
                filled
                v-model="form.file_name"
                label="File Name *"
                hint="Name and surname"
                lazy-rules
                :rules="[
                    (val) => (val && val.length > 0) || 'Please type something',
                ]"
            />
            <q-select
                rounded
                outlined
                v-model="form.employer_id"
                :options="employerOptions"
                label="Employer Name"
                
                
            />
            <p>Current Employer: {{ props.report.employer.name }}</p>
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

            <q-toggle v-model="accept" label="Confirm Edit" />

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
        <Link
            :href="route('report.employee.show', props.report.id)"
            class="text-indigo-600"
        >
            <p>Employee file</p>
        </Link>
        <p>Employer Status: {{ props.report.employer_status }}</p>

        <p>Employer Feedback : {{ props.report.employer_feedback }}</p>

        <Link
            :href="route('report.employer.show', props.report.id)"
            class="text-indigo-600"
        >
            <p>Employer file</p>
        </Link>
    </QuasarLayout>
</template>
<script setup>
import QuasarLayout from "@/Layouts/QuasarLayout.vue";
import { useForm, Link } from "@inertiajs/vue3";
import { useQuasar } from "quasar";
import { ref } from "vue";

const $q = useQuasar();

const accept = ref(false);

const props = defineProps({
    report: Object,
    employer: Object,
});

const form = useForm({
    id: props.report.id,
    employer_name: props.report.employer.name,
    file_name: props.report.name,
    filepath: [],
    employer_id: "",
});

const onSubmit = () => {
    if (accept.value !== true) {
        $q.notify({
            color: "red-5",
            textColor: "white",
            icon: "warning",
            message: "Please Confirm",
        });
    } else {
        // form.put(route("report.update", props.report.id));
        form.post(route("update_employee", props.report.id));
        
        $q.notify({
            color: "green-4",
            textColor: "white",
            icon: "cloud_done",
            message: "Submitted",
        });
    }
    // form.put(route("report.update", props.report.id));
};


const onReset = () => {
    form.file_name = props.report.name;
    form.filepath = [];
    form.employer_id = "";
};

// Compute options based on employer properties
const employerOptions = Object.keys(props.employer).map((key) => ({
    label: props.employer[key].name,
    value: props.employer[key].id,
}));



</script>
