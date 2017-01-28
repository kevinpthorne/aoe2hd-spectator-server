<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class AccountController extends Controller
{

    function __invoke()
    {
        return view('user.you', ['you' => User::findOrFail($_SESSION['steamid'])]);
    }

    public function __construct()
    {
        $this->middleware('steam');
    }

}
