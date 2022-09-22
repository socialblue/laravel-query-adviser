<template>
    <main class="is-vertical tile">
        <router-view name="dialog"></router-view>
        <nav class="panel is-primary">
            <div class="panel-heading">
                <span>
                    Sessions
                </span>

                <router-link class="button is-pulled-right" :to="{ name: 'session-import'}" ><i class="material-icons">file_upload</i></router-link>
                <span class="material-icons button is-pulled-right" title="clear query cache" v-on:click="clearSessionCache">
                    eject
                </span>
            </div>
        </nav>
        <section class="hero">
            <template v-if="sessions.length > 0 && !isActive">
                <div class="hero-body">
                    <div class="container">
                        <h1 class="title">
                            You have sessions
                        </h1>
                        <p>
                            To add another session press play. <span class="material-icons button" v-on:click="startSession" v-if="!isActive ">play_arrow</span>
                        </p>
                    </div>
                </div>

                <div v-for="session in sessions" class="card" >
                    <query-statistics v-bind="session" />
                </div>
            </template>
            <div v-else-if="loading && sessions.length === 0" class="hero-body">
                <div class="container">
                    <div class="button is-primary is-large is-loading" />
                    <h1 class="title">
                        Loading..
                    </h1>
                </div>
            </div>
            <div v-else class="hero-body">
                <div class="container">
                    <h1 class="title" v-if="sessions.length > 0">
                        You have an active session.
                    </h1>
                    <h1 class="title" v-else>
                        No sessions found.
                    </h1>

                    <h2 class="subtitle">
                        Sessions are stored for {{config.ttl/60}} minutes. You can keep them longer by changing the config value of <br>
                        <code>cache.ttl</code> in <code>laravel-query-adviser.php</code>
                    </h2>

                    <ul class="steps">
                        <li class="step-item is-success">
                            <div class="step-marker">
                              <span class="icon">
                                <i class="material-icons" v-on:click="startSession" v-if="!isActive">play_arrow</i>
                                <i class="material-icons" v-else>done</i>
                              </span>
                            </div>
                            <div class="step-details is-completed">
                                <p class="step-title">Step 1</p>
                                <p>Start a new session</p>
                            </div>
                        </li>
                        <li :class="['step-item', {'is-active': isActive && !hasQueries}]">
                            <div class="step-marker">
                                <i class="material-icons" v-if="!hasQueries">open_in_browser</i>
                                <i class="material-icons" v-else-if="isActive && hasQueries">done</i>
                            </div>
                            <div class="step-details">
                                <p class="step-title">Step 2</p>
                                <p>Open your application page where you would like to track the queries.</p>
                            </div>
                        </li>
                        <li :class="['step-item', {'is-active': isActive && hasQueries}]">
                            <div class="step-marker">
                                <span class="icon">
                                    <i class="material-icons" v-on:click="stopSession" v-if="isActive && hasQueries">stop</i>
                                    <i class="material-icons" v-else-if="!isActive">stop</i>
                                    <i class="material-icons" v-else-if="!isActive && hasQueries">done</i>
                                </span>
                            </div>
                            <div class="step-details">
                                <p class="step-title">Step 3</p>
                                <p>Stop the sessions</p>
                            </div>
                        </li>
                        <li :class="['step-item', {'is-active': !isActive && hasQueries}]">
                            <div class="step-marker">
                              <span class="icon" >
                                <i class="material-icons">info</i>
                              </span>
                            </div>
                            <div class="step-details">
                                <p class="step-title">Step 4</p>
                                <p>Open the session details.</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
    </main>
</template>

<script>
    import QueryStatistics from "../components/query-statistics";
    import {clear, isActive, sessionExport, sessions, stop, start} from "../modules/session/api/sessionApi";

    export default {
        components: {QueryStatistics},

        props: {
            config: {
                default() {
                    return {
                        ttl: 3600
                    }
                }
            }
        },

        data() {
            return {
                sessions: [],
                isActive: false,
                hasQueries: false,
                activeSessionsId: null,
                loading: false,
            }
        },

        methods: {
            startSession() {
                start().then(() => {
                     this.pollActiveSession();
                });
            },

            stopSession() {
                stop().then(() => {
                    this.getList();
                    this.isActive = false;
                    this.hasQueries = false;
                    this.activeSessionsId = null;
                });
            },

            hasActiveSession() {
                return isActive();
            },

            getList() {
                this.loading = true;

                return sessions().then((sessions) => {
                    this.sessions = sessions;
                }).finally(() => {
                    this.loading = false;
                })
            },

            exportSession(to) {
                return sessionExport(to.params.sessionKey);
            },

            pollActiveSession() {
                this.hasActiveSession()
                    .then((response) => {
                        this.isActive = response.active ?? false;
                        this.hasQueries = response?.has_queries ?? false;
                        this.activeSessionId = response?.active_session_id ?? false;
                        if (this.isActive) {
                            setTimeout(() => {
                                this.pollActiveSession();
                            }, 1000);
                        } else {
                            this.getList();
                        }
                    });
            },

            clearSessionCache() {
                clear().then(() => {
                    this.sessions = [];
                    this.loading = true;
                    this.pollActiveSession();
                });
            }
        },

        beforeRouteEnter(to, from, next) {

            next((vm) => {
                vm.getList();
                vm.pollActiveSession();
            });
        },

        beforeRouteUpdate(to, from, next) {
            if (to.name === 'session-export') {
                this.exportSession(to).then(next);
            } else if (to.name === 'sessions') {
                this.getList();
                this.pollActiveSession();
                next();
            } else {
                next();
            }
        },
    }
</script>

<style lang="scss">
    .step-item {
        .step-marker {
            &:hover {
                cursor: pointer;
            }
        }
    }
</style>
