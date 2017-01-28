<?php

namespace App\Http\Controllers;

use App\Services\SteamService;
use Illuminate\Http\Request;

class SteamLoginController extends Controller
{

    private $steamService;

    /**
     * SteamLoginController constructor.
     */
    public function __construct(SteamService $steamService)
    {
        $this->steamService = $steamService;
    }

    public function login() {
        if(session_status() === PHP_SESSION_NONE) {
            ob_start();
            session_start();
        }
        return $this->steamService->login();
    }

    public function logout() {
        session_unset();
        session_destroy();
        return $this->steamService->logout();
    }
}
