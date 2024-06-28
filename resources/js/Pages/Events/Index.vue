<script setup>
const props = defineProps(['events'])
</script>

<template>
    <div>
        <div v-for="(event, index) in events"
             :key="index"
             class="card my-4">
            <div class="card-body">
                <div class="flex justify-between">
                    <div class="text-lg">{{ event.title }}</div>
                    <div :class="[event.status === 'upcoming' ? 'bg-blue-50 text-primary' : 'bg-green-100 text-success']"
                         class="rounded-full px-4 py-2 text-sm">
                        {{ event.status }}
                    </div>
                </div>
                <div class="flex text-sm text-gray-500">
                    <div class="flex">
                        <i class="mdi mdi-clock"></i>
                        <div class="ml-1">{{ event.start_time }}</div>
                    </div>
                    <div class="flex ml-2">
                        <i class="mdi mdi-calendar-blank"></i>
                        <div class="ml-1">{{ event.start_date }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
