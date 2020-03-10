<script>
    import highlight from "../mixin/hightlight";

    export default {
        props: {
            query: {
                type: Object
            }
        },

        methods: {
            showExplainDialog() {
                window.EventBus.$emit('show-explain-dialog', {
                    time: this.query.time,
                    timeKey: this.query.timeKey,
                })
            },

            showExecuteDialog() {
                window.EventBus.$emit('show-execute-dialog', {
                    time: this.query.time,
                    timeKey: this.query.timeKey,
                    sql: this.query.sql
                })
            },

            clipboardSuccess() {
                window.EventBus.$emit('show-notification', {message: 'Query is copied to your clipboard'});
            }
        },

        data: () => {
            return {
                showNotification: false,
                showContent: true,
            }
        },

        computed: {
            execUrl() {
                return `/query-adviser/api/query/exec/?time=${this.query.time}&time-key=${this.query.timeKey}`;
            },

            dateTime() {
                return (new Date(this.query.time*1000)).toISOString();
            }
        },


        mixins: [highlight]

    }
</script>

<template>
    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                {{query.url}} ({{query.referer}})
            </p>
            <span v-on:click="showContent = !showContent" class="material-icons button is-pulled-right" title="expand">
                    <template v-if="!showContent">expand_more</template>
                    <template v-if="showContent">expand_less</template>
            </span>
        </header>
        <div class="card-content" v-if="showContent">
            <div class="content">
                <div class="field is-grouped is-grouped-multiline">

                    <div class="control">
                        <div class="tags has-addons">
                            <span class="tag">time</span>
                            <span class="tag is-primary">{{query.queryTime}} ms</span>
                        </div>
                    </div>
                    <template v-if="Object.values(query.backtrace)[0]">

                        <div class="control">
                            <div class="tags has-addons">
                                <span class="tag">file</span>
                                <span class="tag is-primary">
                                    {{Object.values(query.backtrace)[0].file}}:{{Object.values(query.backtrace)[0].line}}
                                </span>
                            </div>
                        </div>

                        <div class="control" v-if="Object.values(query.backtrace)[0].model">
                            <div class="tags has-addons">
                                <span class="tag">class</span>
                                <span class="tag is-primary">{{Object.values(query.backtrace)[0].model}}</span>
                            </div>
                        </div>
                        <!-- phpstorm://open?url=file:///{filename}&line={line}-->
                        <div class="control">
                            <div class="tags has-addons">
                                <span class="tag">function</span>
                                <span class="tag is-primary">{{Object.values(query.backtrace)[0].function}}</span>
                            </div>
                        </div>
                    </template>
                </div>
                <pre class="highlight" ref="sqlcode">{{prettyPrint(query.sql)}}</pre>
                <time :datetime="dateTime">{{ dateTime }}</time>

            </div>
        </div>
        <footer class="card-footer">
            <a v-on:click="showExecuteDialog" class="card-footer-item" title="execute sql">
                <span class="material-icons button is-small">
                    replay
                </span>
            </a>
            <a class="card-footer-item" title="explain sql" v-on:click="showExplainDialog">
                <span class="material-icons button is-small" >
                    info
                </span>
            </a>

            <a class="card-footer-item" title="copy sql" v-clipboard="format(query.sql)" v-clipboard:success="clipboardSuccess">
                <span class="material-icons button is-small">
                    file_copy
                </span>
            </a>
        </footer>
    </div>
</template>