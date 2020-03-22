<template>
    <div>
        <page-header></page-header>
        <router-view></router-view>
        <page-footer></page-footer>
        <notification></notification>
    </div>
</template>

<script>
    import pageHeader from '@/components/page-header';
    import pageFooter from '@/components/page-footer';
    import notification from '@/components/notification';


    export default {
        components: {notification, pageHeader, pageFooter},
    }
</script>