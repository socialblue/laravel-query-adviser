<?php

namespace Socialblue\LaravelQueryAdviser\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Socialblue\LaravelQueryAdviser\Service\Session;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Class SessionController
 * @package Socialblue\LaravelQueryAdviser\Http\Controllers
 */
class SessionController extends Controller
{
    /**
     * Start a new query log session
     */
    public function start(): array
    {
        return Session::start();
    }

    /**
     * Stop current query log session
     */
    public function stop(): array
    {
        return Session::stop();
    }

    /**
     * Get the data of a session
     *
     * @return mixed
     */
    public function show(string $sessionKey, Request $request): array
    {
        return Session::get($sessionKey);
    }

    /**
     * Get the status of session
     */
    public function isActive(): array
    {
        return Session::status();
    }

    /**
     * Export a session
     */
    public function export(string $sessionKey, Request $request): BinaryFileResponse
    {
        return Session::export($sessionKey);
    }

    /**
     * Import a session
     */
    public function import(Request $request): array
    {
        $data = json_decode($request->file('session')->getContent() ?? "[]", true);
        return Session::import($data);
    }

    /**
     * Get the session list
     */
    public function getList(): array
    {
        return Session::sessions();
    }

    /**
     * Clear session list
     */
    public function clear(): array
    {
        return Session::clearList();
    }
}
