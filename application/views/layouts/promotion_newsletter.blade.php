<html>
    <head>
        <title>{{ $title }}</title>
        
        <style type="text/css">

        </style>
    </head>
    <body>
        @include('partials/promotion_profile_list')
        <br>

        @if ($newsletter->user_id > 0)
            <?php
            $route = route('get_discover_email_page';
            $logged_in_help = 'You will need to be logged in.';
            ?>
        @else
            <?php
            $route = route('get_discover_update_email_page', array($newsletter->id, $newsletter->url_key));
            $logged_in_help = '';
            ?>
        @endif

        <a href="{{ $route }}" title="Click here to update your subscription or unsubscribe">Click here to update your subscription or unsubscribe</a> {{ $logged_in_help }} <br>
    </body>
</html>