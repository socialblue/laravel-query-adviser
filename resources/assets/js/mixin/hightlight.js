import sqlFormatter from "sql-formatter";
import hljs from 'highlight.js/lib/highlight';
import sql from 'highlight.js/lib/languages/sql';

export default {

    methods: {
        prettyPrint(sql) {
            this.$nextTick(() => {
                hljs.highlightBlock(this.$refs.sqlcode);
            });

            return this.format(sql);
        },

        format(sql) {
            return sqlFormatter.format(sql);
        }
    },

    beforeCreate() {
        hljs.registerLanguage('sql', sql);
    }
}