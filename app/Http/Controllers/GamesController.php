<?php

namespace App\Http\Controllers;

use App\Game;
use Illuminate\Http\Request;

class GamesController extends Controller
{
    public function __invoke()
    {
        return view('game.games', ['live' =>
            Game::whereNull('time_end')
                ->orderBy('time_start', "desc")
                ->take(50)
                ->get(),
            'finished' =>
                Game::whereNotNull('time_end')
                    ->orderBy('time_end', "desc")
                    ->take(50)
                    ->get()
        ]);
    }
}
