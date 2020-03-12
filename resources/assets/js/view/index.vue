<template>
    <div>
        <page-header />
        <query-execute></query-execute>
        <query-explain></query-explain>
        <query-statistics :queries="amountOfQueries" :routes="amountOfRoutes" :query-time="totalQueryTime"></query-statistics>
        <div class="tile is-parent is-paddingless">
            <main class="is-vertical tile">
                <nav class="panel is-primary">
                    <div class="panel-heading">
                        <span>
                            Queries
                        </span>
                        <span class="material-icons button is-pulled-right" title="show filter menu" v-on:click="showFilterMenu">
                            filter_list
                        </span>
                        <span class="material-icons button is-pulled-right" title="clear query cache" v-on:click="clearQueryCache">
                            eject
                        </span>
                    </div>

                    <p class="panel-tabs">
                        <a v-on:click="listType = 'time'" :class="{'is-active': (listType == 'time')}">Time</a>
                        <a v-on:click="listType = 'url'" :class="{'is-active': (listType == 'url')}">Routes</a>
                        <a v-on:click="listType = 'referer'" :class="{'is-active': (listType == 'referer')}">Referer</a>
                        <a v-on:click="listType = 'rawSql'" :class="{'is-active': (listType == 'rawSql')}">Raw queries</a>
                        <a v-on:click="listType = 'sql'" :class="{'is-active': (listType == 'sql')}">Queries with bindings</a>
                        <a v-on:click="listType = 'queryTime'" :class="{'is-active': (listType == 'queryTime')}">Query time</a>
                    </p>
                </nav>


                <div class="timeline">
                    <header class="timeline-header">
                        <span class="tag is-medium is-primary">Start</span>
                    </header>

                    <div class="timeline-item is-primary" v-for="key in dataListKey">
                        <div class="timeline-marker is-icon button is-info" v-on:click="toggleQueryGroup(key)">
                            <span class="material-icons" title="expand">
                                    <template v-if="!showQueryGroup(key)">expand_more</template>
                                    <template v-if="showQueryGroup(key)">expand_less</template>
                            </span>
                        </div>
                        <div class="timeline-content">
                            <p class="heading">{{groupTitle(key)}} ({{dataList[key].length}})

                            </p>
                            <div>
                                <div class="columns is-multiline" v-if="showQueryGroup(key)">
                                    <div class="column" v-for="query in dataList[key]" >
                                        <query-block
                                                :query="query"
                                        >
                                        </query-block>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <header class="timeline-header">
                        <span class="tag is-medium is-primary">End</span>
                    </header>

                </div>
            </main>
        </div>
        <side-panel :sort-field.sync="sortKey"/>
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
    import sidePanel from '@/components/side-panel';
    import notification from '@/components/notification';
    import Axios from 'Axios';


    export default {
        components: {queryStatistics, sidePanel, queryBlock, notification, queryExplain, QueryExecute, pageHeader, pageFooter},

        data() {
            return {
                sortKey: 'time',
                sortDirection: 1,
                listType: 'time',
                cachedKeys: {},
                showTime: []
            }
        },

        computed: {
            dataList() {
                return this.groupValuesByKey(this.listType);
            },

            dataListKey() {
                let list = this.dataList;

                let sortClosure = (a, b) => {
                    if (list[a][0][this.sortKey] === list[b][0][this.sortKey]) {
                        return 0;
                    } else if(list[a][0][this.sortKey] > list[b][0][this.sortKey]) {
                        return -1 * this.sortDirection
                    }
                    return this.sortDirection;
                };

                if (this.sortKey === 'amount') {
                    sortClosure = (a, b) => {
                        if (list[a].length === list[b].length) {
                            return 0;
                        } else if(list[a].length > list[b].length) {
                            return -1 * this.sortDirection;
                        }
                        return this.sortDirection;
                    }
                }

                return Object.keys(list).sort(sortClosure);
            },

            flattenedCachedKeys() {
                return Object.values(this.cachedKeys).flat();
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

            getUniqueRoutes() {
                return this.getUniqueValuesByKey('url');
            },

            getUniqueRawSql() {
                return this.getUniqueValuesByKey('rawSql');
            },

            getRawQueryList() {
                return this.groupValuesByKey('rawSql');
            },

            getRouteQueryList() {
                return this.groupValuesByKey('url');
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
            },

            getUniqueValuesByKey(key) {
                return [...new Set(this.flattenedCachedKeys.map(val => val[key]))];
            },

            groupValuesByKey(key) {

                let sortClosure = (a, b) => {
                    if (a[this.sortKey] === b[this.sortKey]) {
                        return 0;
                    } else if(a[this.sortKey] > b[this.sortKey]) {
                        return -1 * this.sortDirection
                    }
                    return this.sortDirection;
                };

                if (this.sortKey === 'amount') {
                    sortClosure = (a, b) => {
                        return 0;
                    }
                }

                let data = {};
                this.getUniqueValuesByKey(key).forEach((uniqueValue) => {
                    data[uniqueValue] = this.flattenedCachedKeys
                        .filter(row => row[key] === uniqueValue)
                        .sort(
                            sortClosure
                        );
                });

                return data;
            },

            showFilterMenu() {
                window.EventBus.$emit('show-filter-bar');
            }
        },

        created() {
            this.getQueries();
        }
    }
</script>