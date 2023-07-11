<template>
    <modal title="Import Session">
        <template #body>
            <loader v-if="data.loading"/>
            <form ref="fileUpload" v-else>
                <label class="file-upload-button">
                    Upload Session
                    <input type="file" name="session" v-on:change="uploadSession()" />
                </label>
            </form>
        </template>
    </modal>
</template>

<script setup>
    import {sessionImport} from "../../../api/session-api";
    import Modal from "../../../../default/components/modal.vue";
    import {reactive, ref} from "vue";
    import { useRouter } from 'vue-router';
    import Loader from "../../../../default/components/loader.vue";

    const fileUpload = ref(null)
    const router = useRouter();

    const data = reactive({
        loading: false,
    });

    function uploadSession() {
        const body = new FormData(fileUpload.value);
        data.loading = true;

        sessionImport(body).then(() => {
            hide();
        }).finally(() => {
            data.loading = false;
        }).catch((e) => {
            console.log(e);
        });
    }


    function hide() {
        data.loading = false;
        router.push({name: 'sessions'});
    }
</script>

<style lang="scss">
    .file-upload-button {
        display: inline-block;
        padding: 10px 20px;
        background-color: #2196f3;
        color: #fff;
        border-radius: 4px;
        cursor: pointer;
    }

    .file-upload-button input[type="file"] {
        position: absolute;
        width: 0;
        height: 0;
        opacity: 0;
    }

    .file-upload-button:hover {
        background-color: #1976d2;
    }

</style>
