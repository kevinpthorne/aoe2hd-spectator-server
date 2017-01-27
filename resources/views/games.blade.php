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
                @if(method_exists($live, 'isEmpty'))
                    @foreach ($live as $liveGame)
                        <tr>
                            <td>{{$liveGame->id}}</td>
                            <td>{{$liveGame->owner->name}}</td>
                            <td>{{$liveGame->time_start}}</td>
                            <td><a class="btn btn-primary"
                                   href="aoe2hdspectator://downstream/{{$liveGame->id}}/{{$liveGame->owner->id}}"
                                   role="button">Watch</a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td>{{$live->id}}</td>
                        <td>{{$live->owner->name}}</td>
                        <td>{{$live->time_start}}</td>
                        <td><a class="btn btn-primary"
                               href="aoe2hdspectator://downstream/{{$live->id}}/{{$live->owner->id}}"
                               role="button">Watch</a>
                        </td>
                    </tr>
                @endif

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
                    <th>End Time</th>
                    <th>Duration</th>
                    <th></th>
                </tr>
                @if(method_exists($finished, 'isEmpty'))
                    @foreach ($finished as $game)
                        <tr>
                            <td>{{$game->owner->name}}</td>
                            <td>{{$game->time_start}}</td>
                            <td>{{$game->time_end}}</td>
                            <td>{{($game->time_end - $game->time_start)}}</td>
                            <td><a class="btn btn-primary"
                                   href="aoe2hdspectator://downstream/{{$game->id}}/{{$game->owner->id}}"
                                   role="button">Watch</a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td>{{$finished->owner->name}}</td>
                        <td>{{$finished->time_start}}</td>
                        <td>{{$finished->time_end}}</td>
                        <td>{{($finished->time_end - $finished->time_start)}}</td>
                        <td><a class="btn btn-primary"
                               href="aoe2hdspectator://downstream/{{$finished->id}}/{{$finished->owner->id}}"
                               role="button">Watch</a>
                        </td>
                    </tr>
                @endif
            </table>
        @endif
    </div>
@endsection