import queryExplain from "../components/dialogs/query-explain";
import queryExecute from "../components/dialogs/query-execute";

export default [
    {
        path: '/query-adviser/session/:sessionKey/query/:time/:timeKey/explain',
        name: 'session-query-explain',
        components: {dialog: queryExplain},
        props: {
            dialog: (route) => {
                return route.params;
            }
        }
    },
    {
        path: '/query-adviser/session/:sessionKey/query/:time/:timeKey/execute',
        name: 'session-query-execute',
        components: {dialog: queryExecute},
        props: {
            dialog: (route) => {
                return route.params;
            }
        }
    },
]
