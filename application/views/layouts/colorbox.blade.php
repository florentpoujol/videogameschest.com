<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="robots" content="noindex,nofollow">

    {{ HTML::style('css/bootstrap/bootstrap.min.css') }}
    {{ HTML::style('css/vgc/main.less', array('rel'=>'stylesheet/less')) }}
</head>
<body id="colorbox-layout">
    @yield('colorbox_content')

    @if (Config::get('vgc.environment') == 'local')
        {{ HTML::script('js/jquery-v1.7.1-min.js') }}
    @else
        <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
    @endif
    {{ HTML::script('js/less.js') }}
    {{ HTML::script('js/bootstrap/bootstrap.min.js') }}
</body>
</html>