<?php
namespace Socialblue\LaravelQueryAdviser\Tests\Unit;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Socialblue\LaravelQueryAdviser\DataListener\QueryListener;
use Socialblue\LaravelQueryAdviser\Http\Controllers\SessionController;
use Socialblue\LaravelQueryAdviser\Tests\TestCase;

class QueryListenerTest extends TestCase {

    /** @test */
    public function query_listener_stores_all_data_keys_in_cache()
    {
        $sessionId = (new SessionController())->start(request());

        //fix db connection;
        $query = new QueryExecuted('select * from user where id = ?', [1], 1, DB::connection());
        QueryListener::listen($query);

        $data = Cache::get($sessionId['session_id'], []);

        $this->assertCount(1, $data);

        $data = current($data);

        $this->assertCount(1, $data);

        $data = current($data);

        $this->assertArrayHasKey('time', $data);
        $this->assertArrayHasKey('timeKey', $data);
        $this->assertArrayHasKey('rawSql', $data);
        $this->assertArrayHasKey('bindings', $data);
        $this->assertArrayHasKey('backtrace', $data);
        $this->assertArrayHasKey('queryTime', $data);
        $this->assertArrayHasKey('url', $data);

        $this->assertEquals('select * from user where id = \'1\'', $data['sql']);

    }

}
