<template>
    <div>
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
    </div>
</template>

<script>
    import session from '@/components/session';
    import pageHeader from '@/components/page-header';
    import pageFooter from '@/components/page-footer';

    export default {
        components: {pageHeader, pageFooter, session}
    }
</script>