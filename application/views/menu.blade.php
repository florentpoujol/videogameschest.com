<div class="container">
    <div id="above-menu">
        <div class="pull-right">
            @if (is_guest())
                <a href="{{ route('get_register_page') }}">{{ lang('menu.register') }}</a> 
                {{ lang('menu.or') }} 
                <a href="{{ route('get_login_page') }}" id="login-popover">{{ lang('menu.login.title') }}</a> 
            @else
                {{ lang('admin.home.hello') }} {{ user()->username }}
            @endif
        </div>

        <div>
            Blog | 
            <a href="{{ route('get_about_page') }}">{{ lang('about.title') }}</a>
        </div>
    </div>

    <div id="main-menu" class="navbar">
        <div class="navbar-inner">
            <div class="brand">
                VideoGamesChest
                {{-- <img src=" asset('img/logo_test.png') " height="20%" width="20%"> --}}
            </div>

            <ul class="nav pull-right">
                <!-- General menu -->
                <?php 
                $menu_items = array('home', 'participate', 'search', 'discover', 'promote'); 
                foreach ($menu_items as $item) {
                    if (CONTROLLER == $item) ${$item} = ' class="active"';
                    else ${$item} = '';
                }

                if (CONTROLLER == '') $home = ' class="active"';

                $admin = 'active';
                foreach ($menu_items as $item) {
                    if (${$item} != '') $admin = '';
                }

                $show_profile_edit_link = false;
                if (in_array(CONTROLLER, get_profiles_types()) && ! in_array(ACTION, array('create', 'update'))) {
                    $admin = '';

                    if (isset($profile) && (is_admin() || $profile->user_id == user_id())) {
                        $show_profile_edit_link = true;
                    }
                }
                ?>
                

                <li{{ $home }}><a href="{{ route('get_home_page') }}">{{ lang('menu.home') }}</a></li>
                <li{{ $participate }}><a href="{{ route('get_participate_page') }}">{{ lang('participate.title') }}</a></li>
                <li{{ $search }}><a href="{{ route('get_search_page') }}">{{ lang('search.title') }}</a></li>
                <li{{ $discover }}><a href="{{ route('get_discover_page') }}">{{ lang('discover.title') }}</a></li>
                <li{{ $promote }}><a href="{{ route('get_promote_page') }}">{{ lang('promote.title') }}</a></li>
                
                <!-- /general menu --> 

                @if (is_logged_in())
                    <!-- Admin menu -->
                    <li class="dropdown {{ $admin }}">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            {{ icon('cog') }}
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">

                            @if (is_logged_in())
                                @if ($show_profile_edit_link)
                                    <li><a href="{{ route('get_'.$profile->class_name.'_update', array($profile->id)) }}">{{ lang('common.update_profile') }}</a> </li>

                                    <li class="divider"></li>
                                @endif
                                

                                
                            @endif

                            @if (is_admin())
                                <li><a href="{{ route('get_user_create') }}">Add a user</a></li>
                            @endif
                            <li><a href="{{ route('get_user_update') }}">{{ icon('edit') }} {{ lang('admin.menu.edit_user_account') }}</a></li>

                            <li class="divider"></li>
                            <li><a href="{{ route('get_developer_create') }}">{{ icon('plus') }} {{ lang('admin.menu.add_developer') }}</a></li>
                            @if ( ! empty(user()->devs) || is_admin())
                                <li><a href="{{ route('get_developer_update') }}">{{ icon('edit') }} {{ lang('admin.menu.edit_developer') }}</a></li>
                            @endif


                            <li class="divider"></li>
                            <li><a href="{{ route('get_game_create') }}">{{ icon('plus') }} {{ lang('admin.menu.add_game') }}</a></li>
                            @if ( ! empty(user()->games) || is_admin())
                                <li><a href="{{ route('get_game_update') }}">{{ icon('edit') }} {{ lang('admin.menu.edit_game') }}</a></li>
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
                @endif
            </ul>
        </div> <!-- .navbar-inner -->
    </div> <!-- /div #main-menu .navbar .navbar-fixed-top  -->
</div> <!-- /.container -->
