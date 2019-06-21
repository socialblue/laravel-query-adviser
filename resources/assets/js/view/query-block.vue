<script type="text/ecmascript-6">
    import highlight from "../mixin/hightlight";

    export default {
        props: {
            sql: {
                type: String
            },

            route: {
                type: String
            },

            time: {
                type: Number
            },

            timeKey: {
                type: Number
            },

            executionTime: {
                type: Number
            }
        },

        methods: {
            showExplainDialog() {
                window.EventBus.$emit('show-explain-dialog', [this.time, this.timeKey])
            }
        },

        data: () => {
            return {
                showNotification: false
            }
        },

        computed: {

            execUrl() {
                return `/query-adviser/api/query/exec/?time=${this.time}&time-key=${this.timeKey}`;
            }

        },


        mixins: [highlight]

    }
</script>

<template>
    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                {{route}}
            </p>
            <a href="#" class="card-header-icon" aria-label="more options">
              <span class="icon">
                <i class="fas fa-angle-down" aria-hidden="true"></i>
              </span>
            </a>
        </header>
        <div class="card-content">
            <div class="content">
                <pre class="highlight" ref="sqlcode">{{prettyPrint(sql)}}</pre>
                <time :datetime="(new Date(time*1000)).toISOString()">{{ (new Date(time*1000)).toISOString() }}</time>
            </div>
        </div>
        <footer class="card-footer">
            <a target="_blank" :href="execUrl" class="card-footer-item" title="execute sql">
                <span class="icon is-small">
                    <i class="fas fa-database"></i>
                </span>
            </a>
            <a class="card-footer-item" title="explain sql" v-on:click="showExplainDialog">
                <span class="icon is-small" >
                    <i class="fas fa-procedures"></i>
                </span>
            </a>


            <transition name="component-fade" mode="out-in">
                <nav class="navbar is-fixed-bottom" v-if="showNotification">
                    <div class="notification is-info">
                        <button class="delete" v-on:click="showNotification = false"></button>
                        Query is copied to your clipboard
                    </div>
                </nav>
            </transition>

            <a class="card-footer-item" title="copy sql" v-clipboard="format(sql)" v-clipboard:success="() => {showNotification = true}">
                <span class="icon is-small">
                    <li class="fas fa-clipboard"></li>
                </span>
            </a>
        </footer>
    </div>
</template>