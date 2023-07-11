<template>
    <div class="button" :title="title" v-on:click="emit">
        <span class="icon">
            <span class="material-icons">{{ icon }}</span>
        </span>
    </div>
</template>

<script setup>

    defineProps({
        title: {
            type: String
        },
        icon : {
            type: String,
            default: 'close'
        }
    })

    const emits = defineEmits(['button:click'])

    function emit() {
        emits('button:click');
    }

</script>

<style lang="scss">
    .button {
        font-size: 12px;
        line-height: 20px;
        transition: all .2s;
        text-align: center;
        justify-content: center;
        white-space: nowrap;
        background-color: rgba(233,233,233, 0.5);
        display: flex;
        border-radius: 6px;
        margin-right: 6px;

        &:hover {
            cursor: pointer;
        }

        .icon {
            margin-right: 14px;
            margin-left: 14px;
            margin-top: 2px;

            .material-icons {
                font-size: 12px;
            }
        }

        &:hover {
            background-color: rgba(233,233,233, 0.9);
        }
    }
</style>
