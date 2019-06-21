<?php

namespace Socialblue\LaravelQueryAdviser\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Socialblue\LaravelQueryAdviser\Helper\QueryBuilderHelper;

class QueryController extends Controller
{
    public function index() {
        return view('QueryAdviser::index', [
            'queries' => Cache::get(config('laravel-query-adviser.cache.key'), [])
        ]);
    }


    public function get(Request $request)
    {
        return Cache::get(config('laravel-query-adviser.cache.key'), []);
    }

    public function exec(Request $request)
    {
        $data = Cache::get(config('laravel-query-adviser.cache.key'), []);

        if (isset($data[$request->get('time')][$request->get('time-key')])) {
            $query = $data[$request->get('time')][$request->get('time-key')];
            return DB::connection()->select($query['sql'], $query['bindings']);
        }

        return false;
    }


    public function explain(Request $request)
    {
        $data = Cache::get(config('laravel-query-adviser.cache.key'), []);
        $queryData = [];
        $query = '';
        $sqlOptimized ='';
        if (isset($data[$request->get('time')][$request->get('time-key')])) {
            $query = $data[$request->get('time')][$request->get('time-key')];
            $queryData = DB::connection()->select('EXPLAIN EXTENDED '. $query['sql'], $query['bindings']);

            $ws = DB::connection()->getPdo()->prepare("SHOW WARNINGS");
            $ws->execute();

            $sqlOptimized = $ws->fetchColumn(2);

        }

        return ['queryParts' => $queryData, 'query' => $query, 'optimized' => $sqlOptimized];
    }

}
