<?php
namespace Socialblue\LaravelQueryAdviser\Tests\Feature;

use Socialblue\LaravelQueryAdviser\Tests\TestCase;

class IndexControllerTest extends TestCase {

    /**
     * @test
     */
    public function can_load_layout()
    {
        $this->withoutMix();
        $response = $this->get('/query-adviser/');
        $response->assertOk();
        $response->assertSeeText('Laravel Query Adviser');
        $response->assertSee('<view-layout />', false);
    }

    /**
     * //add test
     */
    public function can_show_server_info()
    {
// no support for sql_lite
//        $response = $this->get('/query-adviser/api/server-info');
//        $response->assertOk();
    }
}
