<script setup>
import {onMounted, ref} from "vue";

const props = defineProps({
    multiple: {
        type: Boolean,
        required: false,
        default: false
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
        required: false,
        default: null
    },
    autofocus: {
        type: Boolean,
        required: false,
        default: false
    },
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

let emit = defineEmits(['update:modelValue'])

function handleChange(event) {
    let files;

    if (event.target.files && event.target.files[0]) {
        if (props.multiple)
            files = event.target.files;
        else
            files = event.target.files[0];
    }

    emit('update:modelValue', files);
}
</script>

<template>
    <div class="my-4">
        <label v-if="label"
               class="label">
            {{ label }}
            <span v-if="required"
                  class="text-error text-sm">*</span>
        </label>
        <input
            class="file-input"
            :class="[error ? 'border-error' : 'border-gray-300']"
            type="file"
            :placeholder="placeholder"
            @input="handleChange($event)"
            :required="required"
            :autofocus="autofocus"
            :multiple="multiple"
            ref="input"
        />
        <span v-if="error"
              class="validation-error">
            {{ error }}
        </span>
    </div>
</template>
