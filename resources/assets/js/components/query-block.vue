<script>
    import highlight from "../mixin/hightlight";

    export default {
        props: {
            sql: {
                type: String
            },

            route: {
                type: String
            },

            time: {
                type: Number
            },

            timeKey: {
                type: Number
            },

            executionTime: {
                type: Number
            }
        },

        methods: {
            showExplainDialog() {
                window.EventBus.$emit('show-explain-dialog', {
                    time: this.time,
                    timeKey: this.timeKey,
                })
            },

            showExecuteDialog() {
                window.EventBus.$emit('show-execute-dialog', {
                    time: this.time,
                    timeKey: this.timeKey,
                    sql: this.sql
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
                return `/query-adviser/api/query/exec/?time=${this.time}&time-key=${this.timeKey}`;
            },

            dateTime() {
                return (new Date(this.time*1000)).toISOString();
            }
        },


        mixins: [highlight]

    }
</script>

<template>
    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                {{route}}
            </p>
            <span v-on:click="showContent = !showContent" class="material-icons button is-pulled-right" title="expand">
                    <template v-if="!showContent">expand_more</template>
                    <template v-if="showContent">expand_less</template>
            </span>
        </header>
        <div class="card-content" v-if="showContent">
            <div class="content">
                <div class="tags has-addons">
                    <span class="tag">time</span>
                    <span class="tag is-primary">{{executionTime}} ms</span>
                </div>
                <pre class="highlight" ref="sqlcode">{{prettyPrint(sql)}}</pre>
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

            <a class="card-footer-item" title="copy sql" v-clipboard="format(sql)" v-clipboard:success="clipboardSuccess">
                <span class="material-icons button is-small">
                    file_copy
                </span>
            </a>
        </footer>
    </div>
</template>