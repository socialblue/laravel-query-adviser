<?php

namespace Socialblue\LaravelQueryAdviser\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * Class SessionController
 * @package Socialblue\LaravelQueryAdviser\Http\Controllers
 */
class SessionController extends Controller
{
    /**
     * Start a new query log session
     *
     *
     * @param Request $request
     * @return array
     */
    public function start(Request $request): array
    {
        $sessionId = uniqid('laravel-query-adviser', true);
        $sessionIds = Cache::get(config('laravel-query-adviser.cache.session.key_list'), []);
        $sessionIds[] = $sessionId;

        Cache::put(config('laravel-query-adviser.cache.session.key_list'), $sessionIds, null);
        Cache::put(config('laravel-query-adviser.cache.session_id'), $sessionId, config('laravel-query-adviser.cache.session.max_time'));
        return ['session_id' => $sessionId];
    }

    /**
     * Stop current query log session
     *
     *
     * @param Request $request
     * @return array
     */
    public function stop(Request $request): array
    {
        Cache::forget(config('laravel-query-adviser.cache.session_id'));
        return ['session_id' => ''];
    }

    /**
     * Stop current query log session
     *
     *
     * @param Request $request
     * @return array
     */
    public function isActive(Request $request): array
    {
        return Cache::has(config('laravel-query-adviser.cache.session_id'));
    }


    /**
     * @return array
     */
    public function getList()
    {
        return Cache::get(config('laravel-query-adviser.cache.session.key_list'), []);
    }
}
