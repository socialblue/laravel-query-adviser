<?php
use Illuminate\Support\Facades\Route;
use Socialblue\LaravelQueryAdviser\Http\Controllers\QueryController;
use Socialblue\LaravelQueryAdviser\Http\Controllers\SessionController;
use Socialblue\LaravelQueryAdviser\Http\Controllers\IndexController;

// api routes
Route::prefix('api')->group(function () {

    Route::prefix('session')->group(function () {
        // get requests
        Route::get('/', [SessionController::class, 'getList']);
        Route::get('/start', [SessionController::class, 'start']);
        Route::get('/stop', [SessionController::class, 'stop']);
        Route::get('/is-active', [SessionController::class, 'isActive']);
        Route::get('/clear', [SessionController::class, 'clear']);

        // post requests
        Route::post('/import', 'SessionController@import');

        // one session routes
        Route::prefix('{sessionKey}')->group(function () {
            Route::get('/', [SessionController::class, 'show']);
            Route::get('/export', [SessionController::class, 'export']);

            // query functionality
            Route::prefix('query/{time}/{timeKey}/')->group(function () {
                Route::get('/exec', [QueryController::class, 'exec']);
                Route::get('/explain', [QueryController::class, 'explain']);
            });
        });
    });

    Route::get('/server-info', [IndexController::class, 'serverInfo']);
});

// defer all front-end routes to vue-router app
Route::get('/', [IndexController::class, 'index']);
Route::get('{any}', [IndexController::class, 'index'])
    ->where('any','^(?!(api)).*$');
