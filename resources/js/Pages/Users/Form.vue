<script setup>
import Admin from "@/Layouts/Admin.vue";
import {Head, usePage} from "@inertiajs/vue3";
import TextField from "@/Components/TextField.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextArea from "@/Components/TextArea.vue";
import {useForm} from 'laravel-precognition-vue-inertia';
import DropZone from "@/Components/DropZone.vue";
import Select from "@/Components/Select.vue";

const page = usePage();
const props = defineProps({
    user: {
        type: Object
    }
});
const roles = page.props.auth.roles;

const form = useForm('post', props.user ? route('users.update', props.user.id) : route('users.store'), {
    _method: props.user ? 'put' : 'post',
    name: props.user ? props.user.name : null,
    email: props.user ? props.user.email : null,
    phone: props.user ? props.user.phone : null,
    address: props.user ? props.user.address : null,
    role: props.user ? [props.user.role] : [],
    image: null,
});

function submit() {
    form.submit({preserveScroll: true})
}
</script>

<template>
    <Head title="Users"/>

    <Admin>
        <div class="card w-full md:w-3/4 mx-auto">
            <h1 class="card-header">{{ user ? 'Edit User' : 'Create User' }}</h1>
            <div class="card-body">
                <form @submit.prevent="submit()">
                    <TextField v-model="form.name"
                               label="Name"
                               @change="form.validate('name')"
                               :error="form.errors.name"
                               autofocus
                               required/>

                    <TextField v-model="form.email"
                               type="email"
                               label="Email"
                               @change="form.validate('email')"
                               :error="form.errors.email"
                               required/>

                    <TextField v-model="form.phone"
                               label="Phone"
                               @change="form.validate('phone')"
                               :error="form.errors.phone"/>

                    <TextArea v-model="form.address"
                              label="Address"
                              @change="form.validate('address')"
                              :error="form.errors.address"/>

                    <Select v-model="form.role"
                            :items="roles"
                            item-label="name"
                            item-value="value"
                            @change="form.validate('role')"
                            label="Roles"
                            clearable
                            required/>

                    <DropZone @files="form.image = $event"
                              label="Profile Image"
                              types="image/png"
                              :previous="[user.image]"
                              :error="form.errors.image"/>

                    <div class="text-right mt-8">
                        <PrimaryButton type="submit">Save</PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </Admin>
</template>
