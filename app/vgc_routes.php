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
        return $layout->nest('page_content', 'home');
    });

    Route::get('home', array('as' => 'get_home_page', 'do' => function() use ($layout)
    {
        return $layout->nest('page_content', 'home');
    }));


    // SUGGEST
    Route::get('suggest', array('as' => 'get_suggest_page', function() use ($layout)
    {
        return $layout->nest('page_content', 'suggest');
    }));


    // RSS FEEDS
    // 10/10/13 why is that in public space ?
    // > because rss agregators aren't loged in
    // the parameter is a user's url_key
    Route::get('reports/feed/(:any)', array('as' => 'get_reports_feed', 'uses' => 'feed@reports_feed'));
        

    //VIEW PROFILE
    Route::get('profiles/(:num)', array('as' => 'get_profile_view', 'uses' => 'admin@profile_view'));
    
    

    // REPORT FORM
    // for use by the colorbox
    Route::get('postreport/(:num)', array('as' => 'get_report_form', function($profile_id)
    {
        return View::make('forms/postreport', array('profile' => Game::find($profile_id)));
    }));



//----------------------------------------------------------------------------------
//  MUST BE LEGIT POST
//----------------------------------------------------------------------------------

    Route::group(array('before' => 'csrf'), function()
    {
        Route::post('reports/create', array('as' => 'post_reports_create', 'uses' => 'admin@reports_create'));

        Route::post('search', array('as' => 'post_search', function()
        {
            $input = Input::all();

            $action = 'search';
            if (isset($input['browse'])) {
                $action = 'browse';
                unset($input['browse']);
            } elseif (isset($input['discover'])) {
                $action = 'discover';
                unset($input['discover']);
            }

            $search = Search::create($input);
            return Redirect::to_route('get_'.$action.'_page', array($search->id));
        }));


        Route::post('browse', array('as' => 'post_browse', function()
        {
            return Redirect::to_route('get_browse_page', array(Input::get('search_id')));
        }));
        
        // Suggest
        Route::post('suggest', array('as' => 'post_suggest', function()
        {
            $url = Input::get('url');
            $validation = Validator::make(Input::all(), array('url' => 'required|url|min:10'));
            if ($validation->passes()) {
                if (SuggestedProfile::where_url($url)->first() !== null) {
                    HTML::set_error(lang('vgc.suggest.msg.url_already_suggested'));
                } else {
                    SuggestedProfile::create(array(
                        'url' => $url,
                        'source' => 'user',
                    ));
                }
            }

            return Redirect::back()->with_input()->with_errors($validation);
        }));
    });



//----------------------------------------------------------------------------------
//  MUST BE GUEST
//----------------------------------------------------------------------------------

    Route::group(array('before' => 'is_guest'), function()
    {
        Route::get('login', array('as' => 'get_login_page', 'uses' => 'admin@login_page'));
        // Route::get('register', array('as' => 'get_register_page', 'uses' => 'admin@register_page'));
        Route::get('user/lostpassword/(:num)/(:any)', array('as' => 'get_lostpassword_confirmation', 'uses' => 'admin@lostpassword_confirmation'));
    });



//----------------------------------------------------------------------------------
//  MUST BE GUEST AND LEGIT POST
//----------------------------------------------------------------------------------

    Route::group(array('before' => 'is_guest|csrf'), function()
    {
        // Route::post('register', array('as' => 'post_register', 'uses' => 'admin@register'));
        Route::post('login', array('as' => 'post_login', 'uses' => 'admin@login'));
        Route::post('lostpassword', array('as' => 'post_lostpassword', 'uses' => 'admin@lostpassword'));
    });



//----------------------------------------------------------------------------------
//  MUST BE LOGGED IN
//----------------------------------------------------------------------------------

    Route::group(array('before' => 'auth'), function()
    {
        
    });



//----------------------------------------------------------------------------------
//  MUST BE LOGGED IN AND LEGIT POST
//----------------------------------------------------------------------------------

    Route::group(array('before' => 'auth|csrf'), function()
    {
        
    });



