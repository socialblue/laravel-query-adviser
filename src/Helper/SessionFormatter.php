<?php

namespace Socialblue\LaravelQueryAdviser\Helper;

use Illuminate\Support\Arr;

class SessionFormatter {

    public static function format(array $data, string $sessionKey): array
    {
        $flattedSessionData = Arr::flatten($data, 1);

        if (empty($flattedSessionData)) {
            return [];
        }

        $queries = count($flattedSessionData);
        $totalQueryTime = array_sum(array_column($flattedSessionData, 'queryTime'));
        $routes = count(array_unique(array_column($flattedSessionData, 'url')));
        $firstQueryLoggedTime = min(array_keys($data));
        $lastQueryLoggedTime = max(array_keys($data));

        return [
            'sessionKey' => $sessionKey,
            'queries' => $queries,
            'queryTime' => $totalQueryTime,
            'routes' => $routes,
            'firstQueryLogged' => $firstQueryLoggedTime,
            'lastQueryLogged' => $lastQueryLoggedTime,
        ];
    }
}
