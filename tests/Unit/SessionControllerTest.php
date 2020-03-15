<?php

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Socialblue\LaravelQueryAdviser\DataListener\QueryListener;
use Socialblue\LaravelQueryAdviser\Http\Controllers\SessionController;
use Socialblue\LaravelQueryAdviser\Tests\TestCase;

class SessionControllerTest extends TestCase {

    /**
     * @test
     */
    public function can_start_new_session()
    {
        $data = (new SessionController())->start(request());
        $cacheData['session_id'] = Cache::get(config('laravel-query-adviser.cache.session_id'));

        $this->assertEquals($data, $cacheData);
    }


    /**
     * @test
     */
    public function can_stop_session()
    {
        $data = (new SessionController())->start(request());
        $cacheData['session_id'] = Cache::get(config('laravel-query-adviser.cache.session_id'));

        $this->assertEquals($data, $cacheData);

        (new SessionController())->stop(request());
        $stopData = Cache::get(config('laravel-query-adviser.cache.session_id'));

        $this->assertEmpty($stopData);
    }

    /**
     * @test
     */
    public function can_get_list()
    {
        $sessionId = (new SessionController())->start(request());

        //fix db connection;
        $query = new QueryExecuted('select * from user where id = ?', [1], 1, DB::connection());
        QueryListener::listen($query);

        $sessionId2 = (new SessionController())->start(request());

        $query = new QueryExecuted('select * from user where id = ?', [2], 52, DB::connection());
        QueryListener::listen($query);

        $query = new QueryExecuted('select * from user where id = ?', [3], 33, DB::connection());
        QueryListener::listen($query);


        $data = (new SessionController())->getList(request());

        $this->assertCount(2, $data);

    }

}