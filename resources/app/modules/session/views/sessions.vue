<template>
    <main>
        <router-view name="dialog" />
        <page-header name="Sessions">
            <template #buttons>
                <button-icon title="clear query cache" icon="delete" @button:click="clearSessionCache" />
                <button-icon title="import session" icon="file_upload" @button:click="importSession" />
            </template>
        </page-header>
        <section>
            <div class="container">
                <steps @session:status="onStatusChange"/>
            </div>
            <div class="sessions">
                <session-row v-bind="session"  v-for="session in data.sessions" v-if="data.sessions.length > 0" />
                <loader v-else-if="data.loading" />
                <div v-else>
                    No sessions found.
                </div>
            </div>
        </section>
    </main>
</template>

<script setup>
    import {reactive, onMounted} from "vue";
    import SessionRow from "../components/sessions/session-row.vue";
    import Steps from "../components/sessions/steps.vue";
    import {sessions} from "../api/session-api";
    import {useRouter, onBeforeRouteUpdate} from "vue-router";
    import PageHeader from "../../default/components/page-header.vue";
    import ButtonIcon from "../../default/components/button-icon.vue";
    import Loader from "../../default/components/loader.vue";

    const $router = useRouter();

    const data = reactive({
        sessions: [{firstQueryLogged: new Date(), lastQueryLogged: new Date()}, {firstQueryLogged: new Date(), lastQueryLogged: new Date()}],
        active: true,
        loading: false,
    });

    function onStatusChange(status) {
        data.active = status;
        getList();
    }

    function getList() {
        data.loading = true;

        return sessions().then((sessions) => {
            data.sessions = sessions;
        }).finally(() => {
            data.loading = false;
        }).catch((e) => {

        });
    }

    function importSession() {
        $router.push({name: 'session-import'});
    }

    function clearSessionCache() {
        $router.push({name: 'session-clear'});
    }

    onBeforeRouteUpdate((to, from, next) => {
        getList().then(() => {
            next();
        });
    });

    onMounted(() => {
        getList();
    });
</script>

<style lang="scss">
    code {
        background: rgba(200,200,200,0.9);
        color: rgba(200, 33,33, .9);
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 900;
        line-height: 30px;
    }

    .panel-heading {
        background-color: #00d1b2;
        color: #fff;
        font-size: 14px;
        font-weight: 700;
        line-height: 20px;
        padding: 0.75em 1em;
        display: flex;

        .is-pulled-right {
            margin-left: auto;
        }
    }



</style>