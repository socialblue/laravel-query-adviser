<template>
    <footer class="footer">
        <div class="content has-text-centered">
            <p>
                <strong>Laravel Query Adviser</strong> by
                <a href="https://socialblue.com">Social Blue</a>.
            </p>
            <sub class="is-small">
                {{serverInfo}}
            </sub>
        </div>
    </footer>
</template>

<script>
    import {serverInfo} from "../modules/default/api/defaultApi";

    export default {
        data() {
            return {
                serverInfo: null
            }
        },

        created() {
            serverInfo()
                .then((serverInfo) => {
                    this.serverInfo = serverInfo;
                });
        }
    }
</script>
