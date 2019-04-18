<?php

namespace Socialblue\LaravelQueryAdviser;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Socialblue\LaravelQueryAdviser\Skeleton\SkeletonClass
 */
class LaravelQueryAdviserFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-query-adviser';
    }
}
