<html>
<head>
    <title>@yield('title') - Age2HD Live</title>

    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/icon?family=Material+Icons">

    <!-- Bootstrap -->
    <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- Bootstrap Material Design -->
    <link rel="stylesheet" type="text/css" href="/css/bootstrap-material-design.min.css">
    <link rel="stylesheet" type="text/css" href="/css/ripples.min.css">
    <link rel="stylesheet" type="text/css" href="/css/app.css">

</head>
<body>
@include('layouts.nav')

<div class="well">
    <div class="container">
        <img src="/img/greenLogo.png" width="64" height="64" style="display: inline; margin-right:1%;"/>
        <h1 style="display: inline; vertical-align: middle">@yield('title')</h1>
    </div>
</div>


<div class="container">
    @yield('content')
</div>

</body>
</html>