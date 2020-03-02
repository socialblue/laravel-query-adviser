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
            }
        }
    }
</script>