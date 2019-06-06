<?php

namespace Socialblue\LaravelQueryAdviser\Enum;

final class SelectType
{
    public CONST SIMPLE = 'simple SELECT query without any subqueries or UNIONs';
    public CONST PRIMARY = 'SELECT is in the outermost query in a JOIN';
    public CONST SUBQUERY = 'SELECT is part of a subquery within a FROM clause';
    public CONST DEPENDENT_SUBQUERY = 'a subquery which is dependent upon on outer query';
    public CONST UNCACHEABLE_SUBQUERY = 'a subquery which is not cacheable (there are certain conditions for a query to be cacheable)';
    public CONST UNION = 'the SELECT is the second or later statement of a UNION';
    public CONST DEPENDENT_UNION = 'the second or later SELECT of a UNION is dependent on an outer query';
    public CONST UNION_RESULT = 'SELECT is a result of a UNION';
    public CONST DERIVED = 'DERIVED';
    

    public static function get($type):string
    {
        $type = strtoupper($type);
        $type = str_replace(" ", "_", $type);

        return (string) constant("self::$type");
    }
}