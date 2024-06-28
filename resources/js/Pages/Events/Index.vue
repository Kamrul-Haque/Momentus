<script setup>
import Auth from "@/Layouts/Auth.vue";
import {Head, Link, router} from "@inertiajs/vue3";
import TextField from "@/Components/TextField.vue";
import Select from "@/Components/Select.vue";
import Paginator from "@/Components/Paginator.vue";
import {reactive, ref, watch} from "vue";
import {debounce} from "lodash";

const props = defineProps(['events', 'filters'])

const params = reactive({
    page: props.filters.page ? props.filters.page : 1,
    search: props.filters.search ? props.filters.search : '',
    sortBy: props.filters.sortBy ? props.filters.sortBy : null,
    sortDesc: !!props.filters.sortDesc,
    perPage: props.filters.perPage ? parseInt(props.filters.perPage) : 15,
});

const search = ref(params.search);
const perPage = ref(params.perPage)

watch(params, (newVal, oldVal) => {
    if (newVal.perPage !== oldVal.perPage) {
        params.page = 1;
    }
    updateData();
}, {
    deep: true
});

watch(search, debounce((value) => {
    if (value) {
        params.search = value;
        updateData();
    }
}, 500));

watch(perPage, (value) => {
    if (value) {
        params.perPage = value;
        updateData();
    }
})

async function updateData() {
    router.get(route('events.index'), params, {
        preserveScroll: true,
        preserveState: true,
        replace: true,
    });
}

function sort(sortBy) {
    if (params.sortBy === sortBy) {
        params.sortDesc = !params.sortDesc;
    } else {
        params.sortBy = sortBy;
        params.sortDesc = false;
    }
    updateData();
}
</script>

<template>
    <Auth>
        <Head title="Events"/>

        <div class="md:flex justify-between items-center space-y-2 md:space-y-0">
            <div class="flex-1 flex items-center">
                <h1 class="heading capitalize">
                    Events
                </h1>
                <!--                <div v-if="$slots.filter"
                                     class="ml-4">
                                    <slot name="filter"></slot>
                                </div>-->
            </div>

            <div class="flex items-center">
                <TextField v-model="params.search"
                           class="flex-1"
                           height="8"
                           name="search"
                           placeholder="search"
                           prepend-icon="mdi mdi-magnify"/>

                <Link :href="route('events.create')"
                      class="btn btn-primary text-white h-8 ml-1">
                    <span class="mdi mdi-plus-circle-outline mr-1"/>
                    Create
                </Link>
            </div>
        </div>

        <div v-for="(event, index) in events.data"
             :key="index"
             class="card mb-4">
            <div class="card-body">
                <div class="flex justify-between mb-2">
                    <Link :href="route('events.show', event.reminder_id)"
                          as="div"
                          class="text-lg hover:underline hover:text-primary hover:cursor-pointer"
                          title="title">
                        {{ event.title }}
                    </Link>
                    <div>
                        <div v-if="event.deleted_at"
                             class="rounded-full px-4 py-1 text-sm bg-gray-200 text-gray-600 text-center capitalize"
                             title="status">
                            archived
                        </div>
                        <div v-else
                             :class="[event.status_name === 'upcoming' ? 'bg-blue-50 text-primary' : 'bg-green-100 text-success']"
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
                         title="created by">
                        <i class="mdi mdi-account"></i>
                        <p>{{ event.created_by_name }}</p>
                    </div>
                    <div class="flex gap-1"
                         title="attendees">
                        <i class="mdi mdi-account-multiple"></i>
                        <p>{{ event.users.length }} Person</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-between items-center">
            <div class="flex-1">
                <Paginator :links="events.links"/>
            </div>
            <div class="flex items-center">
                <label class="font-medium text-gray-800 mr-2"
                       for="perPage">
                    Items:
                </label>
                <Select v-model="perPage"
                        class="min-w-[64px]"
                        :height="10"
                        :items="['5','10','15','20','25','30']"
                        name="perPage"/>
            </div>
        </div>
    </Auth>
</template>

<style scoped>

</style>
