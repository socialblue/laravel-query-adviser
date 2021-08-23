<?php
use Illuminate\Support\Facades\Route;

Route::get('/', 'QueryController@index');
Route::get('/session', 'QueryController@index');


/**
 * Api routes
 */
Route::prefix('api')->group(function () {
//    Route::get('/query/get', 'QueryController@get');
    Route::get('/query/clear', 'QueryController@clear');
    Route::get('/query/exec', 'QueryController@exec');
    Route::get('/query/explain', 'QueryController@explain');
    Route::get('/query/server-info', 'QueryController@serverInfo');

    Route::get('/session/clear', 'SessionController@clear');
    Route::get('/session/show', 'SessionController@show');
    Route::get('/session/list', 'SessionController@getList');
    Route::get('/session/stop', 'SessionController@stop');
    Route::get('/session/start', 'SessionController@start');
    Route::get('/session/is-active', 'SessionController@isActive');
    Route::get('/session/export', 'SessionController@export');
    Route::post('/session/import', 'SessionController@import');
});

