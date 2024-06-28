<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import {computed} from "vue";
import {Link, usePage} from "@inertiajs/vue3";

const page = usePage();
const cssProps = computed(() => {
    return {
        '--primary': page.props.settings.primaryColor,
        '--secondary': page.props.settings.secondaryColor,
        '--accent': page.props.settings.accentColor,
    }
});
</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100"
         :style="cssProps">
        <div>
            <Link href="/">
                <ApplicationLogo class="text-6xl fill-current text-gray-500"/>
            </Link>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <slot/>
        </div>
    </div>
</template>

<style>
:root {
    --primary: var(--primary);
    --secondary: var(--secondary);
    --accent: var(--accent);
}

.icon {
    width: 1em;
    height: 1em;
    vertical-align: -0.125em;
}
</style>
