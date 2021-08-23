<template>
    <div :class="['modal', {'is-active': active}]">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">SQL - explain</p>
                <button class="delete" aria-label="close" v-on:click="hide"></button>
            </header>
            <section class="modal-card-body">
                <div class="button is-primary is-large is-loading" v-if="loading"></div>
                <explain-part :key="key" v-for="(explainPart, key) in explainParts" :table-explain-data="explainPart"></explain-part><!-- Content ... -->
            </section>
        </div>
    </div>
</template>


<script>
    import ExplainPart from "./query-explain/explain-part";
    import Axios from "axios";
    export default {
        name: "query-explain",

        components: {
            ExplainPart
        },

        data() {
            return {
                explainParts: [],
                sql: "",
                active: false,
                loading: false,
                timeKey: 0,
                sessionId: 0,
                time: 0,
            }
        },

        methods: {
            loadExplainParts() {
                this.loading = true;
                Axios.get('/query-adviser/api/query/explain', {params:{'session-id': this.sessionId, time: this.time, 'time-key': this.timeKey}}).then(resp => {
                    this.loading = false;
                    this.explainParts = resp.data.queryParts;
                });
            },

            hide() {
                this.active = false;
                this.time = 0;
                this.timeKey = 0;
                this.sessionId = 0;
                this.explainParts = [];
            }
        },

        mounted() {
            window.EventBus.$on(`show-explain-dialog`, (data)=> {
                this.time = data.time;
                this.timeKey = data.timeKey;
                this.sessionId = data.sessionId;

                this.loadExplainParts();
                this.$nextTick().then(() => {
                    this.active = true;
                });

            });
        }
    }
</script>
