<header id="main-menu" class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">

            <div class="brand">
                Video Games Chest <br>
                <small>{{ lang('common.site_slogan') }}</small>
            </div>

            <ul class="nav pull-right">
                <!-- Ganeral menu -->
                <?php 
                $menu_items = array('home', 'search'); 

                if (is_guest()) $menu_items[] = 'register';
                ?>
                @foreach ($menu_items as $menu_item)
                    <li><a href="{{ route('get_'.$menu_item) }}">{{ lang('menu.'.$menu_item) }}</a></li>
                @endforeach
                <!-- /ganeral menu --> 

                @if (is_logged_in())
                    <!-- Admin menu -->
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            Admin
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('get_admin_home') }}">{{ lang('admin.home.title') }}</a></li>

                            <li class="divider"></li>
                            @if (is_admin())
                                <li><a href="{{ route('get_adduser') }}">Add a user</a></li>
                            @endif
                            <li><a href="{{ route('get_edituser') }}">{{ lang('admin.menu.edit_user_account') }}</a></li>

                            <li class="divider"></li>
                            <li><a href="{{ route('get_adddeveloper') }}">{{ lang('admin.menu.add_developer') }}</a></li>
                            @if ( ! empty(user()->devs) || is_admin())
                                <li><a href="{{ route('get_editdeveloper') }}">{{ lang('admin.menu.edit_developer') }}</a></li>
                            @endif


                            <li class="divider"></li>
                            <li><a href="{{ route('get_addgame') }}">{{ lang('admin.menu.add_game') }}</a></li>
                            @if ( ! empty(user()->devs) || is_admin())
                                <li><a href="{{ route('get_editgame') }}">{{ lang('admin.menu.edit_game') }}</a></li>
                            @endif

                            <li class="divider"></li>
                            @if (is_trusted())
                                <li><a href="{{ route('get_reviews') }}">{{ lang('reviews.title') }}</a></li>
                            @endif
                            @if ( ! empty(user()->devs) || ! empty(user()->games) || is_admin())
                                <li><a href="{{ route('get_reports') }}">{{ lang('reports.title') }}</a></li>
                            @endif
                            
                            <li class="divider"></li>
                            <li><a href="{{ route('get_logout') }}">{{ lang('menu.logout') }}</a></li>
                        </ul>
                    </li> <!-- /Admin menu -->
                @else
                    <li><a href="{{ route('get_login') }}"><i class="icon-user"></i>{{ lang('menu.login.title') }}</a></li>
                @endif

                <!-- language menu 
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
                </li> /language menu -->
            </ul>
        </div>
    </div>
</header> <!-- /header #main-menu .navbar .navbar-fixed-top  -->
