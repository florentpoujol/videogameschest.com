<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Simply tell Laravel the HTTP verbs and URIs it should respond to. It is a
| breeze to setup your application using Laravel's RESTful routing and it
| is perfectly suited for building large applications and simple APIs.
|
| Let's respond to a simple GET request to http://example.com/hello:
|
|       Route::get('hello', function()
|       {
|           return 'Hello World!';
|       });
|
| You can even respond to more than one URI:
|
|       Route::post(array('hello', 'world'), function()
|       {
|           return 'Hello World!';
|       });
|
| It's easy to allow URI wildcards using (:num) or (:any):
|
|       Route::put('hello/(:any)', function($name)
|       {
|           return "Welcome, $name.";
|       });


// controller
// Route::controller('controlerName');
// custom route to a controller method :
// Route::get('customroute', 'controlerName@methodName');
// nammed route with controller
// Route::get('the route', array('as' => 'thenameoftheroute', 'uses' => 'controlerName@methodName'));
|
*/


View::name('layouts.main', 'layout');
$layout = View::of('layout');


//----------------------------------------------------------------------------------
// NO FILTERS
//----------------------------------------------------------------------------------

    // HOME

    Route::get('/', function() use ($layout)
    {
        //return Redirect::to_route('get_home');
        return $layout->nest('page_content', 'home');
    });


    Route::get('home', array('as' => 'get_home', 'do' => function() use ($layout)
    {
        return $layout->nest('page_content', 'home');
    }));


    // SEARCH PROFILE

    Route::get('search/(:num?)', array('as' => 'get_search', function($search_id = null) use ($layout)
    {
        if ( ! is_null($search_id)) {
            $search = Search::get($search_id);
            if ( ! is_null($search)) {
                $profiles = Search::get_profiles($search->data);
                return $layout->nest('page_content', 'search', array(
                    'profiles' => $profiles, 
                    'search_data' => $search->array_data,
                    'search_id' => $search_id,
                ));
            } else {
                HTML::set_error(lang('search.msg.id_not_found', array('id'=>$search_id)));
                return Redirect::to_route('get_search');
            }
        }
        
        return $layout->nest('page_content', 'search');
    }));

    // for POST search, see below in CSRF group


    // DISCOVER
    Route::get('discover', array('as' => 'get_discover_page', 'uses' => 'discover@index'));
    Route::get('discover/feed', array('as' => 'get_discover_feed_page', 'uses' => 'discover@FeedPage'));
    Route::get('discover/feed/(:num)', array('as' => 'get_discover_feed_data', 'uses' => 'discover@FeedData'));
    Route::get('discover/email', array('as' => 'get_discover_email_page', 'uses' => 'discover@EmailPage'));
    Route::get('discover/email/(:num)/(:any)', array('as' => 'get_discover_update_email_page', 'uses' => 'discover@EmailPage'));
    



    // SET LANGUAGE

    Route::get('setlanguage/(:any?)', array('as' => 'get_set_language', 'do' => function($language = null) use ($layout)
    {
        if (is_null($language)) {
            $language = Session::get('language', Config::get('language', 'en'));
        }

        Session::put('language', substr($language, 0, 2));
        return Redirect::back();
    }));


    // RSS FEEDS

    Route::get('feed/(rss|atom)/reports/(developer|admin)/(:num)/(:any)', array('as' => 'get_reports_feed', 'uses' => 'feed@reports_feed'));
    Route::get('feed/(rss|atom)/search/(:num)', array('as' => 'get_search_feed', 'uses' => 'feed@search_feed'));

    Route::get('feed/(rss|atom)/reviews/('.implode('|', Config::get('vgc.review.types')).')/(:num)/(:any)', array('as' => 'get_reviews_feed', 'uses' => 'feed@reviews_feed'));


    // PROMOTE
    Route::get('promote', array('as' => 'get_promotion', 'uses' => 'promotion@index'));
    
    Route::get('promote/crosspromotion', array('as' => 'get_crosspromotion', 'uses' => 'promotion@crosspromotion'));
    // When games wants their promoted profiles
    Route::get('promote/crosspromotion/(:num)/(:any)', array('as' => 'get_crosspromotion_from_game', 'uses' => 'promotion@crosspromotion_from_game'));

        


