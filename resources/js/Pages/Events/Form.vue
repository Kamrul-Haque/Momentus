<script setup>
import Admin from "@/Layouts/Admin.vue";
import {Head} from "@inertiajs/vue3";
import TextField from "@/Components/TextField.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextArea from "@/Components/TextArea.vue";
import {useForm} from 'laravel-precognition-vue-inertia';
import MultiSelect from "@/Components/MultiSelect.vue";

const props = defineProps({
    event: Object,
    users: Array,
    selected_users: Array
});

const form = useForm('post', props.event ? route('events.update', props.event.id) : route('events.store'), {
    title: props.event ? props.event.title : null,
    start_time: props.event ? props.event.start_time : null,
    start_date: props.event ? props.event.start_date : null,
    end_time: props.event ? props.event.end_time : null,
    end_date: props.event ? props.event.end_date : null,
    description: props.event ? props.event.description : null,
    users: props.selected_users || null
});

function submit() {
    form.submit({preserveScroll: true})
}
</script>

<template>
    <Head title="Settings"/>

    <Admin>
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

                    <MultiSelect v-model="form.users"
                                 :items="users"
                                 item-label="name"
                                 item-value="id"
                                 label="Attendees"/>

                    <div class="text-right mt-8">
                        <PrimaryButton type="submit">Save</PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </Admin>
</template>
