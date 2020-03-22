<?php
use Illuminate\Support\Facades\Route;

Route::get('/', 'QueryController@index');
Route::get('/session', 'QueryController@index');


/**
 * Api routes
 */
Route::prefix('api')->group(function () {
    Route::get('/query/get', 'QueryController@get');
    Route::get('/query/clear', 'QueryController@clear');
    Route::get('/query/exec', 'QueryController@exec');
    Route::get('/query/explain', 'QueryController@explain');
    Route::get('/query/server-info', 'QueryController@serverInfo');

    Route::get('/session/list', 'SessionController@list');
    Route::get('/session/stop', 'SessionController@stop');
    Route::get('/session/start', 'SessionController@start');
});

