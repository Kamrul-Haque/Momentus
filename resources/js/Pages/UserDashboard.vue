<script setup>
import Auth from "@/Layouts/Auth.vue";
import {Head} from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard"/>

    <Auth>
        <div class="card">
            <div class="card-body">You're logged in!</div>
        </div>
    </Auth>
</template>
