<?php

namespace Socialblue\LaravelQueryAdviser\Http\Controllers;

use Illuminate\Routing\Controller;
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
}
