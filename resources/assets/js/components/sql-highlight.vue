<template>
    <code :class="['sql-hl', {'light-mode': lightMode}]" >
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

            lightMode: {
                type: Boolean,
                default: () => false,
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


<style lang="scss">

$sql-hl-background-color: rgba(0, 0, 0, 1);
$sql-hl-display: block;
$sql-hl-padding: 0;
$sql-hl-white-mode: 100%;

$sql-hl-bracket-color: rgba(150, 239, 233, .9);
$sql-hl-bracket-font-weight: bold;

$sql-hl-function-color: rgba(255, 150, 0, .9);
$sql-hl-function-font-style: italic;
$sql-hl-function-text-transform: uppercase;

$sql-hl-string-color: rgba(0, 220, 40, .9);

$sql-hl-special-color: rgba(213, 213, 0, .9);

$sql-hl-keyword-color: $sql-hl-function-color;
$sql-hl-keyword-text-transform: uppercase;
$sql-hl-keyword-color: $sql-hl-function-color;

$sql-hl-number-color: rgba(0, 120, 220, .9);

.sql-hl {
    display: $sql-hl-display;
    padding: $sql-hl-padding;
    background: $sql-hl-background-color;

    &.light-mode {
      filter: invert($sql-hl-white-mode);
    }

    pre {
        display: $sql-hl-display;
        background: $sql-hl-background-color;

        .sql-hl-bracket {
            color: $sql-hl-bracket-color;
            font-weight: $sql-hl-bracket-font-weight;
        }

        .sql-hl-function {
            color: $sql-hl-function-color;
            font-style: $sql-hl-function-font-style;
            text-transform: $sql-hl-function-text-transform;
        }

        .sql-hl-string {
            color: $sql-hl-string-color;
        }

        .sql-hl-special {
            color: $sql-hl-special-color;
        }

        .sql-hl-keyword {
            color: $sql-hl-keyword-color;
            text-transform: $sql-hl-keyword-text-transform;
        }

        .sql-hl-number {
            color: $sql-hl-number-color;
        }
    }
}
</style>
