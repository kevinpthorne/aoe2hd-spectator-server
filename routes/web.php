<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', 'GamesController');
Route::get('/gologin', function () {
   return view('login.loginlanding');
});
Route::get('/login/error', function() {
    return 'Oops';
});
Route::get('/game/{id}', 'GameController');
Route::get('/you', 'AccountController');
Route::get('/user/{id}', 'UserController');

Route::get('/login', 'SteamLoginController@login');
Route::get('/logout', 'SteamLoginController@logout');