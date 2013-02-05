<div class="container">

    <div id="above-menu">

        <div class="pull-right">
            @if (is_guest())
                <a href="{{ route('get_register') }}">{{ lang('menu.register') }}</a> 
                {{ lang('menu.or') }} 
                <a href="{{ route('get_login') }}" id="login-popover">{{ lang('menu.login.title') }}</a> 
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
                $menu_items = array('home', 'participate', 'search', 'discover', 'promotion'); 
                foreach ($menu_items as $item) {
                    if (CONTROLLER == $item) ${$item} = ' class="active"';
                    else ${$item} = '';
                }

                if (CONTROLLER == 'promote') $promotion = ' class="active"';
                if (CONTROLLER == '') $home = ' class="active"';

                $admin = 'active';
                foreach ($menu_items as $item) {
                    if (${$item} != '') $admin = '';
                }

                if (in_array(CONTROLLER, get_profiles_types())) {
                    $admin = '';
                    
                }
                ?>
                

                <li{{ $home }}><a href="{{ route('get_home_page') }}">{{ lang('menu.home') }}</a></li>
                <li{{ $participate }}><a href="{{ route('get_participate_page') }}">{{ lang('participate.title') }}</a></li>
                <li{{ $search }}><a href="{{ route('get_search_page') }}">{{ lang('search.title') }}</a></li>
                <li{{ $discover }}><a href="{{ route('get_discover_page') }}">{{ lang('discover.title') }}</a></li>
                <li{{ $promotion }}><a href="{{ route('get_promotion_page') }}">{{ lang('promotion.title') }}</a></li>
                
                <!-- /general menu --> 

                @if (is_logged_in())
                    <!-- Admin menu -->
                    <li class="dropdown {{ $admin }}">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            {{ icon('cog') }}
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
                            @if ( ! empty(user()->games) || is_admin())
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
                @endif
            </ul>
        </div> <!-- .navbar-inner -->
    </div> <!-- /div #main-menu .navbar .navbar-fixed-top  -->
</div> <!-- /.container -->
