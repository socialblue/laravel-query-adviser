import Index from '@/view/index.vue';
import Session from '@/view/session.vue';
import VueRouter from 'vue-router';
import Vue from 'vue';

const routes = [
    {
        path: '/query-adviser/query',
        name: 'session',
        component: Session,
        meta: {permission: 1},
    },
    {
        path: '/query-adviser/',
        name: 'query',
        component: Index,
        meta: {permission: 1},
    },
];

Vue.use(VueRouter);

/**
 * Router options
 *
 * @type {VueRouter}
 */
const router = new VueRouter({
    mode: 'history',
    routes,
});

export default router;