<div class="container">
    <div id="main_menu" class="navbar" >
        <div class="navbar-inner">
            <div class="brand">
                VideoGamesChest <small class="muted">alpha</small>
            </div>

            <ul class="nav">
                <!-- General menu -->
                
                <li><a href="{{ route('get_home_page') }}">{{ lang('menu.home') }}</a></li>
                
                <!-- /general menu --> 
                @if (is_logged_in())
                    <!-- Admin menu -->
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            {{ icon('cog') }}
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('get_user_update') }}">{{ icon('edit') }} {{ lang('admin.menu.edit_user_account') }}</a></li>
                            <li class="divider"></li>
                            
                            <li><a href="{{ route('get_profile_create') }}">{{ icon('plus') }} {{ lang('profile.add.title') }}</a></li>
                            <li><a href="{{ route('get_profile_update') }}">{{ icon('edit') }} {{ lang('profile.edit.title') }}</a></li>
                            <li class="divider"></li>

                            <li><a href="{{ route('get_reports') }}">{{ lang('reports.title') }}</a></li>
                            <li><a href="{{ route('get_private_profiles') }}">Private profiles</a></li>
                            
                            <li><a href="{{ route('get_crawler_page') }}">Crawler</a></li>
                                                    
                            <li class="divider"></li>
                            
                            <li><a href="{{ route('get_logout') }}">{{ icon('off') }} {{ lang('menu.logout') }}</a></li>
                        </ul>
                    </li> <!-- /Admin menu -->                      
                @endif <!-- /if logged in -->
            </ul> <!-- /ul .nav .pull-right -->
        </div>
    </div> <!-- /div #main-menu .navbar .navbar-fixed-top  -->
</div> <!-- /.container -->
