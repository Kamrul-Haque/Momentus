<script setup>
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import SideBarLinks from "@/Components/SideBarLinks.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import {computed, ref} from "vue";
import {usePage} from "@inertiajs/vue3";

const page = usePage();
let showSideBar = ref(screen.width >= 640)
const cssProps = computed(() => {
    return {
        '--primary': page.props.settings.primaryColor,
        '--secondary': page.props.settings.secondaryColor,
        '--accent': page.props.settings.accentColor,
        '--background': page.props.settings.backgroundColor,
        '--table-stripe-color': page.props.settings.tableStripeColor,
    }
})
</script>

<template>
    <div class="app"
         :style="cssProps">
        <header class="header">
            <div class="brand">
                <img v-if="page.props.settings.portal_logo"
                     :src="page.props.settings.portal_logo"
                     class="avatar"
                     alt="logo"
                     width="50"
                     height="50"/>

                <h1 class="brand-title">
                    {{ page.props.settings.name }}
                </h1>
            </div>
            <div class="nav">
                <button @click="showSideBar = !showSideBar"
                        class="mx-4 hover:text-white active:text-white transition ease-in-out duration-150">
                    <span v-if="showSideBar"
                          class="mdi mdi-chevron-left-box text-2xl"></span>
                    <span v-else
                          class="mdi mdi-chevron-right-box text-2xl"></span>
                </button>

                <img v-if="page.props.settings.portal_logo"
                     :src="page.props.settings.portal_logo"
                     class="avatar md:hidden"
                     alt="logo"
                     width="50"
                     height="50"/>

                <Dropdown>
                    <template #trigger>
                        <span class="inline-flex rounded-md">
                            <button type="button"
                                    class="header-link">
                                {{ page.props.auth.user.name }}

                                <span class="mdi mdi-chevron-down ml-1"></span>
                            </button>
                        </span>
                    </template>

                    <template #content>
                        <DropdownLink :href="route('profile.edit')">
                            <span class="mdi mdi-account"></span>
                            Profile
                        </DropdownLink>

                        <DropdownLink as="Link"
                                      method="post"
                                      :href="route('logout')">
                            <span class="mdi mdi-power"></span>
                            Log Out
                        </DropdownLink>
                    </template>
                </Dropdown>
            </div>
        </header>
        <nav :class="[showSideBar ? 'w-64' : 'w-0 md:w-[75px]']"
             class="side-nav">
            <div class="overflow-y-auto overflow-x-hidden">
                <SideBarLinks :show-side-bar="showSideBar"/>
            </div>
        </nav>
        <div class="content"
             :class="[showSideBar ? 'md:pl-[256px]' : 'md:pl-20']">
            <main class="main">
                <div class="container">
                    <FlashMessage/>

                    <slot/>
                </div>
            </main>
        </div>
        <footer class="footer"
                :class="[showSideBar ? 'pl-64' : 'pl-0 md:pl-[75px]']">
            {{ page.props.settings.copyright_text }}
            Developed by
            <a href="https://www.linkedin.com/in/utchas/"
               target="_blank"
               class="text-accent hover:text-blue-900">
                Kamrul Haque
            </a>
        </footer>
    </div>
</template>

<style>
:root {
    --primary: var(--primary);
    --secondary: var(--secondary);
    --accent: var(--accent);
    --background: var(--background);
    --table-stripe-color: var(--table-stripe-color);
}

select option {
    text-transform: capitalize;
}
</style>
