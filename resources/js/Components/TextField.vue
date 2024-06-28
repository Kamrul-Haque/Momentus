<script setup>
import {computed, onMounted, ref} from "vue";

const props = defineProps({
    modelValue: {
        type: [String, Number, Object],
        required: false,
    },
    type: {
        type: String,
        required: false,
        default: 'text'
    },
    required: Boolean,
    label: String,
    placeholder: String,
    error: String,
    autofocus: Boolean,
    prependIcon: String,
    appendIcon: String,
    rounded: Boolean,
    regex: String,
    height: {
        type: [String, Number],
    }
});

let show = ref(false);

const inputType = computed(() => {
    if (props.type === 'password')
        return show.value ? 'text' : 'password';

    return props.type;
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
               class="label capitalize">
            {{ label }}
            <span v-if="required"
                  class="text-error text-sm">*</span>
        </label>
        <div class="relative">
            <div v-if="prependIcon"
                 class="absolute flex items-center text-xl text-gray-800 left-0 ml-3 inset-y-0">
                <i :class="prependIcon"></i>
            </div>
            <input class="input"
                   :class="[error ? 'border-error' : 'border-gray-200', prependIcon ? 'pl-9' : '', rounded ? 'rounded-full' : 'rounded-md', type==='password'? 'pr-9' : '', height ? `h-${height}` : '']"
                   :value="modelValue"
                   :type="inputType"
                   :placeholder="placeholder"
                   @input="$emit('update:modelValue', $event.target.value)"
                   :required="required"
                   :autofocus="autofocus"
                   :pattern="regex"
                   autocomplete="off"
                   ref="input"/>
            <div v-if="type==='password'"
                 @click="show=!show"
                 class="absolute flex items-center text-xl text-gray-800 right-0 mr-3 inset-y-0">
                <i v-if="show"
                   class="mdi mdi-eye-off"></i>
                <i v-else
                   class="mdi mdi-eye"></i>
            </div>
            <div v-if="appendIcon"
                 class="absolute flex items-center text-xl text-gray-800 right-0 mr-3 inset-y-0">
                <i :class="appendIcon"></i>
            </div>
        </div>
        <span v-if="error"
              class="validation-error">
            {{ error }}
        </span>
    </div>
</template>
