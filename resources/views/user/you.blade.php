@extends('layouts.app')

@section('title', 'You')

@section('content')

    <div class="well well-lg">
        <div class="row">

            <div class="col-md-1">
                <img src="{{$you->avatar}}" class="img-rounded" width="64" height="64"/>
            </div>
            <div class="col-md-5">
                <h2>{{$you->name}}</h2>
            </div>

        </div>
        <div class="row">

            <div class="col-md-5 col-md-offset-1">
                <a href="/user/{{$you->id}}">View Your Profile</a>
            </div>

        </div>
    </div>

    <h1>Key</h1>

    <div class="well well-lg">
        <div class="row">

            <div style="text-align: center;">
                <h3 id="key">
                    {{$you->key}}
                </h3>
            </div>

            <div style="text-align: right; vertical-align: middle;">
                <div id="status">
                    <button class="btn btn-primary"
                            id="copy-button"
                            data-clipboard-target="h3#key"
                            data-clipboard-action="copy">Copy
                    </button>
                </div>
            </div>

        </div>
        <div class="row">

            <div style="text-align: center;">
                <h5>Keep this safe. The client requires a key for streaming and spectating.</h5>
            </div>

        </div>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.5.16/clipboard.min.js"></script>
    <script>
        (function () {
            var clipboard = new Clipboard('#copy-button');
            clipboard.on('success', function (e) {
                console.log(e);
                document.getElementById("status").innerHTML = "<h4>Copied!</h4>";
            });
            clipboard.on('error', function (e) {
                console.log(e);
            });
        })();
    </script>

@endsection