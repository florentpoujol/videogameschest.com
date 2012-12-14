<?php
if ( ! isset($page_title)) {
    $page_title = ucfirst(CONTROLLER);

    if ($page_title == '') $page_title = 'Home';
}

if ( ! isset($page_content)) $page_content = '';
?>
<!DOCTYPE html>
<html lang="{{ LANGUAGE }}"> 
    <head>
        <title>{{ $page_title }} | VideoGamesChest.com</title>

        <!-- Meta --> 
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="robots" content="noindex,nofollow" >
        <!-- /Meta -->

        <!-- CSS -->        
        {{ Asset::container('bootstrapper')->styles(); }}

        <link rel="stylesheet/less" type="text/css" media="screen" href="{{ asset("css/main.less") }}" >
        <!-- /CSS -->
    </head>
    
    <body>
        @include('main_menu')

        <div class="container" id="page_content">
            <div id="message-box">
                {{ HTML::get_messages($errors) }}
            </div>
            
            {{ $page_content }}

            <br>
            <br>
            <br>
        </div>
        <!-- /#page_content .container -->

        <!-- JavaScript -->
        <script src="http://lesscss.googlecode.com/files/less-1.3.0.min.js" type="text/javascript"></script>
        <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
        {{ Asset::container('bootstrapper')->scripts(); }}
        @yield('jsfile')

        <script>
          $(function () {
            @yield('jQuery')
          })

          @yield('jscode')
        </script>
        <!-- /JavaScript -->
    </body>
</html>
