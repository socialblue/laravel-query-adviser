<template>
    <div :class="['modal', {'is-active': active}]">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Sql execute</p>
                <button class="delete" aria-label="close" v-on:click="active = false"></button>
            </header>
            <section class="modal-card-body">
                <nav class="panel">
                    <h2 class="panel-heading has-primary-background">
                        Query
                    </h2>
                    <pre class="textarea" ref="sqlcode">{{sql}}</pre>
                </nav>
                <nav class="panel">
                    <h2 class="panel-heading has-primary-background">
                        Results
                    </h2>

                    <div class="table-container" v-if="result.length > 0">
                        <table class="table is-fullwidth">
                            <thead>
                                <tr>
                                    <th v-for="header in Object.keys(result[0])">{{header}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="row in result">
                                    <td v-for="field in row">{{field}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </nav>

            </section>
        </div>
    </div>


</template>

<script>
    import highlight from "../mixin/hightlight";
    import Axios from "Axios";

    export default {
        mixins: [highlight],

        data() {
            return {
                timeKey: 0,
                time: 0,
                sql: null,
                active: false,
                result: [
                ]
            }
        },

        methods: {
            getQuery() {
                Axios.get(`/query-adviser/api/query/exec/?time=${this.time}&time-key=${this.timeKey}`).then((response) => {
                    this.result = response.data;
                });
            }
        },

        created() {
            window.EventBus.$on(`show-execute-dialog`, (data) => {
                this.time = data.time;
                this.timeKey = data.timeKey;
                this.sql = this.format(data.sql);

                this.getQuery();
                this.$nextTick().then(() => {
                    this.active = true;
                });
            });
        }
    }
</script>