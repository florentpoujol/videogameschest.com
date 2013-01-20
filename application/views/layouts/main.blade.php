<?php
if ( ! isset($page_title)) {
    $page_title = Section::yield('page_title');
    
    if ($page_title == '') $page_title = ucfirst(CONTROLLER);
}

if ( ! isset($page_content)) $page_content = '';
$page_content .= Section::yield('page_content');
?><!DOCTYPE html>
<html lang="{{ LANGUAGE }}"> 
    <head>
        <title>{{ $page_title }} - VideoGamesChest.com</title>

        <!-- Meta --> 
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="robots" content="noindex,nofollow" >
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
    </head>
    
    <body>
        <header>
            @include('menu')
        </header>

        <div class="container" id="page_content">
            <div id="message-box">
                {{ HTML::get_messages($errors) }}
            </div>
            
            {{ $page_content }}
            
        </div> <!-- /#page_content .container -->

        <footer>
            <div class="container">
                <div class="row-fluid">
                    <div class="span4">
                        <P class="muted copyright">Copyright &copy; 2013 VideoGamesChest.com</p>
                    </div>

                    <div class="span3 offset5">
                        <ul class="unstyled">
                            <li><a href="https://twitter.com/videogameschest" title="twitter"><i class="icon-twitter-sign icon-2x"></i></a><li>
                            <li class="muted">contact at videogameschest.com</li>
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
            @yield('jQuery')
          })

          @yield('jscode')
        </script> <!-- /JavaScript -->
    </body>
</html>
