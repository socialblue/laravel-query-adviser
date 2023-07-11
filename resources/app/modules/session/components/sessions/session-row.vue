<template>
    <div class="metrics">
        <div>
            <h1>Queries</h1>
            <h2>{{queries}}</h2>
        </div>
        <div>
            <h1>Routes</h1>
            <h2>{{routes}}</h2>
        </div>
        <div>
            <h1>Total Query time</h1>
            <h2>{{ (queryTime).toFixed(2) }} ms</h2>
        </div>
        <div class="level-item has-text-centered">
            <h1>Total Query time</h1>
            <h2>{{ (queryTime).toFixed(2) }} ms</h2>
        </div>
        <div class="level-item has-text-centered" v-if="firstQueryLoggedDate">
            <h1>{{firstQueryLoggedDate}}</h1>
            <h2>{{timeLog}}</h2>
        </div>
        <div class="buttons">
            <button-icon title="open session" icon="info" v-on:button:click="openSession" />
            <button-icon title="download session" icon="file_download" v-on:button:click="exportSession" />
        </div>
    </div>
</template>

<script setup>

    import { computed } from 'vue';
    import {useRouter} from "vue-router";
    import ButtonIcon from "../../../default/components/button-icon.vue";

    const router = useRouter();

    const props = defineProps({
        sessionKey: {
            default: () => {
                return false;
            }
        },

        queries: {
            type: Number,
            default() {
                return 0;
            }
        },

        routes: {
            type: Number,
            default() {
                return 0;
            }
        },

        queryTime: {
            type: Number,
            default() {
                return 0;
            }
        },

        firstQueryLogged: {
            default() {
                return false;
            }
        },

        lastQueryLogged: {
            default() {
                return false;
            }
        }
    });

    const firstQueryLoggedDate = computed(() => {
        if (!props.firstQueryLogged) {
            return false;
        }

        return formatDate(new Date(props.firstQueryLogged*1000));
    });

    const timeLog = computed(() => {
        if (!props.lastQueryLogged || !props.firstQueryLogged) {
            return false;
        }

        return `${new Date(props.firstQueryLogged*1000).toLocaleString('en-us', {hour:'2-digit', minute: '2-digit', second: '2-digit', hourCycle: 'h24'})} -
         ${new Date(props.lastQueryLogged*1000).toLocaleString('en-us', {hour:'2-digit', minute: '2-digit', second: '2-digit', hourCycle: 'h24'})}`;
    });


    function formatDate(date) {
        return date.toLocaleString('en-us', { weekday: 'short', day: 'numeric', month: 'short',  year: "numeric", });
    }

    function exportSession() {

        router.push({ name: 'session-download', params: { sessionKey: props.sessionKey }});


        //{ name: 'session-export', params: { sessionKey }}
        //window.location.href = `/laravel-query-adviser/api/session/export?session-key=${sessionKey}`;
    }

    function deleteSession() {
        router.push({ name: 'session-delete', params: { sessionKey: props.sessionKey }});
    }

    function openSession() {
        router.push({ name: 'session', params: { sessionKey: props.sessionKey }});

       // { name: 'session', params: { sessionKey, queries, routes, queryTime, firstQueryLogged, lastQueryLogged}}
    }

</script>




<style lang="scss">
    $h1-font-size: 12px;
    $h1-font-weight: 900;
    $h1-color: rgba(188, 188, 188, 0.9);
    $h2-font-size: 16px;
    $h2-font-weight: 900;
    $h2-color: rgba(33, 33, 33, 0.9);

    .metrics {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;

        border: 1px solid rgba(188, 188, 188, 0.9);

        &:hover {
            background-color: rgba(66, 188, 188, 0.1);
        }

        &:not(:first-child) {
            border-top: 0 solid black;
        }

        > div {
            margin: 0;
            padding: 0 0 20px 20px;

            h1 {
                color: $h1-color;
                font-size: $h1-font-size;
                font-weight: $h1-font-weight;
                margin: 8px 0;
            }

            h2 {
                color: $h2-color;
                font-size: $h2-font-size;
                font-weight: $h2-font-weight;
                margin: 0;
            }

            &.buttons {
                padding: 0;
                display: flex;

                .button {
                    margin: 4px 5px;
                }
            }
        }


    }
</style>
