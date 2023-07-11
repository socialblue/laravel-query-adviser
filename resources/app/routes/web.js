import {createRouter, createWebHashHistory} from 'vue-router';

import sessionRoutes from '../modules/session/routes/session-routes.js';

const router = createRouter({
    mode: 'history',
    history: createWebHashHistory(),
    routes: [
        ...sessionRoutes,
    ],
})

export default router;
