<?php
if ( ! isset($page_title)) $page_title = '';
if ( $page_title != '' )
    $page_title .= " - ";


if ( ! isset($page_content)) $page_content = '';

$is_profile_public = true;
if (isset($profile))
    $is_profile_public = $profile->is_public;
?><!DOCTYPE html>
<html lang="en"> 
    <!-- ENVIRONEMENT : {{ Config::get('vgc.environment') }} -->
    <head>
        <title>{{ $page_title }}VideoGamesChest.com</title>

        <!-- Meta --> 
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        
        @if (Config::get('vgc.environment') == 'production' && $is_profile_public)
            <meta name="robots" content="index,follow">
            <?php
            $meta_description = lang('vgc.common.site_meta_description');
            $meta_keywords = lang('vgc.common.site_meta_keywords');
            ?>
            <meta name="description" content="{{ $meta_description }}">
            <meta name="keywords" content="{{ $meta_keywords }}">
        @else
            <meta name="robots" content="noindex,nofollow">
        @endif
        <!-- /Meta -->

        <!-- CSS -->        
        {{ HTML::style('css/bootstrap.min.css') }}

        {{HTML::style('css/vgc/main.less', array('rel'=>'stylesheet/less')) }}
        {{ HTML::script('css/chosen.css') }}
        @yield('cssfiles')
        <!-- /CSS -->
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
            <hr>
            
            <div class="row">
                <div class="span4">
                    <P class="muted copyright">Copyright &copy; 2013 VideoGamesChest.com</p>
                </div>

                <div class="span3 offset5">
                    <p>
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
        @if (Config::get('vgc.environment') == 'local')
            {{ HTML::script('js/jquery-v1.10.2-min.js') }}
        @else
            <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
        @endif
        {{ HTML::script('js/less.js') }}
        {{ HTML::script('js/bootstrap/bootstrap.min.js') }}
        {{ HTML::script('js/bootstrap/bootstrap-responsive.min.js') }}
        {{ HTML::script('js/chosen.jquery.min.js') }}

        @yield('jsfiles')

        <script>
          $(function () {
            // common jquery
            // $('i[rel=tooltip]').tooltip();
            // $('i[rel=popover]').popover();

            // suggest form link in the menu
            // $("#suggest_form_link").colorbox({iframe:true, width:"600px", height:"600px"});
            // $(".colorbox-iframe").colorbox({iframe:true, width:"600px", height:"600px"});

            
            // per page jquery
            @yield('jQuery')
          })

          @yield('jscode') 
        </script> <!-- /JavaScript -->

        @if (Config::get('vgc.environment') == 'production')
            @include('partials.googleanalytics')
        @endif
    </body>
</html>
