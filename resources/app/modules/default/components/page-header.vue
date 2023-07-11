<template>
    <nav class="panel is-primary">
        <div class="panel-heading ">
                <span>
                    {{ name }}
                </span>

            <div class="is-pulled-right" style="display: flex;">
                <slot name="buttons"></slot>
            </div>
        </div>
    </nav>
</template>

<script setup>
    import {defineProps} from "vue";

    const props = defineProps({
        name: {
            type: String
        },
    });
</script>
