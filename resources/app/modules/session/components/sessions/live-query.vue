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

        errorInfo: {
            type: Array,
            default: () => null,
        },

        sql: {
            type: String,
            default: () => '',
        },
    });

</script>

<template>
    <div :class="['live-query', {'has-error': !!props.errorInfo}]" >
        <header>
            <h1>{{ props.route }}</h1>
            <summary v-if="!!props.errorInfo">
                <div class="material-icons">warning</div>
                {{ props.errorInfo[2] }}
            </summary>
            <summary v-else>{{ props.time }} ms</summary>
        </header>
        <section>
            <sql-highlight :sql="props.sql" />
        </section>
    </div>
</template>

<style lang="scss">
    .live-query {
        &.has-error {
            > header {
                > h1 {
                    color: #cc9600e6;
                }
                > summary {
                    color: #cc9600e6;
                    > .material-icons {
                        font-size: 10px;
                        color: #cc9600e6;
                    }
                }
            }
        }

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
