<template>
    <aside :class="['quickview', 'is-active']">
        <header class="quickview-header">
            <p class="title">Setting</p>
            <span class="delete" v-on:click="$router.push({name: $route.matched[0].name})"></span>
        </header>

        <div class="quickview-body">
            <div class="">
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
        </div>

        <footer class="quickview-footer" />
    </aside>
</template>

<script>
    export default {
        computed: {
            sortField: {
                get() {
                    return this.$attrs['sort-field'];
                },

                set(field) {
                    this.$emit('update:sort-field', field);
                }
            }
        },
    }
</script>
