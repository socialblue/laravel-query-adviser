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

        $url = url()->current();
        if (strpos($url, '/query-adviser') !== false) {
            return;
        }

        $referer = request()->headers->get('referer');

        if (empty($url)) {
            $url = '';
        }

        $data = Cache::get(config('laravel-query-adviser.cache.key'), []);
        if (!is_array($data)) {
            $data = [];
        }

        $possibleTraces = array_filter(debug_backtrace(\DEBUG_BACKTRACE_PROVIDE_OBJECT, 25),
            static function ($trace) {
                return isset($trace['file']) &&
                    strpos($trace['file'], 'vendor/laravel/framework/src') === false &&
                    isset($trace['object']);

            }
        );

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

        $data[time()][] = [
            'backtrace' => $possibleTraces,
            'sql' => QueryBuilderHelper::combineQueryAndBindings($query->sql, $query->bindings),
            'rawsql' => $query->sql,
            'bindings' => $query->bindings,
            'time' => $query->time,
            'url' => $url,
            'referer'=> $referer,
        ];

        if (count($data) > config('laravel-query-adviser.cache.max_entries')) {
            array_shift($data);
        }

        Cache::put(config('laravel-query-adviser.cache.key'), $data, config('laravel-query-adviser.cache.ttl'));
    }
}
