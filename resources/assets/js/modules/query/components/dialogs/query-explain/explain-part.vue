<template>
    <div class="card">
        <div class="card-header">
                <h1 class="card-header-title is-4">{{tableExplainData['table']}}</h1>
        </div>
        <div class="card-content">
                <div class="level-item has-text-centered">
                    <div title="">
                        <p class="heading">Select type</p>
                        <p>
                            <span class="title is-4">{{tableExplainData['select_type']}}</span>
                            <span class="subtitle is-small is-7">{{tableExplainData['select_type']|selectType}}</span>
                        </p>

                    </div>
                </div>
                <div class="level-item -level-down  has-text-centered">
                    <div title="">
                        <p class="heading">Type</p>
                        <p>
                            <span class="title is-4">{{tableExplainData['type']}}</span>
                            <span class="subtitle is-small is-7">{{tableExplainData['type']|joinType}}</span>
                        </p>
                    </div>
                </div>
                <div class="level-item -level-down has-text-centered">
                    <div title="">
                        <p class="heading">Possible keys</p>
                        <p class="title is-4">{{tableExplainData['possible_keys']}}</p>
                    </div>
                </div>
                <div class="level-item has-text-centered">
                    <div title="">
                        <p class="heading">Key used</p>
                        <p class="title is-4">{{tableExplainData['key']}}</p>
                    </div>
                </div>
                <div class="level-item has-text-centered">
                    <div title="">
                        <p class="heading">Key length</p>
                        <p class="title is-4">{{tableExplainData['key_len']}}</p>
                    </div>
                </div>
                <div class="level-item has-text-centered">
                    <div title="">
                        <p class="heading">Rows</p>
                        <p class="title is-4">{{tableExplainData['rows']}}</p>
                    </div>
                </div>
                <div class="level-item has-text-centered">
                    <div title="">
                        <p class="heading">Filtered</p>
                        <p class="title is-4">{{tableExplainData['filtered']}}</p>
                    </div>
                </div>
        </div>
    </div>
</template>

<script>

    import selectType from "../../../../../mixin/select-type";
    import joinType from "../../../../../mixin/join-type";


    export default {
        name: "explain-part",

        mixins: [selectType, joinType],

        props: {
            'table': {
                type: String
            },

            tableExplainData: {
                type: Object
            }
        }
    }
</script>
