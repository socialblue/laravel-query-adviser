<?php

namespace Socialblue\LaravelQueryAdviser\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
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
        if (!is_array($sessionIds)) {
            $sessionIds = [$sessionId];
        }
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
     * @param Request $request
     * @return mixed
     */
    public function show(Request $request)
    {
        $data = Cache::tags(['laravel-query-adviser-sessions'])->get($request->input('id'));

        if (!is_array($data)) {
            return [];
        }

        return $data;
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
        return ['active' => Cache::has(config('laravel-query-adviser.cache.session_id'))];
    }


    /**
     * @return array
     */
    public function getList(): array
    {
        $keys = Cache::get(config('laravel-query-adviser.cache.session.key_list'), []);

        foreach ($keys as $key) {
            $sessionData = Cache::tags(['laravel-query-adviser-sessions'])->get($key) ?? [];
            $flattedSessionData = Arr::flatten($sessionData, 1);

            if (empty($flattedSessionData)) {
                continue;
            }

            $queries = count($flattedSessionData);
            $totalQueryTime = array_sum(array_column($flattedSessionData, 'queryTime'));
            $routes = count(array_unique(array_column($flattedSessionData, 'url')));
            $firstQueryLoggedTime = min(array_keys($sessionData));
            $lastQueryLoggedTime = max(array_keys($sessionData));

            $dataList[] = [
                'sessionKey' => $key,
                'queries' => $queries,
                'queryTime' => $totalQueryTime,
                'routes' => $routes,
                'firstQueryLogged' => $firstQueryLoggedTime,
                'lastQueryLogged' => $lastQueryLoggedTime,
            ];
        }

        return $dataList ?? [];
    }

    /**
     * @return array
     */
    public function clear(): array
    {
        return ['success' => Cache::forget(config('laravel-query-adviser.cache.session.key_list'))];
    }
}
