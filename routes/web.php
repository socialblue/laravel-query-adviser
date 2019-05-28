<?php
use Illuminate\Support\Facades\Route;

Route::get('/query', 'QueryController@index');

Route::prefix('api')->group(function () {
    Route::get('/query/get', 'QueryController@get');
});