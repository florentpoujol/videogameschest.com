<!DOCTYPE html>
<html lang="{{ LANGUAGE }}"> 
    <head>
        <title>{{ page_title }}</title>

        <!-- Meta -->
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
{% if ENVIRONMENT is "development" or CONTROLLER is "admin" %}
        <meta name="robots" content="noindex,nofollow" >
{% else %}
        <meta name="robots" content="index,follow" >
{% endif %}
        
{% for meta in metas %}
        <meta name="{{ meta.name }}" content="{{ meta.content }}" >
{% endfor %}
        <!-- /Meta -->

        <!-- CSS -->        
        <!--<link rel="stylesheet" type="text/css" media="screen" href="{{ css_link("main") }}" >-->
        
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo css_link("bootstrap.min"); ?>" >
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo css_link("bootstrap-responsive.min"); ?>" >
        <link rel="stylesheet/less" type="text/css" media="screen" href="<?php echo css_link("main", ".less");?>" >

{% for url in css %}
        <link rel="stylesheet" type="text/css" media="screen" href="{{ url }}" >
{% endfor %}
        <!-- /CSS -->
    </head>
    
    <body>
        <header class="container navbar nav-inner">
                <ul class="nav">
                    <!-- Menu -->
{{{ $menu_items = array('home', 'search', 'adddeveloper', 'addgame', 'about'); }}}
                {% for menu_item in menu_items %}
                    <li {{ controller_selected($menu_item) }}><a href="{{ site_url($menu_item) }}">{{ lang('menu_'.$menu_item) }}</a></li>
                {% endfor %}

{% if IS_LOGGED_IN %}
                    <!-- Admin menu -->
                    <li class="dropdown <?php echo controller_selected("admin", true); ?>">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            Admin
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li {{ method_selected('admin_index') }}><a href="{{ site_url('admin') }}">Admin hub</a></li>
                            <li {{ method_selected('edituser') }}><a href="{{ site_url('admin/edituser') }}">Edit user</a></li>
                            <li {{ method_selected('addgame') }}><a href="{{ site_url('admin/addgame') }}">Add a game</a></li>
                            <li {{ method_selected('editgame') }}><a href="{{ site_url('admin/editgame') }}">Edit a game</a></li>
                            <li {{ method_selected('gamequeue') }}><a href="{{ site_url('admin/gamequeue') }}">Game queue</a></li>
                            <li {{ method_selected('reports') }}><a href="{{ site_url('admin/reports') }}">Reports</a></li>
                            <li {{ method_selected('messages') }}><a href="{{ site_url('admin/messages') }}">Messages</a></li>
    {% if IS_ADMIN %}
                            <li {{ method_selected('adduser') }}><a href="{{ site_url('admin/adduser') }}">Create user</a></li>
                            <li {{ method_selected('adddeveloper') }}><a href="{{ site_url('admin/adddeveloper') }}">Add a developer</a></li>
                            <li {{ method_selected('editdeveloper') }}><a href="{{ site_url('admin/editdeveloper') }}">Edit a developer</a></li>
    {% elseif IS_DEVELOPER %}
                            <li {{ method_selected('editdeveloper') }}><a href="{{ site_url('admin/editdeveloper/'.userdata('user_id')) }}">Edit your dev profile</a></li>
    {% endif %} {# end if is admin or dev #}
                            <li class="divider"></li>
                            <li><a href="{{ site_url('admin/logout') }}">{{ lang('menu_logout') }}</a></li>
                        </ul>
                    </li>
                    
                    <!-- /Admin menu -->
{% else %}{# not logged in #} 
                    <li><a href="{{ site_url('admin/login') }}"><i class="icon-user"></i>{{ lang('menu_login') }}</a></li>
{% endif %}
                    <!-- /menu --> 
                    
                    <!-- Language menu -->
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            {{ lang("menu_languages") }} 
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                
<?php
foreach ($this->static_model->site->languages as $lang):
    $lang == LANGUAGE ? $is_active_lang = 'class="active"': $is_active_lang = "";
    $current_url_escaped = str_replace("/", ":", uri_string()); // replace / by : in the current url
    $lang_url = site_url("admin/setlanguage/$lang:$current_url_escaped");
?>
                            <li {{ is_active_lang }}><a href="{{ lang_url }}" title="{{ lang }}">{{ lang('languages_'.$lang) }}</a></li> 
{% endfor %}
                        </ul>
                    </li>
                    <!-- /#lang_menu --> 
                </ul>

                <form class="navbar-search pull-left">
                    <input type="text" class="search-query" placeholder="Search">
                </form>
        </header>
        <!-- /header .container .navbar .nav-inner -->

        <div class="container" id="page_content">
            <!-- Body hook -->
            {{ body_views }}
            <!-- /Body hook -->
        </div>
        <!-- /#page_content .container -->

        <!-- JavaScript -->
        <script src="http://lesscss.googlecode.com/files/less-1.3.0.min.js" type="text/javascript"></script>
        <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
        <script src="{{ js_link("bootstrap.min") }}" type="text/javascript"></script>

{% for url in js %}
        <script type="text/javascript" src="{{ url }}"></script> 
{% endfor %}
        
        <script type="text/javascript">
        </script>
        <!-- /JavaScript -->
    </body>
</html>