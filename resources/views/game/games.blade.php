@extends('layouts.app')

@section('title', 'Games')

@section('content')

    <div class="row well well-lg">
        @if($live === false) @elseif($live->isEmpty())
            <h4>No live games!</h4>
        @else
            <table class="table table-hover">
                <tr>
                    <th></th>
                    <th>Player</th>
                    <th>Start Time</th>
                    <th></th>
                </tr>
                @foreach($live as $liveGame)
                    <a href="/game/{{$liveGame->id}}">
                        <tr onclick="window.document.location='/game/{{$liveGame->id}}'" style="cursor: hand;">
                            <td>
                                <a href="/user/{{$liveGame->owner->id}}">
                                    <img src="{{$liveGame->owner->avatar}}" width="32" height="32"/>
                                </a>
                            </td>
                            <td><a href="/user/{{$liveGame->owner->id}}">{{$liveGame->owner->name}}</a></td>
                            <td>{{$liveGame->time_start}}</td>
                            <td><a class="btn btn-raised btn-primary"
                                   href="aoe2hdspectator://downstream/{{$liveGame->id}}/{{$liveGame->owner->id}}"
                                   role="button">Watch</a>
                            </td>
                        </tr>
                    </a>
                @endforeach

            </table>
        @endif
    </div>

    <h3>Previous Games</h3>

    <div class="row well well-lg">
        @if($finished === false) @elseif($finished->isEmpty())
            <h4>No games!</h4>
        @else
            <table class="table table-hover">
                <tr>
                    <th></th>
                    <th>Player</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Duration</th>
                    <th></th>
                </tr>
                @foreach($finished as $game)
                    <tr onclick="window.document.location='/game/{{$game->id}}'" style="cursor: hand;">
                        <td>
                            <a href="/user/{{$game->owner->id}}">
                                <img src="{{$game->owner->avatar}}" width="32" height="32"/>
                            </a>
                        </td>
                        <td><a href="/user/{{$game->owner->id}}">{{$game->owner->name}}</a></td>
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