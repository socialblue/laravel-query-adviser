<?php

namespace Socialblue\LaravelQueryAdviser;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
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
        /*
         * Optional methods to load your package assets
         */
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'QueryAdviser');

        Route::group([
            'prefix' => 'query-adviser',
            'namespace' => 'Socialblue\LaravelQueryAdviser\Http\Controllers',
            'middleware' =>'web',
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });

        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/socialblue/laravel-query-adviser'),
        ], 'public');

        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('laravel-query-adviser.php'),
        ], 'config');

        $this->bootLaravelQueryAdviser();
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laravel-query-adviser');

        // Register the main class to use with the facade
        $this->app->singleton('laravel-query-adviser', function () {
            return new LaravelQueryAdviser;
        });
    }

    /**
     * Add helper functions to app
     */
    protected function bootLaravelQueryAdviser()
    {
        DB::listen(static function ($query) {
            QueryListener::listen($query);
        });

        Builder::macro('dd', function () {
            dd(QueryBuilderHelper::infoByBuilder($this));
        });

        Builder::macro('dump', function () {
            dump(QueryBuilderHelper::infoByBuilder($this));
        });
    }
}
