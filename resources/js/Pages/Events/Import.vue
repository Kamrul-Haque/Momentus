<script setup>
import {Head} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {useForm} from 'laravel-precognition-vue-inertia';
import Auth from "@/Layouts/Auth.vue";
import DarkButton from "@/Components/DarkButton.vue";
import DropZone from "@/Components/DropZone.vue";

const form = useForm('post', route('events.import.create'), {
    file: null
});

function submit() {
    form.submit({preserveScroll: true})
}
</script>

<template>
    <Head title="Events"/>

    <Auth>
        <div class="card w-full md:w-3/4 mx-auto">
            <h1 class="card-header">Import Events</h1>
            <div class="card-body">
                <form @submit.prevent="submit()">
                    <DropZone @files="form.file = $event"
                              :error="form.errors.file"
                              label="File (csv)"
                              types="text/csv"
                              :previous="[]"
                              required/>

                    <div class="text-right mt-8">
                        <DarkButton :href="route('events.index')"
                                    class="mr-1">
                            Cancel
                        </DarkButton>
                        <PrimaryButton type="submit">Save</PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </Auth>
</template>
