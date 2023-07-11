<template>
    <aside :class="['quickview', 'is-active']">
        <button-icon icon="close" @click="$router.push({name: $route.matched[0].name})"></button-icon>
        <div class="quickview-body">
            <div class="rows">
                <h3 class="subtitle">Order</h3>
                <div class="field">
                    <input class="is-checkradio" id="default" type="radio" value="time" name="sortField" v-model="sortField">
                    <label for="default">Last inserted first</label>
                </div>
                <div class="field">
                    <input class="is-checkradio" id="querytime" type="radio" value="queryTime" name="sortField" v-model="sortField">
                    <label for="querytime">Slowest query first</label>
                </div>
                <div class="field">
                    <input class="is-checkradio" id="amount" type="radio" value="amount" name="sortField" v-model="sortField">
                    <label for="amount">Grouped Amount</label>
                </div>
            </div>
        </div>
    </aside>
</template>

<script setup>
    import { computed, defineEmits, useAttrs } from 'vue';
    import ButtonIcon from "../../../../default/components/button-icon.vue";

    const emit = defineEmits(['update:sort-field']);
    const $attrs = useAttrs();


    const sortField = computed({
        get() {
            return $attrs['sort-field'];
        },

        set(field) {
            emit('update:sort-field', field);
        }
    });

</script>

<style lang="scss">
    .quickview {
        .button {
            position: absolute;
            top: 10px;
            right: 5px;
        }

        width: 180px;
        position: fixed;
        top: 0;
        height: 100vh;
        right: 0;
        border-left: 1px solid rgba(233,233,233,0.9);
        background: #fff;
    }

    .quickview-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .quickview-body {
        padding: 0;
    }

    .quickview-footer {
        padding: 0;
    }

    .quickview-body .rows {
        padding: 1rem;
    }

    .quickview-body .rows .field {
        margin-bottom: 0.5rem;
    }

    .quickview-body .rows .field:last-child {
        margin-bottom: 0;
    }

    .quickview-body .rows .field .is-checkradio {
        margin-right: 0.5rem;
    }

    .quickview-body .rows .field .is-checkradio label {
        margin-bottom: 0;
    }
</style>
