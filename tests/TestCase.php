<?php

namespace Socialblue\LaravelQueryAdviser\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $app['config']->set('laravel-query-adviser.cache.key', 'test');
        $app['config']->set('laravel-query-adviser.enable_query_logging', true);
        $app['config']->set('laravel-query-adviser.cache.ttl', 60);
        $app['config']->set('laravel-query-adviser.cache.max_entries', 60);
    }
}