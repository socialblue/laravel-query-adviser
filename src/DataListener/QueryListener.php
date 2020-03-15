<?php

namespace Socialblue\LaravelQueryAdviser\DataListener;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Cache;
use Socialblue\LaravelQueryAdviser\Helper\QueryBuilderHelper;

class QueryListener {

    public static function listen(QueryExecuted $query) {
        if (config('laravel-query-adviser.enable_query_logging') === false) {
            return;
        }

        $sessionKey = self::getSessionKey();

        if (empty($sessionKey)) {
            return;
        }

        $url = url()->current();
        if (strpos($url, '/query-adviser') !== false) {
            return;
        }
        $time = time();
        $referer = request()->headers->get('referer');

        $data = self::getFromCache($time, $sessionKey);

        $possibleTraces = self::formatPossibleTraces(self::getPossibleTraces());

        self::putToCache(
            self::formatData($query, $data, $time, $possibleTraces, $url, $referer),
            $sessionKey
        );
    }

    /**
     * @return array
     */
    protected static function getPossibleTraces(): array
    {
        $possibleTraces = array_filter(debug_backtrace(\DEBUG_BACKTRACE_PROVIDE_OBJECT, 25),
            static function ($trace) {
                return isset($trace['file']) &&
                    strpos($trace['file'], '/var/www/app/') !== false &&
                    isset($trace['object']);

            }
        );
        return $possibleTraces;
    }

    /**
     * @param possibleTraces
     * @return mixed
     */
    protected static function formatPossibleTraces($possibleTraces)
    {
        array_walk($possibleTraces, static function (&$trace) {
            if (method_exists($trace['object'], 'getModel')) {
                $a = $trace['object']->getModel();
                if (is_string($a)) {
                    $trace['model'] = $a;
                } else {
                    $trace['model'] = get_class($a);
                }
            }
            unset($trace['object']);
            unset($trace['args']);
        });
        return $possibleTraces;
    }

    /**
     * @param QueryExecuted $query
     * @param array $data
     * @param int $time
     * @param $possibleTraces
     * @param string $url
     * @param string|null $referer
     * @return array
     */
    protected static function formatData(QueryExecuted $query, array $data, int $time, $possibleTraces, string $url, $referer = ''): array
    {
        $key = count($data[$time]);

        $data[$time][$key] = [
            'time'      => $time,
            'timeKey'   => $key,
            'backtrace' => $possibleTraces,
            'sql'       => QueryBuilderHelper::combineQueryAndBindings($query->sql, $query->bindings),
            'rawSql'    => $query->sql,
            'bindings'  => $query->bindings,
            'queryTime' => $query->time,
            'url'       => empty($url) ? '/' : $url,
            'referer'   => empty($referer) ? '/' : $referer,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param $sessionKey
     * @return array
     */
    protected static function putToCache(array $data, $sessionKey): array
    {
        if (count($data) > config('laravel-query-adviser.cache.max_entries')) {
            array_shift($data);
        }

        Cache::tags(['laravel-query-adviser-sessions'])->put(
            $sessionKey,
            $data,
            config('laravel-query-adviser.cache.ttl')
        );

        return $data;
    }

    /**
     * @param int $time
     * @param $sessionKey
     * @return array|mixed
     */
    protected static function getFromCache(int $time, $sessionKey)
    {

        $data = Cache::tags(['laravel-query-adviser-sessions'])->get($sessionKey, []);
        if (!is_array($data)) {
            $data = [];
        }

        if (!isset($data[$time])) {
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
