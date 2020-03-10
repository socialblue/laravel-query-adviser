<?php

namespace Socialblue\LaravelQueryAdviser\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Socialblue\LaravelQueryAdviser\Helper\QueryBuilderHelper;

/**
 * Class QueryController
 * @package Socialblue\LaravelQueryAdviser\Http\Controllers
 */
class QueryController extends Controller
{
    /**
     * Show view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        return view('QueryAdviser::index');
    }

    /**
     * Get from cache
     *
     *
     * @param Request $request
     * @return array
     */
    public function get(Request $request): array
    {
        return Cache::get(config('laravel-query-adviser.cache.key'), []);
    }

    /**
     * Clears cache
     * @return array
     */
    public function clear():array
    {
        return ['success' => Cache::forget(config('laravel-query-adviser.cache.key'))];
    }

    /**
     * Execute query
     *
     *
     * @param Request $request
     * @return array
     */
    public function exec(Request $request): array
    {
        $data = Cache::get(config('laravel-query-adviser.cache.key'), []);

        if (isset($data[$request->get('time')][$request->get('time-key')])) {
            $query = $data[$request->get('time')][$request->get('time-key')];
            return DB::connection()->select($query['sql'], $query['bindings']);
        }

        return [];
    }

    /**
     * Use explain for query
     *
     *
     * @param Request $request
     * @return array
     */
    public function explain(Request $request): array
    {
        $data = Cache::get(config('laravel-query-adviser.cache.key'), []);
        if (isset($data[$request->get('time')][$request->get('time-key')])) {
            $query = $data[$request->get('time')][$request->get('time-key')];
            return QueryBuilderHelper::analyze($query['sql'], $query['bindings']);
        }

        return ['queryParts' => "", 'query' => "", 'optimized' => ""];
    }

    /**
     *
     * @return array
     */
    public function serverInfo(): array
    {
        return QueryBuilderHelper::getServerInfo();
    }
}
