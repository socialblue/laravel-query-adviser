<?php

namespace Socialblue\LaravelQueryAdviser\Enum;

final class JoinType
{
    public const SYSTEM = 'the table has only zero or one row';

    public const CONST = 'the table has only one matching row which is indexed. This is the fastest type of join because the table only has to be read once and the column’s value can be treated as a constant when joining other tables';

    public const EQ_REF = 'all parts of an index are used by the join and the index is PRIMARY KEY or UNIQUE NOT NULL. This is the next best possible join type.';

    public const REF = 'all of the matching rows of an indexed column are read for each combination of rows from the previous table. This type of join appears for indexed columns compared using = or <=> operators.';

    public const FULLTEXT = 'the join uses the table’s FULLTEXT index';

    public const REF_OR_NULL = 'this is the same as ref but also contains rows with a null value for the column.';

    public const DEPENDENT_UNION = 'the second or later SELECT of a UNION is dependent on an outer query';

    public const INDEX_MERGE = 'SELECT is a result of a UNION';

    public const UNION_RESULT = 'the join uses a list of indexes to produce the result set. The key column of EXPLAIN‘s output will contain the keys used';

    public const UNIQUE_SUBQUERY = 'an IN subquery returns only one result from the table and makes use of the primary key';

    public const INDEX_SUBQUERY = 'the same as unique_subquery but returns more than one result row.';

    public const RANGE = 'an index is used to find matching rows in a specific range, typically when the key column is compared to a constant using operators like BETWEEN, IN, >, >=, etc.';

    public const INDEX = 'he entire index tree is scanned to find matching row';

    public const ALL = 'the entire table is scanned to find matching rows for the join. This is the worst join type and usually indicates the lack of appropriate indexes on the table.';

    public static function get($type): string
    {
        $type = strtoupper($type);
        $type = str_replace(" ", "_", $type);

        return (string) constant("self::$type");
    }
}
