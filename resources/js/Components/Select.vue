<script setup>
import {computed, nextTick, onMounted, ref, watch} from "vue";

const props = defineProps({
    modelValue: {
        type: [String, Number, Object],
        required: false,
    },
    items: Array,
    itemLabel: String,
    itemValue: String,
    required: Boolean,
    label: String,
    placeholder: String,
    error: String,
    autofocus: Boolean,
    prependIcon: String,
    rounded: Boolean,
    height: {
        type: [String, Number],
    },
    clearable: {
        type: Boolean,
        default: false
    }
});

const query = ref('');
const focused = ref(false);
const highlightedIndex = ref(-1);
const preventBlur = ref(false);

const filteredSuggestions = computed(() => props.items);

const emit = defineEmits(['update:modelValue']);

function selectItem(suggestion) {
    preventBlur.value = true;

    if (typeof suggestion === 'object')
        query.value = suggestion[props.itemLabel];
    else
        query.value = suggestion;

    focused.value = false;
    highlightedIndex.value = -1;

    if (typeof suggestion === 'object')
        emit('update:modelValue', suggestion[props.itemValue]);
    else
        emit('update:modelValue', suggestion);
}

watch(query, () => {
    if (query.value === '') {
        input.value.focus();
        emit('update:modelValue', null);
    }
})

function handleKeyDown(event) {
    if (event.key === 'ArrowDown') {
        event.preventDefault();
        if (highlightedIndex.value < filteredSuggestions.value.length - 1) {
            highlightedIndex.value++;
        } else {
            highlightedIndex.value = 0;
        }
        nextTick(() => {
            scrollToHighlightedItem();
        });
    } else if (event.key === 'ArrowUp') {
        event.preventDefault();
        if (highlightedIndex.value > 0) {
            highlightedIndex.value--;
        } else {
            highlightedIndex.value = filteredSuggestions.value.length - 1;
        }
        nextTick(() => {
            scrollToHighlightedItem();
        });
    } else if (event.key === 'Enter' && highlightedIndex.value !== -1) {
        event.preventDefault();
        selectItem(filteredSuggestions.value[highlightedIndex.value]);
    } else if (event.key === 'Escape') {
        focused.value = false;
        highlightedIndex.value = -1;
    }
}

function scrollToHighlightedItem() {
    const list = suggestionList.value;
    const item = list.children[highlightedIndex.value];
    if (item) {
        const itemTop = item.offsetTop;
        const itemBottom = itemTop + item.offsetHeight;
        const listTop = list.scrollTop;
        const listBottom = listTop + list.clientHeight;

        if (itemTop < listTop) {
            list.scrollTop = itemTop;
        } else if (itemBottom > listBottom) {
            list.scrollTop = itemBottom - list.clientHeight;
        }
    }
}

const input = ref(null);
const suggestionList = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }

    if (props.modelValue !== null) {
        let item;

        if (typeof props.items[0] === 'object') {
            item = props.items.find(item => item[props.itemValue] == props.modelValue);
        } else {
            item = props.items.find(item => item == props.modelValue);
        }

        selectItem(item);
    }
});
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
            <div class="relative w-full"
                 @focus="focused">
                <input class="input"
                       :class="[error ? 'border-error' : 'border-gray-200', prependIcon ? 'pl-9' : '', rounded ? 'rounded-full' : 'rounded-md', height ? `h-${height}` : '']"
                       v-model="query"
                       type="text"
                       :placeholder="[placeholder || 'Please Select...']"
                       :required="required"
                       :autofocus="autofocus"
                       autocomplete="off"
                       @keydown="handleKeyDown"
                       @focus="focused = true"
                       @blur="() => { if (!preventBlur) focused = false; }"
                       ref="input"
                       readonly/>
                <transition name="fade">
                    <ul v-show="filteredSuggestions.length && focused"
                        class="absolute w-full bg-white border rounded mt-1 p-1 max-h-48 overflow-y-auto z-10"
                        ref="suggestionList">
                        <li v-for="(suggestion, index) in filteredSuggestions"
                            :key="index"
                            @mousedown="selectItem(suggestion)"
                            :class="['p-2 cursor-pointer rounded', highlightedIndex === index ? 'bg-primary text-white' : 'hover:bg-primary hover:text-white']">
                            {{ typeof suggestion === 'object' ? suggestion[itemLabel] : suggestion }}
                        </li>
                    </ul>
                </transition>
            </div>
            <div v-if="query && clearable"
                 class="absolute flex items-center text-xl text-primary right-0 mr-8 inset-y-0"
                 @click="query = ''">
                <i class="mdi mdi-close"></i>
            </div>
            <div class="absolute flex items-center text-xl text-gray-500 right-0 mr-3 inset-y-0">
                <i :class="[filteredSuggestions.length && focused ? 'mdi mdi-chevron-up' : 'mdi mdi-chevron-down']"></i>
            </div>
        </div>
        <span v-if="error"
              class="validation-error">
            {{ error }}
        </span>
    </div>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from, .fade-leave-to {
    opacity: 0;
}

.fade-enter-to, .fade-leave-from {
    opacity: 1;
}
</style>
