<template>
    <div :class="['sql-hl', {'light-mode': lightMode}]" >
        <div class="sql" title="copy to clipboard" v-clipboard>
            <span v-for="segment in segments" :class="`sql-hl-${segment.name}`">
                {{segment.content}}
            </span>
        </div>
        <div class="copy material-icons" title="copy to clipboard" v-clipboard>
            content_copy
        </div>
        <div class="copy-feedback material-icons">done</div>
    </div>
</template>

<script setup>
    import { format } from 'sql-formatter';
    import { getSegments } from 'sql-highlight';
    import {computed, ref} from "vue";

    const props = defineProps({
        sql: {
            type: String,
            default: () => "",
        },

        lightMode: {
            type: Boolean,
            default: () => false,
        },
    });

    const hasClipboardPermission = ref(false);

    function requestClipboardPermission() {
        if (hasClipboardPermission.value) {
            return;
        }

        navigator.permissions.query(
            { name: 'clipboard-write' }
        ).then((result) => {
            hasClipboardPermission.value = (result.state === 'granted' || result.state === 'prompt');
        });
    }

    function handleClick() {
        requestClipboardPermission();
        const text = formatted.value ?? "no sql";
        try {
            navigator.clipboard.writeText(text).then(() => {
            }).catch((error) => {
                fallbackClipboardMethod();
            });
        } catch (e) {
            fallbackClipboardMethod();
        }
    }

    function fallbackClipboardMethod() {
        const text = formatted.value ?? "no sql";
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
    }


    function handlePointerDown(event) {
        event.preventDefault();
    }

    // Register the v-clipboard directive
    const vClipboard = {
        mounted(el, binding) {
            el.addEventListener('click', handleClick);
            el.addEventListener('pointerup', handleClick);
            el.addEventListener('pointerdown', handlePointerDown);
        },

        unmounted(el) {
            el.removeEventListener('click', handleClick);
            el.removeEventListener('pointerup', handleClick);
            el.removeEventListener('pointerdown', handlePointerDown);
        }
    }


    const formatted = computed(() => {
        return format(`${props.sql};`, {
            language: 'mysql',
            keywordCase: 'lower',
            tabWidth: 4,
            linesBetweenQueries: 2,
        });
    });

    const segments = computed(() => {
        return getSegments(formatted.value);
    });

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
    position: relative;

    padding: $sql-hl-padding;
    background: $sql-hl-background-color;

    &.light-mode {
      filter: invert($sql-hl-white-mode);
    }



    .sql:active + .copy {
        visibility: visible;
        opacity: 1;
        transform: scale(2);
        filter: invert(0);
        background: rgba(255, 255, 255, 0.9);


        & + div.copy-feedback {
            visibility: visible;
            opacity: 1;
            transform: scale(1.5);
        }
    }


    > div:hover {
        cursor: pointer;
        flex-grow: 1;
        &.copy:active {
            visibility: visible;
            opacity: 1;
            transform: scale(2);
            filter: invert(0);
            background: rgba(255, 255, 255, 0.9);

            & + div.copy-feedback {
                visibility: visible;
                opacity: 1;
                transform: scale(1.5);
            }
        }

        & + div.copy {
            visibility: visible;
            opacity: 1;
            transform: scale(1.5);
        }
    }




    &:not(.light-mode) {
        div {
            &.copy {
                filter: invert($sql-hl-white-mode);
            }
        }
    }


    div {
        display: $sql-hl-display;
        background: $sql-hl-background-color;
        word-wrap: normal;
        overflow-x: auto;
        padding: 20px 24px;

        &.copy, &.copy-feedback {
            position: absolute;
            visibility: hidden;
            opacity: 0;
            top: 8px;
            right: 8px;
            padding: 4px;
            font-size: 12px;
            cursor: pointer;

        }
        &.copy-feedback {
            background: transparent;
            color: $sql-hl-string-color;
            z-index: 2;
            font-size: 28px;
            font-weight: 900;
            transition: all 0.2s 0.2s;
        }

        &.copy {
            color: $sql-hl-string-color;
            background-color: $sql-hl-background-color;
            border: rgba(233,233,233,.9);
            border-radius: 50%;
            transition: all 0.2s ease-in-out;

            &:hover {
                visibility: visible;
                opacity: 1;
                transform: scale(1.5);
            }
        }

        span {
            padding: 0;
            margin: 0;
            position: relative;
            height: 10px;
            white-space: pre;
            font-size: 12px;
            font-weight: 900;
        }

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
