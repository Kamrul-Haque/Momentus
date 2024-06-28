<script setup>
import Guest from '@/Layouts/Guest.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextField from '@/Components/TextField.vue';
import {Head} from '@inertiajs/vue3';
import {useForm} from 'laravel-precognition-vue-inertia'

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm('post', route('password.store'), {
    token: props.token,
    email: props.email,
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
        <Head title="Reset Password"/>

        <form @submit.prevent="submit">
            <TextField type="email"
                       label="Email"
                       v-model="form.email"
                       :error="form.errors.email"
                       required/>

            <TextField type="password"
                       label="Password"
                       v-model="form.password"
                       :error="form.errors.password"
                       required/>

            <TextField type="password"
                       label="Confirm Password"
                       v-model="form.password_confirmation"
                       :error="form.errors.password_confirmation"
                       required/>

            <div class="flex items-center justify-end mt-4">
                <PrimaryButton :class="{ 'opacity-25': form.processing }"
                               :disabled="form.processing">
                    Reset Password
                </PrimaryButton>
            </div>
        </form>
    </Guest>
</template>
