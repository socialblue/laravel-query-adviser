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
                <router-link class="button" :to="{ name: 'session-export', params: { sessionKey }}" v-if="sessionKey"><i class="material-icons">file_download</i></router-link>
                <router-link class="button" :to="{ name: 'sessions'}"  v-if="!sessionKey"><i class="material-icons">domain</i></router-link>
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
            },

            export() {
                // should be vue-router link?
                window.location.href = `/laravel-query-adviser/api/session/export?session-key=${this.sessionKey}`;
            },

            getUniqueValuesByKey(key) {
                return [...new Set(this.flattenedCachedKeys.map(val => val[key]))];
            },
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
            },

            totalQueryTime() {
                if (this.flattenedCachedKeys.length === 0) {
                    return 0;
                }

                return this.flattenedCachedKeys.reduce((total, time, index) => {
                    if (index === 1) {
                        total = total.queryTime;
                    }
                    return total + time.queryTime;
                });
            },

            amountOfQueries() {
                return this.flattenedCachedKeys.length;
            },

            amountOfRoutes() {
                return this.getUniqueRoutes.length;
            },
        }
    }
</script>
