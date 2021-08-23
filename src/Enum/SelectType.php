<?php

namespace Socialblue\LaravelQueryAdviser\Enum;

final class SelectType
{
    public const SIMPLE = 'simple SELECT query without any subqueries or UNIONs';

    public const PRIMARY = 'SELECT is in the outermost query in a JOIN';

    public const SUBQUERY = 'SELECT is part of a subquery within a FROM clause';

    public const DEPENDENT_SUBQUERY = 'a subquery which is dependent upon on outer query';

    public const UNCACHEABLE_SUBQUERY = 'a subquery which is not cacheable (there are certain conditions for a query to be cacheable)';

    public const UNION = 'the SELECT is the second or later statement of a UNION';

    public const DEPENDENT_UNION = 'the second or later SELECT of a UNION is dependent on an outer query';

    public const UNION_RESULT = 'SELECT is a result of a UNION';

    public const DERIVED = 'DERIVED';

    public static function get($type): string
    {
        $type = strtoupper($type);
        $type = str_replace(" ", "_", $type);

        return (string) constant("self::$type");
    }
}
