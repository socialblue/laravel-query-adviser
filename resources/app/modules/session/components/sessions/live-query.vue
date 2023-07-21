<script setup>
    import SqlHighlight from "../session/datagrid/query-card/sql-highlight.vue";

    const props = defineProps({
        route: {
            type: String,
            default: () => '',
        },

        time: {
            type: Number,
            default: () => 0,
        },

        sql: {
            type: String,
            default: () => '',
        },
    });
</script>

<template>
    <div class="live-query">
        <header>
            <h1>{{ props.route }}</h1>
            <summary>{{ props.time }} ms</summary>
        </header>
        <section>
            <sql-highlight :sql="props.sql" />
        </section>
    </div>
</template>

<style lang="scss">
    .live-query {
        > header {
            > h1 {
                margin: 16px 0 3px;
                font-size: 18px;
            }

            > summary {
                font-size: 10px;
                color: rgba(180, 180, 180, .9);
            }
        }
    }
</style>
