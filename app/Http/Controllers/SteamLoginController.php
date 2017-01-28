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

    public function login(Request $request) {
        return $this->steamService->login($request->session());
    }

    public function logout() {
        return $this->steamService->logout();
    }
}
