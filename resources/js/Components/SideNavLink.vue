<script setup>
import {Link} from "@inertiajs/vue3";

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean
    }
});
</script>

<template>
    <Link :href="href"
          as="div"
          class="text-lg font-bold rounded w-full px-5 py-3 my-0 cursor-pointer hover:text-opacity-75 transition-all ease-in-out duration-150"
          :class="[active ? 'bg-accent' : 'text-white']">
        <slot/>
    </Link>
</template>
