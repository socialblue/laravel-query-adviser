<?php
use Illuminate\Support\Facades\Route;

Route::get('/query', 'QueryController@index');

Route::get('/query/explain', 'QueryController@explain');


Route::prefix('api')->group(function () {
    Route::get('/query/get', 'QueryController@get');
});

Route::prefix('api')->group(function () {
    Route::get('/query/exec', 'QueryController@exec');
});

