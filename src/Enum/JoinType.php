<?php

namespace Socialblue\LaravelQueryAdviser\Enum;

final class JoinType
{
    public CONST SYSTEM = 'the table has only zero or one row';

    public CONST CONST = 'the table has only one matching row which is indexed. This is the fastest type of join because the table only has to be read once and the column’s value can be treated as a constant when joining other tables';

    public CONST EQ_REF = 'all parts of an index are used by the join and the index is PRIMARY KEY or UNIQUE NOT NULL. This is the next best possible join type.';

    public CONST REF = 'all of the matching rows of an indexed column are read for each combination of rows from the previous table. This type of join appears for indexed columns compared using = or <=> operators.';

    public CONST FULLTEXT = 'the join uses the table’s FULLTEXT index';

    public CONST REF_OR_NULL = 'this is the same as ref but also contains rows with a null value for the column.';

    public CONST DEPENDENT_UNION = 'the second or later SELECT of a UNION is dependent on an outer query';

    public CONST INDEX_MERGE = 'SELECT is a result of a UNION';

    public CONST UNION_RESULT = 'the join uses a list of indexes to produce the result set. The key column of EXPLAIN‘s output will contain the keys used';

    public CONST UNIQUE_SUBQUERY = 'an IN subquery returns only one result from the table and makes use of the primary key';

    public CONST INDEX_SUBQUERY = 'the same as unique_subquery but returns more than one result row.';

    public CONST RANGE = 'an index is used to find matching rows in a specific range, typically when the key column is compared to a constant using operators like BETWEEN, IN, >, >=, etc.';

    public CONST INDEX = 'he entire index tree is scanned to find matching row';

    public CONST ALL = 'the entire table is scanned to find matching rows for the join. This is the worst join type and usually indicates the lack of appropriate indexes on the table.';

    public static function get($type): string
    {
        $type = strtoupper($type);
        $type = str_replace(" ", "_", $type);

        return (string) constant("self::$type");
    }
}
