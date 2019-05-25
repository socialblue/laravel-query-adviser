<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

Route::get('/', view('laravel-query-adviser::index'));


//Route::get('/query/list', function () {
//    return Cache::get(config('laravel-query-adviser.cache.key'), []);
//});