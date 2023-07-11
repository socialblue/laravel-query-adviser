<template>
    <modal title="Clear Sessions">
        <template #body>
            <loader />
        </template>
    </modal>
</template>
<script setup>
import Modal from "../../../../default/components/modal.vue";
import {onMounted} from "vue";
import {clear} from "../../../api/session-api";
import {useRouter} from "vue-router";
import Loader from "../../../../default/components/loader.vue";

const router = useRouter();

onMounted(() => {
    clear().then(() => {
        router.push({name: 'sessions'});
    }).catch((e) => {
    });
});

</script>
