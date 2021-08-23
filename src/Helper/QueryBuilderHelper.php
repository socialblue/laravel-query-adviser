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
            'query' => self::addBindingsToQueryByBuilder($builder),
            'optimizeQuery' => self::showOptimizedQueryByBuilder($builder),
        ];
    }

    /**
     * @param $builder
     * @return mixed
     */
    public static function getQueryByBuilder($builder)
    {
        return self::addBindingsToQueryByBuilder($builder);
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
            if ($value instanceof \DateTime) {
                $value = $value->format('Y-m-d H:i:s');
            }

            $sql = preg_replace('/\?/', $pdo->quote(addslashes(addslashes($value))), $sql, 1);
        }
        return $sql;
    }

    public static function getServerInfo(): array
    {
        return [
            'info' =>
                DB::connection()
                    ->getPdo()
                    ->getAttribute(\PDO::ATTR_SERVER_INFO),
            'version' =>
                DB::connection()
                    ->getPdo()
                    ->getAttribute(\PDO::ATTR_SERVER_VERSION),
            'client' =>
                DB::connection()
                    ->getPdo()
                    ->getAttribute(\PDO::ATTR_CLIENT_VERSION),
            'status' =>
                DB::connection()
                    ->getPdo()
                    ->getAttribute(\PDO::ATTR_CONNECTION_STATUS),
        ];
    }

    /**
    id – a sequential identifier for each SELECT within the query (for when you have nested subqueries)
    select_type – the type of SELECT query. Possible values are:
    table – the table referred to by the row
    type – how MySQL joins the tables used. This is one of the most insightful fields in the output because it can indicate missing indexes or how the query is written should be reconsidered. Possible values are:
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
        //todo fix to use
        return self::analyze($builder->toSql(), $builder->getBindings());
    }

    /**
     * @param $sql
     * @param $bindings
     * @return array
     */
    public static function analyze($sql, $bindings)
    {
        $query = [
            'sql' => $sql,
            'bindings' => $bindings,
        ];
        $queryData = DB::connection()->select('EXPLAIN ' . $sql, $bindings);

        DB::connection()->getPdo()->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);
        $showWarnings = DB::connection()->getPdo()->query("SHOW WARNINGS");
        $sqlOptimized = $showWarnings->fetchColumn(2);
        DB::connection()->getPdo()->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);

        return [
            'queryParts' => $queryData,
            'query' => $query,
            'optimized' => $sqlOptimized,
        ];
    }

    /**
     * @param $builder
     * @return mixed
     * @throws \Exception
     */
    public static function showOptimizedQueryByBuilder($builder)
    {
        return self::analyzeByBuilder($builder)['optimized'];
    }

    /**
     * @param $builder
     */
    protected static function addBindingsToQueryByBuilder($builder): string
    {
        return self::combineQueryAndBindings($builder->toSql(), $builder->getBindings());
    }
}
