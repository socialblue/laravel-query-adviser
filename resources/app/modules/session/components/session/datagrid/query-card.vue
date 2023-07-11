<template>

    <div class="main-column">
        <div class="code">
            <div>
                <sql-highlight :sql="query.sql" />
            </div>
        </div>
        <div class="information">

            <header>
                <div class="title">
                    {{ new Intl.DateTimeFormat('en-US', {dateStyle: 'long', timeStyle: 'medium', hourCycle: 'h24'}).format(new Date(query.time * 1000))}}
                </div>

                <div class="buttons">
                    <button-icon title="explain query" icon="quiz" @button:click="showExplainDialog" />
                    <button-icon title="execute query" icon="restart_alt" @button:click="showExecuteDialog" />
                </div>
            </header>

            <div class="container-info" >
                <div class="column">
                    <div class="item">
                        <h1>Query Time:</h1>
                        <h2>{{query.queryTime}} ms</h2>
                    </div>
                    <div class="item">
                        <h1>Referer:</h1>
                        <h2>{{query.referer}}</h2>
                    </div>
                    <div class="item">
                        <h1>Url:</h1>
                        <h2>{{ query.url }}</h2>
                    </div>
                    <div v-if="Object.values(query.backtrace)[0]" class="item">
                        <h1>Source:</h1>
                        <h2>
                            {{query.backtrace[0].file}}:{{query.backtrace[0].line}}
                        </h2>
                    </div>
                    <div v-if="Object.values(query.backtrace)[0]" class="item" >
                        <h1>Executed:</h1>
                        <h2>
                        {{query.backtrace[0].model}}::{{query.backtrace[0].function}}
                        </h2>
                    </div>
                </div>

            </div>
        </div>
    </div>


</template>

<script setup>
    import {useRouter} from "vue-router";
    import SqlHighlight from "./query-card/sql-highlight.vue";
    import ButtonIcon from "../../../../default/components/button-icon.vue";

    const router = useRouter();

    const props = defineProps({
        sessionKey: {
            type: String,
            default() {
                return '';
            }
        },

        time: {
            type: Number,
            default() {
                return 0;
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
    })

    function showExplainDialog() {


        router.push({
            name: 'session-query-explain',
            params: {...props}
        });
    }

    function showExecuteDialog() {
        console.log(props);
        router.push({
            name: 'session-query-execute',
            params: {
                sessionKey: props.sessionKey,
                time: props.time,
                timeKey: props.timeKey,
                query: props.query,
            }
        });
    }
</script>


<style lang="scss">

    .main-column {
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
                margin-left: -10px;
                z-index: 1;
            }
        }

        header {
            background: rgba(0, 184, 156, 0.9);
            padding: 4px 10px;
            color: rgba(33,33,33, 0.8);
            font-size: 16px;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;

            .buttons {
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                width: 100px;
                height: 100%;
                overflow: hidden;
                margin-left: -10px;
                z-index: 1;
            }

            border-bottom: 4px solid rgba(233,233,233, 0.7);
        }

        .information {
            display: flex;
            flex-direction: column;
            font-weight: 700;
            vertical-align: center;
            font-size: 20px;
            flex: 1;
            max-width: calc(50vw - 29px);

            border-bottom: 4px solid rgba(233,233,233, 0.7);

            .container-info {
                display: flex;
                flex-direction: column;
                flex: 1;
                overflow: hidden;

                .column {
                    display: flex;
                    h1 {
                        color: rgba(188, 188, 188, 0.9);
                        font-size: 12px;
                        font-weight: 900;
                        margin: 4px 0;
                    }

                    h2 {
                        color: rgba(33, 33, 33, 0.9);
                        font-size: 16px;
                        font-weight: 900;
                        margin: 0;
                    }

                    flex-direction: column;
                    margin: 0;
                    padding: 0;

                    .item {
                        flex-grow: 1;
                        padding: 5px 10px;

                        &:last-child {
                            h2 {
                                margin-bottom: 20px;
                            }
                        }

                    }

                    svg.item {
                        background: rgba(200, 200, 200, 0.9);
                        width: 60px;
                        height: 60px;
                        border-radius: 30px;
                        border: 3px solid rgba(33, 33, 33, .9);
                        transition: all .3s;
                        z-index: 1;
                        &:hover {
                            transform: scale(1.5);
                            border: 1px solid rgba(66, 66, 66, .9);
                            background: rgba(222, 222, 222, 0.9);
                        }
                    }


                }
            }
        }
    }



</style>
