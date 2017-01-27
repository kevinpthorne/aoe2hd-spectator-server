@extends('layouts.app')

@section('title', 'Games')

@section('content')

    <div class="row well well-lg">
        @if($live === false) @elseif($live->isEmpty())
            <h6>No live games!</h6>
        @else
            <table class="table">
                <tr>
                    <th>Player</th>
                    <th>Start Time</th>
                    <th></th>
                </tr>
                @foreach($live as $liveGame)
                    <tr>
                        <td>{{$liveGame->owner->name}}</td>
                        <td>{{$liveGame->time_start}}</td>
                        <td><a class="btn btn-raised btn-primary"
                               href="aoe2hdspectator://downstream/{{$liveGame->id}}/{{$liveGame->owner->id}}"
                               role="button">Watch</a>
                        </td>
                    </tr>
                @endforeach

            </table>
        @endif
    </div>

    <h3>Previous Games</h3>

    <div class="row well well-lg">
        @if($finished === false) @elseif($finished->isEmpty())
            <h6>No games!</h6>
        @else
            <table class="table">
                <tr>
                    <th>Player</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Duration</th>
                    <th></th>
                </tr>
                @foreach($finished as $game)
                    <tr>
                        <td>{{$game->owner->name}}</td>
                        <td>{{$game->time_start}}</td>
                        <td>{{$game->time_end}}</td>

                        @php
                            $start = strtotime($game->time_start);
                            $end = strtotime($game->time_end);
                        @endphp
                        <td>{{date('H:i:s', ($end - $start))}}</td>
                        <td><a class="btn btn-raised btn-primary"
                               href="aoe2hdspectator://downstream/{{$game->id}}/{{$game->owner->id}}"
                               role="button">Watch</a>
                        </td>
                    </tr>
                @endforeach
            </table>
        @endif
    </div>
@endsection