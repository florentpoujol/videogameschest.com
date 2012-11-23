<!DOCTYPE html>
<html lang="{{ LANGUAGE }}"> 
    <head>
        <title>@yield('page_title') | VideoGamesChest.com</title>

        <!-- Meta --> 
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="robots" content="noindex,nofollow" >
        <!-- /Meta -->

        <!-- CSS -->        
        {{ HTML::style("css/bootstrap.min.css") }}
        {{ HTML::style("css/bootstrap-responsive.min.css") }}
        <link rel="stylesheet/less" type="text/css" media="screen" href="{{ asset("css/main.less") }}" >
        <!-- /CSS -->
    </head>
    
    <body>
        <header class="container navbar nav-inner">
                <ul class="nav">
                    <!-- Menu -->
                <?php $menu_items = array('home'); ?>
                @foreach ($menu_items as $menu_item)
                    <li><a href="{{ route($menu_item) }}">{{ __('vgc.menu_'.$menu_item) }}</a></li>
                @endforeach

                @if (IS_LOGGED_IN)
                    <!-- Admin menu -->
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            Admin
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('admin_home') }}">Admin home</a></li>
                            <li><a href="{{ route('admin_edituser') }}">Edit user</a></li>
                            <li><a href="{{ route('admin_addgame') }}">Add a game</a></li>
                            <li><a href="{{ route('admin_editgame') }}">Edit a game</a></li>
                            <li><a href="{{ route('admin_gamequeue') }}">Game queue</a></li>
                            <li><a href="{{ route('admin_reports') }}">Reports</a></li>
                            <li><a href="{{ route('admin_messages') }}">Messages</a></li>
                        @if (IS_ADMIN) 
                            <li><a href="{{ route('admin_adduser') }}">Create user</a></li>
                            <li><a href="{{ route('admin_adddeveloper') }}">Add a developer</a></li>
                            <li><a href="{{ route('admin_editdeveloper') }}">Edit a developer</a></li>
                        @elseif (IS_DEVELOPER)
                            <li><a href="{{ route('admin_editdeveloper', array(USER_ID)) }}">Edit your dev profile</a></li>
                        @endif 
                            <li class="divider"></li>
                            <li><a href="{{ route('admin_logout') }}">{{ __('vgc.menu_logout') }}</a></li>
                        </ul>
                    </li>
                    
                    <!-- /Admin menu -->
                @else
                    <li><a href="{{ route('admin_login') }}"><i class="icon-user"></i>{{ __('vgc.menu_login') }}</a></li>
                @endif
                    <!-- /menu --> 
                </ul>

                <form class="navbar-search pull-left">
                    <input type="text" class="search-query" placeholder="Search">
                </form>
        </header>
        <!-- /header .container .navbar .nav-inner -->

        <div class="container" id="page_content">
            @yield('page_content')
        </div>
        <!-- /#page_content .container -->

        <!-- JavaScript -->
        <script src="http://lesscss.googlecode.com/files/less-1.3.0.min.js" type="text/javascript"></script>
        <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
        {{ HTML::script('js/bootstrap.min.js') }}
        <!-- /JavaScript -->
    </body>
</html>
