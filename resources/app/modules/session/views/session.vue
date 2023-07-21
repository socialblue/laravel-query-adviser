<template>
    <div class="session">
        <router-view name="dialog" />
        <div>
            <page-header name="Queries in session">
                <template #buttons>
                    <button-icon icon="sort" @button:click="sortMenu" title="sort queries" />
                    <button-icon icon="close" @button:click="close" title="clear session cache" />
                </template>
            </page-header>
            <session-row v-bind="data.sessionSummary" :session-key="sessionKey" />
            <lqa-tabs v-model:active-tab="data.listType" :tabs="tabs" />
            <data-grid
                :data-list="dataList"
                :session-key="sessionKey"
                :data-list-key="dataListKey"
                :list-type="data.listType"
            />
        </div>
        <router-view name="sidePanelLeft" :sort-field="data.sortKey" @update:sort-field="setSortField" />
    </div>
</template>

<script setup>
    import {clear, show} from "../api/session-api";
    import DataGrid from "../components/session/datagrid.vue";
    import SessionRow from "../components/sessions/session-row.vue";
    import {onMounted, computed, reactive} from "vue";
    import {RouterView, useRouter} from "vue-router";
    import PageHeader from "../../default/components/page-header.vue";
    import ButtonIcon from "../../default/components/button-icon.vue";
    import lqaTabs from "../../default/components/tabs.vue";

    const router = useRouter();

    const props = defineProps({
        sessionKey: {
            type: String,
            required: true
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
        }
    });

    //data
    const data = reactive({
        sortKey: 'time',
        sortDirection: 1,
        listType: 'time',
        sessionData: {},
        sessionSummary: {},
        loading: true,
    });

    const tabs = {
        time: {
            name: 'time',
            label: 'Time',
        },
        url: {
            name: 'url',
            label: 'Routes',
        },
        referer: {
            name: 'referer',
            label: 'Referer',
        },
        rawSql: {
            name: 'rawSql',
            label: 'Queries',
        },
        sql: {
            name: 'sql',
            label: 'Queries with bindings',
        },
        queryTime: {
            name: 'queryTime',
            label: 'Query time',
        },
    };


    function clearQueryCache() {
        clear().finally(() => {
            router.push({name: 'sessions'});
        });
    }

    function setSortField(field) {
        data.sortKey = field;
    }

    function getQueries() {
        data.loading = true;

        show(props.sessionKey).then((cachedKeys) => {
            data.sessionData = cachedKeys['data'] ?? [];
            data.sessionSummary = cachedKeys['summary'] ?? {};
        }).finally(() => {
            data.loading = false;
        });
    }

    function getUniqueValuesByKey(key) {
        return [...new Set(flattenedCachedKeys.value.map(val => val[key]))];
    }

    function groupValuesByKey(key) {

        let sortClosure = (a, b) => {
            if (a[data.sortKey] === b[data.sortKey]) {
                return 0;
            } else if(a[data.sortKey] > b[data.sortKey]) {
                return -1 * data.sortDirection
            }
            return data.sortDirection;
        };

        if (data.sortKey === 'amount') {
            sortClosure = () => {
                return 0;
            }
        }

        let cdata = {};
        getUniqueValuesByKey(key).forEach((uniqueValue) => {
            cdata[uniqueValue] = flattenedCachedKeys.value
                .filter(row => row[key] === uniqueValue)
                .sort(
                    sortClosure
                );
        });

        return cdata;
    }

    function close() {
        router.push({
            name: 'sessions'
        });
    }

    const dataList = computed(() => {
        return groupValuesByKey(data.listType);
    })

    const dataListKey = computed(() => {
        let list = dataList.value;

        let sortClosure = (a, b) => {
            if (list[a][0][data.sortKey] === list[b][0][data.sortKey]) {
                return 0;
            } else if(list[a][0][data.sortKey] > list[b][0][data.sortKey]) {
                return -1 * data.sortDirection
            }
            return data.sortDirection;
        };

        if (data.sortKey === 'amount') {
            sortClosure = (a, b) => {
                if (list[a].length === list[b].length) {
                    return 0;
                } else if(list[a].length > list[b].length) {
                    return -1 * data.sortDirection;
                }
                return data.sortDirection;
            }
        }

        return Object.keys(list).sort(sortClosure);
    });

    const flattenedCachedKeys = computed(() => {
        return Object.values(data.sessionData).flat();
    });




    function sortMenu()
    {
        router.push({
            name: 'session-order-menu'
        });
    }


    onMounted(() => {
        getQueries();
    })


</script>

<style lang="scss">
    $header-height: 44px;
    $metrics-height: 68px;
    $footer-height: 56px;
    $tabs-height: 42px;
    $tabs-size-diff: 42px - 33px;

    $data-grid-height-minus: ($header-height + $footer-height + $metrics-height + $tabs-height);

    @media (max-height: 480px) {
        .session {
            .tabs {
                font-size: 10px;
            }

            .datagrid {
                max-height: calc(100vh - ($data-grid-height-minus -  $metrics-height - $tabs-size-diff));
            }

            .metrics {
                display: none;
            }
        }

    }

    @media (max-height: 280px) {
        .session {
            .datagrid {
                max-height: calc(100vh - ($data-grid-height-minus -  $metrics-height - $footer-height -  $tabs-size-diff));
            }

            .tabs {
                font-size: 10px;
            }
        }

        footer {
            display: none;
        }
    }


    @media only screen and (max-width: 640px) {
        .session {
            .tabs {
                font-size: 10px;
            }

            .metrics {
                .buttons {
                    display: none;
                    .button {
                        display: none;
                    }
                }
            }
        }
    }




</style>
