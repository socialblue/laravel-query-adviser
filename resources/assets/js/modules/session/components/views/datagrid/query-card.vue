<template>
    <div>
        <div class="main-column">
            <div class="options">
                <div class="buttons">
                    <div class="button" title="explain query" v-on:click="showExplainDialog">
                        <svg><use xlink:href="#sql-time" href="#explain"></use></svg>
                    </div>
                    <div class="button" title="execute query" v-on:click="showExecuteDialog">
                        <svg><use xlink:href="#sql-time" href="#execute"></use></svg>
                    </div>
                    <div class="button" title="copy query to clipboard" v-clipboard="format(query.sql)" v-clipboard:success="clipboardSuccess">
                        <svg><use xlink:href="#sql-time" href="#copy"></use></svg>
                    </div>
                </div>
            </div>
            <div class="code">
                <div>
                    <sql-highlight :sql="query.sql" />
                </div>
            </div>
            <div class="information">
                <div>
                    <header>
                        {{ new Intl.DateTimeFormat('en-US', {dateStyle: 'long', timeStyle: 'medium', hourCycle: 'h24'}).format(new Date(query.time * 1000))}}
                    </header>

                    <div class="container-info" >
                        <div class="column">
                            <svg class="item"><use href="#sql-time"></use></svg>
                            <svg class="item"><use href="#referrer"></use></svg>
                            <svg class="item"><use href="#url"></use></svg>
                            <svg class="item" v-if="Object.values(query.backtrace)[0]"><use href="#src"></use></svg>
                            <svg class="item" v-if="Object.values(query.backtrace)[0]"><use href="#exec"></use></svg>
                        </div>
                        <div class="column" >
                            <div class="item">
                                {{query.queryTime}} ms
                            </div>
                            <div class="item">
                                {{ query.referer }}
                            </div>
                            <div class="item">
                                {{ query.url }}
                            </div>

                            <div v-if="Object.values(query.backtrace)[0]" class="item">
                                {{query.backtrace[0].file}}:{{query.backtrace[0].line}}
                            </div>

                            <div v-if="Object.values(query.backtrace)[0]" class="item" >
                                {{query.backtrace[0].model}}::{{query.backtrace[0].function}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import {format} from "sql-formatter";
    import SqlHighlight from "../../../../../components/sql-highlight";

    export default {
        components: {SqlHighlight},
        props: {
            sessionKey: {
                type: String,
                default() {
                    return '';
                }
            },

            timeKey: {
                type: Number,
                default() {
                    return 0;
                }
            },

            query: {
                type: Object,
                default() {
                    return {
                        time: (new Date().getTime()/1000),
                        sql: '',
                        referer: '',
                        url: '',
                        queryTime: 0,
                        backtrace: [],
                    }
                }
            }
        },

        methods: {
            showExplainDialog() {
                this.$router.push({name: 'session-query-explain', params: {
                        sessionKey: this.sessionKey,
                        time: this.query.time,
                        timeKey: this.query.timeKey,
                    }});
            },

            showExecuteDialog() {
                this.$router.push({name: 'session-query-execute', params: {
                        sessionKey: this.sessionKey,
                        time: this.query.time,
                        timeKey: this.query.timeKey,
                        sql: this.query.sql
                    }});
            },

            clipboardSuccess() {
                window.EventBus.$emit('show-notification', {message: 'Query is copied to your clipboard'});
            },

            format(sql) {
                return format(`${sql};`, {
                    language: 'mysql',
                    keywordCase: "upper",
                });
            }
        }
    }
</script>


<style lang="scss">

    .main-column {
        min-width: 100vw;
        display: flex;
        flex-wrap: nowrap;
        flex-direction: row;
        align-items: stretch;
        align-content: stretch;
        margin-left: 10px;
        border-left: 4px solid #3ab89d;

        .code {
            min-width: 50vw;
            max-width: 50vw;
            background: #000000;
            border-bottom: 4px solid rgba(233,233,233, 0.7);
            flex: 1;
        }

        .options {
            min-width: 36px;
            min-height: 40px;
            flex: 0;
            align-items: stretch;
            align-content: stretch;

            .buttons {
                width: 64px;
                height: calc(48 * 4px);
                overflow: hidden;
                margin-top: -5px;
                z-index: 1;
            }
        }

        header {
            background: rgba(0, 184, 156, 0.9);
            padding: 4px 10px;
            color: rgba(33,33,33, 0.8);
            font-size: 28px;
            text-decoration: none;

            border-bottom: 4px solid rgba(233,233,233, 0.7);
        }

        .information {
            font-weight: 700;
            vertical-align: center;
            font-size: 20px;
            flex: 1;

            border-bottom: 4px solid rgba(233,233,233, 0.7);

            .container-info {
                text-decoration: underline;
                display: flex;
                flex-direction: row;
                justify-content: flex-start;
                align-items: stretch;
                align-content: stretch;
                margin: 0 0 0 -20px;

                &:nth-child(1) {
                    width: 40px;
                }

                &:nth-child(2) {
                    width: 25vw;
                }

                .column {
                    display: flex;
                    flex-direction: column;
                    margin: 0;
                    padding: 0;

                    .item {
                        padding: 5px 10px;
                        margin: 10px;
                    }

                    svg.item {
                        background: rgba(200, 200, 200, 0.9);
                        width: 60px;
                        height: 60px;
                        border-radius: 30px;
                        border: 3px solid rgba(33, 33, 33, .9);
                        transition: all .3s;
                        &:hover {
                            transform: scale(1.5);
                            border: 1px solid rgba(66, 66, 66, .9);
                            background: rgba(222, 222, 222, 0.9);
                        }
                    }

                    div.item {
                        height: 60px;
                        text-overflow: ellipsis;
                        overflow: hidden;
                        white-space: nowrap;
                        vertical-align: middle;
                        line-height: 46px;
                    }
                }
            }
        }
    }

    .group-action {
        svg {
            width: 14px;
            height: 14px;
            transition: all .3s;
        }

        &.arrow-open {
            svg {
                transform: rotate(90deg);
            }
        }
    }

</style>
