<template>
    <div :class="['modal', {'is-active': active}]">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Sql execute</p>
                <button class="delete" aria-label="close" v-on:click="hide"></button>
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

    export default {
        mixins: [highlight],

        data() {
            return {
                timeKey: 0,
                time: 0,
                sessionId: 0,
                sql: null,
                active: false,
                result: [
                ]
            }
        },

        methods: {
            getQuery() {

                const params = {'session-id': this.sessionId, time: this.time, 'time-key': this.timeKey};
                fetch(`/query-adviser/api/query/exec?${new URLSearchParams(params)}`).then(resp => {
                    return resp.json();
                }).then(result => {
                    this.loading = false;
                    this.result = result;
                });
            },

            hide() {
                this.active = false;
                this.timeKey = null;
                this.time = null;
                this.sessionId = 0;
            }

        },

        created() {
            window.EventBus.$on('show-execute-dialog', (data) => {
                this.time = data.time;
                this.timeKey = data.timeKey;
                this.sessionId = data.sessionId;
                this.sql = data.sql;

                this.getQuery();
                this.$nextTick().then(() => {
                    this.active = true;
                });
            });
        }
    }
</script>