//----------------------------------------------------------------------------------
//  MUST BE ADMIN
//----------------------------------------------------------------------------------

    Route::group(array('before' => 'auth|is_admin'), function() use ($layout)
    {
        Route::get('user', function()
        {
            return Redirect::to_route('get_user_update'); // no regular profile user
        });
        Route::get('user/create', array('as' => 'get_user_create', 'uses' => 'admin@user_create'));
        Route::get('user/update/(:num?)', array('as' => 'get_user_update', 'uses' => 'admin@user_update'));

        Route::get('logout', array('as' => 'get_logout', 'uses' => 'admin@logout'));
        
        Route::get('profiles/create', array('as' => 'get_profile_create', 'uses' => 'admin@profile_create'));
        Route::get('profiles/update/(:num?)', array('as' => 'get_profile_update', 'uses' => 'admin@profile_update'));
        Route::get('profiles/preview/(:num)', array('as' => 'get_profile_preview', 'uses' => 'admin@profile_preview'));

        Route::get('reports', array('as' => 'get_reports', 'uses' => 'admin@reports'));
        Route::get('review', array('as' => 'get_review', 'uses' => 'admin@review'));

        Route::get('testadmincontroller/(:num)', array('as' => 'get_testadmin_controller', 'uses' => 'discover@FeedData'));
        Route::get('test/(:all?)', function($searches = null) use ($layout)
        {
            /*$url = 'http://www.indiedb.com/games/minecraft';
            $result = Crawler::crawl_game($url);
            dd($result);*/
            // echo '<pre>'.Crawler::crawl_game($url).'</pre>';;

            return $layout->nest('page_content', 'test');
        });

        Route::post('test', array('as' => 'post_test', function() use ($layout)
        {
            return $layout->nest('page_content', 'test');
        }));

        // CRAWLER
        Route::get('crawler', array('as' => 'get_crawler_page', 'uses' => 'crawler@index'));
        Route::get('crawler/readrss', array('as' => 'get_crawler_read_feed_urls', 'uses' => 'crawler@read_feed_urls'));
    });



//----------------------------------------------------------------------------------
//  MUST BE ADMIN AND LEGIT POST
//----------------------------------------------------------------------------------

    Route::group(array('before' => 'auth|is_admin|csrf'), function()
    {
        Route::post('user/create', array('as' => 'post_user_create', 'uses' => 'admin@user_create'));
        Route::post('user/update', array('as' => 'post_user_update', 'uses' => 'admin@user_update'));
        Route::post('user/updatepassword', array('as' => 'post_password_update', 'uses' => 'admin@password_update'));
        
        Route::post('review', array('as' => 'post_review', 'uses' => 'admin@review'));

        Route::post('profiles/select', array('as' => 'post_profile_select', 'uses' => 'admin@profile_select'));
        Route::post('profiles/create', array('as' => 'post_profile_create', 'uses' => 'admin@profile_create'));
        Route::post('profiles/update', array('as' => 'post_profile_update', 'uses' => 'admin@profile_update'));
       
        Route::post('reports/update', array('as' => 'post_reports_update', 'uses' => 'admin@reports_update'));

        // CRAWLER
        Route::post('crawler_perform_actions', array('as' => 'post_crawler_perform_actions', 'uses' => 'crawler@perform_actions'));
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
    return Redirect::to_route('get_home_page');
});

Event::listen('500', function()
{
    Log::write('error 500', 'Error 500 have been caught.');
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

    // check if user has the logged in cokkie
    $logged_in = Cookie::get('vgc_user_logged_in', '0');
    if ($logged_in != '0') Auth::login((int) $logged_in);


    $route = Request::$route;
    // CONTROLLER is used in views/layouts/main and views/menu
    if ($route->controller != null) { // seems to never be the case ??
        define('CONTROLLER', $route->controller);

    } else {
        $uri = $route->uri;
        $segments = preg_split('#/#', $route->uri);
        
        define('CONTROLLER', $segments[0]);
        
        if ( ! isset($segments[1]))
            $segments[1] = 'index';
    }
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


// OLD :

// SEARCH PROFILE
    /*Route::get('search/(:any?)', array('as' => 'get_search_page', function($search_id = null) use ($layout)
    {
        if ($search_id !== null) {
            if (is_string($search_id)) {
                $search_id = get_category_id($search_id);
            }

            $search = Search::get($search_id);
            if ($search !== null) {
                $profiles = Search::make($search->data)->where_privacy('public')->get();
                return $layout->nest('page_content', 'search', array(
                    'profiles' => $profiles, 
                    'search_data' => $search->array_data, // search_data is used by search_profiles_common
                    'search_id' => $search_id,
                ));
            } else {
                HTML::set_error(lang('search.msg.id_not_found', array('id'=>$search_id)));
                return Redirect::to_route('get_search_page');
            }
        }
        
        return $layout->nest('page_content', 'search');
    }));*/

    // Route::get('search/feed/(:num)', array('as' => 'get_search_feed', 'uses' => 'feed@search_feed'));

    // BROWSE
    /*Route::get('browse/(:any?)', array('as' => 'get_browse_page', function($search_id = null) use ($layout)
    {
        if ($search_id !== null) {
            if (is_string($search_id)) {
                $search_id = get_category_id($search_id);
            }
            
            $search = Search::get($search_id);
            if ($search !== null) {
                $profiles = Search::make($search->data)->where_privacy('public')->get();
                return $layout->nest('page_content', 'browse', array(
                    'profiles' => $profiles, 
                    'search_data' => $search->array_data,
                    'search_id' => $search_id,
                ));
            } else {
                HTML::set_error(lang('search.msg.id_not_found', array('id'=>$search_id)));
                return Redirect::to_route('get_browse_page');
            }
        }
        
        return $layout->nest('page_content', 'browse');
    }));*/