//----------------------------------------------------------------------------------
//  MUST BE LEGIT POST
//----------------------------------------------------------------------------------

    Route::group(array('before' => 'csrf'), function()
    {
        Route::post('reports/add', array('as' => 'post_addreport', 'uses' => 'admin@addreport'));

        Route::post('/search', array('as' => 'post_search', 'before' => 'csrf', function()
        {
            $input = Input::all();
            $search = Search::create($input);
            return Redirect::to_route('get_search', array($search->id));
        }));


        // user subscribe to a promotion feed or email
        Route::post('discover/feed/create', array('as' => 'post_discover_create_feed', 'uses' => 'discover@CreateFeed'));
        Route::post('discover/email/create', array('as' => 'post_discover_create_email', 'uses' => 'discover@CreateEmail'));
        Route::post('discover/email/update', array('as' => 'post_discover_update_email', 'uses' => 'discover@UpdateEmail'));
    });



//----------------------------------------------------------------------------------
//  MUST BE GUEST
//----------------------------------------------------------------------------------

    Route::group(array('before' => 'is_guest'), function()
    {
        Route::get('login', array('as' => 'get_login', 'uses' => 'admin@login'));
        Route::get('lostpassword', array('as' => 'get_lostpassword', 'uses' => 'admin@lostpassword'));
        Route::get('register', array('as' => 'get_register', 'uses' => 'admin@register'));
        Route::get('register/confirmation/(:num)/(:any)', array('as' => 'get_register_confirmation', 'uses' => 'admin@register_confirmation'));
        Route::get('user/lostpassword/(:num)/(:any)', array('as' => 'get_lostpassword_confirmation', 'uses' => 'admin@lostpassword_confirmation'));
    });



//----------------------------------------------------------------------------------
//  MUST BE GUEST AND LEGIT POST
//----------------------------------------------------------------------------------

    Route::group(array('before' => 'is_guest|csrf'), function()
    {
        Route::post('register', array('as' => 'post_register', 'uses' => 'admin@register'));
        Route::post('login', array('as' => 'post_login', 'uses' => 'admin@login'));
        Route::post('lostpassword', array('as' => 'post_lostpassword', 'uses' => 'admin@lostpassword'));
    });



//----------------------------------------------------------------------------------
//  MUST BE LOGGED IN + display developers and games
//----------------------------------------------------------------------------------

    Route::group(array('before' => 'auth'), function()
    {
        //admin routes
        Route::get('admin', array('as' => 'get_admin_home', 'uses' => 'admin@index'));

        Route::get('user', function()
        {
            return Redirect::to_route('get_edituser');
        });

        Route::get('logout', array('as' => 'get_logout', 'uses' => 'admin@logout'));

        Route::get('user/edit/(:num?)', array('as' => 'get_edituser', 'uses' => 'admin@edituser'));
        
        Route::get('developer/add', array('as' => 'get_adddeveloper', 'uses' => 'admin@adddeveloper'));
        Route::get('developer/edit/(:num?)', array('as' => 'get_editdeveloper', 'uses' => 'admin@editdeveloper'));
        // I could also use
        // Route::get('(add|edit)developer', 'admin@(:1)developer');

        Route::get('game/add', array('as' => 'get_addgame', 'uses' => 'admin@addgame'));
        Route::get('game/edit/(:num?)', array('as' => 'get_editgame', 'uses' => 'admin@editgame'));    

        Route::get('reports/(:any?)', array('as' => 'get_reports', 'uses' => 'admin@reports'));


    });


    // DISPLAY DEVELOPERS
    // they are written in order to be after the add and edit routing

    Route::get('developer/(:all?)', array('as' => 'get_developer', function($name = null) use ($layout)
    {
        if (is_null($name)) return Redirect::to_route('get_search');
        
        if (is_numeric($name)) {
            $profile = Dev::find($name);
            return Redirect::to_route('get_developer', array(name_to_url($profile->name)));
        }

        $profile = Dev::where_name(url_to_name($name))->first();

        if (is_null($profile)) {
            if (is_numeric($name)) {
                HTML::set_error(lang('errors.developer_profile_id_not_found', array('id'=>$name)));
            } else HTML::set_error(lang('errors.developer_profile_name_not_found', array('name'=>$name)));

            return Redirect::to_route('get_search');
        }

        // display profile if :
        // profile is public
        // user is admin
        // user is dev and profile is user's or in review   
        if ($profile->privacy == 'public' || is_admin() || $profile->user_id == user_id()) {
            return $layout->nest('page_content', 'developerdisplay', array('profile' => $profile));
        } else {
            HTML::set_error(lang('common.msg.access_not_allowed', array('page' => 'Developer profile '.$name)));
            return Redirect::to_route('get_search');
        }
    }));


    // DISPLAY DEVELOPERS

    Route::get('game/(:all?)', array('as' => 'get_game', function($name = null) use ($layout)
    {
        if (is_null($name)) return Redirect::to_route('get_search');
        
        if (is_numeric($name)) {
            $profile = Game::find($name);
            return Redirect::to_route('get_game', array(name_to_url($profile->name)));
        } 

        $profile = Game::where_name(url_to_name($name))->first();

        if (is_null($profile)) {
            if (is_numeric($name)) { 
                HTML::set_error(lang('errors.game_profile_id_not_found', array('id'=>$name)));
            } else HTML::set_error(lang('errors.game_profile_name_not_found', array('name'=>$name)));

            return Redirect::to_route('get_search');
        }

        // display profile if :
        // profile is public
        // user is admin
        // user is dev and profile is user's or in review
        if ($profile->privacy == 'public' || is_admin() || $profile->user_id == user_id()) {
            return $layout->nest('page_content', 'gamedisplay', array('profile' => $profile));
        } else {
            HTML::set_error(lang('common.msg.access_not_allowed', array('page' => 'Game profile '.$name)));
            return Redirect::to_route('get_search');
        }
    }));



