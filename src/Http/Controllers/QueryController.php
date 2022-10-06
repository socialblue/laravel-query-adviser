<?php

namespace Socialblue\LaravelQueryAdviser\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Socialblue\LaravelQueryAdviser\DataListener\Services\BindingsMapper;
use Socialblue\LaravelQueryAdviser\Helper\QueryBuilderHelper;

/**
 * Class QueryController
 * @package Socialblue\LaravelQueryAdviser\Http\Controllers
 */
class QueryController extends Controller
{
    /**
     * Execute query
     */
    public function exec(string $sessionKey, string $time, string $timeKey, Request $request): array
    {
        $data = Cache::get($sessionKey);

        if (isset($data[$time][$timeKey])) {
            $query = $data[$time][$timeKey];
            return DB::connection()->select($query['rawSql'], (new BindingsMapper())->fromCache($query['bindings']));
        }

        return [];
    }

    /**
     * Use explain for query
     */
    public function explain(string $sessionKey, string $time, string $timeKey, Request $request): array
    {
        $data = Cache::get($sessionKey);
        if (isset($data[$time][$timeKey])) {
            $query = $data[$time][$timeKey];
            return QueryBuilderHelper::analyze($query['rawSql'], (new BindingsMapper())->fromCache($query['bindings']));
        }

        return [
            'queryParts' => "",
            'query' => "",
            'optimized' => "",
        ];
    }
}
