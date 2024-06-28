<script setup>
import {defineEmits, defineProps} from 'vue';

const props = defineProps({
    modelValue: {
        type: Boolean,
        required: true,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['update:modelValue']);

function toggle(event) {
    emit('update:modelValue', event.target.checked);
}
</script>

<template>
    <div class="my-4">
        <label :class="['relative inline-flex items-center cursor-pointer', { 'opacity-50 pointer-events-none': disabled }]">
            <input type="checkbox"
                   class="sr-only peer"
                   :checked="modelValue"
                   @change="toggle"
                   :disabled="disabled"/>
            <div :class="['w-11 h-6 bg-gray-200 rounded-full',{ 'peer-checked:bg-primary': modelValue, 'peer-checked:bg-gray-300': !modelValue }]"></div>
            <span :class="['absolute w-5 h-5 bg-white rounded-full transform transition-transform duration-200',{ 'translate-x-5': modelValue, 'translate-x-1': !modelValue }]"></span>
        </label>
    </div>
</template>

