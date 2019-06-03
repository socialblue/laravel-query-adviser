<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    'cache' => [
        'key' => env('QUERY_ADVISER_CACHE_KEY', 'query_adviser_recent'),
        'ttl' => env('QUERY_ADVISER_CACHE_TTL', 3600),
        'max_entries' => env('QUERY_ADVISER_CACHE_MAX_ENTRIES', 10000)
    ]
];