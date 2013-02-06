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
// NO FILTERS (display profiles below 'must be logged in')
//----------------------------------------------------------------------------------

    // HOME

    Route::get('/', function() use ($layout)
    {
        //return Redirect::to_route('get_home');
        return $layout->nest('page_content', 'home');
    });

    Route::get('home', array('as' => 'get_home_page', 'do' => function() use ($layout)
    {
        return $layout->nest('page_content', 'home');
    }));

    Route::get('about', array('as' => 'get_about_page', 'do' => function() use ($layout)
    {
        return $layout->nest('page_content', 'about');
    }));


    // PARTICIPATE
    Route::get('participate', array('as' => 'get_participate_page', 'do' => function() use ($layout)
    {
        return $layout->nest('page_content', 'participate');
    }));


    // SEARCH PROFILE

    Route::get('search/(:num?)', array('as' => 'get_search_page', function($search_id = null) use ($layout)
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

    Route::get('search/feed/(:num)', array('as' => 'get_search_feed', 'uses' => 'feed@search_feed'));


    // DISCOVER
    Route::get('discover', array('as' => 'get_discover_page', 'uses' => 'discover@index'));
    Route::get('discover/feed', array('as' => 'get_discover_feed_page', 'uses' => 'discover@feed_page'));
    Route::get('discover/feed/(:num)', array('as' => 'get_discover_feed_data', 'uses' => 'discover@feed_data'));
    Route::get('discover/newsletter', array('as' => 'get_discover_newsletter_page', 'uses' => 'discover@newsletter_page'));
    Route::get('discover/newsletter/(:num)/(:any)', array('as' => 'get_discover_newsletter_update', 'uses' => 'discover@newsletter_update'));
    



    // SET LANGUAGE

    Route::get('setlanguage/(:any?)', array('as' => 'get_set_language_page', 'do' => function($language = null) use ($layout)
    {
        /*if (is_null($language)) {
            $language = Session::get('language', Config::get('language', 'en'));
        }

        Session::put('language', substr($language, 0, 2));*/
        return Redirect::back();
    }));


    // RSS FEEDS
    Route::get('reports/feed/(developer|admin)/(:num)/(:any)', array('as' => 'get_reports_feed', 'uses' => 'feed@reports_feed'));
    Route::get('reviews/feed/('.implode('|', Config::get('vgc.review.types')).')/(:num)/(:any)', array('as' => 'get_reviews_feed', 'uses' => 'feed@reviews_feed'));


    // PROMOTE
    Route::get('promote', array('as' => 'get_promote_page', 'uses' => 'promotion@index'));
    
    Route::get('promote/crosspromotion', array('as' => 'get_crosspromotion_page', 'uses' => 'promotion@crosspromotion_page'));
    // When games wants their promoted profiles
    Route::get('promote/crosspromotion/(:num)/(:any)', array('as' => 'get_crosspromotion_from_game', 'uses' => 'promotion@crosspromotion_from_game'));



//----------------------------------------------------------------------------------
//  MUST BE LEGIT POST
//----------------------------------------------------------------------------------

    Route::group(array('before' => 'csrf'), function()
    {
        Route::post('reports/add', array('as' => 'post_addreport', 'uses' => 'admin@report_create'));

        Route::post('search', array('as' => 'post_search', 'before' => 'csrf', function()
        {
            $input = Input::all();
            $search = Search::create($input);
            return Redirect::to_route('get_search', array($search->id));
        }));


        // user subscribe to a promotion feed or email
        Route::post('discover/feed/create', array('as' => 'post_discover_feed_create', 'uses' => 'discover@feed_create'));
        Route::post('discover/newsletter/create', array('as' => 'post_discover_newsletter_create', 'uses' => 'discover@newsletter_create'));
        Route::post('discover/newsletter/update', array('as' => 'post_discover_newsletter_update', 'uses' => 'discover@newsletter_update'));
    });



//----------------------------------------------------------------------------------
//  MUST BE GUEST
//----------------------------------------------------------------------------------

    Route::group(array('before' => 'is_guest'), function()
    {
        Route::get('login', array('as' => 'get_login_page', 'uses' => 'admin@login_page'));
        Route::get('lostpassword', array('as' => 'get_lostpassword_page', 'uses' => 'admin@lostpassword_page'));
        Route::get('register', array('as' => 'get_register_page', 'uses' => 'admin@register_page'));
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
            return Redirect::to_route('get_user_update');
        });

        Route::get('logout', array('as' => 'get_logout', 'uses' => 'admin@logout'));

        Route::get('user/update/(:num?)', array('as' => 'get_user_update', 'uses' => 'admin@user_update'));
        
        Route::get('developer/create', array('as' => 'get_developer_create', 'uses' => 'admin@developer_create'));
        Route::get('developer/update/(:num?)', array('as' => 'get_developer_update', 'uses' => 'admin@developer_update'));
        // I could also use
        // Route::get('(add|edit)developer', 'admin@(:1)developer');

        Route::get('game/create', array('as' => 'get_game_create', 'uses' => 'admin@game_create'));
        Route::get('game/update/(:num?)', array('as' => 'get_game_update', 'uses' => 'admin@game_update'));    

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
            return $layout
            ->with('profile', $profile)
            ->nest('page_content', 'developer', array('profile' => $profile));
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
            return $layout
            ->with('profile', $profile)
            ->nest('page_content', 'game', array('profile' => $profile));
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
        Route::post('user/update', array('as' => 'post_user_update', 'uses' => 'admin@user_update'));
        Route::post('user/updatepassword', array('as' => 'post_password_update', 'uses' => 'admin@password_update'));
        Route::post('user/updateblacklist', array('as' => 'post_blacklist_update', 'uses' => 'admin@blacklist_update'));

        Route::post('selecteditdeveloper', array('as' => 'post_selecteditdeveloper', 'uses' => 'admin@selecteditdeveloper'));
        Route::post('developer/create', array('as' => 'post_developer_create', 'uses' => 'admin@developer_create'));
        Route::post('developer/update', array('as' => 'post_developer_update', 'uses' => 'admin@developer_update'));

        Route::post('selecteditgame', array('as' => 'post_selecteditgame', 'uses' => 'admin@selecteditgame'));
        Route::post('game/create', array('as' => 'post_game_create', 'uses' => 'admin@game_create'));
        Route::post('game/update', array('as' => 'post_game_update', 'uses' => 'admin@game_update'));

        
        Route::post('reports/update', array('as' => 'post_reports_update', 'uses' => 'admin@reports_update'));

        Route::post('promotion/crosspromotion', array('as' => 'post_crosspromotion_update', 'uses' => 'promotion@crosspromotion_update'));
        Route::post('game/update/crosspromotion', array('as' => 'post_crosspromotion_game_update', 'uses' => 'promotion@crosspromotion_game_update'));
    });



