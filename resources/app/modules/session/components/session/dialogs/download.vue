<template>
    <modal title="Download session">
        <template #body>
            <loader />
        </template>
    </modal>
</template>

<script setup>
import Modal from "../../../../default/components/modal.vue";
import {onMounted} from "vue";
import {useRouter} from "vue-router";
import {sessionExport} from "../../../api/session-api";
import Loader from "../../../../default/components/loader.vue";

const router = useRouter();
const props = defineProps({
    sessionKey: {
        type: String,
        required: true,
    }
});

function downloadSession() {
    sessionExport(props.sessionKey)
        .then(() => {
            hide();
        }).catch((e) => {
            console.log(e);
            hide();
        });
}

function hide() {
    setTimeout(() => {
        router.push({name: 'sessions'});
    }, 1000);
}

onMounted(() => {
    downloadSession();
});

</script>


<style scoped lang="scss">



</style>
