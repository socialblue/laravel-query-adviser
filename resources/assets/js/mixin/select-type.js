export default {

    filters: {
      selectType(value) {
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
    }

    
}