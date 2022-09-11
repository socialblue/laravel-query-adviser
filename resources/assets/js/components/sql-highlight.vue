<template>
    <code class="sql-hl" >
        <pre><span v-for="segment in segments" :class="`sql-hl-${segment.name}`">{{segment.content}}</span></pre>
    </code>
</template>

<script>
    import { format } from 'sql-formatter';
    import { getSegments } from 'sql-highlight';

    export default {
        props: {
            sql: {
                type: String,
                default: () => "",
            },
        },

        computed: {
            formatted() {
                return format(`${this.sql};`, {
                    language: 'mysql',
                    keywordCase: 'lower',
                    tabWidth: 4,
                    linesBetweenQueries: 2,
                });
            },

            segments() {
                return getSegments(this.formatted);
            },
        },
        methods: {
            copyToClipboard() {

            }
        }
    }

</script>
