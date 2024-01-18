<template>
    <QuasarLayout>
        <h5>Report</h5>
        <p>Report Name: {{ props.report.name }}</p>
        <p>Employee Name: {{ props.report.employee.name }}</p>
        <p>Employer Name: {{ props.report.employer.name }}</p>
        <!-- <p>Employer Feedback: {{ props.report.employer_feedback }}</p> -->
        <q-btn
            unelevated
            rounded
            label="Employer Feedbaack"
            color="primary"
            @click="alert = true"
        />
        <!-- <p>Movement: {{ props.report.movement }}</p> -->

        <!-- <a :href="route('report.employee.show', props.report.id)" target="_blank">
        <div>
            <p>Employee File</p>
        </div>
    </a> -->
        <q-dialog v-model="alert">
            <q-card>
                <q-card-section>
                    <div class="text-h6">Employer Feedback</div>
                </q-card-section>

                <q-card-section class="q-pt-none">
                    <section v-html="props.report.employer_feedback"></section>
                </q-card-section>

                <q-card-actions align="right">
                    <q-btn flat label="OK" color="primary" v-close-popup />
                </q-card-actions>
            </q-card>
        </q-dialog>

        <Link
            :href="route('report.employee.show', props.report.id)"
            target="_blank"
        >
            <q-btn unelevated rounded label="Employee File" color="primary" />
        </Link>

        <a
            :href="route('report.employer.show', props.report.id)"
            target="_blank"
        >
            <q-btn unelevated rounded label="Employer File" color="primary" />
        </a>
        <br class="mt-4" />
        <q-btn
            unelevated
            rounded
            label="Verify"
            color="primary"
            class="mt-5"
            @click="openModal(report)"
        />

        <q-dialog v-model="card">
            <q-card>
                <q-card-section>
                    <div class="text-h6">Verify Report</div>
                    <!-- <p>{{ selectedReport }}</p> -->
                </q-card-section>

                <q-separator />

                <q-card-section style="max-height: 50vh" class="scroll">
                    <label
                        for="name"
                        class="text-sm font-bold leading-tight tracking-normal text-gray-800"
                        >Filename: {{ report.name }}
                    </label>
                </q-card-section>
                <q-separator />
                <q-card-section style="max-height: 50vh" class="scroll">
                    <a
                        :href="route('report.employer.show', report.id)"
                        target="_blank"
                        as="button"
                    >
                        <q-btn flat label="Employer File" color="primary" />
                        <!-- <label
                            for="name"
                            class="text-sm font-bold leading-tight tracking-normal text-gray-800"
                            >Employer File</label
                        > -->
                    </a>
                    <a
                        :href="route('report.employee.show', report.id)"
                        target="_blank"
                    >
                        <q-btn flat label="Employee File" color="primary" />
                        <!-- <label
                            for="name"
                            class="px-10 text-sm font-bold leading-tight tracking-normal text-gray-800"
                            >Employee File</label
                        > -->
                    </a>
                </q-card-section>
                <q-separator />
                <q-card-section>
                    <!-- change to dropdown -->
                    <q-select
                        rounded
                        outlined
                        v-model="form.status"
                        :options="options"
                        label="Manager Status"
                    />
                </q-card-section>
                <q-separator />

                <q-separator />
                <q-card-section>
                    <q-file
                        rounded
                        outlined
                        bottom-slots
                        v-model="form.filepath"
                        label="Manager File"
                        counter
                    >
                        <template v-slot:before>
                            <q-icon name="attachment" />
                        </template>

                        <template v-slot:append>
                            <q-icon
                                v-if="form.filepath !== null"
                                name="close"
                                @click.stop.prevent="form.filepath = null"
                                class="cursor-pointer"
                            />
                            <q-icon name="search" @click.stop.prevent />
                        </template>
                    </q-file>
                </q-card-section>

                <q-card-section>
                    <label> Manager Feedback</label>
                    <q-editor
                        v-model="form.feedback"
                        :dense="$q.screen.lt.md"
                        :toolbar="[
                            [
                                {
                                    label: $q.lang.editor.align,
                                    icon: $q.iconSet.editor.align,
                                    fixedLabel: true,
                                    options: [
                                        'left',
                                        'center',
                                        'right',
                                        'justify',
                                    ],
                                },
                            ],
                            [
                                'bold',
                                'italic',
                                'strike',
                                'underline',
                                'subscript',
                                'superscript',
                            ],
                            ['token', 'hr', 'link', 'custom_btn'],
                            ['print', 'fullscreen'],
                            [
                                {
                                    label: $q.lang.editor.formatting,
                                    icon: $q.iconSet.editor.formatting,
                                    list: 'no-icons',
                                    options: [
                                        'p',
                                        'h1',
                                        'h2',
                                        'h3',
                                        'h4',
                                        'h5',
                                        'h6',
                                        'code',
                                    ],
                                },
                                {
                                    label: $q.lang.editor.fontSize,
                                    icon: $q.iconSet.editor.fontSize,
                                    fixedLabel: true,
                                    fixedIcon: true,
                                    list: 'no-icons',
                                    options: [
                                        'size-1',
                                        'size-2',
                                        'size-3',
                                        'size-4',
                                        'size-5',
                                        'size-6',
                                        'size-7',
                                    ],
                                },
                                {
                                    label: $q.lang.editor.defaultFont,
                                    icon: $q.iconSet.editor.font,
                                    fixedIcon: true,
                                    list: 'no-icons',
                                    options: [
                                        'default_font',
                                        'arial',
                                        'arial_black',
                                        'comic_sans',
                                        'courier_new',
                                        'impact',
                                        'lucida_grande',
                                        'times_new_roman',
                                        'verdana',
                                    ],
                                },
                                'removeFormat',
                            ],
                            [
                                'quote',
                                'unordered',
                                'ordered',
                                'outdent',
                                'indent',
                            ],

                            ['undo', 'redo'],
                            ['viewsource'],
                        ]"
                        :fonts="{
                            arial: 'Arial',
                            arial_black: 'Arial Black',
                            comic_sans: 'Comic Sans MS',
                            courier_new: 'Courier New',
                            impact: 'Impact',
                            lucida_grande: 'Lucida Grande',
                            times_new_roman: 'Times New Roman',
                            verdana: 'Verdana',
                        }"
                    />
                </q-card-section>

                <q-separator />

                <q-card-actions align="right">
                    <q-btn flat label="Cancel" color="primary" v-close-popup />
                    <!-- add @click function to submit the form below -->
                    <q-btn
                        flat
                        label="Submit"
                        color="primary"
                        v-close-popup
                        @click="onSubmit"
                    />
                </q-card-actions>
            </q-card>
        </q-dialog>
    </QuasarLayout>
</template>
<script setup>
import { ref } from "vue";
import QuasarLayout from "@/Layouts/QuasarLayout.vue";
import { Link, usePage, useForm } from "@inertiajs/vue3";
import { useQuasar } from "quasar";

const page = usePage();
const $q = useQuasar();

const card = ref(false);
const props = defineProps({
    report: Object,
});
const options = ["Accepted", "Rejected"];
const alert = ref(false);
const form = useForm({
    id: "",
    feedback: "",
    status: "",
    filepath: null,
});
const openModal = () => {
    card.value = true;
};
const onSubmit = () => {
    // console.log(form.status);
    if (form.status !== "") {
        form.id = page.props.report.id;
        form.post(route("report.store"));
    } else if (form.status === "") {
        $q.notify({
            message: "Please select a status for the file",
            color: "purple",
            position: "top",
            actions: [{ label: "Dismiss", color: "white" }],
        });
    }
};
</script>
