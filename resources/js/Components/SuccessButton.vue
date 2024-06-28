<script setup>
const props = defineProps({
    rounded: {
        type: Boolean,
        required: false,
        default: false
    }
});
</script>
<template>
    <button
        :class="[rounded ? 'rounded-full' : 'rounded-md']"
        class="inline-flex items-center px-4 py-2 bg-success border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-success hover:opacity-75 focus:bg-success active:bg-success focus:outline-none focus:ring-2 focus:ring-success focus:ring-offset-2 transition ease-in-out duration-150"
    >
        <slot/>
    </button>
</template>
