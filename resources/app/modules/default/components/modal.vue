<template>
    <div class="modal">
        <div class="modal-background"></div>
        <div class="modal-card" >
            <header class="modal-card-head">
                <p class="modal-card-title">{{ title }}</p>
                <div class="buttons is-pulled-right" style="display: flex;">
                    <button-icon title="close modal" v-on:button:click="hide" />
                </div>

            </header>
            <section class="modal-card-body">
                <slot name="body"></slot>
            </section>
        </div>
    </div>


</template>


<script setup>
    import {useRouter} from "vue-router";
    import ButtonIcon from "./button-icon.vue";
    const router = useRouter();
    const route = router.currentRoute;

    defineProps({
        title: {
            type: String
        },
    })

    function hide() {
        // go 1 level back
        const previousKey = route.value.matched.length-2;

        router.push({
            name: route.value.matched[previousKey].name
        });
    }

</script>


<style lang="scss">
.modal {
    align-items: center;
    flex-direction: column;
    justify-content: center;
    overflow: hidden;
    position: fixed;
    z-index: 98;
    display: flex;

    min-height: 100vh;
    min-width: 100vw;

    .modal-background {
        background-color: rgba(0, 0, 0, 0.5);
        bottom: 0;
        left: 0;
        position: absolute;
        right: 0;
        top: 0;
        z-index: 99;
    }

    .modal-card {
        display: flex;
        flex-direction: column;
        max-height: calc(100vh - 40px);
        overflow: hidden;

        background-color: #fff;
        border-radius: 5px;
        margin: auto;
        box-shadow: 0 2px 3px rgba(10, 10, 10, 0.1), 0 0 0 1px rgba(10, 10, 10, 0.1);
        align-items: center;
        align-content: center;
        max-width: 100vw;
        position: relative;
        width: calc(100vw - 40px);
        z-index: 100;

        .modal-card-head {
            align-items: center;
            background-color: #f5f5f5;
            display: flex;
            flex-shrink: 0;
            justify-content: flex-start;
            position: relative;
            border-bottom: 1px solid #dbdbdb;
            width: 100%;

            .buttons {
                padding: 0 20px;
            }

            .modal-card-title {
                color: #363636;
                flex-grow: 1;
                flex-shrink: 0;
                font-size: 1.5rem;
                line-height: 1;
                margin: 18px 20px;
            }
        }

        .modal-card-body {
            padding: 20px;
            width: 100%;
            overflow: scroll;
        }
    }
}
</style>
