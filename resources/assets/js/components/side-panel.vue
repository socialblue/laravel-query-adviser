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
                        <input class="is-checkradio" id="exampleRadioInline1" type="radio" name="exampleRadioInline" checked="checked">
                        <label for="exampleRadioInline1">Default</label>
                    </div>
                    <div class="field">
                        <input class="is-checkradio" id="exampleRadioInline2" type="radio" name="exampleRadioInline">
                        <label for="exampleRadioInline2">QueryTime</label>
                    </div>
                    <div class="field">
                        <input class="is-checkradio" id="exampleRadioInline3" type="radio" name="exampleRadioInline">
                        <label for="exampleRadioInline3">Grouped Amount</label>
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

        beforeCreate() {
            window.EventBus.$on('show-filter-bar', () => {
                this.active = true;
            });
        }
    }
</script>