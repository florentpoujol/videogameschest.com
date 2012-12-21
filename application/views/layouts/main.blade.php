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
        <title>{{ $page_title }} | VideoGamesChest.com</title>

        <!-- Meta --> 
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="robots" content="noindex,nofollow" >
        <!-- /Meta -->

        <!-- CSS -->        
        {{ HTML::style('css/bootstrap.min.css') }}
        {{ HTML::style('css/bootstrap-responsive.min.css') }}
        {{ HTML::style('css/main.less', array('rel'=>'stylesheet/less')) }}
        @yield('cssfiles')
        <!-- /CSS -->
    </head>
    
    <body>
        @include('main_menu')

        <div class="container" id="page_content">
            <div id="message-box">
                {{ HTML::get_messages($errors) }}
            </div>
            
            {{ $page_content }}
            
        </div> <!-- /#page_content .container -->

        <footer>
            <div class="container">
                <P class="muted">Copyright &copy; 2012-2013 VideoGamesChest.com</p>
            </div>
        </footer>

        <!-- JavaScript -->
        <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
        <script src="http://lesscss.googlecode.com/files/less-1.3.0.min.js" type="text/javascript"></script>
        {{ HTML::script('js/bootstrap.min.js') }}
        @yield('jsfiles')

        <script>
          $(function () {
            @yield('jQuery')
          })

          @yield('jscode')
        </script> <!-- /JavaScript -->
    </body>
</html>
