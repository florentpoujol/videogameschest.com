<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">

            <a class="brand" href="#">VideoGamesChest</a>

            <ul class="nav">
            <!-- Menu -->
                <?php $menu_items = array('home', 'search', 'adddeveloper', 'addgame'); ?>
                @foreach ($menu_items as $menu_item)
                    <li><a href="{{ route('get_'.$menu_item) }}">{{ lang('menu.'.$menu_item) }}</a></li>
                @endforeach

                @if (IS_LOGGED_IN)
                    <!-- Admin menu -->
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            Admin
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('get_admin_home') }}">Admin home</a></li>

                            <li class="divider"></li>
                        @if (IS_ADMIN)
                            <li><a href="{{ route('get_adduser') }}">Add a user</a></li>
                        @endif
                            <li><a href="{{ route('get_edituser') }}">Edit your user account</a></li>

                            <li class="divider"></li>
                        @if (IS_ADMIN) 
                            <li><a href="{{ route('get_admin_adddeveloper') }}">Add a developer</a></li>
                            <li><a href="{{ route('get_editdeveloper') }}">Edit a developer</a></li>
                        @elseif (IS_DEVELOPER)
                            <li><a href="{{ route('get_editdeveloper', array(USER_ID)) }}">Edit your developer profile</a></li>
                        @endif 

                            <li class="divider"></li>
                            <li><a href="{{ route('get_admin_addgame') }}">Add a game</a></li>
                            <li><a href="{{ route('get_editgame') }}">Edit a game</a></li>

                            <li class="divider"></li>
                        @if (IS_TRUSTED)
                            <li><a href="{{ route('get_reviews') }}">{{ lang('reviews.title') }}</a></li>
                        @endif
                            <li><a href="{{ route('get_reports') }}">{{ lang('reports.title') }}</a></li>
                            
                            <li class="divider"></li>
                            <li><a href="{{ route('get_logout') }}">{{ lang('menu.logout') }}</a></li>
                        </ul>
                    </li>
                    
                    <!-- /Admin menu -->
                @else
                    <li><a href="{{ route('get_login') }}"><i class="icon-user"></i>{{ lang('menu.login.title') }}</a></li>
                @endif

                <!-- language menu -->
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        {{ lang('menu.languages') }}
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                    <?php $languages = Config::get('vgc.site_languages'); ?>
                    @foreach ($languages as $language)
                        <li><a href="{{ route('get_set_language', array($language)) }}">{{ lang("languages.$language") }}</a></li>
                    @endforeach
                    </ul>
                </li><!-- /language menu -->

                <!-- /menu --> 
            </ul>

        </div>
    </div>
</div>
<!-- /header  .navbar .nav-inner -->
