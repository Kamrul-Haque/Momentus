<script setup>
// Import FilePond
import vueFilePond from 'vue-filepond';

// Import plugins
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';

// Import styles
import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css';
import FilePondPluginImageExifOrientation from 'filepond-plugin-image-exif-orientation';
import FilePondPluginImageCrop from 'filepond-plugin-image-crop';
import {reactive, ref} from "vue";

// Create FilePond component
const FilePond = vueFilePond(
    FilePondPluginFileValidateType,
    FilePondPluginImagePreview,
    FilePondPluginImageExifOrientation,
    FilePondPluginImageCrop
);

const props = defineProps({
    label: String,
    required: Boolean,
    preview: {
        type: Boolean,
        default: true
    },
    multiple: Boolean,
    maximum: Number,
    error: String,
    types: {
        type: String,
        default: 'image/jpg, image/jpeg, image/png'
    },
    previous: {
        type: [Array, Object],
        default: null
    }
})

let files = reactive([]);
let showPrevious = ref(!!props.previous);
const emit = defineEmits(["files"]);

const pond = ref(null);

function handleFilePondInit() {
    // console.log('FilePond has initialized');

    // example of instance method call on pond reference
    // pond.value.getFiles();
}

function update(pondFiles) {
    files = pondFiles.map(file => file.file)

    props.multiple === false
        ? emit("files", files[0])
        : emit("files", files);

    showPrevious.value = !(props.previous && files.length !== 0);
}
</script>

<template>
    <div class="my-4"
         id="app">
        <label v-if="label"
               class="label capitalize">
            {{ label }}
            <span v-if="required"
                  class="text-error text-sm">*</span>
        </label>
        <file-pond
            name="test"
            ref="pond"
            class="pb-0 mb-0"
            class-name="my-pond"
            label-idle='Drag & Drop your files or <span class="filepond--label-action"> Browse </span>'
            imagePreviewHeight="100"
            :allow-multiple="multiple"
            :maxFiles="maximum"
            :accepted-file-types="types"
            :files="files"
            :allowImagePreview="preview"
            instant-upload="false"
            :credits="''"
            @init="handleFilePondInit"
            @updatefiles="update"
        />

        <div v-if="showPrevious && previous[0]">
            <label v-if="label"
                   class="label capitalize">
                Previous Files
            </label>
            <div class="previous-container">
                <div class="items-container">
                    <div v-for="(item, index) in previous"
                         class="image-card">
                        <img :src="item"
                             :alt="'item'+index">
                        <div class="filename">Screenshot from 2024-06-20 14-04-37.png</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.filepond--root .filepond--drop-label {
    background-color: rgb(249 250 251);
    font-weight: bold;
    color: rgb(107 114 128);
    border: 2px dashed rgb(229 231 235);
    border-radius: 0.375rem;
}

.filepond--root .filepond--drop-label:hover {
    background-color: rgb(244, 244, 244);
}

.filepond--panel-root {
    background-color: rgb(249 250 251);
    border: 2px solid rgb(229 231 235);
    border-radius: 0.375rem;
}

.filepond--panel-bottom .filepond--panel-root {
    border: 0 !important;
    padding: 0 !important;
    margin: 0 !important;
}

.filepond--item {
    width: calc(50% - 0.5em);
}

.previous-items {
    display: flex;
}

.previous-item-wrapper {
    margin: 5px;
    width: calc(50% - 0.5em);
    max-height: 100px;
    align-items: center;
    justify-content: center;
}

.previous-item {
    object-fit: contain;
}

@media (min-width: 30em) {
    .filepond--item {
        width: calc(50% - 0.5em);
    }
}

@media (min-width: 50em) {
    .filepond--item {
        width: calc(33.33% - 0.5em);
    }
}

.previous-container {
    display: flex;
    min-height: 100px;
    background-color: rgb(249 250 251);
    border: 2px solid rgb(229 231 235);
    border-radius: 0.375rem;
    margin: 0 !important;
    padding: 0 !important;
}

.items-container {
    width: calc(50% - 0.5em);
    display: flex;
    margin: 5px;
}

.image-card {
    width: auto;
    height: 100px;
    position: relative;
    background-color: lightgrey;
    border-radius: 10px;
    overflow: hidden;
}

.image-card img {
    width: 100%;
    height: 100%;
    object-fit: contain; /* Ensure the full image is displayed */
}
</style>
