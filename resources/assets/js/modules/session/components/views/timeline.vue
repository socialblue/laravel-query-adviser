<template>
    <div class="timeline">
        <header class="timeline-header">
            <span class="tag is-medium is-primary">Start</span>
        </header>

        <div class="timeline-item is-primary" v-for="key in dataListKey">
            <div class="timeline-marker is-icon button is-info" v-on:click="toggleQueryGroup(key)">
                <span class="material-icons" title="expand">
                    <template v-if="!showQueryGroup(key)">expand_more</template>
                    <template v-if="showQueryGroup(key)">expand_less</template>
                </span>
            </div>
            <div class="timeline-content">
                <p class="heading">
                    {{groupTitle(key)}} ({{dataList[key].length}})
                </p>
                <div>
                    <div class="columns is-multiline" v-if="showQueryGroup(key)">
                        <div class="column" v-for="query in dataList[key]" >
                            <query-block
                                :query="query"
                                :session-key="sessionKey"
                            />

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <header class="timeline-header">
            <span class="tag is-medium is-primary">End</span>
        </header>
    </div>
</template>

<script>
import QueryBlock from "../../../query/components/query-block";
export default {
    name: "timeline",
    components: {QueryBlock},
    props: {
        sessionKey: {
            type: String,
            default: () => '',
        },

        dataListKey: {
            type: Array,
            default: () => [],
        },

        dataList: {
            type: Object,
            default:() => {},
        },

        listType: {
            type: String,
            default: () => 'Time',
        }
    },

    data() {
        return {
            showTime: [],
        }
    },

    methods: {
        showQueryGroup(time) {
            return this.showTime.includes(time);
        },

        toggleQueryGroup(time) {
            if (this.showTime.includes(time)) {
                this.showTime = this.showTime.filter(val => val !== time);
                return;
            }
            this.showTime.push(time);
        },

        groupTitle(value) {
            if (this.listType === 'time') {
                return new Date(value * 1000).toISOString();
            }
            return value;
        },
    }
}
</script>

<style scoped>

</style>
