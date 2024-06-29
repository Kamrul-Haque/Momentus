<script setup>
import {Link} from "@inertiajs/vue3";

const props = defineProps({
    rounded: Boolean,
    href: String,
    method: String,
});
</script>

<template>
    <Link v-if="href"
          :href="href"
          :method="method"
          class="btn btn-dark">
        <slot/>
    </Link>
    <button v-else
            :class="[rounded ? 'rounded-full' : '']"
            class="btn btn-dark">
        <slot/>
    </button>
</template>
