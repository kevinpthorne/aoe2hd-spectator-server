@extends('layouts.app')

@section('title', 'Please Login')

@section('content')

    <div class="row well well-lg">

        <div style="text-align: center;">

            <h3>Please login to Steam</h3>
            @php
                if(isset($_SESSION)) {
                print_r($_SESSION);
            } else {
                echo "no session \n";
            }
            @endphp

        </div>

    </div>

@endsection