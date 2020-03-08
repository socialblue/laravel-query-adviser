<template>
    <div>
        <page-header />

        <query-statistics :queries="amountOfQueries" :routes="amountOfRoutes" :query-time="totalQueryTime"></query-statistics>
        <nav class="panel is-primary">

            <div class="panel-heading">
                <span>
                    Queries
                </span>
                <span class="material-icons button is-pulled-right" title="clear query cache" v-on:click="clearQueryCache">
                    eject
                </span>

            </div>

            <p class="panel-tabs">
                <a v-on:click="listType = 'time'" :class="{'is-active': (listType == 'time')}">Time</a>
                <a v-on:click="listType = 'routes'" :class="{'is-active': (listType == 'routes')}">Routes</a>
                <a v-on:click="listType = 'rawQueries'" :class="{'is-active': (listType == 'rawQueries')}">Raw queries</a>
            </p>
        </nav>
        <query-execute></query-execute>
        <query-explain></query-explain>

        <nav class="panel" v-for="(queries, key) in dataList">
            <p class="panel-heading">
                <span>
                    {{groupTitle(key)}} ({{queries.length}})
                </span>
                <span v-on:click="toggleQueryGroup(key)" class="material-icons button is-pulled-right" title="expand">
                    <template v-if="!showQueryGroup(key)">expand_more</template>
                    <template v-if="showQueryGroup(key)">expand_less</template>
                </span>
            </p>
            <div class="columns panel-block is-multiline" v-if="showQueryGroup(key)">
                <div class="column" v-for="(query) in queries" >
                    <query-block
                            :query="query"
                    >
                    </query-block>
                </div>
            </div>
        </nav>

        <page-footer />
        <notification />
    </div>


</template>

<script>
    import queryStatistics from '@/components/query-statistics';
    import queryBlock from '@/components/query-block';
    import QueryExecute from '@/components/query-execute';
    import queryExplain from '@/components/query-explain';
    import pageHeader from '@/components/page-header';
    import pageFooter from '@/components/page-footer';
    import notification from '@/components/notification';
    import Axios from 'Axios';


    export default {
        components: {queryStatistics, queryBlock, notification, queryExplain, QueryExecute, pageHeader, pageFooter},

        data() {
            return {
                listType: 'time',
                cachedKeys: {},
                showTime: []
            }
        },

        computed: {
            dataList() {
                if (this.listType === 'routes') {
                    return this.getRouteQueryList;
                } else if (this.listType === 'rawQueries') {
                    return this.getRawQueryList;
                }

                return this.cachedKeys;
            },

            totalQueryTime() {
                return Object.values(this.cachedKeys).flat().reduce((total, time, index) => {
                    if (index === 1) {
                        total = total.queryTime;
                    }

                    return total + time.queryTime;
                });
            },

            amountOfQueries() {
                return Object.values(this.cachedKeys).flat().length;
            },

            amountOfRoutes() {
                return this.getUniqueRoutes.length;
            },

            getUniqueRoutes() {
                return [...new Set(Object.values(this.cachedKeys).flat().map(val => val.url))];
            },

            getUniqueRawSql() {
                return [...new Set(Object.values(this.cachedKeys).flat().map(val => val.rawSql))];
            },

            getRawQueryList() {
                let data = {};

                this.getUniqueRawSql.forEach(rawSql => {
                    data[rawSql] = Object.values(this.cachedKeys).flat().filter(val => val.rawSql === rawSql);
                });

                return data;
            },

            getRouteQueryList() {
                let data = {};

                this.getUniqueRoutes.forEach(route => {
                    data[route] = Object.values(this.cachedKeys).flat().filter(val => val.url === route);
                });

                return data;
            }
        },

        methods: {
            clearQueryCache() {
                Axios.get('/query-adviser/api/query/clear').then((response) => {
                    this.cachedKeys = [];
                    window.EventBus.$emit('show-notification', {message: 'Query cache cleared'});
                });
            },

            getQueries() {
                Axios.get('/query-adviser/api/query/get').then((response) => {
                    this.cachedKeys = response.data;
                });
            },

            showQueryGroup(time) {
                return this.showTime.includes(time);
            },

            toggleQueryGroup(time) {
                if (this.showTime.includes(time)) {
                    this.showTime = this.showTime.filter(val => val !== time);
                    return;
                }
                this.showTime.push(time);
            },

            groupTitle(value) {
                if (this.listType === "time") {
                    return new Date(value * 1000).toISOString();
                }
                return value;
            }
        },

        created() {
            this.getQueries();
        }
    }
</script>