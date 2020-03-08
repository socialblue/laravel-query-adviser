<template>
    <nav class="level">
        <div class="level-item has-text-centered">
            <div>
                <p class="heading">Queries</p>
                <p class="title">{{queries}}</p>
            </div>
        </div>
        <div class="level-item has-text-centered">
            <div>
                <p class="heading">Routes</p>
                <p class="title">{{routes}}</p>
            </div>
        </div>
        <div class="level-item has-text-centered">
            <div>
                <p class="heading">Total Query time</p>
                <p class="title">{{queryTime | round}} ms</p>
            </div>
        </div>
        <div class="level-item has-text-centered">
            <div>
                <p class="heading">AVG Query time</p>
                <p class="title">{{queryTime / queries | round}} ms</p>
            </div>
        </div>
    </nav>
</template>

<script>
    export default {
        props: {
            queries: {
                type: Number,
                default() {
                    return 0;
                }
            },

            routes: {
                type: Number,
                default() {
                    return 0;
                }
            },

            queryTime: {
                type: Number,
                default() {
                    return 0;
                }
            }
        },

        filters: {
            round(val) {
                return Math.round(val, 2);
            }
        }
    }
</script>