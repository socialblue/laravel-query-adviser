<?php

namespace Socialblue\LaravelQueryAdviser\Tests;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Socialblue\LaravelQueryAdviser\LaravelQueryAdviserServiceProvider;

class TestCase extends BaseTestCase
{
    use InteractsWithViews;

    protected function getPackageProviders($app)
    {
        return [
            LaravelQueryAdviserServiceProvider::class
        ];
    }

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        // Code before application created.

        parent::setUp();

        // Code after application created.
    }


    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app->useStoragePath('/tmp/storage');

        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $app['config']->set('filesystems.default', 'local');
        $app['config']->set('filesystems.disks.local.root', '/tmp/storage/app');
        $app['config']->set('laravel-query-adviser.macros.dd', 'qadd');
        $app['config']->set('laravel-query-adviser.macros.dump', 'qadump');
        $app['config']->set('laravel-query-adviser.cache.key', 'test');
        $app['config']->set('laravel-query-adviser.enable_query_logging', true);
        $app['config']->set('laravel-query-adviser.cache.ttl', 600);
        $app['config']->set('laravel-query-adviser.cache.max_entries', 60);
        $app['config']->set('laravel-query-adviser.cache.session.key', 60);
        $app['config']->set('laravel-query-adviser.cache.session.key_list', 'query_advisor_session_key_list');
        $app['config']->set('laravel-query-adviser.cache.session.max', 25);
        $app['config']->set('laravel-query-adviser.cache.session_id', 'query_advisor_sessions_id');
        $app['config']->set('laravel-query-adviser.cache.display_key', 'query_adviser_display');
        $app['config']->set('laravel-query-adviser.cache.session_max_time', 120);
    }


}
