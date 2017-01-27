@extends('layouts.app')

@section('title', 'You')

@section('content')

    <div class="well well-lg">
        <div class="row">

            <div class="col-md-3">
                <img src="{{$you->avatar}}" class="img-rounded" width="64" height="64"/>
            </div>
            <div class="col-md-5">
                <h2>{{$you->name}}</h2>
            </div>

        </div>
        <div class="row">

            <div class="col-md-5 col-md-offset-3">
                <a href="/user/{{$you->id}}">View Your Profile</a>
            </div>

        </div>
    </div>

    <h1>Key</h1>

    <div class="well well-lg">
        <div class="row">

            <div style="text-align: center;">

                <h3>{{$you->key}}</h3>

            </div>

        </div>
        <div class="row">

            <div style="text-align: center;">

                <h5>Keep this safe. The client requires a key for streaming and spectating.</h5>

            </div>

        </div>
    </div>

@endsection