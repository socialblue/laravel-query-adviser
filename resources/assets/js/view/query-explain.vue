<template>
    <div :class="['modal', {'is-active': active}]">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">SQL - explain</p>
                <button class="delete" aria-label="close" v-on:click="active = false"></button>
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
    export default {
        name: "query-explain",

        components: {
            ExplainPart
        },

        props: {
            time: {
                type: Number
            },

            timeKey: {
                type: Number
            }
        },

        data() {
            return {
                explainParts: [],
                sql: "",
                active: false,
                loading: false,
            }
        },

        methods: {

            loadExplainParts()
            {
                if (this.explainParts.length > 0) {
                    return;
                }
                this.loading = true;
                window.Axios.get('/query-adviser/api/query/explain', {params:{time: this.time, 'time-key': this.timeKey}}).then(resp => {
                    this.loading = false;
                    this.explainParts = resp.data.queryParts;
                });
            }
        },

        mounted() {

            window.EventBus.$on(`show-explain-dialog`, (data)=> {
                if (data[0] === this.time && data[1] === this.timeKey) {
                    this.loadExplainParts();
                    this.$nextTick().then(() => {
                        this.active = true;
                    });
                }
            });
        }
    }
</script>