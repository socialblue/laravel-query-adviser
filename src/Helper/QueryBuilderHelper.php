<?php

namespace Socialblue\LaravelQueryAdviser\Helper;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;


class QueryBuilderHelper
{
    public static function infoByBuilder($builder)
    {
        return [
            'toSql' => $builder->toSql(),
            'bindings' => $builder->getBindings(),
            'query' =>  self::addBindingsToQuery($builder),
            'optimizeQuery' => self::showOptimizedQuery($builder)
        ];
    }

    /**
     * @param $builder
     * @return mixed
     */
    public static function getQueryByBuilder($builder)
    {
       return self::addBindingsToQuery($builder);
    }

    /**
     * @param $sql
     * @param $bindings
     * @return string|string[]|null
     */
    public static function combineQueryAndBindings($sql, $bindings)
    {
        $pdo = DB::connection()->getPdo();

        while (strpos($sql, '?') !== false) {
            $value = array_shift($bindings);
            $sql = preg_replace('/\?/', ($pdo->quote($value)), $sql, 1);
        }
        return $sql;
    }


    /**
     *
     *
    id – a sequential identifier for each SELECT within the query (for when you have nested subqueries)
    select_type – the type of SELECT query. Possible values are:
        SIMPLE – the query is a simple SELECT query without any subqueries or UNIONs
        PRIMARY – the SELECT is in the outermost query in a JOIN
        DERIVED – the SELECT is part of a subquery within a FROM clause
        SUBQUERY – the first SELECT in a subquery
        DEPENDENT SUBQUERY – a subquery which is dependent upon on outer query
        UNCACHEABLE SUBQUERY – a subquery which is not cacheable (there are certain conditions for a query to be cacheable)
        UNION – the SELECT is the second or later statement of a UNION
        DEPENDENT UNION – the second or later SELECT of a UNION is dependent on an outer query
        UNION RESULT – the SELECT is a result of a UNION
    table – the table referred to by the row
    type – how MySQL joins the tables used. This is one of the most insightful fields in the output because it can indicate missing indexes or how the query is written should be reconsidered. Possible values are:
        system – the table has only zero or one row
        const – the table has only one matching row which is indexed. This is the fastest type of join because the table only has to be read once and the column’s value can be treated as a constant when joining other tables.
        eq_ref – all parts of an index are used by the join and the index is PRIMARY KEY or UNIQUE NOT NULL. This is the next best possible join type.
        ref – all of the matching rows of an indexed column are read for each combination of rows from the previous table. This type of join appears for indexed columns compared using = or <=> operators.
        fulltext – the join uses the table’s FULLTEXT index.
        ref_or_null – this is the same as ref but also contains rows with a null value for the column.
        index_merge – the join uses a list of indexes to produce the result set. The key column of EXPLAIN‘s output will contain the keys used.
        unique_subquery – an IN subquery returns only one result from the table and makes use of the primary key.
        index_subquery – the same as unique_subquery but returns more than one result row.
        range – an index is used to find matching rows in a specific range, typically when the key column is compared to a constant using operators like BETWEEN, IN, >, >=, etc.
        index – the entire index tree is scanned to find matching rows.
        all – the entire table is scanned to find matching rows for the join. This is the worst join type and usually indicates the lack of appropriate indexes on the table.
    possible_keys – shows the keys that can be used by MySQL to find rows from the table, though they may or may not be used in practice. In fact, this column can often help in optimizing queries since if the column is NULL, it indicates no relevant indexes could be found.
    key – indicates the actual index used by MySQL. This column may contain an index that is not listed in the possible_key column. MySQL optimizer always look for an optimal key that can be used for the query. While joining many tables, it may figure out some other keys which is not listed in possible_key but are more optimal.
    key_len – indicates the length of the index the Query Optimizer chose to use. For example, a key_len value of 4 means it requires memory to store four characters. Check out MySQL’s data type storage requirements to know more about this.
    ref – Shows the columns or constants that are compared to the index named in the key column. MySQL will either pick a constant value to be compared or a column itself based on the query execution plan. You can see this in the example given below.
    rows – lists the number of records that were examined to produce the output. This Is another important column worth focusing on optimizing queries, especially for queries that use JOIN and subqueries.
    Extra – contains additional information regarding the query execution plan. Values such as “Using temporary”, “Using filesort”, etc. in this column may indicate a troublesome query. For a complete list of possible values and their meaning, check out the MySQL documentation.
     *
     *
     *
     *
     * @param $builder
     */
    public static function analyzeByBuilder($builder)
    {
        $statement = DB::connection()->getPdo()->prepare("EXPLAIN EXTENDED " . $builder->toSql());

        if (! ($statement instanceof \PDOStatement)) {
            throw new \Exception("");
        }

        $statement->execute($builder->getBindings());

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param $builder
     * @return mixed
     * @throws \Exception
     */
    public static function showOptimizedQueryByBuilder($builder)
    {
        self::analyze($builder);

        $ws = DB::connection()->getPdo()->prepare("SHOW WARNINGS");
        $ws->execute();

        return $ws->fetchColumn(2);
    }

    /**
     * @param $builder
     * @return string
     */
    protected static function addBindingsToQueryByBuilder($builder): string
    {
       return self::combineQueryAndBindings($builder->toSql(), $builder->getBindings());
    }





}