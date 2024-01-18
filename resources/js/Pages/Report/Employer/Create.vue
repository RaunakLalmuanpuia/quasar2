<template>
    <QuasarLayout>
        <div class="py-2 bg-white sm:py-10">
            <div class="px-6 mx-auto max-w-7xl lg:px-8">
                <div class="max-w-2xl mx-auto lg:mx-0">
                    <h2
                        class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl"
                    >
                        Pending Files
                    </h2>
                    <p class="mt-2 text-lg leading-8 text-gray-600">
                        Learn how to grow your business with our expert advice.
                    </p>
                </div>
                <div
                    class="grid max-w-2xl grid-cols-1 pt-5 mx-auto mt-5 border-t border-gray-200 gap-x-8 gap-y-16 sm:mt-6 sm:pt-16 lg:mx-0 lg:max-w-none lg:grid-cols-3"
                >
                    <div
                        v-for="report in employerPendingFiles"
                        :key="report.id"
                        class="flex flex-col items-start justify-between max-w-xl"
                    >
                        <div class="flex items-center text-xs gap-x-4">
                            <time
                                :datetime="report.created_at"
                                class="text-gray-500"
                            >
                                {{ report.created_at }}
                            </time>
                            <!-- <p>
                                {{ report }}
                                {{
                                    report.employer_id.value ===
                                    $page.props.user.id
                                }}
                            </p> -->

                            <!-- <button
                                @click="openModal(report)"
                                class="relative z-10 rounded-full bg-indigo-200 px-3 py-1.5 font-medium text-gray-600 hover:bg-gray-100"
                            >
                                Verify
                            </button> -->
                            <!-- <q-btn
                                label="Card"
                                color="primary"
                                @click="selectedReport(report)"
                                class="rounded-full bg-indigo-200 px-3 py-1.5 font-medium text-gray-600 hover:bg-gray-100"
                            /> -->
                            <q-btn
                                unelevated
                                rounded
                                label="Verify"
                                color="primary"
                                @click="openModal(report)"
                            />
                        </div>

                        <div class="relative group">
                            <h3
                                class="mt-3 text-lg font-semibold leading-6 text-gray-900 group-hover:text-gray-600"
                            >
                                <a
                                    :href="
                                        route('report.employee.show', report.id)
                                    "
                                    target="_blank"
                                >
                                    <span class="absolute inset-0" />
                                    Filename: {{ report.name }}
                                </a>
                            </h3>
                        </div>
                        <div class="relative flex items-center mt-8 gap-x-4">
                            <div class="text-sm leading-6">
                                <p class="font-semibold text-gray-900">
                                    <span class="absolute inset-0" />
                                    Employee Name:
                                    {{ report.employee_name }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                        >Filename:
                        <Link
                            :href="
                                route('report.employee.show', selectedReport.id)
                            "
                            target="_blank"
                            >{{ selectedReport.name }}
                        </Link>
                    </label>
                </q-card-section>
                <q-separator />
                <q-card-section>
                    <!-- change to dropdown -->
                    <q-select
                        rounded
                        outlined
                        v-model="form.status"
                        :options="options"
                        label="Employer Status"
                    />
                </q-card-section>
                <q-separator />
                <q-card-section>
                    <q-select
                        rounded
                        outlined
                        v-model="form.manager"
                        :options="managerOptions"
                        label="Manager"
                    />
                </q-card-section>
                <q-separator />
                <q-card-section>
                    <q-file
                        rounded
                        outlined
                        bottom-slots
                        v-model="form.filepath"
                        label="Employer File"
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
                    <label
                        for="
                  employer_feedback"
                    >
                        Employer Feedback</label
                    >
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
import { usePage, Link, useForm, Head } from "@inertiajs/vue3";
import { useQuasar } from "quasar";

const $q = useQuasar();

const options = ["Accepted", "Rejected"];

const card = ref(false);

const page = usePage();

const props = defineProps({
    employerPendingFiles: Object,
    manager: Object,
});
const selectedReport = ref(null);

const form = useForm({
    feedback: "",
    status: "",
    filepath: null,
    manager: "",
    selectedReport: "",
});

const openModal = (report) => {
    selectedReport.value = report;
    card.value = true;
    form.selectedReport = selectedReport.value.id;
};

// Compute options based on Manager properties
const managerOptions = Object.keys(props.manager).map((key) => ({
    label: props.manager[key].name,
    value: props.manager[key].id,
}));

const onSubmit = () => {
    // console.log(form.status);
    if (form.status !== "") {
        form.selectedReport = selectedReport.value.id;
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
