@extends('layouts.app')

@if($user !== false)
    @section('title', $user->name)
@else
    @section('title', '?')
@endif

@section('content')

    <div class="row well well-lg">

        @if($user !== false)

            <div class="col-md-12">
                <img src="{{$user->avatar}}" width="64" height="64"/>
            </div>

            <div class="row">
                @if($user->games->isEmpty())
                    <div style="text-align: center;">
                        <h5>No games yet!</h5>
                    </div>
                @else
                    <table class="table">
                        <tr>
                            <th></th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Duration</th>
                            <th></th>
                        </tr>
                        @foreach($user->games as $game)
                            <tr>
                                <td><img src="{{$user->avatar}}" width="32" height="32"/></td>
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

        @else
            <div class="row">

                <div style="text-align: center;">
                    <h5>Haven't seen them around...</h5>
                </div>

            </div>
        @endif

    </div>

@endsection