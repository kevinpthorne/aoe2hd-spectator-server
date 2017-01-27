<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">Age2HD Live</a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            @if(isset($_SESSION['steamid']))
                @php(include('../app/steamauth/userInfo.php'))
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="false">{{$steamprofile['personaname']}} <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="/user/{{$_SESSION['steamid']}}">Profile</a></li>
                            <li><a href="/you">Account</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="/logout">Log Out</a></li>
                        </ul>
                    </li>
                </ul>
            @else
                <ul class="nav navbar-nav navbar-right">
                    <li><a href='/login'><img src='http://cdn.steamcommunity.com/public/images/signinthroughsteam/sits_01.png'></a></li>
                </ul>
            @endif
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>