<script setup>
import Guest from '@/Layouts/Guest.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextField from '@/Components/TextField.vue';
import {Head} from '@inertiajs/vue3';
import {useForm} from "laravel-precognition-vue-inertia";

defineProps({
    status: {
        type: String,
    },
});

const form = useForm('post', route('password.email'), {
    email: '',
});

const submit = () => {
    form.submit();
};
</script>

<template>
    <Guest>
        <Head title="Forgot Password"/>

        <div class="mb-4 text-sm text-gray-600">
            Forgot your password? No problem. Just let us know your email address and we will email you a password reset
            link that will allow you to choose a new one.
        </div>

        <div v-if="status"
             class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <TextField type="email"
                       label="Email"
                       v-model="form.email"
                       :error="form.errors.email"
                       @change="form.validate('email')"
                       required/>

            <div class="flex items-center justify-end mt-4">
                <PrimaryButton :class="{ 'opacity-25': form.processing }"
                               :disabled="form.processing">
                    Email Password Reset Link
                </PrimaryButton>
            </div>
        </form>
    </Guest>
</template>
