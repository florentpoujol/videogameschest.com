<div class="container">
    <div id="above-menu">
        <div class="pull-right">
            @if (is_guest())
                <a href="{{ route('get_register_page') }}">{{ lang('vgc.menu.register') }}</a> 
                {{ lang('vgc.menu.or') }} 
                <a href="{{ route('get_login_page') }}" id="login-popover">{{ lang('vgc.menu.login.title') }}</a> 
            @else
                {{ lang('vgc.admin.home.hello') }} {{ user()->username }}
            @endif
        </div>

        <div>
            <a href="{{ route('get_blog_page') }}">{{ lang('vgc.blog.title') }}</a> 
        </div>
    </div>

    <div id="main-menu" class="navbar">
        <div class="navbar-inner">
            <div class="brand">
                VideoGamesChest <small class="muted">alpha</small>
            </div>

            <ul class="nav pull-right">
                <!-- General menu -->
                <?php 
                $menu_items = array('home', 'search', 'browse', 'discover'); 
                foreach ($menu_items as $item) {
                    if (CONTROLLER == $item) ${$item} = ' class="active"';
                    else ${$item} = '';
                }

                if (CONTROLLER == '') $home = ' class="active"';

                $admin = 'active';
                foreach ($menu_items as $item) {
                    if (${$item} != '') $admin = '';
                }
                ?>
                
                <li{{ $home }}><a href="{{ route('get_home_page') }}">{{ lang('vgc.menu.home') }}</a></li>
                <li{{ $search }}><a href="{{ route('get_search_page') }}">{{ lang('vgc.search.title') }}</a></li>
                <li{{ $browse }}><a href="{{ route('get_browse_page') }}">{{ lang('vgc.browse.title') }}</a></li>
                <li{{ $discover }}><a href="{{ route('get_discover_page') }}">{{ lang('vgc.discover.title') }}</a></li>
                <li><a href="{{ route('get_suggest_page') }}" id="suggest_form_link">{{ lang('vgc.suggest.menu_link') }}</a></li>
                <!-- <li><a href="{{ route('get_suggest_page_colorbox') }}" id="suggest_form_link">{{ lang('vgc.suggest.menu_link') }}</a></li> -->
                
                <!-- /general menu --> 

                @if (is_logged_in())
                    <!-- Admin menu -->
                    <li class="dropdown {{ $admin }}">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            {{ icon('cog') }}
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('get_user_update') }}">{{ icon('edit') }} {{ lang('vgc.admin.menu.edit_user_account') }}</a></li>
                            <li class="divider"></li>
                            
                            <!-- add and edit profiles links -->
                            @foreach (get_profile_types() as $profile_type)
                                <li><a href="{{ route('get_profile_create', array($profile_type)) }}">{{ icon('plus') }} {{ lang($profile_type.'.add.title') }}</a></li>
                                @if ( ! empty(user()->{$profile_type.'s'}) || is_admin())
                                    <li><a href="{{ route('get_profile_update', array($profile_type)) }}">{{ icon('edit') }} {{ lang($profile_type.'.edit.title') }}</a></li>
                                @endif
                                <li class="divider"></li>
                            @endforeach

                            @if (is_admin())
                                <li><a href="{{ route('get_blog_post_create') }}">{{ lang('vgc.blog.write_post') }}</a></li>
                                <li><a href="{{ route('get_crawler_page') }}">Crawler</a></li>
                                <li><a href="{{ route('get_review') }}">{{ lang('vgc.review.title') }}</a></li>
                            @endif
                            @if ( ! empty(user()->devs) || ! empty(user()->games) || is_admin())
                                <li><a href="{{ route('get_reports') }}">{{ lang('vgc.reports.title') }}</a></li>
                                <li class="divider"></li>
                            @endif
                            
                            <li><a href="{{ route('get_logout') }}">{{ icon('off') }} {{ lang('vgc.menu.logout') }}</a></li>
                        </ul>
                    </li> <!-- /Admin menu -->                      
                @endif <!-- /if logged in -->
            </ul> <!-- /ul .nav .pull-right -->
        </div> <!-- .navbar-inner -->
    </div> <!-- /div #main-menu .navbar .navbar-fixed-top  -->
</div> <!-- /.container -->
