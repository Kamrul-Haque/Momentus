<script setup>
import {debounce} from "lodash";
import Paginator from "@/Components/Paginator.vue";
import {Link, router} from "@inertiajs/vue3";
import TextField from "@/Components/TextField.vue";
import {reactive, ref, watch} from "vue";
import Select from "@/Components/Select.vue";

const props = defineProps({
    headers: {
        type: Array,
        required: true,
    },
    items: {
        type: Object,
        required: false
    },
    caption: {
        type: String,
        required: false,
        default: null
    },
    indexRoute: {
        type: String,
        required: true
    },
    createRoute: {
        type: String,
        required: false,
        default: null
    },
    createButtonLabel: {
        type: String,
        required: false,
        default: null
    },
    filters: {
        type: Object,
        required: true,
    }
});

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

async function updateData() {
    router.get(props.indexRoute, params, {
        preserveScroll: true,
        preserveState: true,
        replace: true,
    });
}

// Method to handle sorting
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
    <div v-if="$slots.top"
         class="mb-2">
        <slot name="top"/>
    </div>
    <div class="md:flex justify-between items-center space-y-2 md:space-y-0">
        <div class="flex-1 flex items-center">
            <h1 v-if="caption"
                class="heading capitalize">
                {{ caption }}
            </h1>
            <div v-if="$slots.filter"
                 class="ml-4">
                <slot name="filter"></slot>
            </div>
        </div>

        <div class="flex items-center">
            <TextField v-model="params.search"
                       class="flex-1"
                       height="8"
                       name="search"
                       placeholder="search"
                       prepend-icon="mdi mdi-magnify"/>

            <div v-if="createRoute">
                <Link :href="createRoute"
                      class="btn btn-primary text-white h-8 ml-1">
                    <span class="mdi mdi-plus-circle-outline mr-1"/>
                    {{ createButtonLabel || 'Add' }}
                </Link>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="flex flex-col">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                    <div class="overflow-hidden">
                        <table class="min-w-full table table-auto rounded">
                            <thead class="capitalize border-b text-left">
                            <tr>
                                <th v-for="(header,index) in headers"
                                    :key="index"
                                    class="px-4 py-2"
                                    scope="col"
                                    @click="sort(header.value)">
                                    {{ header.text }}
                                    <span v-if="header.sortable">
                                        <span v-if="params.sortBy === header.value && !params.sortDesc"
                                              class="cursor-pointer mdi mdi-chevron-up">
                                        </span>
                                        <span v-else-if="params.sortBy === header.value && params.sortDesc"
                                              class="cursor-pointer mdi mdi-chevron-down">
                                        </span>
                                        <span v-else
                                              class="cursor-pointer mdi mdi-unfold-more-horizontal">
                                        </span>
                                    </span>
                                </th>
                                <th v-if="$slots.default"
                                    class="px-4 py-2">
                                    Actions
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-if="items.data.length">
                                <tr v-for="(item,index) in items.data"
                                    :key="index"
                                    class="border-t">
                                    <td v-for="(header,index) in headers"
                                        :key="index"
                                        class="px-4 py-2 h-14 whitespace-nowrap">
                                        <slot :name=[header.value]
                                              :item="item"/>
                                        <template v-if="!$slots[header.value]">
                                            {{ item[header.value] }}
                                        </template>
                                    </td>
                                    <td v-if="$slots.default"
                                        class="px-4 py-2 h-14">
                                        <slot :item="item"/>
                                    </td>
                                </tr>
                            </template>
                            <template v-else>
                                <tr>
                                    <td :colspan="$slots.default ? headers.length+1 : headers.length"
                                        class="text-center py-2">No Records Found
                                    </td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="flex justify-between items-center">
        <div class="flex-1">
            <Paginator :links="items.links"/>
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
    <div v-if="$slots.bottom"
         class="mt-2">
        <slot name="bottom"/>
    </div>
</template>
