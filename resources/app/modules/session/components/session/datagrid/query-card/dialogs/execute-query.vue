<template>
    <modal title="Execute query">
        <template #body>
            <div class="query-results">
                <h2>
                    Results
                </h2>
                <loader v-if="data.loading" />
                <table class="table is-fullwidth" v-else-if="data.result.length > 0">
                    <thead>
                        <tr>
                            <th v-for="header in Object.keys(data.result[0])">{{header}}</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr v-for="row in data.result">
                        <td v-for="field in row">{{field}}</td>
                    </tr>
                    </tbody>
                </table>
                <div v-else>
                    No results found.
                </div>
            </div>
        </template>
    </modal>
</template>

<script setup>
import Modal from "../../../../../../default/components/modal.vue";
import {onMounted, reactive} from "vue";
import {execute} from "../../../../../../query/api/query-api";
import Loader from "../../../../../../default/components/loader.vue";

const props = defineProps({
    sessionKey: {
        type: String,
    },

    time: {
        type: String,
    },

    timeKey: {
        type: Number,
    },
});


const data = reactive({
    loading: true,
    result: [],
});

onMounted(() => {
    this.loading = true;
    execute(props.sessionKey, props.time, props.timeKey)
        .then((result) => {
            data.result = result;
        }).finally(() => {
            data.loading = false;
        });
})
</script>

<style lang="scss">
    .query-results {

        thead {
            tr {
                background-color: #00d1b2;
                margin: 0;
                padding: 0 20px;
                th {
                    color: white;
                    font-weight: 900;
                    padding: 4px 20px;
                    text-align: left;
                    text-wrap: nowrap;
                }
            }
        }

        tbody {
            tr {
                &:nth-child(odd) {
                    background-color: aliceblue;
                }

                td {
                    text-wrap: nowrap;
                    padding: 0 20px;
                }
            }
        }


    }


</style>

