import Session from "../views/session";
import sidePanel from "../components/side-panels/side-panel";
import QueryRoutes from "../../query/routes/queryRoutes";


export default {
    path: '/query-adviser/session/:sessionKey',
    name: 'session',
    components: {default: Session},
    meta: {permission: 1},
    props: {
    default: (route) => {
            return route.params
        }
    },
    children: [
        {
            path: 'order-menu',
            name: 'session-order-menu',
            components: {sidePanelLeft: sidePanel},
        },
        {
            path: 'clear',
            name: 'session-clear'
        },
        ...QueryRoutes,
    ]
};
