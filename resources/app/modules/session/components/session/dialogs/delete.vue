<template>
    <modal title="Delete session">
        <template #body>
            <loader />
        </template>
    </modal>
</template>

<script setup>
import Modal from "../../../../default/components/modal.vue";
import {onMounted} from "vue";
import Loader from "../../../../default/components/loader.vue";
import {useRouter} from "vue-router";

const router = useRouter();

const props = defineProps({
    sessionKey: {
        type: String,
        required: true,
    }
});

function hide() {
    router.push({name: 'sessions'});
}

onMounted(() => {
    //delete session api call

    // delete(props.sessionKey).then(() => {
    //  hide();
    // }).catch((e) => {
    //
    // });
});





</script>



<style scoped lang="scss">

</style>
