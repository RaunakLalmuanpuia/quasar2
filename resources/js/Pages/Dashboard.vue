<script setup>
import { ref } from "vue";
import QuasarLayout from "@/Layouts/QuasarLayout.vue";
import Welcome from "@/Components/Welcome.vue";
import { Head } from "@inertiajs/vue3";



</script>

<template>
    <QuasarLayout>
        <Head title="Dashboard" />
        <!-- <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template> -->

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <!-- Model Code -->
                    <!-- <div class="mb-5 bg-slate-600">
                        <SecondaryButton @click="openModal">
                            Open Modal
                        </SecondaryButton>

                        <DialogModal :show="modal" @close="closeModal">
                            <template #title>New Modal </template>
                            <template #content> Hello from Model </template>
                            <template #footer>
                                <SecondaryButton @click="closeModal">
                                    Cancel
                                </SecondaryButton>
                            </template>
                        </DialogModal>
                    </div> -->

                    

                   
                    
                    <Welcome />
                </div>
            </div>
        </div>
    </QuasarLayout>
</template>