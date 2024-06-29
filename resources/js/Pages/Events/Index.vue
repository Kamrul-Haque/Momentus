<script setup>
import Auth from "@/Layouts/Auth.vue";
import {Head, Link, router} from "@inertiajs/vue3";
import TextField from "@/Components/TextField.vue";
import Select from "@/Components/Select.vue";
import Paginator from "@/Components/Paginator.vue";
import {reactive, watch} from "vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import DarkButton from "@/Components/DarkButton.vue";

const props = defineProps(['events', 'statuses', 'filters'])

const params = reactive({
    page: props.filters.page ? props.filters.page : 1,
    search: props.filters.search ? props.filters.search : '',
    status: props.filters.status ? props.filters.status : 'upcoming',
    sortBy: props.filters.sortBy ? props.filters.sortBy : null,
    sortDesc: !!props.filters.sortDesc,
    perPage: props.filters.perPage ? parseInt(props.filters.perPage) : 5,
});

watch(params, (newVal, oldVal) => {
    if (newVal.perPage !== oldVal.perPage) {
        params.page = 1;
    }
    updateData();
}, {
    deep: true
});

async function updateData() {
    router.get(route('events.index'), params, {
        preserveScroll: true,
        preserveState: true,
        replace: true,
    });
}

function sort(sortBy, sortDesc) {
    params.sortBy = sortBy
    params.sortDesc = sortDesc
}
</script>

<template>
    <Auth>
        <Head title="Events"/>

        <div class="md:flex justify-between items-center space-y-2 md:space-y-0">
            <div class="flex-1 flex items-center gap-1">
                <Select v-model="params.status"
                        class="w-[150px]"
                        :height="8"
                        :items="['all', ...statuses]"
                        name="status"/>

                <div class="ms-3 relative">
                    <Dropdown align="right"
                              width="48">
                        <template #trigger>
                        <span class="inline-flex rounded-md">
                            <button type="button"
                                    class="inline-flex justify-between items-center bg-white rounded px-4 py-1 text-gray-600 hover:text-primary focus:outline-none focus:text-primary transition ease-in-out duration-150 shadow">
                                <i class="mdi mdi-sort-ascending"></i>
                                <i class="mdi mdi-chevron-down"></i>
                            </button>
                        </span>
                        </template>

                        <template #content>
                            <DropdownLink @click="sort('id', true)">
                                Newest
                            </DropdownLink>
                            <DropdownLink @click="sort('id', false)">
                                Oldest
                            </DropdownLink>
                            <DropdownLink @click="sort('start_at', true)">
                                Starting At (Ascending)
                            </DropdownLink>
                            <DropdownLink @click="sort('start_at', false)">
                                Starting At (Descending)
                            </DropdownLink>
                        </template>
                    </Dropdown>
                </div>

                <div>
                    <DarkButton :href="route('events.import.create')">
                        <i class="mdi mdi-upload-circle mr-1"></i>
                        Import
                    </DarkButton>
                </div>
            </div>

            <div class="flex items-center gap-1">
                <TextField v-model="params.search"
                           class="flex-1"
                           height="8"
                           name="search"
                           placeholder="search"
                           prepend-icon="mdi mdi-magnify"/>

                <Link :href="route('events.create')"
                      class="btn btn-primary text-white h-8">
                    <span class="mdi mdi-plus-circle-outline mr-1"/>
                    Create
                </Link>
            </div>
        </div>

        <template v-if="events.data.length">
            <div v-for="(event, index) in events.data"
                 :key="index"
                 class="card mb-4">
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
        <template v-else>
            <p class="text-center">No Data Found.</p>
        </template>

        <div class="flex justify-between items-center">
            <div class="flex-1">
                <Paginator :links="events.links"/>
            </div>
            <div class="flex items-center">
                <label class="font-medium text-gray-800 mr-2"
                       for="perPage">
                    Items:
                </label>
                <Select v-model="params.perPage"
                        class="w-[64px]"
                        :height="8"
                        :items="['5','10','15','20','25','30']"
                        name="perPage"/>
            </div>
        </div>
    </Auth>
</template>
