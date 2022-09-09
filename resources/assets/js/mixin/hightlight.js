import sqlFormatter from "sql-formatter";
import hljs from 'highlight.js';
import sql from 'highlight.js/lib/languages/sql';

export default {

    methods: {
        prettyPrint(sql) {
            return sql;
        },

        format(sql) {
            return sql;
        }
    },

    beforeCreate() {
        hljs.registerLanguage('sql', sql);
    }
}
