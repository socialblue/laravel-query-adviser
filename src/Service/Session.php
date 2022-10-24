<?php

namespace Socialblue\LaravelQueryAdviser\Service;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Socialblue\LaravelQueryAdviser\Helper\SessionFormatter;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class Session
{
    public static function add(string $sessionId)
    {
        $sessionIds = Cache::get(config('laravel-query-adviser.cache.session.key_list'), []);
        $sessionIds[] = $sessionId;

        Cache::put(config('laravel-query-adviser.cache.session.key_list'), $sessionIds, null);
    }

    public static function clearList(): array
    {
        return [
            'success' => Cache::forget(config('laravel-query-adviser.cache.session.key_list')),
        ];
    }

    /**
     * @param $sessionKey
     * @return BinaryFileResponse
     */
    public static function export($sessionKey)
    {
        $directory = storage_path('laravel-query-adviser/');
        File::makeDirectory($directory, 0777, true, true);
        $file = $directory . 'last-export.json';
        file_put_contents($file, json_encode(self::get($sessionKey)['data'], JSON_PRETTY_PRINT));

        return Response::download(
            $file,
            'query-adviser-export.json',
            ['Content-Type: application/json']
        );
    }

    public static function get(string $sessionKey): array
    {
        $data = Cache::get($sessionKey);

        if (! is_array($data)) {
            return [];
        }

        return [
            'summary' => SessionFormatter::format($data, $sessionKey),
            'data' => $data,
        ];
    }

    /**
     * @param $data
     * @return bool[]
     */
    public static function import($data): array
    {
        $sessionKey = self::newSessionKey();
        self::add($sessionKey);
        Cache::put(
            $sessionKey,
            $data,
            config('laravel-query-adviser.cache.ttl')
        );

        return [
            'success' => true,
        ];
    }

    public static function status(): array
    {
        return [
            'active' => Cache::has(config('laravel-query-adviser.cache.session_id')),
            'active_session_id' => Cache::get(config('laravel-query-adviser.cache.session_id')),
            'has_queries' => Cache::has(Cache::get(config('laravel-query-adviser.cache.session_id'))),
        ];
    }

    /**
     * @return string[]
     */
    public static function start(): array
    {
        $sessionId = self::newSessionKey();
        self::add($sessionId);
        Cache::put(config('laravel-query-adviser.cache.session_id'), $sessionId, config('laravel-query-adviser.cache.session.max_time'));
        return [
            'session_id' => $sessionId,
        ];
    }

    /**
     * @return string[]
     */
    public static function stop(): array
    {
        Cache::forget(config('laravel-query-adviser.cache.session_id'));
        return [
            'session_id' => '',
        ];
    }

    public static function keyList(): array
    {
        return Cache::get(config('laravel-query-adviser.cache.session.key_list'), []);
    }

    /**
     * @return array[]
     */
    public static function sessions(): array
    {
        $keys = self::keyList();

        foreach ($keys as $key) {
            $sessionData = Cache::get($key) ?? [];
            $formattedData = SessionFormatter::format($sessionData, $key);

            if (! empty($formattedData)) {
                $dataList[] = $formattedData;
            }
        }

        return $dataList ?? [];
    }

    protected static function newSessionKey(): string
    {
        return uniqid('laravel-query-adviser', true);
    }
}
