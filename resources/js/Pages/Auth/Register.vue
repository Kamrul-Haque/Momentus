<script setup>
import Guest from '@/Layouts/Guest.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextField from '@/Components/TextField.vue';
import {Head, Link} from "@inertiajs/vue3";
import {useForm} from 'laravel-precognition-vue-inertia';

const form = useForm("post", route('register'), {
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.submit({
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Guest>
        <Head title="Register"/>

        <form @submit.prevent="submit">
            <TextField v-model="form.name"
                       :error="form.errors.name"
                       @change="form.validate('name')"
                       autofocus
                       required
                       label="Name"/>

            <TextField type="email"
                       label="Email"
                       v-model="form.email"
                       :error="form.errors.email"
                       @change="form.validate('email')"
                       required/>

            <TextField type="password"
                       label="Password"
                       v-model="form.password"
                       :error="form.errors.password"
                       @change="form.validate('password')"
                       required/>

            <TextField type="password"
                       label="Confirm Password"
                       v-model="form.password_confirmation"
                       :error="form.errors.password_confirmation"
                       @change="form.validate('password_confirmation')"
                       required/>

            <div class="flex items-center justify-end mt-4">
                <Link :href="route('login')"
                      class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Already registered?
                </Link>

                <PrimaryButton class="ml-4"
                               :class="{ 'opacity-25': form.processing }"
                               :disabled="form.processing">
                    Register
                </PrimaryButton>
            </div>
        </form>
    </Guest>
</template>
