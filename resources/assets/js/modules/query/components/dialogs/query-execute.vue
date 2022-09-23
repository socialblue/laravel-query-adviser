<template>
    <div :class="['modal', 'is-active']">
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
import {execute} from "../../api/queryApi";

    export default {

        props: {
            timeKey:{},
            time:{},
            sessionKey:{},
            sql:{},
        },

        data() {
            return {
                active: false,
                result: [
                ]
            }
        },

        methods: {
            getQuery() {
                execute(this.sessionKey, this.time, this.timeKey).then(result => {
                    this.loading = false;
                    this.result = result;
                });
            },

            hide() {
                this.$router.push({name: this.$route.matched[0].name});
            }

        },

        beforeRouteEnter(to, from, next) {
            next((vm) => {
                vm.getQuery();
            })
        },
    }
</script>