//----------------------------------------------------------------------------------
//  MUST BE LOGGED IN AND LEGIT POST
//----------------------------------------------------------------------------------

    Route::group(array('before' => 'auth|csrf'), function()
    {
        Route::post('user/edit', array('as' => 'post_edituser', 'uses' => 'admin@edituser'));
        Route::post('user/editpassword', array('as' => 'post_editpassword', 'uses' => 'admin@editpassword'));
        Route::post('user/editblacklist', array('as' => 'post_editblacklist', 'uses' => 'admin@editblacklist'));

        Route::post('selecteditdeveloper', array('as' => 'post_selecteditdeveloper', 'uses' => 'admin@selecteditdeveloper'));
        Route::post('developer/add', array('as' => 'post_adddeveloper', 'uses' => 'admin@adddeveloper'));
        Route::post('developer/edit', array('as' => 'post_editdeveloper', 'uses' => 'admin@editdeveloper'));

        Route::post('selecteditgame', array('as' => 'post_selecteditgame', 'uses' => 'admin@selecteditgame'));
        Route::post('game/add', array('as' => 'post_addgame', 'uses' => 'admin@addgame'));
        Route::post('game/edit', array('as' => 'post_editgame', 'uses' => 'admin@editgame'));

        
        Route::post('reports/edit', array('as' => 'post_editreports', 'uses' => 'admin@editreports'));

        Route::post('promotion/crosspromotion', array('as' => 'post_crosspromotion', 'uses' => 'promotion@crosspromotion'));
        Route::post('game/edit/crosspromotion', array('as' => 'post_crosspromotion_editgame', 'uses' => 'promotion@crosspromotion_editgame'));
    });



//----------------------------------------------------------------------------------
//  MUST BE ADMIN
//----------------------------------------------------------------------------------

    Route::group(array('before' => 'auth|admin'), function() use ($layout)
    {
        Route::get('user/add', array('as' => 'get_adduser', 'uses' => 'admin@adduser'));
        Route::get('reviews/(:any?)', array('as' => 'get_reviews', 'uses' => 'admin@reviews'));


        Route::get('test', function() use ($layout)
        {
            return $layout->nest('page_content', 'test');
        });

        Route::post('test', array('as' => 'post_test', function() use ($layout)
        {
            Input::flash();


            return $layout->nest('page_content', 'test');
        }));
    });



//----------------------------------------------------------------------------------
//  MUST BE ADMIN AND LEGIT POST
//----------------------------------------------------------------------------------

    Route::group(array('before' => 'auth|admin|csrf'), function()
    {
        Route::post('adduser', array('as' => 'post_adduser', 'uses' => 'admin@adduser'));
        Route::post('reviews', array('as' => 'post_reviews', 'uses' => 'admin@reviews'));
    });




