<?php

namespace Socialblue\LaravelQueryAdviser\DataListener\Services;

use Socialblue\LaravelQueryAdviser\Helper\QueryBuilderHelper;

class SessionFormatter
{
    public function format($time, $data, $query): array
    {
        $referer = request()->headers->get('referer');
        $url = url()->current();
        $possibleTraces = (new TraceMapper())->get();
        $key = count($data[$time]);

        $data[$time][$key] = [
            'time' => $time,
            'timeKey' => $key,
            'backtrace' => $possibleTraces,
            'sql' => QueryBuilderHelper::combineQueryAndBindings($query->sql, $query->bindings),
            'rawSql' => $query->sql,
            'bindings' => (new BindingsMapper())->toCache($query->bindings),
            'queryTime' => $query->time,
            'url' => empty($url) ? '/' : $url,
            'referer' => empty($referer) ? '/' : $referer,
        ];

        return $data;
    }
}
