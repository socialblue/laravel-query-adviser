<?php
namespace Socialblue\LaravelQueryAdviser\Tests\Feature;

use Illuminate\Support\Facades\Http;
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

    /**
     * @test
     */
    public function can_show_version() {
        $response = json_decode('{
  "url": "https://api.github.com/repos/socialblue/laravel-query-adviser/releases/80881851",
  "assets_url": "https://api.github.com/repos/socialblue/laravel-query-adviser/releases/80881851/assets",
  "upload_url": "https://uploads.github.com/repos/socialblue/laravel-query-adviser/releases/80881851/assets{?name,label}",
  "html_url": "https://github.com/socialblue/laravel-query-adviser/releases/tag/2.2.1",
  "id": 80881851,
  "author": {
    "login": "mbroersen",
    "id": 2418774,
    "node_id": "MDQ6VXNlcjI0MTg3NzQ=",
    "avatar_url": "https://avatars.githubusercontent.com/u/2418774?v=4",
    "gravatar_id": "",
    "url": "https://api.github.com/users/mbroersen",
    "html_url": "https://github.com/mbroersen",
    "followers_url": "https://api.github.com/users/mbroersen/followers",
    "following_url": "https://api.github.com/users/mbroersen/following{/other_user}",
    "gists_url": "https://api.github.com/users/mbroersen/gists{/gist_id}",
    "starred_url": "https://api.github.com/users/mbroersen/starred{/owner}{/repo}",
    "subscriptions_url": "https://api.github.com/users/mbroersen/subscriptions",
    "organizations_url": "https://api.github.com/users/mbroersen/orgs",
    "repos_url": "https://api.github.com/users/mbroersen/repos",
    "events_url": "https://api.github.com/users/mbroersen/events{/privacy}",
    "received_events_url": "https://api.github.com/users/mbroersen/received_events",
    "type": "User",
    "site_admin": false
  },
  "node_id": "RE_kwDOCtoaUs4E0ii7",
  "tag_name": "2.2.1",
  "target_commitish": "master",
  "name": "2.2.1",
  "draft": false,
  "prerelease": false,
  "created_at": "2022-10-25T06:43:15Z",
  "published_at": "2022-10-25T06:44:50Z",
  "assets": [

  ],
  "tarball_url": "https://api.github.com/repos/socialblue/laravel-query-adviser/tarball/2.2.1",
  "zipball_url": "https://api.github.com/repos/socialblue/laravel-query-adviser/zipball/2.2.1",
  "body": "## What\'s Changed\r\n* build: fix build js app by @mbroersen in https://github.com/socialblue/laravel-query-adviser/pull/126\r\n\r\n\r\n**Full Changelog**: https://github.com/socialblue/laravel-query-adviser/compare/2.2.0...2.2.1",
  "mentions_count": 1
}', true);

        $this->withoutMix();
        Http::fake(
            [
                'github.com/*' => Http::response($response),
            ],
        );

        $data = $this->get('/query-adviser/api/version')->json();

        $this->assertSame([
            'installed' => 'dev-develop',
            'latest' => '2.2.1',
            'update' => -1
        ], $data);

    }

}
