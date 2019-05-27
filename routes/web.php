<?php
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    Route::get('/query/get', 'QueryController@get');
});