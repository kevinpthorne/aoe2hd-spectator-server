<?php

namespace App\Http\Controllers;

use App\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    function __invoke($id)
    {
        return view('game.game', ['game' => Game::findOrFail($id)]);
    }

}
