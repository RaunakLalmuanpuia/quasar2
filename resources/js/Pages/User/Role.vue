<template>
    <QuasarLayout>
        <!-- {{props.roles}} -->
        <h4>Roles and permission</h4>
        <div class="flex mb-4">
            <!-- create a dialog -->
            <q-btn label="Create" color="primary" @click="creatPrompt()" />
               
        </div>
<!-- Tabel -->
<div class="overflow-x-auto">
    <table class="w-full text-left bg-white border border-gray-300 divide-y shadow-sm table-auto rounded-xl">
      <thead>
        <tr class="bg-gray-500/5">
          <th class="px-4">ID</th>
          <th>Name</th>
          <th>Permission</th>
          <th>Created at</th>
          <th></th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="(role, index) in roles" :key="role.id">
          <td class="px-4 py-2">{{index + 1}}</td>
          <td>{{ role.name }}</td>
          <td>
            <span
              v-for="permission in role.permissions"
              :key="permission.id"
              class="px-2 py-1 text-xs text-blue-700 bg-blue-300 rounded-xl"
            >
              {{ permission.name }}
            </span>
          </td>
          <td>{{ role.created_at }}</td>
          <td class="px-4 divide-x-2">
            <q-btn color="primary" class="mr-4" label="Edit"  @click="editPromptModel(role.id)"/>
            
            <q-btn label="Delete" color="primary" @click="destroy(role.id)" />
        
            <!-- <q-btn flat label="delete"  @click="Delete(role.id)"/> -->
          </td>
         
        </tr>
        <tr v-if="roles.length === 0">
          <td class="p-4" colspan="4">{{ __('PermissionsUI::permissions.global.no_records') }}</td>
        </tr>
      </tbody>
    </table>
  </div>
        <!-- Create Dialog -->
        <q-dialog v-model="prompt" persistent>
            <q-card style="min-width: 350px">
                <q-card-section>
                <div class="text-h6">Create Role</div>
                </q-card-section>
                <q-input v-model="form.name" label="Standard" />
                <q-card-actions align="right" class="text-primary">
                <q-btn flat label="Cancel" v-close-popup />
                <q-btn flat label="Confirm" v-close-popup  @click="onSubmit()"/>
                </q-card-actions>
            </q-card>
            </q-dialog>
            
            <!-- edit dialog -->
            <q-dialog v-model="editPrompt" persistent>
            <q-card style="min-width: 350px">
                <q-card-section>
                <div class="text-h6">Edit Role</div>
                </q-card-section>
                <q-input v-model="updateForm.name" label="Standard" />
                <q-card-actions align="right" class="text-primary">
                <q-btn flat label="Cancel" v-close-popup />
                <q-btn flat label="Confirm" v-close-popup  @click="onUpdate()"/>
                </q-card-actions>
            </q-card>
            </q-dialog>
    </QuasarLayout>
    
</template>

<script setup>

import QuasarLayout from "@/Layouts/QuasarLayout.vue";
import {ref} from 'vue';
import {useForm, router, Link} from "@inertiajs/vue3";
import { useQuasar } from "quasar";


const $q = useQuasar();
const edit = ref("");
const prompt = ref(false);
const editPrompt = ref(false);
const props = defineProps({
    roles: Object,
});

const creatPrompt = () => {
    prompt.value = true;
};
const editPromptModel = (role) => {
    edit.value = role;
    editPrompt.value = true;
};

const form = useForm({
    name : "",
})
const updateForm = useForm({
    name : "",
})


const onSubmit = () => {
    
    if (form.role !== "") {
        form.post(route("storeRole"));
        $q.notify({
            message: "Role succesfully Added",
            color: "purple",
            position: "bottom",
            actions: [{ label: "Dismiss", color: "white" }],
        });
    } else if (form.status === "") {
        $q.notify({
            message: "Please Enter a Role",
            color: "red",
            position: "bottom",
            actions: [{ label: "Dismiss", color: "white" }],
        });
    }
};
const onUpdate = () => {
    if (updateForm.name !== "") {
        console.log(edit.value);
        updateForm.post(route("updateRole", edit.value));
        $q.notify({
            message: "Role succesfully Updated",
            color: "purple",
            position: "bottom",
            actions: [{ label: "Dismiss", color: "white" }],
        });
    } else if (updateForm.name === "") {
        $q.notify({
            message: "Please Enter a Role",
            color: "red",
            position: "bottom",
            actions: [{ label: "Dismiss", color: "white" }],
        });
    }
};
// const Delete = (role) =>{
//      form.delete(route('destroyRole', role));
//     router.delete(route("destroyRole", role));
//     console.log(role);
// }
function destroy(id) {
    if (confirm("Delete Role")) {
        form.delete(route("destroyRole", id));
    }
}
</script>