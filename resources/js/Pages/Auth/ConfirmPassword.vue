<script setup>
import Guest from '@/Layouts/Guest.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextField from '@/Components/TextField.vue';
import {Head} from '@inertiajs/vue3';
import {useForm} from 'laravel-precognition-vue-inertia';

const form = useForm('post', route('password.confirm'), {
    password: '',
});

const submit = () => {
    form.submit({
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <Guest>
        <Head title="Confirm Password"/>

        <div class="mb-4 text-sm text-gray-600">
            This is a secure area of the application. Please confirm your password before continuing.
        </div>

        <form @submit.prevent="submit">
            <TextField type="password"
                       v-model="form.password"
                       label="Password"
                       :error="form.errors.password"
                       @change="form.validate('password')"
                       required/>

            <div class="flex justify-end mt-4">
                <PrimaryButton class="ml-4"
                               :class="{ 'opacity-25': form.processing }"
                               :disabled="form.processing">
                    Confirm
                </PrimaryButton>
            </div>
        </form>
    </Guest>
</template>
