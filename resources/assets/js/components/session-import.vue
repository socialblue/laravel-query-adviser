<template>
    <div :class="['modal', 'is-active']">
        <div class="modal-background"></div>
        <div class="modal-card" >
            <header class="modal-card-head">
                <p class="modal-card-title">Session import</p>
                <button class="delete" aria-label="close" v-on:click="hide"></button>
            </header>
            <section class="modal-card-body">
                <div class="button is-primary is-large is-loading" v-if="loading" />
                <form ref="fileUpload" v-else>
                    <input type="file" name="session" v-on:change="uploadSession()" />
                </form>
            </section>
        </div>
    </div>
</template>

<script>
import {sessionImport} from "../modules/session/api/sessionApi";

export default {
    data() {
        return {
            loading: false,
        };
    },

    methods: {
        uploadSession() {
            const body = new FormData(this.$refs['fileUpload']);
            this.loading = true;
            sessionImport(body).then(() => {
                this.hide();
            });
        },

        hide() {
            this.loading = false;
            this.$router.push({name: 'sessions'});
        }
    }
}
</script>
