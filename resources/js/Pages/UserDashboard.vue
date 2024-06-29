<script setup>
import Auth from "@/Layouts/Auth.vue";
import {Head, Link} from '@inertiajs/vue3';

const props = defineProps(['events'])
</script>

<template>
    <Head title="Dashboard"/>

    <Auth>
        <template v-if="events.length">
            <h4 class="text-lg text-gray-700 font-semibold">Today's Events</h4>
            <div v-for="(event, index) in events"
                 :key="index"
                 class="card my-4">
                <div class="card-body">
                    <div class="flex justify-between mb-2">
                        <div class="flex items-center gap-1">
                            <Link :href="route('events.show', event.reminder_id)"
                                  as="div"
                                  class="text-lg hover:underline hover:text-primary hover:cursor-pointer"
                                  title="title">
                                {{ event.title }}
                            </Link>
                            <p v-if="event.duration"
                               class="text-sm text-gray-500"
                               title="duration">
                                <i class="mdi mdi-timer-sand"></i>
                                {{ event.duration }}
                            </p>
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
                    <div class="flex gap-2 text-sm text-gray-500 flex-wrap">
                        <div class="flex gap-1"
                             title="start time">
                            <i class="mdi mdi-clock"></i>
                            <p>{{ event.start_at.time }}</p>
                        </div>
                        <div class="flex gap-1"
                             title="start date">
                            <i class="mdi mdi-calendar-blank"></i>
                            <p>{{ event.start_at.date }}</p>
                        </div>
                        <div class="flex gap-1"
                             title="attendees">
                            <i class="mdi mdi-account-multiple"></i>
                            <p>{{ event.users.length }} Person</p>
                        </div>
                        <div class="flex gap-1"
                             title="created by">
                            <i class="mdi mdi-account"></i>
                            <p>{{ event.created_by_name }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </Auth>
</template>
