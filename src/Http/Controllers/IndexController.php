<?php

namespace Socialblue\LaravelQueryAdviser\Http\Controllers;

use Composer\InstalledVersions;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Socialblue\LaravelQueryAdviser\Helper\QueryBuilderHelper;

/**
 * Class QueryController
 * @package Socialblue\LaravelQueryAdviser\Http\Controllers
 */
class IndexController extends Controller
{
    /**
     * Show view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('QueryAdviser::index');
    }

    public function serverInfo(): array
    {
        return QueryBuilderHelper::getServerInfo();
    }

    public function version()
    {
        $installedVersion = InstalledVersions::getPrettyVersion('socialblue/laravel-query-adviser');
        $latestVersion = Http::get('https://api.github.com/repos/socialblue/laravel-query-adviser/releases/latest')
            ->json('tag_name');

        return (new Response([
            'installed' => $installedVersion,
            'latest' => $latestVersion,
            'update' => version_compare($installedVersion, $latestVersion),
        ]))->header('Access-Control-Allow-Origin', '*');
    }
}
