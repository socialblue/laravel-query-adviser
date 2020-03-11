<template>
    <aside :class="['quickview', {'is-active': active}]">
        <header class="quickview-header">
            <p class="title">Setting</p>
            <span class="delete" v-on:click="active = false"></span>
        </header>

        <div class="quickview-body">
            <div class="">
                <div class="rows">
                    <h3 class="subtitle">Order</h3>
                    <div class="field">
                        <input class="is-checkradio" id="default" type="radio" value="time" name="sortField" v-model="sortField">
                        <label for="default">Default</label>
                    </div>
                    <div class="field">
                        <input class="is-checkradio" id="querytime" type="radio" value="queryTime" name="sortField" v-model="sortField">
                        <label for="querytime">QueryTime</label>
                    </div>
                    <div class="field">
                        <input class="is-checkradio" id="amount" type="radio" value="amount" name="sortField" v-model="sortField">
                        <label for="amount">Grouped Amount</label>
                    </div>
                </div>
            </div>
        </div>

        <footer class="quickview-footer">

        </footer>
    </aside>
</template>

<script>
    export default {
        data() {
            return {
                active: false,
            }
        },

        computed: {
            sortField: {
                get() {
                    return this.$attrs['sort-field'];
                },

                set(field) {
                    console.log(field);
                    this.$emit('update:sort-field', field);
                }
            }
        },

        beforeCreate() {
            window.EventBus.$on('show-filter-bar', () => {
                this.active = true;
            });
        }
    }
</script>