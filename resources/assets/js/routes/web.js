import Sessions from '../view/sessions.vue';
import Session from '../view/session.vue';
import VueRouter from 'vue-router';
import Vue from 'vue';

const routes = [
    {
        path: '/query-adviser/session',
        name: 'session',
        components: {default: Session},
        meta: {permission: 1},
        props: {
            default: (route) => {
                return route.params
            }
        }
    },
    {
        path: '/query-adviser/',
        name: 'sessions',
        component: Sessions,
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
