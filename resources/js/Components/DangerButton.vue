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
    <button :class="[rounded ? 'rounded-full' : '']"
            class="btn btn-danger">
        <slot/>
    </button>
</template>
