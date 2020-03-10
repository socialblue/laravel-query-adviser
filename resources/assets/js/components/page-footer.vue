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
    import Axios from "Axios";

    export default {
        data() {
            return {
                serverInfo: null
            }
        },

        created() {
            Axios.get('/query-adviser/api/query/server-info').then((response) => {
                this.serverInfo = response.data;
            });
        }
    }
</script>