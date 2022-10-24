<template>
    <div>
        <router-view name="dialog" />

        <!-- should be own component -->
        <div class="tile is-parent is-paddingless">
            <main class="is-vertical tile">
                <nav class="panel is-primary">
                    <div class="panel-heading">
                        <span>
                            Queries
                        </span>
                        <router-link tag="span" class="button is-pulled-right is-primary is-text material-icons is-small" :to="{ name: 'sessions'}">close</router-link>

                        <span class="material-icons button is-text is-primary is-pulled-right is-small" title="show filter menu" v-on:click="showFilterMenu">
                            sort
                        </span>

                        <router-link tag="span" title="download session file" class="material-icons button is-text is-primary is-pulled-right is-small" :to="{ name: 'download-session'}">
                            download
                        </router-link>

                        <span class="material-icons button is-text is-primary is-pulled-right is-small" title="clear query cache" v-on:click="clearQueryCache">
                            delete
                        </span>
                    </div>

                    <p class="panel-tabs">
                        <a v-on:click="listType = 'time'" :class="{'is-active': (listType === 'time')}">Time</a>
                        <a v-on:click="listType = 'url'" :class="{'is-active': (listType === 'url')}">Routes</a>
                        <a v-on:click="listType = 'referer'" :class="{'is-active': (listType === 'referer')}">Referer</a>
                        <a v-on:click="listType = 'rawSql'" :class="{'is-active': (listType === 'rawSql')}">Raw queries</a>
                        <a v-on:click="listType = 'sql'" :class="{'is-active': (listType === 'sql')}">Queries with bindings</a>
                        <a v-on:click="listType = 'queryTime'" :class="{'is-active': (listType === 'queryTime')}">Query time</a>
                    </p>
                </nav>

                <!-- should contain it's own helpers -->
                <query-statistics v-if="!loading" :queries="sessionSummary.queries" :routes="sessionSummary.routes" :query-time="sessionSummary.queryTime"  />

                <div class="container" v-if="loading">
                    <div class="button is-primary is-text is-large is-loading" />
                    <h1 class="title">
                        Loading..
                    </h1>
                </div>

                <datagrid
                    v-else
                    :data-list="dataList"
                    :session-key="sessionKey"
                    :data-list-key="dataListKey"
                    :list-type="listType"
                />
            </main>
        </div>
        <router-view name="sidePanelLeft" :sort-field.sync="sortKey" />
    </div>
</template>

<script>
    import pageFooter from '../../../components/page-footer';
    import queryBlock from '../../query/components/query-block';
    import queryStatistics from '../../../components/query-statistics';
    import {clear, show} from "../api/sessionApi";
    import Datagrid from "../components/views/datagrid";

    export default {
        components: {Datagrid, pageFooter, queryBlock, queryStatistics},

        props: {
            sessionKey: {

            },

        },


        data() {
            return {
                sortKey: 'time',
                sortDirection: 1,
                listType: 'time',
                sessionData: {},
                sessionSummary: {},
                loading: true,
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
                return Object.values(this.sessionData).flat();
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
                clear().finally(() => {
                    this.$router.push({name: 'sessions'});
                });
            },

            getQueries() {
                this.loading = true;
                show(this.sessionKey).then((cachedKeys) => {
                    this.sessionData = cachedKeys['data'] ?? [];
                    this.sessionSummary = cachedKeys['summary'] ?? {};
                }).finally(() => {
                    this.loading = false;
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
                    sortClosure = () => {
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
                this.$router.push({name: 'session-order-menu'})
            }
        },

        created() {
            this.getQueries();
        }

    }
</script>
