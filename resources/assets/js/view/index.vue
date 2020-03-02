<template>
    <div>
        <page-header />

        <query-statistics :queries="amountOfQueries" :routes="amountOfRoutes"></query-statistics>
        <nav class="panel">
            <h2 class="panel-heading has-primary-background">
                Queries
            </h2>
            <p class="panel-tabs">
                <a class="is-active">Time</a>
                <a>Routes</a>
            </p>
        </nav>


        <nav class="panel" v-for="(queries, time) in cachedKeys">
            <p class="panel-heading">
                <span>
                    {{new Date(time*1000).toISOString()}} ({{queries.length}})
                </span>
                <span v-on:click="toggleQueryGroup(time)" class="material-icons button is-pulled-right" title="expand">
                    <template v-if="!showQueryGroup(time)">expand_more</template>
                    <template v-if="showQueryGroup(time)">expand_less</template>
                </span>
            </p>
            <div class="columns panel-block is-multiline" v-if="showQueryGroup(time)">
                <div class="column" v-for="(query, key) in queries" >
                    <query-block
                            :sql="query.sql"
                            :time-key="key"
                            :time="+time"
                            :route="query.url"
                            :referer="query.referer"
                            :execution-time="query.time"
                    >
                    </query-block>
                    <query-explain :time-key="key" :time="+time"></query-explain>
                </div>
            </div>
        </nav>

        <page-footer />
    </div>


</template>

<script>
    import queryStatistics from '@/components/query-statistics';
    import queryBlock from '@/components/query-block';
    import queryExplain from '@/components/query-explain';
    import pageHeader from '@/components/page-header';
    import pageFooter from '@/components/page-footer';
    import Axios from 'Axios';


    export default {
        components: {queryStatistics, queryBlock, queryExplain, pageHeader, pageFooter},

        data() {
            return {
                cachedKeys: {},
                showTime: []
            }
        },

        computed: {
            amountOfQueries() {
                return Object.values(this.cachedKeys).flat().length;
            },

            amountOfRoutes() {
                return [...new Set(Object.values(this.cachedKeys).flat().map(val => val.url))].length;
            }
        },

        methods: {
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

            }
        },

        created() {
            this.getQueries();
        }
    }

</script>