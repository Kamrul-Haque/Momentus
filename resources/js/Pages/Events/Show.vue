<script setup>
import Auth from "@/Layouts/Auth.vue";
import {Head, Link, router} from "@inertiajs/vue3";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import {ref} from "vue";
import DarkButton from "@/Components/DarkButton.vue";

const props = defineProps({
    event: Object
})

let showConfirmModal = ref(false);

function deleteItem() {
    router.delete(route('events.delete', props.event.id));
}
</script>

<template>
    <Auth>
        <Head title="Events"/>

        <div class="flex justify-between items-center border-b-2 mb-2">
            <Link :href="route('events.index')"
                  class="text-gray-600 text-2xl hover:text-primary hover:cursor-pointer">
                <i class="mdi mdi-arrow-left-circle-outline"></i>
            </Link>

            <div class="flex items-center gap-2">
                <div class="text-xl font-medium hover:underline hover:text-primary hover:cursor-pointer"
                     title="title">
                    {{ event.title }}
                </div>
                <div>
                    <div v-if="event.deleted_at"
                         class="rounded-full px-4 py-1 text-sm bg-gray-200 text-gray-600 text-center capitalize"
                         title="status">
                        archived
                    </div>
                    <div v-else
                         :class="[event.status_name === 'upcoming' ? 'bg-blue-100 text-primary' : 'bg-green-100 text-success']"
                         class="rounded-full px-4 py-1 text-sm text-center capitalize"
                         title="status">
                        {{ event.status_name }}
                    </div>
                </div>
            </div>

            <div class="ms-3 relative">
                <Dropdown align="right"
                          width="48">
                    <template #trigger>
                        <span class="inline-flex rounded-md">
                            <button type="button"
                                    class="text-2xl text-gray-600 hover:text-primary focus:outline-none focus:text-primary transition ease-in-out duration-150">
                                <i class="mdi mdi-dots-vertical"></i>
                            </button>
                        </span>
                    </template>

                    <template #content>
                        <template v-if="event.deleted_at">
                            <DropdownLink :href="route('events.restore', event.id)"
                                          method="PUT"
                                          as="button">
                                Restore
                            </DropdownLink>
                            <DropdownLink @click="showConfirmModal=true">
                                Delete
                            </DropdownLink>
                        </template>
                        <template v-else>
                            <DropdownLink :href="route('events.edit', event.reminder_id)">
                                Edit
                            </DropdownLink>
                            <DropdownLink :href="route('events.destroy', event.reminder_id)"
                                          method="DELETE"
                                          as="button">
                                Archive
                            </DropdownLink>
                        </template>
                    </template>
                </Dropdown>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-9">
                <h4 class="text-lg font-bold">Description:</h4>
                <p>{{ event.description }}</p>
            </div>
            <div class="col-span-3">
                <div class="card">
                    <div class="card-body">
                        <div class="grid grid-cols-2"
                             title="start time">
                            <p>
                                <i class="mdi mdi-clock"></i>
                                Start Time:
                            </p>
                            <p class="text-gray-700">{{ event.start_at.time }}</p>
                        </div>
                        <div class="grid grid-cols-2"
                             title="start date">
                            <p>
                                <i class="mdi mdi-calendar-blank"></i>
                                Start Date:
                            </p>
                            <p class="text-gray-700">{{ event.start_at.date }}</p>
                        </div>
                        <template v-if="event.end_at">
                            <div class="grid grid-cols-2"
                                 title="end time">
                                <p>
                                    <i class="mdi mdi-clock-check"></i>
                                    End Time:
                                </p>
                                <p class="text-gray-700">{{ event.end_at.time }}</p>
                            </div>
                            <div class="grid grid-cols-2"
                                 title="end date">
                                <p>
                                    <i class="mdi mdi-calendar-check"></i>
                                    End Date:
                                </p>
                                <p class="text-gray-700">{{ event.end_at.date }}</p>
                            </div>
                        </template>
                        <template v-if="event.duration">
                            <div class="grid grid-cols-2"
                                 title="duration">
                                <p>
                                    <i class="mdi mdi-timer-sand"></i>
                                    Duration:
                                </p>
                                <p class="text-gray-700">{{ event.duration }}</p>
                            </div>
                        </template>
                        <div class="grid grid-cols-2"
                             title="created by">
                            <p>
                                <i class="mdi mdi-account"></i>
                                Created By:
                            </p>
                            <p class="text-gray-700">{{ event.created_by_name }}</p>
                        </div>
                        <div class="grid grid-cols-2"
                             title="attendees">
                            <p>
                                <i class="mdi mdi-account-multiple"></i>
                                Attendees:
                            </p>
                            <p class="text-gray-700">{{ event.users.length }} Person</p>
                        </div>
                    </div>
                </div>
                <div class="my-4">
                    <h5 class="text-center font-bold">People Attending</h5>
                    <div v-for="(user, index) in event.users"
                         :key="index">
                        <div class="flex items-center gap-2 py-2">
                            <div class="avatar-circle">
                                <img v-if="user.image"
                                     :src="user.image"
                                     alt="logo"
                                     width="20px"
                                     height="20px"/>
                                <i v-else
                                   class="mdi mdi-account"></i>
                            </div>
                            <p class="text-lg">{{ user.name }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <Modal closeable
               @close="showConfirmModal=false"
               max-width="md"
               :show="showConfirmModal">
            <div class="card">
                <div class="card-body">
                    <div class="flex justify-center">
                        <div class="block">
                            <h4 class="heading flex-1">Do you really want to delete this record?</h4>
                            <div class="text-right mt-8">
                                <DarkButton @click="showConfirmModal=false"
                                            class="mr-1">
                                    Cancel
                                </DarkButton>
                                <DangerButton @click.prevent="deleteItem">Confirm</DangerButton>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Modal>
    </Auth>
</template>

<style scoped>

</style>
