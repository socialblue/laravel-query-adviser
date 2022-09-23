<template>
    <div :class="['modal', 'is-active']">
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
    import {explain} from "../../api/queryApi";
    export default {
        name: "query-explain",

        components: {
            ExplainPart
        },

        props: {
            timeKey: {},
            sessionKey: {},
            time: {},
            sql: {},
        },

        data() {
            return {
                explainParts: [],
                active: false,
                loading: false,
            }
        },

        methods: {
            loadExplainParts() {
                this.loading = true;
                explain(this.sessionKey, this.time, this.timeKey).then(explainParts => {
                    this.loading = false;
                    this.explainParts = explainParts?.queryParts ?? [];
                });
            },

            hide() {
                this.$router.push({name: this.$route.matched[0].name});
            }
        },

        beforeRouteEnter(to, from, next) {
            next((vm) => {
                vm.loadExplainParts();
            })
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
