<template>
    <main class="is-vertical tile">

        <nav class="panel is-primary">
            <div class="panel-heading">
                <span>
                    Sessions
                </span>

                <span class="material-icons button is-pulled-right" title="clear query cache" v-on:click="clearSessionCache">
                    eject
                </span>
            </div>
        </nav>

        <div>
            <div class="material-icons button" v-on:click="startSession" v-if="!isActive ">play_arrow</div>
            <div class="material-icons button" v-on:click="stopSession" v-if="isActive">stop</div>
        </div>

        <div v-for="session in sessions" class="card">
            <router-link :to="{ name: 'session', params: { id: session }}">{{session}}</router-link>
        </div>
    </main>

</template>


<script>
    import Axios from "Axios";

    export default {

        data() {
            return {
                sessions: [],
                isActive: false
            }
        },

        methods: {
            startSession() {
                Axios.get('/query-adviser/api/session/start').then(() => {
                     this.pollActiveSession();
                });
            },

            stopSession() {
                Axios.get('/query-adviser/api/session/stop', () => {
                    this.getList();
                });
            },

            hasActiveSession() {
                return Axios.get('/query-adviser/api/session/is-active')
            },

            getList() {
                Axios.get('/query-adviser/api/session/list').then((response) => {
                    this.sessions = response.data;
                })
            },

            pollActiveSession() {

                this.hasActiveSession().then((response) => {
                    if (this.isActive = response.data.active) {
                        setTimeout(() => {
                            this.pollActiveSession();
                        }, 1000);
                    } else {
                        this.getList();
                    }
                })

            },

            clearSessionCache() {
                Axios.get('/query-adviser/api/session/clear').then((response) => {
                    this.cachedKeys = [];
                    window.EventBus.$emit('show-notification', {message: 'Session cache cleared'});
                });
            }

        },

        mounted() {
            this.getList();
        }
    }
</script>