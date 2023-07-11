<template>
    <ul class="steps">
        <li class="step-item is-success">
            <div class="step-marker" @click="startSession">
                <span class="icon">
                    <i class="material-icons" v-if="!data.active">play_arrow</i>
                    <i class="material-icons" v-else>done</i>
                </span>
            </div>
            <div class="step-details">
                <p class="step-title">Step 1</p>
                <p>Start a new session</p>
            </div>
        </li>
        <li :class="['step-item', {'is-active': (data.active && !data.hasQueries), 'is-success': (data.active && data.hasQueries)}]">
            <div class="step-marker">
                <span class="icon">
                    <i class="material-icons" v-if="!data.hasQueries">open_in_browser</i>
                    <i class="material-icons" v-else>done</i>
                </span>
            </div>
            <div class="step-details">
                <p class="step-title">Step 2</p>
                <p>Open your application page where you would like to track the queries.</p>
            </div>
        </li>
        <li :class="['step-item', {'is-active': data.active && data.hasQueries}]">
            <div class="step-marker">
                <span class="icon">
                    <i class="material-icons" v-on:click="stopSession" v-if="data.active && data.hasQueries">stop</i>
                    <i class="material-icons" v-else-if="!data.active">stop</i>
                    <i class="material-icons" v-else-if="!data.active && data.hasQueries">done</i>
                </span>
            </div>
            <div class="step-details">
                <p class="step-title">Step 3</p>
                <p>Stop the sessions</p>
            </div>
        </li>
        <li :class="['step-item', {'is-active': !data.active && data.hasQueries}]">
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
</template>
<script setup>
    import {reactive, onMounted, watch, defineEmits} from "vue";
    import {onBeforeRouteLeave, onBeforeRouteUpdate} from "vue-router";
    import {isActive, start, stop} from "../../api/session-api";

    const emit = defineEmits(['session:status']);

    const data = reactive({
        active: false,
        hasQueries: false,
        activeSessionId: null,
        timeOut: 0,
    });


    function startSession() {
        if (data.active) {
            return;
        }

        start().then(() => {
            pollActiveSession();
        });
    }

    function stopSession() {
        stop().then(() => {
            data.active = false;
            data.hasQueries = false;
            data.activeSessionId = null;
        });
    }

    function hasActiveSession() {
        return isActive();
    }

    function pollActiveSession() {
        return hasActiveSession()
            .then((response) => {
                data.active = response.active ?? false;
                data.hasQueries = response?.has_queries ?? false;
                data.activeSessionId = response?.active_session_id ?? false;

                clearTimeout(data.timeOut);
                data.timeOut = setTimeout(() => {
                    pollActiveSession();
                }, 2500);
            });
    }

    watch(() => data.active, (active) => {
        emit('session:status', active);
    });

    onBeforeRouteUpdate((to, from, next) => {
        pollActiveSession()
            .finally(() => {
                next();
            });

    });

    onBeforeRouteLeave((to, from, next) => {
        clearTimeout(data.timeOut);
        next();
    });

    onMounted(() => {
        pollActiveSession();
    });
</script>


<style lang="scss">

    $color: #00d1b2;
    $font-size: 1rem;
    $font-weight: 700;

    .steps {
        display: flex;
        flex-wrap: wrap;

        position: relative;
        min-height: 2rem;

        font-size: $font-size;
        background-color: #fff;
        list-style: none;

        > .step-item {
            display: flex;
            flex-basis: 0;
            flex-grow: 1;
            justify-content: center;

            position: relative;


            &.is-active, &.is-success {
                .step-marker {
                    background-color: #fff;
                    border: 0.2em solid $color;
                    .icon {

                        color: $color;
                        border-color: $color;
                        cursor: pointer;


                    }
                }

                &:before {
                    background-position: 0 100%;
                }
            }

            > .step-marker {
                display: flex;
                position: absolute;
                font-weight: $font-weight;
                justify-content: center;
                align-items: center;
                background: #b5b5b5;
                z-index: 1;

                border: 0.2em solid #fff;
                height: 2rem;
                left: calc(50% - 1rem);
                width: 2rem;
                border-radius: 50%;

                .icon {
                    cursor: not-allowed;
                    display: inline-flex;
                    height: 1.5rem;
                    width: 1.5rem;
                    justify-content: center;
                    align-items: center;

                    > * {
                        font-size: 1rem;
                    }
                }
            }

            > .step-details {
                display: block;
                margin-left: 0.5em;
                margin-right: 0.5em;
                margin-top: 2rem;
                padding-top: 0.2em;
                text-align: center;

                p {
                    margin: 0;
                    padding: 0;
                    font-size: 10px;
                }

                .step-title {
                    font-size: 1.2rem;
                    font-weight: 600;
                }

            }


            &:not(:first-child):before {
                content: " ";
                display: block;
                position: absolute;
                bottom: 0;
                height: 0.2em;
                left: -50%;
                top: 1rem;
                width: 100%;
                background: linear-gradient(270deg,#dbdbdb 50%,#00d1b2 0);
                background-position: 100% 100%;
                background-size: 200% 100%;
            }
        }
    }


</style>
