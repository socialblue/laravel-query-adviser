<template>
    <div>
        <div>
            <div class="material-icons button" v-on:click="startSession" v-if="!isActive ">play_arrow</div>
            <div class="material-icons button" v-on:click="stopSession" v-if="isActive">stop</div>
        </div>

        <div v-for="session in sessions" class="card">
            <router-link :to="{ name: 'session', params: { id: session }}">{{session}}</router-link>
        </div>
    </div>

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

            }

        },

        mounted() {
            this.getList();
        }
    }
</script>