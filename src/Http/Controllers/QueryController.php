<?php

namespace Socialblue\LaravelQueryAdviser\Http\Controllers;

use Illuminate\Http\Request;

class QueryController extends Controller
{
    public function get(Request $request)
    {
        return Cache::get(config('laravel-query-adviser.cache.key'), []);
    }
}
