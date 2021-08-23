<template>
    <div :class="['modal', {'is-active': active}]">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Session import</p>
                <button class="delete" aria-label="close" v-on:click="hide"></button>
            </header>
            <section class="modal-card-body">
                <form ref="fileUpload">
                    <input type="file" name="session" v-on:change="uploadSession()" />
                </form>
            </section>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            active: true,
        };
    },

    methods: {
        uploadSession() {
            const body = new FormData(this.$refs['fileUpload']);
            const method = 'POST';
            const headers = {
                'Accept': 'application/json, text-plain, */*',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': window.Laravel.csrfToken
            };

            fetch('/query-adviser/api/session/import', {
                method,
                body,
                headers
            }).then(() => this.hide());
        },

        hide() {
            this.active = false;
        }
    }
}
</script>
