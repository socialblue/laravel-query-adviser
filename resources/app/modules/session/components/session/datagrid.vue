<template>
    <div class="datagrid">
        <div class="row" v-for="key in dataListKey" >
            <div :class="['header', {'active': showQueryGroup(key)}]" @click="toggleQueryGroup(key)">
                <div title="show queries" :class="['group-action', {'arrow-open': showQueryGroup(key)}]">
                    <svg><use xlink:href="#triangle" href="#triangle"></use></svg>
                </div>
                <div class="number-of-queries">
                    {{ dataList[key].length }}
                </div>

                <div class="group-title">
                    {{ groupTitle(key) }}
                </div>
            </div>
            <div class="queries" v-if="showQueryGroup(key)">
                <query-card :session-key="sessionKey" :time="query.time" :time-key="queryId" :query="query" v-for="(query, queryId) in dataList[key]" />
            </div>
        </div>
    </div>
</template>

<script setup>
    import QueryCard from "./datagrid/query-card.vue";
    import {reactive} from "vue";

    const props = defineProps({
        sessionKey: {
            type: String,
            default: () => '',
        },

        dataListKey: {
            type: Array,
            default: () => [],
        },

        dataList: {
            type: Object,
            default: () => {
            },
        },

        listType: {
            type: String,
            default: () => 'time',
        },
    });

    const data = reactive({
        showTime: [],
    });

    function showQueryGroup(time) {
        return data.showTime.includes(time);
    }

    function toggleQueryGroup(time) {
        if (showQueryGroup(time)) {
            data.showTime = data.showTime.filter(val => val !== time);
            return;
        }
        data.showTime.push(time);
    }

    function groupTitle(value) {
        if (props.listType === 'time') {
            return new Intl.DateTimeFormat('en-US', {dateStyle: 'full', timeStyle: 'medium', hourCycle: 'h24'}).format(new Date(value * 1000));
        }
        return value;
    }

</script>

<style lang="scss">
    .datagrid {
        display: flex;
        flex-direction: column;
        overflow: scroll;
        max-height: calc(100vh - 210px);

        .row {
            display: flex;
            flex-direction: column;
            flex-grow: 1;

            .header {
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: flex-start;
                padding: 4px 8px;
                border-bottom: 2px solid  rgba(233, 233, 233, 0.9);
                transition: all .3s ease-in-out;



                &:hover:not(.active) {
                    background: #eee;
                    cursor: pointer;
                    border-bottom: 2px solid rgba(33,180,180, .5);
                }

                &.active {
                    background: #eee;
                    border-bottom: 2px solid rgba(33,180,180,.9);
                    font-weight: 900;
                }

                .group-action {
                    min-width: 20px;

                    svg {
                        width: 14px;
                        height: 14px;
                        transition: all .3s;

                        &:hover {
                            cursor: pointer;
                        }
                    }

                    &.arrow-open {
                        svg {
                            transform: rotate(90deg);
                        }
                    }


                }

                .number-of-queries {
                    min-width: 40px;
                    text-align: center;
                }

                .group-title {
                    font-size: 12px;
                    font-weight: 700;
                    flex-grow: 1;
                    text-align: left;
                    padding-left: 8px;

                    text-overflow: ellipsis;
                    overflow: hidden;
                    max-height: 20px;
                }
            }


        }

        .button {
            svg {
                width: 20px;
                height: 20px;
                transition: all 0.3s;
            }

            &:hover {

                svg {
                    transform: scale(2);
                }
            }
        }

        .queries {
            td {
                padding: 0;

                table {
                    min-width: 100%;
                    border-spacing: 0;
                    text-align: left;
                    margin-left: 4px;
                    border-left: 4px solid rgb(58, 184, 157);
                }
            }
        }

    }
</style>
