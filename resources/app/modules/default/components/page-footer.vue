<template>
    <footer class="footer">
        <div class="content has-text-centered">
            <p>
                <strong>Laravel Query Adviser</strong> by
                <a href="https://socialblue.com">Social Blue</a>.
            </p>
            <sub class="is-small">
                {{data.serverInfo}}
            </sub>
        </div>
    </footer>
</template>

<script setup>
    import {onMounted, reactive} from "vue";
    import {serverInfo} from "../api/default-api";

    const data = reactive({
        serverInfo: null
    });


    onMounted(() => {
        serverInfo().then((response) => {
            serverInfo.value = response.data;
        });
    });
</script>


<style lang="scss">
.footer {
    background-color: #fafafa;
    text-align: center;
    flex-grow: 1;
    font-size: 12px;

    strong {
        color: #363636;
        font-weight: 700;
    }

    a {
        color: #485fc7;
        cursor: pointer;
        text-decoration: none;
    }
}

</style>
