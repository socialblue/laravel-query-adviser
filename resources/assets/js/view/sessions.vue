<template>

    <div>
        <div icon="material-icons button" v-on:click="startSession">play_arrow</div>
        <div icon="material-icons button" v-on:click="stopSession">stop</div>
    </div>


</template>


<script>
    import Axios from "Axios";

    export default {

        methods: {
            startSession() {
                Axios.get('/query-adviser/api/session/start', () => {
                    this.pollActiveSession();
                });
            },

            stopSession() {
                Axios.get('/query-adviser/api/session/stop', () => {

                });
            },

            hasActiveSession() {
                return Axios.get('/query-adviser/api/session/is-active')
            },

            pollActiveSession() {

                this.hasActiveSession().then((response) => {
                    if (this.isActive = response.isActive) {
                        setTimeout(() => {
                            this.pollActiveSession();
                        }, 1000);
                    }
                })

            }

        }

    }
</script>