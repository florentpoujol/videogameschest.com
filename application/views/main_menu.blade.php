<header id="main-menu" class="navbar container">
    <div class="navbar-inner">
        <!-- <div class="container"> -->

            <div class="brand">
                Video Games Chest
            </div>

            <ul class="nav pull-right">
                <!-- General menu -->
                <?php 
                $menu_items = array('home', 'search', 'find', 'advertising'); 
                foreach ($menu_items as $item) {
                    if (CONTROLLER == $item) ${$item} = ' class="active"';
                    else ${$item} = '';
                }

                if (CONTROLLER == '') $home = 'class="active"';
                ?>
                <!-- @foreach ($menu_items as $menu_item)
                    <li><a href="{{ route('get_'.$menu_item) }}">{{ lang('menu.'.$menu_item) }}</a></li>
                @endforeach -->

                <li{{ $home }}><a href="{{ route('get_home') }}">{{ icon('home') }} {{ lang('menu.home') }}</a></li>
                <li{{ $search }}><a href="{{ route('get_search') }}">{{ icon('search') }} {{ lang('menu.search') }}</a></li>

                <li{{ $find }}><a href="{{ route('get_find') }}">{{ icon('eye-open') }} {{ lang('menu.find') }}</a></li>

                @if (is_logged_in())
                    <li{{ $advertising }}><a href="{{ route('get_advertising') }}">{{ icon('bullhorn') }} {{ lang('menu.advertising') }}</a></li>
                @endif
                <!-- /general menu --> 

                @if (is_logged_in())
                    <!-- Admin menu -->
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            {{ icon('cog') }} Admin
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('get_admin_home') }}">{{ icon('cogs') }} {{ lang('admin.home.title') }}</a></li>

                            <li class="divider"></li>
                            @if (is_admin())
                                <li><a href="{{ route('get_adduser') }}">Add a user</a></li>
                            @endif
                            <li><a href="{{ route('get_edituser') }}">{{ icon('edit') }} {{ lang('admin.menu.edit_user_account') }}</a></li>

                            <li class="divider"></li>
                            <li><a href="{{ route('get_adddeveloper') }}">{{ icon('plus') }} {{ lang('admin.menu.add_developer') }}</a></li>
                            @if ( ! empty(user()->devs) || is_admin())
                                <li><a href="{{ route('get_editdeveloper') }}">{{ icon('edit') }} {{ lang('admin.menu.edit_developer') }}</a></li>
                            @endif


                            <li class="divider"></li>
                            <li><a href="{{ route('get_addgame') }}">{{ icon('plus') }} {{ lang('admin.menu.add_game') }}</a></li>
                            @if ( ! empty(user()->devs) || is_admin())
                                <li><a href="{{ route('get_editgame') }}">{{ icon('edit') }} {{ lang('admin.menu.edit_game') }}</a></li>
                            @endif

                            <li class="divider"></li>
                            @if (is_admin())
                                <li><a href="{{ route('get_reviews') }}">{{ lang('reviews.title') }}</a></li>
                            @endif
                            @if ( ! empty(user()->devs) || ! empty(user()->games) || is_admin())
                                <li><a href="{{ route('get_reports') }}">{{ icon('list-alt') }} {{ lang('reports.title') }}</a></li>
                            @endif
                            
                            <li class="divider"></li>
                            <li><a href="{{ route('get_logout') }}">{{ icon('off') }} {{ lang('menu.logout') }}</a></li>
                        </ul>
                    </li> <!-- /Admin menu -->
                @else
                    <li><a href="{{ route('get_register') }}">{{ icon('user') }} {{ lang('menu.register') }}</a></li>
                    <li><a href="{{ route('get_login') }}">{{ icon('ok-sign') }} {{ lang('menu.login.title') }}</a></li>
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
        <!-- </div> .container -->
    </div> <!-- .navbar-inner -->
</header> <!-- /header #main-menu .navbar .navbar-fixed-top  -->
