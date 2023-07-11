<template>
    <modal title="Explain query">
        <template #body>
            <loader v-if="data.loading" />
            <explain-part :key="key" v-for="(explainPart, key) in data.explainParts" :table-explain-data="explainPart" />
        </template>
    </modal>


</template>
<script setup>
import Modal from "../../../../../../default/components/modal.vue";
import ExplainPart from "./explain-query/explain-part.vue";
import Loader from "../../../../../../default/components/loader.vue";
import {onMounted, reactive} from "vue";
import {explain} from "../../../../../../query/api/query-api";

const data = reactive({
    loading: true,
    explainParts: [],
});

const props = defineProps({
    sessionKey: {
        type: String,
        default: () => '',
    },

    time: {
        type: String,
        default: () => '',
    },

    timeKey: {
        type: Number,
        default: () => 0,
    },


    sql: {
        type: Object,
        default: () => {
        },
    },
});

function getExplain() {
    this.loading = true;
    console.log(props);

    explain(props.sessionKey, props.time, props.timeKey)
        .then((explainParts) => {
            data.explainParts = explainParts.queryParts;
        }).finally(() => {
            data.loading = false;
        }).catch((e) => {
            console.log(e);
        });
}


onMounted(() => {
    getExplain();
})

function joinType(value) {
    const joinTypes = {
        SYSTEM : 'the table has only zero or one row',
        CONST : 'the table has only one matching row which is indexed. This is the fastest type of join because the table only has to be read once and the column’s value can be treated as a constant when joining other tables',
        EQ_REF : 'all parts of an index are used by the join and the index is PRIMARY KEY or UNIQUE NOT NULL. This is the next best possible join type.',
        REF : 'all of the matching rows of an indexed column are read for each combination of rows from the previous table. This type of join appears for indexed columns compared using : or <:> operators.',
        FULLTEXT : 'the join uses the table’s FULLTEXT index',
        REF_OR_NULL : 'this is the same as ref but also contains rows with a null value for the column.',
        DEPENDENT_UNION : 'the second or later SELECT of a UNION is dependent on an outer query',
        INDEX_MERGE : 'SELECT is a result of a UNION',
        UNION_RESULT : 'the join uses a list of indexes to produce the result set. The key column of EXPLAIN‘s output will contain the keys used',
        UNIQUE_SUBQUERY : 'an IN subquery returns only one result from the table and makes use of the primary key',
        INDEX_SUBQUERY : 'the same as unique_subquery but returns more than one result row.',
        RANGE : 'an index is used to find matching rows in a specific range, typically when the key column is compared to a constant using operators like BETWEEN, IN, >, >:, etc.',
        INDEX : 'he entire index tree is scanned to find matching row',
        ALL : 'the entire table is scanned to find matching rows for the join. This is the worst join type and usually indicates the lack of appropriate indexes on the table.'
    };

    return joinTypes[value.toUpperCase().replace(" ", "_")]
}

function selectType(value) {
    const selectTypes = {
        SIMPLE: 'simple SELECT query without any subqueries or UNIONs',
        PRIMARY: 'SELECT is in the outermost query in a JOIN',
        SUBQUERY: 'SELECT is part of a subquery within a FROM clause',
        DEPENDENT_SUBQUERY: 'a subquery which is dependent upon on outer query',
        UNCACHEABLE_SUBQUERY: 'a subquery which is not cacheable (there are certain conditions for a query to be cacheable)',
        UNION: 'the SELECT is the second or later statement of a UNION',
        DEPENDENT_UNION: 'the second or later SELECT of a UNION is dependent on an outer query',
        UNION_RESULT: 'SELECT is a result of a UNION',
        DERIVED: 'DERIVED',
    };

    return selectTypes[value.toUpperCase().replace(" ", "_")]
}
</script>
