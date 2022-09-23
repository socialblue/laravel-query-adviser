<template>
    <div class="card">
        <a v-on:click.prevent="openSession">
            <div class="card-header">
                Session {{dateTime.toISOString()}}
            </div>
            <div class="card-content">
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
            </div>
        </a>
    </div>
</template>

<script>
    export default {

        props: {
            id: {
                type: Number,
                default() {
                    return 0;
                }
            },

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
            },

            dateTime: {
                type: Date,
                default() {
                    return new Date();
                }
            }
        },

        methods: {
            openSession() {
                this.$router.push({name: 'session', params: {sessionKey: this.id}});
            }
        }
    }
</script>

