<?php
if ( ! isset($page_title)) {
    $page_title = Section::yield('page_title');
    
    if ($page_title == '') $page_title = ucfirst(CONTROLLER);
}

if ( ! isset($page_content)) $page_content = '';
$page_content .= Section::yield('page_content');

if ( ! isset($preview_profile)) $preview_profile = false;
?><!DOCTYPE html>
<html lang="{{ LANGUAGE }}"> 
    <!-- ENVIRONEMENT : {{ Config::get('vgc.environment') }} -->
    <head>
        <title>{{ $page_title }} - VideoGamesChest.com</title>

        <!-- Meta --> 
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        
        @if (Config::get('vgc.environment') == 'production' && $preview_profile == false)
            <meta name="robots" content="index,follow">
            <?php
            if (isset($profile)) { //displaying a profile
                $meta_description = $profile->meta_description;

                $meta_keywords = '';
                $keywords = $profile->meta_keywords;
                $keywords = explode(',', $keywords);

                foreach ($keywords as $keyword) {
                    $keyword = trim($keyword);

                    if ($keyword != '') {
                        $meta_keywords .= $keyword.', ';
                    }
                }

                $meta_keywords = substr($meta_keywords, 0, strlen($meta_keywords)-2);
            } else {
                $meta_description = lang('vgc.common.site_meta_description');
                $meta_keywords = lang('vgc.common.site_meta_keywords');
            }
            ?>
            <meta name="description" content="{{ $meta_description }}">
            <meta name="keywords" content="{{ $meta_keywords }}">
        @else
            <meta name="robots" content="noindex,nofollow">
        @endif
        
        <!-- /Meta -->

        <!-- CSS -->        
        {{ HTML::style('css/bootstrap/bootstrap.min.css') }}
        {{ HTML::style('css/font-awesome/font-awesome.min.css') }}
        <!--[if IE 7]>
        {{ HTML::style('css/font-awesome/font-awesome-ie7.min.css') }}
        <![endif]-->
        {{ HTML::style('css/vgc/main.less', array('rel'=>'stylesheet/less')) }}
        @yield('cssfiles')
        <!-- /CSS -->

        <link rel="alternate" href="{{ route('get_blog_feed') }}" title="Blog feed" type="application/rss+xml">
    </head>
    
    <body>
        <header>
            @include('menu')
        </header>

        <div class="container" id="page_content" role="main">
            <div id="message-box">
                {{ HTML::get_messages($errors) }}
            </div>
            
            {{ $page_content }}
            
        </div> <!-- /#page_content .container -->

        <footer class="container">
            <div class="row-fluid">
                <div class="span4">
                    <P class="muted copyright">Copyright &copy; 2013 VideoGamesChest.com</p>
                </div>

                <div class="span3 offset5">
                    <p>
                        <a href="{{ route('get_blog_feed') }}" title="Blog feed">{{ icon('rss', null, 27) }}</a> 
                        <a href="{{ Config::get('vgc.social.twitter_url') }}" title="Follow us on Twitter">{{ icon('twitter', null, 30) }}</a> 
                        <a href="{{ Config::get('vgc.social.facebook_url') }}" title="Like us on Facebook">{{ icon('facebook', null, 30) }}</a>
                        <a href="{{ Config::get('vgc.social.google+_url') }}" title="Add us on Google+">{{ icon('google+', null, 26) }}</a>
                        <br>
                        <a href="mailto:contact@videogameschest.com" class="muted">contact@videogameschest.com</a>
                    </p>
                </div>
            </div>
        </footer>

        <!-- JavaScript -->
        <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
        <script src="http://lesscss.googlecode.com/files/less-1.3.0.min.js" type="text/javascript"></script>
        {{ HTML::script('js/bootstrap/bootstrap.min.js') }}
        @yield('jsfiles')

        <script>
          $(function () {
            // common jquery
            $('i[rel=tooltip]').tooltip();
            $('i[rel=popover]').popover();

            // per page jquery
            @yield('jQuery')
          })

          @yield('jscode')
        </script> <!-- /JavaScript -->

        @if (Config::get('vgc.is_production_environement') === true)
            @include('partials.googleanalytics')
        @endif
    </body>
</html>
