<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import Guest from '@/Layouts/Guest.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextField from '@/Components/TextField.vue';
import {Head, Link} from '@inertiajs/vue3';
import {useForm} from 'laravel-precognition-vue-inertia';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm("post", route('login'), {
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.submit({
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Guest>
        <Head title="Log in"/>

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
                       autofocus
                       required/>

            <TextField type="password"
                       label="Password"
                       v-model="form.password"
                       :error="form.errors.password"
                       @change="form.validate('password')"
                       required/>

            <div class="block mt-4">
                <label class="flex items-center">
                    <Checkbox name="remember"
                              v-model:checked="form.remember"/>
                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link v-if="canResetPassword"
                      :href="route('password.request')"
                      class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Forgot your password?
                </Link>

                <PrimaryButton class="ml-4"
                               :class="{ 'opacity-25': form.processing }"
                               :disabled="form.processing">
                    Log in
                </PrimaryButton>
            </div>
        </form>
    </Guest>
</template>
