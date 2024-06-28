<script setup>
import {Link} from "@inertiajs/vue3";

const props = defineProps({
    links: {
        type: Array
    }
});
</script>

<template>
    <div>
        <Component :is="(link.url) ? Link : 'span'"
                   v-for="(link, index) in links"
                   :key="index"
                   :class="{'page-link-disabled': !link.url, 'page-link-active': link.active}"
                   :href="link.url"
                   class="page-link"
                   v-html="link.label">
        </Component>
    </div>
</template>

<style scoped>
.page-link {
    background: #ffffff;
    border-radius: 4px;
    font-size: 1rem;
    height: 75px;
    margin: 3px;
    min-width: 75px;
    padding: 5px 10px;
    text-decoration: none;
    transition: 0.3s cubic-bezier(0, 0, 0.2, 1);
    width: auto;
    color: #616161 !important;
}

.page-link-active {
    background: #222222;
    color: white !important;
}

.page-link-disabled {
    background: lightgrey !important;
    color: #9e9e9e !important;
}
</style>
