<?php

namespace Socialblue\LaravelQueryAdviser;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Socialblue\LaravelQueryAdviser\DataListener\QueryListener;
use Socialblue\LaravelQueryAdviser\Helper\QueryBuilderHelper;

class LaravelQueryAdviserServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'QueryAdviser');

        Route::group([
            'prefix' => 'query-adviser',
            'namespace' => 'Socialblue\LaravelQueryAdviser\Http\Controllers',
            'middleware' => 'web',
        ], function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        });

        $this->publishes([
            __DIR__ . '/../public' => public_path('vendor/socialblue/laravel-query-adviser'),
        ], 'public');

        $this->publishes([
            __DIR__ . '/../public' => public_path('vendor/socialblue/laravel-query-adviser'),
        ], 'public');

        $this->publishes([
            __DIR__ . '/../config/config.php' => config_path('laravel-query-adviser.php'),
        ], 'config');

        $this->bootLaravelQueryAdviser();
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'laravel-query-adviser');

        $this->app->singleton('laravel-query-adviser', function () {
            return new LaravelQueryAdviser();
        });
    }

    /**
     * Add helper functions to app
     */
    protected function bootLaravelQueryAdviser(): void
    {
        $this->setupQueryLog();
        $this->setExceptionHandler();
        $this->setupMacro();
    }

    private function setupMacro(): void
    {
        \Illuminate\Database\Eloquent\Builder::macro(config('laravel-query-adviser.macros.dd'), function () {
            dd(QueryBuilderHelper::infoByBuilder($this));
        });

        \Illuminate\Database\Query\Builder::macro(config('laravel-query-adviser.macros.dd'), function () {
            dd(QueryBuilderHelper::infoByBuilder($this));
        });

        \Illuminate\Database\Eloquent\Builder::macro(config('laravel-query-adviser.macros.dump'), function () {
            dump(QueryBuilderHelper::infoByBuilder($this));
        });

        \Illuminate\Database\Query\Builder::macro(config('laravel-query-adviser.macros.dump'), function () {
            dump(QueryBuilderHelper::infoByBuilder($this));
        });
    }

    private function setExceptionHandler(): void
    {
        set_exception_handler(function (QueryException $exception) {
            QueryListener::onQueryException($exception);
            throw $exception;
        });
    }

    private function setupQueryLog(): void
    {
        DB::listen(static function ($query) {
            QueryListener::listen($query);
        });
    }
}
