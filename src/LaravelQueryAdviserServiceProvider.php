<?php

namespace Socialblue\LaravelQueryAdviser;

use Socialblue\LaravelQueryAdviser\Helper\QueryBuilderHelper;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

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
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravel-query-adviser');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-query-adviser');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        Route::group([
            'prefix' => 'query-adviser',
            'namespace' => 'Socialblue\LaravelQueryAdviser\Http\Controllers',
            'middleware' =>'web',
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });


        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('laravel-query-adviser.php'),
            ], 'config');

            // Publishing the views.
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-query-adviser'),
            ], 'views');

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/laravel-query-adviser'),
            ], 'assets');*/

            // Publishing the translation files.
//            $this->publishes([
//                __DIR__.'/../resources/lang' => resource_path('lang/vendor/laravel-query-adviser'),
//            ], 'lang');

            // Registering package commands.
            // $this->commands([]);
        }

        DB::listen(function($query) {
            $data = Cache::get(config('laravel-query-adviser.cache.key'), []);
            $data[time()][] = [$query->sql, $query->bindings, $query->time];

            if (count($data) > config('laravel-query-adviser.cache.max_entries')) {
                array_shift($data);
            }
            Cache::put(config('laravel-query-adviser.cache.key'), $data, config('laravel-query-adviser.cache.ttl'));
        });
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
}
