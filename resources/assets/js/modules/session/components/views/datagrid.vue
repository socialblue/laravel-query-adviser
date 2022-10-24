<template>
    <div class="datagrid">
        <table>
            <template v-for="key in dataListKey">
                <thead>
                    <tr>
                        <th v-on:click="toggleQueryGroup(key)" :class="['group-action', {'arrow-open': showQueryGroup(key)}]"><svg><use xlink:href="#triangle" href="#triangle"></use></svg></th>
                        <th>{{dataList[key].length}}</th>
                        <th style="max-width: 75vw; text-overflow: ellipsis; overflow: hidden; white-space: nowrap; padding: 4px 10px;">{{ groupTitle(key) }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="showQueryGroup(key)" class="queries">
                        <td colspan="4" >
                            <div class="main-row">
                                <query-card :session-key="sessionKey" :query="query" :key="queryId" v-for="(query, queryId) in dataList[key]" />
                            </div>
                        </td>
                    </tr>
                </tbody>
            </template>
        </table>
    </div>
</template>

<script>
import SqlHighlight from "../../../../components/sql-highlight";
import QueryCard from "./datagrid/query-card";
export default {
    name: "Datagrid",
    components: {QueryCard, SqlHighlight},
    props: {
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
            default: () => 'Time',
        },
    },

    data() {
        return {
            showTime: [],
        }
    },

    methods: {
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
            if (this.listType === 'time') {
                return new Intl.DateTimeFormat('en-US', {dateStyle: 'full', timeStyle: 'medium', hourCycle: 'h24'}).format(new Date(value * 1000));
            }
            return value;
        },
    }
}
</script>

<style lang="scss">
    .datagrid {
        min-width: 100vw;

        table {
            min-width: 100vw;
        }

        thead {
            tr {
                border-bottom: 1px solid #333333;
            }
        }

        td, th {
            padding: 4px 8px;
        }

        td {
            .main-row {
                display: flex;
                flex-direction: column;
            }
        }

        tr {
            &:hover, &.queries {
                background: rgba(160, 160, 160, 0.9);
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
