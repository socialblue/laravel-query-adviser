<script setup>
import { RouterView } from "vue-router";
import IconSheet from "../modules/default/components/icon-sheet.vue";
import PageFooter from "../modules/default/components/page-footer.vue";

defineProps({
    msg: String,
});

</script>

<template>
    <main class="laravel-query-adviser">
        <router-view  />
        <page-footer />
        <icon-sheet />
    </main>
</template>

<style lang="scss">
    body, html {
        padding: 0;
        margin: 0;
    }

    * {
        box-sizing: border-box;
    }

    .laravel-query-adviser {
        color: #4a4a4a;
        font-size: 1em;
        display: flex;
        font-weight: 400;
        line-height: 1.5;
        font-family: "Roboto Condensed", serif;
        flex-direction: column;
        min-height: 100vh;
        max-height: 100vh;

        .container {
            flex-grow: 1;
            margin: 0 auto;
            position: relative;
            width: auto;
            max-width: 1344px;
            padding: 2px 20px;
        }

        h1 {
            color: #363636;
            font-size: 2rem;
            font-weight: 600;
            line-height: 1.125rem;
        }

        h2 {
            color: #363636;
            font-size: 14px;
            font-weight: 600;
            line-height: 14px;
        }
    }
</style>
