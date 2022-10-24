<template>
    <div>
        <router-view :key="routeKey"></router-view>
        <page-footer />
        <notification />
        <svg-sheet />
    </div>
</template>

<script>
    import pageHeader from '../components/page-header';
    import pageFooter from '../components/page-footer';
    import notification from '../components/notification';
    import svgSheet from '../components/icon-sheet';

    export default {
        components: {
            notification,
            pageHeader,
            pageFooter,
            svgSheet,
        },

        computed: {
            routeKey() {
                return this.$route.matched[0]?.path ?? 'home';
            }
        }
    }
</script>
