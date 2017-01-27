<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function __invoke($id)
    {
        return view('user.user', ['user' => User::findOrFail($id)]);
    }


    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->middleware('steam');
    }
}