//----------------------------------------------------------------------------------
//  MUST BE DEVELOPER
//----------------------------------------------------------------------------------

    Route::group(array('before' => 'auth|is_developer'), function()
    {
        // nothing
    });



//----------------------------------------------------------------------------------
//  MUST BE DEVELOPER IN AND LEGIT POST
//----------------------------------------------------------------------------------

    Route::group(array('before' => 'auth|is_developer|csrf'), function()
    {
        Route::post('promote/update_game_subscription', array('as' => 'post_promote_update_profile_subscription', 'uses' => 'promotion@promote_update_profile_subscription'));
    });



//----------------------------------------------------------------------------------
//  MUST BE ADMIN
//----------------------------------------------------------------------------------

    Route::group(array('before' => 'auth|is_admin'), function() use ($layout)
    {
        Route::get('user/create', array('as' => 'get_user_create', 'uses' => 'admin@user_create'));
        Route::get('reviews/(:any?)', array('as' => 'get_reviews', 'uses' => 'admin@reviews'));


        Route::get('testadmincontroller/(:num)', array('as' => 'get_testadmin_controller', 'uses' => 'discover@FeedData'));
        Route::get('testadmin', function() use ($layout)
        {
            //return \Laravel\CLI\Command::run(array('sendpromotionnewsletters'));
            //return Response::view('partials/feed_profile_list');
            return $layout->nest('page_content', 'test');
        });

        Route::post('testadmin', array('as' => 'post_test', function() use ($layout)
        {
            Input::flash();


            return $layout->nest('page_content', 'test');
        }));
    });



//----------------------------------------------------------------------------------
//  MUST BE ADMIN AND LEGIT POST
//----------------------------------------------------------------------------------

    Route::group(array('before' => 'auth|is_admin|csrf'), function()
    {
        Route::post('user/create', array('as' => 'post_user_create', 'uses' => 'admin@user_create'));
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
    // $lang = Session::get('language', Config::get('language', 'en'));
    define("LANGUAGE", 'en');

    // check if user has the logged in cokkie
    $logged_in = Cookie::get('user_logged_in', '0');
    if ($logged_in != '0') Auth::login((int) $logged_in);


    $route = Request::$route;
    // 01 feb 2013
    // ACTION does not seems to be used anywhere
    // but CONTROLLER is sometimes
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
        // I do that here since user()->games and user()->devs would return an empty array (or the equivalent)
        // when used for the first time in a condition
        // calling them here ensure that every next calls will return the array properly filled
        $games = user()->games;
        $devs = user()->devs;
    }


    // checking if it's time to sendpromotion email



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


Route::filter('is_guest', function()
{
    if ( ! Auth::guest()) {
        HTML::set_error(lang('common.msg.guest_only'));
        return Redirect::to_route('get_home_page');
    }
});


Route::filter('auth', function()
{
    if (Auth::guest()) {
        HTML::set_error(lang('common.msg.logged_in_only'));
        return Redirect::to_route('get_login_page');
    }
});


Route::filter('is_developer', function()
{
    if (Auth::guest()) {
        HTML::set_error(lang('common.msg.logged_in_only'));
        return Redirect::to_route('get_login_page');
    } else {
        if (Auth::user()->type != 'admin' && Auth::user()->type != 'developer') {
            HTML::set_error(lang('common.msg.developer_only'));
            return Redirect::to_route('get_home_page');
        }
    }
});

Route::filter('is_admin', function()
{
    if (Auth::guest()) {
        HTML::set_error(lang('common.msg.logged_in_only'));
        return Redirect::to_route('get_login_page');
    } else {
        if (Auth::user()->type != 'admin') {
            HTML::set_error(lang('common.msg.admin_only'));
            return Redirect::to_route('get_home_page');
        }
    }
});
