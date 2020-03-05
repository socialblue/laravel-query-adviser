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
     * @return mixed
     */
    public function get(Request $request)
    {
        return Cache::get(config('laravel-query-adviser.cache.key'), []);
    }

    /**
     * Clears cache
     */
    public function clear() {
        return ['success' => Cache::forget(config('laravel-query-adviser.cache.key'))];
    }

    /**
     * Execute query
     *
     *
     * @param Request $request
     * @return array|bool
     */
    public function exec(Request $request)
    {
        $data = Cache::get(config('laravel-query-adviser.cache.key'), []);

        if (isset($data[$request->get('time')][$request->get('time-key')])) {
            $query = $data[$request->get('time')][$request->get('time-key')];
            return DB::connection()->select($query['sql'], $query['bindings']);
        }

        return false;
    }

    /**
     * Use explain for query
     *
     *
     * @param Request $request
     * @return array
     */
    public function explain(Request $request)
    {
        $data = Cache::get(config('laravel-query-adviser.cache.key'), []);
        if (isset($data[$request->get('time')][$request->get('time-key')])) {
            $query = $data[$request->get('time')][$request->get('time-key')];
            return QueryBuilderHelper::analyze($query['sql'], $query['bindings']);
        }

        return ['queryParts' => "", 'query' => "", 'optimized' => ""];
    }
}
