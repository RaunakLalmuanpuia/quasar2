<template>
    <div class="container" width="500px">
        <div class="panel panel-primary" style="margin-top: 110px">
            <div class="panel-heading">
                <h3 class="text-center">
                    Payment gateway using Paytm Laravel 7
                </h3>
            </div>
            <div class="panel-body">
                <form @submit="onSubmit">
                    <div class="row">
                        <div class="col-md-12">
                            <strong>Name:</strong>
                            <input
                                type="text"
                                name="name"
                                placeholder="Name"
                                required
                                v-model="form.name"
                            />
                        </div>
                        <div class="col-md-12">
                            <strong>Mobile No:</strong>
                            <input
                                type="text"
                                name="mobile"
                                maxlength="10"
                                placeholder="Mobile No."
                                required
                                v-model="form.mobile"
                            />
                        </div>
                        <div class="col-md-12">
                            <strong>Email:</strong>
                            <input
                                type="email"
                                placeholder="Email"
                                name="email"
                                required
                                v-model="form.email"
                            />
                        </div>
                        <div class="col-md-12">
                            <br />
                            <div class="btn btn-info">Term Fee : 1500 Rs/-</div>
                        </div>
                        <div class="col-md-12">
                            <br />
                            <button type="submit" class="btn btn-success">
                                Paytm
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
const form = useForm({
    name: "",
    mobile: "",
    email: "",
});

const onSubmit = () => {
    form.post(route("make.payment"));
};
</script>
