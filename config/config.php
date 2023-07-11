<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    'cache' => [
        'key' => env('QUERY_ADVISER_CACHE_KEY', 'query_adviser_recent'),
        'ttl' => env('QUERY_ADVISER_CACHE_TTL', 25200),
        'max_entries' => env('QUERY_ADVISER_CACHE_MAX_ENTRIES', 10000),
        'display_key' => env('QUERY_ADVISER_CACHE_DISPLAY_KEY', 'query_adviser_display'),
        'session_id' => env('QUERY_ADVISER_CACHE_SESSION_ID', 'query_advisor_sessions_id'),
        'session' => [
            'key' => env('QUERY_ADVISER_CACHE_SESSION_KEY', 'query_advisor_sessions'),
            'key_list' => env('QUERY_ADVISER_CACHE_SESSION_KEY_LIST', 'query_advisor_session_key_list'),
            'max' => env('QUERY_ADVISER_CACHE_SESSION_MAX', 25)
        ],
        'session_max_time' => env('QUERY_ADVISER_CACHE_SESSION_MAX_TIME', 120),
    ],
    'macros' => [
        'dd' => 'qadd',
        'dump' => 'qadump',
    ],
    'enable_query_logging' => env('QUERY_ADVISER_ENABLE_QUERY_LOGGING', true)
];
