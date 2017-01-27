@extends('layouts.app')

@section('title', 'Games')

@section('content')
    <h1>Games</h1>

    <div class="well well-lg">
        @if($live === false) @elseif($live->isEmpty())
            <h6>No live games!</h6>
        @else
            <table>
                <tr>
                    <th>Game ID</th>
                    <th>Player</th>
                    <th>Start Time</th>
                    <th></th>
                </tr>
                @foreach ($live as $liveGame)
                    <tr>
                        <td>{{$liveGame->id}}</td>
                        <td>{{$liveGame->owner->name}}</td>
                        <td>{{$liveGame->startTime}}</td>
                        <td><a class="btn btn-primary"
                               href="aoe2hdspectator://downstream/{{$liveGame->id}}/{{$liveGame->owner->id}}"
                               role="button">Watch</a>
                        </td>
                    </tr>
                @endforeach
            </table>
        @endif
    </div>

    <h3>Previous Games</h3>

    <div class="well well-lg">
        @if($finished === false) @elseif($finished->isEmpty())
            <h6>No games!</h6>
        @else
            <table>
                <tr>
                    <th>Player</th>
                    <th>Start Time</th>
                    <th></th>
                </tr>
                @foreach ($finished as $game)
                    <tr>
                        <td>{{$game->owner->name}}</td>
                        <td>{{$game->startTime}}</td>
                        <td><a class="btn btn-primary"
                               href="aoe2hdspectator://downstream/{{$game->id}}/{{$game->owner->id}}"
                               role="button">Watch</a>
                        </td>
                    </tr>
                @endforeach
            </table>
        @endif
    </div>
@endsection