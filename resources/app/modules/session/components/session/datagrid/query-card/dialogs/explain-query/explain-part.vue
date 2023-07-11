<template>
    <div class="explain-part">
        <page-header :name="tableExplainData['table']" />
        <section class="explain-part-content">
            <div class="explain-part-info">
               <h1>Select type</h1>
               <h2>{{tableExplainData['select_type']}}</h2>
            </div>
            <div class="explain-part-info">
                <h1>Type</h1>
                <h2>{{tableExplainData['type']}}</h2>
            </div>
            <div class="explain-part-info">
                <h1>Key length</h1>
                <h2>{{formatNumber(tableExplainData['key_len']) ?? '-'}}</h2>
            </div>
            <div class="explain-part-info">
                <h1>Rows</h1>
                <h2>{{formatNumber(tableExplainData['rows'])}}</h2>
            </div>
            <div class="explain-part-info">
                <h1>Filtered</h1>
                <h2>{{formatNumber(tableExplainData['filtered'])}} %</h2>
            </div>
            <div class="explain-part-info large-text">
                <h1>Key used</h1>
                <h2>{{tableExplainData['key'] ?? '-' }}</h2>
            </div>
            <div class="explain-part-info large-text pull-right">
                <h1>Extra</h1>
                <h2>{{tableExplainData['Extra'] ?? '-'}}</h2>
            </div>
            <div class="explain-part-info large-text">
                <h1>Possible keys</h1>
                <h2>{{(tableExplainData['possible_keys'] ?? '-').replaceAll(',', ', ')}}</h2>
            </div>
        </section>
   </div>
</template>

<script setup>
    import PageHeader from "../../../../../../../default/components/page-header.vue";

    const props = defineProps({
        table: {
            type: String
        },

        tableExplainData: {
            type: Object
        }
    });

    function formatNumber(number) {
        return new Intl.NumberFormat('en-US', {
            maximumFractionDigits: 0
        }).format(number);
    }

</script>

<style lang="scss">
    $h1-font-size: 12px;
    $h1-font-weight: 900;
    $h1-color: rgba(188, 188, 188, 0.9);

    $h2-font-size: 16px;
    $h2-font-weight: 900;
    $h2-color: rgba(33, 33, 33, 0.9);


    .explain-part {
        .explain-part-content {
            display: flex;
            flex-direction: row;
            padding-bottom: 20px;
            padding-left: 12px;
            overflow-x: auto;

            .explain-part-info {
                margin: 0 20px;
                max-width: 32vw;
                min-width: 80px;

                &.pull-right {
                    margin-right: auto;
                }

                &.large-text {
                    min-width: 200px;
                    line-break: anywhere;
                }

                h1 {
                    color: $h1-color;
                    font-size: $h1-font-size;
                    font-weight: $h1-font-weight;
                    margin: 8px 0;
                }

                h2 {
                    color: $h2-color;
                    font-size: $h2-font-size;
                    font-weight: $h2-font-weight;
                    margin: 0;
                }
            }
        }
    }

</style>
