<template>
    <transition name="component-fade" mode="out-in">
        <nav class="navbar is-fixed-bottom" v-if="showNotification">
            <div class="notification is-info is-fullwidth">
                <button class="delete" v-on:click="showNotification = false"></button>
                {{message}}
            </div>
        </nav>
    </transition>
</template>

<script>
    export default {

        data() {
            return {
                message: '',
                showNotification: false
            }
        },

        methods: {
            show(message) {
                this.message = message;
                this.showNotification = true;

                setTimeout(() => {
                    this.hide();
                }, 2500);
            },

            hide() {
                this.message = '';
                this.showNotification = false;
            }
        },

        created() {
            window.EventBus.$on('show-notification', (data) => {
                this.show(data.message);
            })
        }
    }
</script>