Event::listen('laravel.query', function($sql, $bindings, $time) {
    //var_dump($sql);
});



/*
|--------------------------------------------------------------------------
| Application 404 & 500 Error Handlers
|--------------------------------------------------------------------------
|
| To centralize and simplify 404 handling, Laravel uses an awesome event
| system to retrieve the response. Feel free to modify this function to
| your tastes and the needs of your application.
|
| Similarly, we use an event to handle the display of 500 level errors
| within the application. These errors are fired when there is an
| uncaught exception thrown in the application.
|
*/

Event::listen('404', function()
{
    HTML::set_error(lang('common.msg.page_not_found'));
    Log::write('error 404', 'Page not found : '.URL::current());
    return Redirect::to_route('get_home');
});

Event::listen('500', function()
{
    return Response::error('500');
});

/*
|--------------------------------------------------------------------------
| Route Filters
|--------------------------------------------------------------------------
|
| Filters provide a convenient method for attaching functionality to your
| routes. The built-in before and after filters are called before and
| after every request to your application, and you may even create
| other filters that can be attached to individual routes.
|
| Let's walk through an example...
|
| First, define a filter:
|
|       Route::filter('filter', function()
|       {
|           return 'Filtered!';
|       });
|
| Next, attach the filter to a route:
|
|       Router::register('GET /', array('before' => 'filter', function()
|       {
|           return 'Hello World!';
|       }));
|
*/

Route::filter('before', function()
{
    // Do stuff before every request to your application...
    $lang = Session::get('language', Config::get('language', 'en'));
    define("LANGUAGE", $lang);

    // check if user has the logged in cokkie
    $logged_in = Cookie::get('user_logged_in', '0');
    if ($logged_in != '0') Auth::login((int) $logged_in);


    $route = Request::$route;
    
    if ($route->controller != null) { // seems to never be the case ??
        define('CONTROLLER', $route->controller);
        define('ACTION', $route->controller_action);
    } else {
        $uri = $route->uri;
        $segments = preg_split('#/#', $route->uri);
        
        define('CONTROLLER', $segments[0]);
        
        if ( ! isset($segments[1]))
            $segments[1] = 'index';

        define('ACTION', $segments[1]);
    }


    if (is_logged_in()) {
        // I do that here since user()->games and user()->games would return an empty array (or the equivalent)
        // when used for the first time in a condition
        // calling them here ensure that evry next calls will return the array properly filled
        $games = user()->games;
        $devs = user()->devs;
    }

    // checking success of reviews
    // check number of approvals
    // will also be called by a cron tab job
    /*$last_check_date = new DateTime(DBConfig::get('review_check_date'));
    $interval = new DateInterval('PT'. Config::get('vgc.review.check_interval') .'M');
    $last_check_date->add($interval);
    $now = new DateTime();
    
    if ($last_check_date < $now) {
        DBConfig::put('review_check_date', $now);
        $reviews = Config::get('vgc.review.types');

        foreach ($reviews as $review) {
            $profiles = Game::where_privacy($review)->get();
            $profiles = array_merge($profiles, Dev::where_privacy($review)->get());

            foreach ($profiles as $profile) {
                if (count($profile->approved_by) >= Config::get('vgc.review.approval_threshold', 1000) && 
                    count($profile->reports('admin')) == 0
                ) {
                    $profile->passed_review();
                }
            }
        }
    }*/


});

Route::filter('after', function($response)
{
    // Do stuff after every request to your application...
});

Route::filter('csrf', function()
{
    if (Request::forged()) {
        return Response::error('500');
    }
});

Route::filter('auth', function()
{
    if (Auth::guest()) {
        HTML::set_error(lang('messages.logged_in_only'));
        return Redirect::to_route('get_login');
    }
});

Route::filter('is_guest', function()
{
    if ( ! Auth::guest()) {
        return Redirect::to_route('get_admin_home');
    }
});

Route::filter('admin', function()
{
    if ( ! Auth::guest()) {
        if (Auth::user()->type != 'admin') {
            HTML::set_error(lang('messages.admin_only'));
            return Redirect::to_route('get_admin_home');
        }
    } else {
        HTML::set_error(lang('messages.admin_and_logged_in'));
        return Redirect::to_route('get_login');
    }
});
