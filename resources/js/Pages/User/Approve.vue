<template>
    <QuasarLayout>
        <p>Approve Roles</p>
        {{ pendingRoles }}
        <main class="container w-full p-4 mx-auto">
            <div
                class="p-4 border border-gray-200 rounded-md shadow-sm dark:border-gray-800 dark:text-gray-300"
            >
                <div
                    class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3"
                >
                    <div
                        v-for="user in pendingRoles"
                        :key="user.id"
                    >
                        <div class="p-4 mb-4 border border-gray-300 rounded-md">
                            <div @click="openPrompt(user)">
                                <div class="flex flex-col items-start gap-2">
                                    <span class="font-bold">Name: {{
                                        user.users.name
                                    }}</span>
                                    <span>Email: {{ user.email }}</span>
                                    <span>Id: {{ user.company_id }}</span>
                                    <span>Address: {{ user.address }}</span>
                                    <span>Role Applied: {{ user.role_applied }}</span>
                                    <span class="font-bold">Role Status: {{
                                        user.role_status
                                    }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <q-dialog v-model="prompt" persistent>
            <q-card style="min-width: 350px">
                <q-card-section>
                <div class="text-h6">Role Verification</div>
                </q-card-section>

                <q-card-section class="q-pt-none">
                <q-select dense  v-model="form.status" :options="options" label="Options" autofocus  @keyup.enter="prompt = false" />
                <!-- <q-input dense v-model="address" autofocus @keyup.enter="prompt = false" /> -->
                </q-card-section>

                <q-card-actions align="right" class="text-primary">
                <q-btn flat label="Cancel" v-close-popup />
                <q-btn flat label="Confirm" v-close-popup  @click="onSubmit()"/>
                </q-card-actions>
            </q-card>
            </q-dialog>
    </QuasarLayout>
</template>

<script setup>
import QuasarLayout from "@/Layouts/QuasarLayout.vue";
import {ref} from 'vue';
import { router, usePage, Link, useForm } from "@inertiajs/vue3";
import { useQuasar } from "quasar";

const $q = useQuasar();
const props = defineProps({
    'pendingRoles' : Object
})
const options = ['Approve', 'Reject'];

const prompt = ref(false);
const selectedUser = ref(null);
const form = useForm({
    status : "",
})
const openPrompt = (user) => {
    console.log(user.id);
    selectedUser.value = user.id;
    console.log(selectedUser.value);
    prompt.value = true;
};
const onSubmit = () => {
    console.log(selectedUser.value);
    if (form.status !== "") {
        form.put(route("users.update", selectedUser.value));
        $q.notify({
            message: "Role succesfully Applied",
            color: "purple",
            position: "bottom",
            actions: [{ label: "Dismiss", color: "white" }],
        });
    } else if (form.status === "") {
        $q.notify({
            message: "Please select a status for the verification",
            color: "red",
            position: "bottom",
            actions: [{ label: "Dismiss", color: "white" }],
        });
    }
};
</script>