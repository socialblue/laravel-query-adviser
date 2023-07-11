import Sessions from "../views/sessions.vue";
import Session from "../views/session.vue";
import SessionImport from "../components/sessions/dialogs/import.vue";
import SessionClear from "../components/sessions/dialogs/clear.vue";
import ExplainQuery from "../components/session/datagrid/query-card/dialogs/explain-query.vue";
import ExecuteQuery from "../components/session/datagrid/query-card/dialogs/execute-query.vue";
import Download from "../components/session/dialogs/download.vue";
import OrderMenu from "../components/session/sidemenu/order-menu.vue";

export default [
    {
        path: '/',
        name: 'sessions',
        components: {default: Sessions},
        children: [
            {
                path: 'import',
                name: 'session-import',
                components: {dialog: SessionImport},
            },

            {
                path: 'clear',
                name: 'session-clear',
                components: {dialog: SessionClear},
            },
        ]
    },
    {
        path: '/:sessionKey',
        name: 'session',
        components: {default: Session},
        props: { default: true, dialog: true},
        children: [
            {
                path: 'query/:time/:timeKey/explain',
                name: 'session-query-explain',
                components: {dialog: ExplainQuery},
                props: {default: true, dialog: true },
            },
            {
                path: 'query/:time/:timeKey/execute',
                name: 'session-query-execute',
                components: {dialog: ExecuteQuery},
                props: { dialog: (route) => {
                    return route.params;
                }},
            },
            {
                path: 'download',
                name: 'session-download',
                components: {dialog: Download},
                props: { dialog: true },
            },
            {
                path: 'order-menu',
                name: 'session-order-menu',
                components: {sidePanelLeft: OrderMenu},
            }
        ],
    }
];
