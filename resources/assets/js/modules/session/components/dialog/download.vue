<template>
    <div :class="['modal', 'is-active']">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Download Session</p>
            </header>
            <section class="modal-card-body">
                <div class="button is-primary is-large is-loading" v-if="loading"></div>
            </section>
        </div>
    </div>
</template>

<script>
    import {sessionExport} from "../../api/sessionApi";

    export default {
        data() {
            return {
                loading: true,
            }
        },

        beforeRouteEnter(to, from, next) {
            next(vm => {
                sessionExport(from.params.sessionKey).then(() => {
                    vm.$router.push({name: from.name, params: from.params});
                });
            });
        }

    }
</script>
