<script setup>
import {onMounted, ref} from "vue";

const props = defineProps({
    modelValue: {
        type: String,
        required: false,
        default: null
    },
    rows: {
        rows: Number,
        required: false,
        default: 2
    },
    required: {
        type: Boolean,
        required: false,
        default: false
    },
    label: {
        type: String,
        required: false
    },
    placeholder: {
        type: String,
        required: false
    },
    error: {
        type: String,
        required: false
    },
    autofocus: {
        type: Boolean,
        required: false,
        default: false
    },
    resize: {
        type: Boolean,
        required: false,
        default: false
    }
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineEmits(['update:modelValue']);
</script>

<template>
    <div class="my-4">
        <label v-if="label"
               class="label">
            {{ label }}
            <span v-if="required"
                  class="text-error text-sm">*</span>
        </label>
        <textarea class="input"
                  :class="[error ? 'border-error' : 'border-gray-300', resize ? '': 'resize-none']"
                  :value="modelValue"
                  :placeholder="placeholder"
                  :rows="rows"
                  @input="$emit('update:modelValue', $event.target.value)"
                  :required="required"
                  :autofocus="autofocus"
                  ref="input">
        </textarea>
        <span v-if="error"
              class="validation-error">
            {{ error }}
        </span>
    </div>
</template>
