import Sessions from '../view/sessions.vue';
import SessionImport from '../components/session-import.vue';
import SessionRoutes from "../modules/session/routes/session-routes";
import VueRouter from 'vue-router';
import Vue from 'vue';

const routes = [
    SessionRoutes,
    {
        path: '/query-adviser/',
        name: 'sessions',
        component: Sessions,
        meta: {permission: 1},
        children: [
            {
                path: '#export',
                name: 'session-export',
                components: {},
                beforeRouteEnter(to, from, next) {
                    next();
                },
            },
            {
                path: '#import',
                name: 'session-import',
                components: {dialog: SessionImport},
                beforeRouteEnter(to, from, next) {
                    next();
                },
            },
        ]
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
