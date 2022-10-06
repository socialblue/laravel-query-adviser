<?php
namespace Socialblue\LaravelQueryAdviser\Tests\Feature;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Socialblue\LaravelQueryAdviser\DataListener\QueryListener;
use Socialblue\LaravelQueryAdviser\Tests\TestCase;

class QueryControllerTest extends TestCase {

    /**
     * @test
     */
    public function can_execute_query_without_existing_session()
    {
        $response = $this->get('/query-adviser/api/session/1/query/1/1/exec');
        $response->assertOk();
    }

    /**
     * @test
     */
    public function can_explain_query()
    {
        $response = $this->get('/query-adviser/api/session/1/query/1/1/explain');
        $response->assertOk();
    }

    /**
     * @test
     */
    public function can_execute_query_of_session()
    {
        DB::statement('CREATE TABLE user (id INTEGER, name varchar);');
        DB::insert('INSERT INTO user VALUES(?,?);', [1, 'test']);
        $sessionKey = $this->get('/query-adviser/api/session/start')->json('session_id');

        // reset current url for test
        $this->get('/application/url');

        $query = new QueryExecuted('select * from user where id = ?', [1], 52, DB::connection());
        QueryListener::listen($query);

        $sessions = $this->get("/query-adviser/api/session/$sessionKey/")->json();
        $time = key($sessions);

        $response = $this->get("/query-adviser/api/session/$sessionKey/query/$time/0/exec");
        $response->assertOk();

        $data = $response->json();
        $this->assertCount(1, $data);
        $this->assertEquals([['id' => '1', 'name' => 'test']], $data);
    }

    /**
     * @test
     */
    public function returns_empty_array_when_no_traces_in_session()
    {
        $sessionKey = $this->get('/query-adviser/api/session/start')->json('session_id');
        $sessionData = $this->get("/query-adviser/api/session/$sessionKey/")->json();

        $this->assertSame([], $sessionData);
    }


}
