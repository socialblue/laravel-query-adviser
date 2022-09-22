<?php
namespace Socialblue\LaravelQueryAdviser\Tests\Feature;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Socialblue\LaravelQueryAdviser\DataListener\QueryListener;
use Socialblue\LaravelQueryAdviser\Tests\TestCase;

class SessionControllerTest extends TestCase {

    /**
     * @test
     */
    public function can_start_new_session()
    {
        $data = $this->get('/query-adviser/api/session/start')->json();
        $cacheData['session_id'] = Cache::get(config('laravel-query-adviser.cache.session_id'));

        $this->assertEquals($data, $cacheData);
    }

    /**
     * @test
     */
    public function can_stop_session()
    {
        $data = $this->get('/query-adviser/api/session/start')->json();
        $cacheData['session_id'] = Cache::get(config('laravel-query-adviser.cache.session_id'));

        $this->assertEquals($data, $cacheData);

        $this->get('/query-adviser/api/session/stop');
        $stopData = Cache::get(config('laravel-query-adviser.cache.session_id'));

        $isActive = $this->get('/query-adviser/api/session/is-active')->json();
        $this->assertIsArray($isActive);
        $this->assertArrayHasKey('active', $isActive);
        $this->assertFalse($isActive['active']);

        $this->assertEmpty($stopData);
    }

    /**
     * @test
     */
    public function can_clear_session_list()
    {
        $this->get('/query-adviser/api/session/start');

        // reset current url for test
        $this->get('/application/url');

        $this->addQueryToListener();

        $this->get('/query-adviser/api/session/start');

        // reset current url for test
        $this->get('/application/url');

        $this->addQueryToListener();

        $this->get('/query-adviser/api/session/start');

        // reset current url for test
        $this->get('/application/url');

        $this->addQueryToListener();

        $data = $this->get('/query-adviser/api/session/')->json();
        $this->assertCount(3, $data);

        $this->get('/query-adviser/api/session/clear');

        $data = $this->get('/query-adviser/api/session/')->json();
        $this->assertEmpty($data);
    }


    /**
     * @test
     */
    public function can_get_list()
    {
        Cache::flush();
        $this->get('/query-adviser/api/session/start')->json();

        // reset current url for test
        $this->get('/application/url');

        $this->addQueryToListener(1);

        $this->get('/query-adviser/api/session/start')->json();

        // reset current url for test
        $this->get('/application/url');

        $this->addQueryToListener();
        $this->addQueryToListener(3);


        $data = $this->get('/query-adviser/api/session/')->json();

        $this->assertCount(2, $data);
    }

    /**
     * @test
     */
    public function we_should_be_able_to_see_if_there_is_an_active_session()
    {
        $isInactive = $this->get('/query-adviser/api/session/is-active')->json();

        $this->assertIsArray($isInactive);
        $this->assertArrayHasKey('active', $isInactive);
        $this->assertFalse($isInactive['active']);

        $this->get('/query-adviser/api/session/start');

        $isActive = $this->get('/query-adviser/api/session/is-active')->json();
        $this->assertIsArray($isActive);
        $this->assertArrayHasKey('active', $isActive);
        $this->assertTrue($isActive['active']);
    }

    /**
     * @test
     */
    public function we_should_be_able_to_show_session_data_of_one_session()
    {
        $sessionKey = $this->get('/query-adviser/api/session/start')->json('session_id');

        // reset current url for test
        $this->get('/application/url');

        $this->addQueryToListener();

        $response = $this->get('/query-adviser/api/session/1/');
        $response->assertOk();

        $response = $this->get("/query-adviser/api/session/$sessionKey/");
        $response->assertOk();

        $this->assertCount(1, $response->json());
    }

    /**
     * @test
     */
    public function can_import_sessions()
    {
        $sessionKey = $this->get('/query-adviser/api/session/start')->json('session_id');

        // reset current url for test
        $this->get('/application/url');

        $this->addQueryToListener();

        $data = $this->get("/query-adviser/api/session/$sessionKey/")->getContent();

        $file = \Illuminate\Http\UploadedFile::fake()->createWithContent('session', $data);

        $response = $this->post("/query-adviser/api/session/import" , ['session' => $file]);
        $response->assertOk();
    }

    /**
     * @test
     */
    public function when_disabled_no_sessions_should_be_filled()
    {
        \Illuminate\Support\Facades\Config::set('laravel-query-adviser.enable_query_logging', false);
        $sessionKey = $this->get('/query-adviser/api/session/start')->json('session_id');

        // reset current url for test
        $this->get('/application/url');

        $this->addQueryToListener();

        $data = $this->get("/query-adviser/api/session/$sessionKey/")->json();
        $this->assertEmpty($data);
    }

    /**
     * @test
     */
    public function when_no_sessions_is_started_we_shouldnt_store_session_data()
    {
        // reset current url for test
        $this->get('/application/url');

        $this->addQueryToListener();

        $data = $this->get("/query-adviser/api/session/")->json();
        $this->assertEmpty($data);
    }

    /**
     * @test
     */
    public function when_url_has_query_adviser_skip_storing_sessions_data()
    {
        $sessionKey = $this->get('/query-adviser/api/session/start')->json('session_id');

        $this->addQueryToListener();

        $data = $this->get("/query-adviser/api/session/$sessionKey/")->json();
        $this->assertEmpty($data);
    }


    /**
     * @test
     */
    public function can_export_sessions()
    {
        $sessionKey = $this->get('/query-adviser/api/session/start')->json('session_id');
        // reset current url for test
        $this->get('/application/url');

        $this->addQueryToListener();

        $response = $this->get("/query-adviser/api/session/$sessionKey/export");
        $response->assertOk();

        $response->assertDownload('query-adviser-export.json');
    }

    /**
     * @return void
     */
    private function addQueryToListener(int $id = 2): void
    {
        $query = new QueryExecuted('select * from user where id = ?', [$id], 52, DB::connection());
        QueryListener::listen($query);
    }


}
