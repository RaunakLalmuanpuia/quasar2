<template>
    <QuasarLayout>
        <h5>Apply for roles</h5>

        <!-- <h5>Roles:</h5>
        <div v-for="role in props.role" :key="role.id">
            {{ role.name }}
        </div> -->

        
        <br>
        <!-- <select v-model="selectedRole" id="selectRole">
            <option
                v-for="role in props.role"
                :key="role.id"
                :value="role.name"
            >
                {{ role.name }}
            </option>
        </select> -->
        <div class="q-pa-md" style="max-width: 300px">
            <div class="q-gutter-md">
                <label for="selectRole">Select a role:</label>
                <q-select
                v-model="selectedRole"
                    :options="props.role"
                    option-value="name"
                    option-label="name"
                    emit-value
                    map-options
                    id="selectRole"
                />
            </div>
        </div>
        

        <div v-if="selectedRole === 'employee'">
            <!-- Form for Role 1 -->
            <form @submit.prevent="submitForm">
                <!-- Form fields for Role 1 -->
                <div class="q-pa-md">
                    <div class="q-gutter-md" style="max-width: 300px">
                        <q-input filled v-model="form.email" label="Email"/>
                        <q-input filled v-model="form.id" label="Employee Id"/>
                        <q-input filled v-model="form.address" label="Address"/>
                    </div>
                </div>
                <!-- ... -->
                <div class="q-pa-md q-gutter-sm">
                    <q-btn unelevated rounded color="primary" type="submit" label="Apply for Employee" />
                </div>
                <!-- <button type="submit">Submit Role Employee</button> -->
            </form>
        </div>

        <div v-if="selectedRole === 'employer'">
            <!-- Form for Role 2 -->
            <form @submit.prevent="submitForm">
                <div class="q-pa-md">
                    <div class="q-gutter-md" style="max-width: 300px">
                        <q-input filled v-model="form.email" label="Email"/>
                        <q-input filled v-model="form.id" label="Employer Id"/>
                        <q-input filled v-model="form.address" label="Address"/>
                    </div>
                </div>
                <!-- ... -->
                <div class="q-pa-md q-gutter-sm">
                    <q-btn unelevated rounded color="primary" type="submit" label="Apply for Employer" />
                </div>
            </form>
        </div>
        <div v-if="selectedRole === 'manager'">
            <!-- Form for Role 2 -->
            <form @submit.prevent="submitForm">
                <div class="q-pa-md">
                    <div class="q-gutter-md" style="max-width: 300px">
                        <q-input filled v-model="form.email" label="Email"/>
                        <q-input filled v-model="form.id" label="Manager Id"/>
                        <q-input filled v-model="form.address" label="Address"/>
                    </div>
                </div>
                <!-- ... -->
                <div class="q-pa-md q-gutter-sm">
                    <q-btn unelevated rounded color="primary" type="submit" label="Apply for Manager" />
                </div>
                
            </form>
        </div>
    </QuasarLayout>
</template>
<script setup>
import QuasarLayout from "@/Layouts/QuasarLayout.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { ref } from "vue";

const page = usePage();

const props = defineProps({
    role: Object,
});
const form = useForm({
    email: "",
    address: "",
    id: "",
    post_name: "",
    user_id: "",

})
const selectedRole = ref("");

console.log(page.props.auth.user.id)
const submitForm = () => {
    form.post_name = selectedRole.value;
    form.user_id = page.props.auth.user.id;
    form.post(route('users.store'));
    console.log("Employee form submitted");
};
</script>

