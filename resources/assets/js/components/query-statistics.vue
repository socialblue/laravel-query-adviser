<template>
    <nav class="level is-normal">
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
        <div class="level-item has-text-centered" v-if="firstQueryLoggedDate">
            <div>
                <p>{{firstQueryLoggedDate}}</p>
                <p>{{timeLog}}</p>
            </div>
        </div>
        <div class="level-item level-right">
            <p class="level-item">
                <router-link class="button" :to="{ name: 'session', params: { sessionKey, queries, routes, queryTime, firstQueryLogged, lastQueryLogged}}" v-if="sessionKey"><i class="material-icons">info</i></router-link>
                <router-link class="button" :to="{ name: 'sessions'}" v-else><i class="material-icons">domain</i></router-link>
            </p>
        </div>
    </nav>
</template>

<script>
    export default {
        props: {
            sessionKey: {
                default: () => {
                    return false;
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

            firstQueryLogged: {
                default() {
                    return false;
                }
            },

            lastQueryLogged: {
                default() {
                    return false;
                }
            }

        },

        methods: {
            formatDate(date) {
                return date.toLocaleString('en-us', { weekday: 'short', day: 'numeric', month: 'short', year: '2-digit' });
            }
        },

        filters: {
            round(val) {
                return val.toFixed(2);
            },
        },

        computed: {
            firstQueryLoggedDate() {
                if (!this.firstQueryLogged) {
                    return false;
                }

                return this.formatDate(new Date(this.firstQueryLogged*1000));
            },

            timeLog() {
                if (!this.lastQueryLogged || !this.firstQueryLogged) {
                    return false;
                }

                return `${new Date(this.firstQueryLogged*1000).toLocaleString('en-us', {hour:'2-digit', minute: '2-digit', second: '2-digit', hourCycle: 'h24'})} -
                 ${new Date(this.lastQueryLogged*1000).toLocaleString('en-us', {hour:'2-digit', minute: '2-digit', second: '2-digit', hourCycle: 'h24'})}`
            }
        }
    }
</script>
