<?php

namespace Socialblue\LaravelQueryAdviser\Http\Controllers;

use Socialblue\LaravelQueryAdviser\Service\Session;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

/**
 * Class SessionController
 * @package Socialblue\LaravelQueryAdviser\Http\Controllers
 */
class SessionController extends Controller
{
    /**
     * Start a new query log session
     *
     * @return array
     */
    public function start(): array
    {
        return Session::start();
    }

    /**
     * Stop current query log session
     *
     *
     * @return array
     */
    public function stop(): array
    {
        return Session::stop();
    }

    /**
     * Get the data of a session
     *
     * @param Request $request
     * @return mixed
     */
    public function show(Request $request): array
    {
       return Session::get($request->input('id'));
    }

    /**
     * Get the status of session
     *
     * @return array
     */
    public function isActive(): array
    {
        return Session::status();
    }

    /**
     * Export a session
     *
     * @param Request $request
     * @return BinaryFileResponse
     */
    public function export(Request $request): BinaryFileResponse
    {
        $sessionKey = $request->input('session-key');
        return Session::export($sessionKey);
    }

    /**
     * Import a session
     *
     * @param Request $request
     * @return array
     */
    public function import(Request $request): array
    {
        $data = json_decode($request->file('session')->getContent() ?? "[]", true);
        return Session::import($data);
    }

    /**
     * Get the session list
     *
     * @return array
     */
    public function getList(): array
    {
        return Session::sessions();
    }

    /**
     * Clear session list
     *
     * @return array
     */
    public function clear(): array
    {
        return Session::clearList();
    }
}
