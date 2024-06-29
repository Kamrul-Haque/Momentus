<script setup>
import {Head} from "@inertiajs/vue3";
import TextField from "@/Components/TextField.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextArea from "@/Components/TextArea.vue";
import {useForm} from 'laravel-precognition-vue-inertia';
import MultiSelect from "@/Components/MultiSelect.vue";
import Auth from "@/Layouts/Auth.vue";
import DarkButton from "@/Components/DarkButton.vue";

const props = defineProps({
    event: Object,
    users: Array,
    selected_users: Array
});

const form = useForm('post', props.event ? route('events.update', props.event.reminder_id) : route('events.store'), {
    _method: props.event ? 'PUT' : 'POST',
    title: props.event ? props.event.title : null,
    start_time: props.event ? props.event.start_at.raw_time : null,
    start_date: props.event ? props.event.start_at.raw_date : null,
    end_time: props.event ? props.event.end_at.raw_time : null,
    end_date: props.event ? props.event.end_at.raw_date : null,
    description: props.event ? props.event.description : null,
    users: props.selected_users || []
});

function submit() {
    form.submit({preserveScroll: true})
}
</script>

<template>
    <Head title="Settings"/>

    <Auth>
        <div class="card w-full md:w-3/4 mx-auto">
            <h1 class="card-header">{{ event ? 'Edit User' : 'Create User' }}</h1>
            <div class="card-body">
                <form @submit.prevent="submit()">
                    <TextField v-model="form.title"
                               label="Name"
                               @change="form.validate('title')"
                               :error="form.errors.title"
                               autofocus
                               required/>

                    <MultiSelect v-model="form.users"
                                 :items="users"
                                 item-label="name"
                                 item-value="id"
                                 label="Attendees"/>

                    <TextField v-model="form.start_time"
                               type="time"
                               label="Start Time"
                               @change="form.validate('start_time')"
                               :error="form.errors.start_time"
                               required/>

                    <TextField v-model="form.start_date"
                               type="date"
                               label="Start Date"
                               @change="form.validate('start_date')"
                               :error="form.errors.start_date"/>

                    <TextField v-model="form.end_time"
                               type="time"
                               label="End Time"
                               @change="form.validate('end_time')"
                               :error="form.errors.end_time"
                               required/>

                    <TextField v-model="form.end_date"
                               type="date"
                               label="End Date"
                               @change="form.validate('end_date')"
                               :error="form.errors.end_date"/>

                    <TextArea v-model="form.description"
                              label="Description"
                              @change="form.validate('description')"
                              :error="form.errors.description"/>

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
