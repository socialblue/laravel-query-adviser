<?php

namespace Socialblue\LaravelQueryAdviser\DataListener;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Socialblue\LaravelQueryAdviser\DataListener\Services\BindingsMapper;
use Socialblue\LaravelQueryAdviser\DataListener\Services\SessionFormatter;
use Socialblue\LaravelQueryAdviser\DataListener\Services\TraceMapper;
use Socialblue\LaravelQueryAdviser\Helper\QueryBuilderHelper;

class QueryListener
{
    public static function listen(QueryExecuted $query)
    {
        if (config('laravel-query-adviser.enable_query_logging') === false) {
            return;
        }

        $sessionKey = self::getSessionKey();

        if (empty($sessionKey)) {
            return;
        }

        $url = url()->current();
        if (str_contains($url, '/query-adviser')) {
            return;
        }

        $time = time();
        $data = self::getFromCache($time, $sessionKey);

        self::putToCache(
            (new SessionFormatter())->format($time, $data, $query),
            $sessionKey
        );
    }

    public static function onQueryException(QueryException $queryException): void
    {
        if (config('laravel-query-adviser.enable_query_logging') === false) {
            return;
        }

        $sessionKey = self::getSessionKey();

        if (empty($sessionKey)) {
            return;
        }

        $url = url()->current();
        if (str_contains($url, '/query-adviser')) {
            return;
        }

        $time = time();
        $data = self::getFromCache($time, $sessionKey);

        $key = count($data[$time]);
        $data[$time][$key] = [
            'time' => $time,
            'timeKey' => $key,
            'errorInfo' => $queryException->errorInfo,
            'backtrace' => $queryException->getTrace(),
            'sql' => QueryBuilderHelper::combineQueryAndBindings($queryException->getSql(), $queryException->getBindings()),
            'rawSql' => $queryException->getSql(),
            'bindings' => (new BindingsMapper())->toCache($queryException->getBindings()),
            'queryTime' => 0,
            'url' => empty($url) ? '/' : $url,
            'referer' => empty($referer) ? '/' : $referer,
        ];
        self::putToCache($data, $sessionKey);
    }


    /**
     * @param $sessionKey
     */
    protected static function putToCache(array $data, $sessionKey): array
    {
        if (count($data) > config('laravel-query-adviser.cache.max_entries')) {
            array_shift($data);
        }

        Cache::put(
            $sessionKey,
            $data,
            config('laravel-query-adviser.cache.ttl')
        );

        return $data;
    }

    /**
     * @param $sessionKey
     * @return array|mixed
     */
    protected static function getFromCache(int $time, $sessionKey)
    {
        $data = Cache::get($sessionKey, []);
        if (! is_array($data)) {
            $data = [];
        }

        if (! isset($data[$time])) {
            $data[$time] = [];
        }

        return $data;
    }

    /**
     * @return \Illuminate\Config\Repository|mixed
     */
    protected static function getSessionKey()
    {
        return Cache::get(config('laravel-query-adviser.cache.session_id'), false);
    }
}
