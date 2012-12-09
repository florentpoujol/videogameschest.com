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
|
*/

View::name('layouts.main', 'layout');
$layout = View::of('layout');



Route::get('test', function() use ($layout)
{
    return $layout->nest('page_content', 'test');
});
Route::post('test', function() use ($layout)
{
    var_dump(Input::all());
    Input::flash();
    return $layout->nest('page_content', 'test')->with('input', Input::all());
});


//----------------------------------------------------------------------------------
// ROUTES
//----------------------------------------------------------------------------------

// Home

Route::get('/', function() use ($layout)
{
    return $layout->nest('page_content', 'home');
});


Route::get('home', array('as' => 'get_home', 'do' => function() use ($layout)
{
    return $layout->nest('page_content', 'home');
}));


// ADD DEVELOPER / GAME

Route::get('/adddeveloper', array('as' => 'get_adddeveloper', function() use ($layout)
{
    return $layout->nest('page_content', 'adddeveloper')->with('page_title', lang('developer.add.title'));
}));

Route::get('/addgame', array('as' => 'get_addgame', function() use ($layout)
{
    return $layout->nest('page_content', 'addgame')->with('page_title', lang('game.add.title'));
}));


// SEARCH PROFILE

Route::get('/search', array('as' => 'get_search', function()
{
    return "Search page";
}));


// DISPLAY PROFILE

Route::get('/developer/(:any)', array('as' => 'get_developer', function($name = null)
{
    if (is_null($name)) return Redirect::to_route('get_search');

    return $layout->nest('page_content', 'devprofile')->with('page_title', lang('developer.profile.title'));
}));

Route::get('/game/(:any)', array('as' => 'get_game', function($name = null)
{
    if (is_null($name)) return Redirect::to_route('get_search');

    return $layout->nest('page_content', 'devprofile')->with('page_title', lang('developer.profile.title'));
}));


// SET LANGUAGE

Route::get('setlanguage/(:any?)', array('as' => 'get_set_language', 'do' => function($language = null) use ($layout)
{
    if (is_null($language)) {
        $language = Session::get('language', Config::get('language', 'en'));
    }

    Session::put('language', substr($language, 0, 2));
    return Redirect::back();
}));


//----------------------------------------------------------------------------------
//  ADMIN CONTROLLER ROUTES
//----------------------------------------------------------------------------------

// controller
// Route:controller('controlerName');
// custom route to a controller method :
// Route::get('customroute', 'controlerName@methodName');
// nammed route with controller
// Route::get('the route', array('as' => 'thenameoftheroute', 'uses' => 'controlerName@methodName'));

Route::get('admin/login', array('as' => 'get_login', 'uses' => 'admin@login'));


// must be logged in
Route::group(array('before' => 'auth'), function()
{
    Route::get('admin', array('as' => 'get_admin_home', 'uses' => 'admin@index'));

    Route::get('admin/logout', array('as' => 'get_logout', 'uses' => 'admin@logout'));

    Route::get('admin/edituser/(:num?)', array('as' => 'get_edituser', 'uses' => 'admin@edituser'));

    
    Route::get('admin/editdeveloper/(:num?)', array('as' => 'get_editdeveloper', 'uses' => 'admin@editdeveloper'));
    // I could also use
    // Route::get('admin/(add|edit)developer', 'admin@(:1)developer');

    Route::get('admin/addgame', array('as' => 'get_admin_addgame', 'uses' => 'admin@addgame'));
    Route::get('admin/editgame/(:num?)', array('as' => 'get_editgame', 'uses' => 'admin@editgame'));

    Route::get('admin/gamequeue', array('as' => 'get_gamequeue', 'uses' => 'admin@gamequeue'));

    Route::get('admin/reports', array('as' => 'get_reports', 'uses' => 'admin@reports'));
});


// must be logged in + admin
Route::group(array('before' => 'auth|admin'), function()
{
    Route::get('admin/adduser', array('as' => 'get_adduser', 'uses' => 'admin@adduser'));
    Route::get('admin/adddeveloper', array('as' => 'get_admin_adddeveloper', 'uses' => 'admin@adddeveloper'));
});


// must be logged in + admin + logit post
Route::group(array('before' => 'auth|admin|csrf'), function()
{
    Route::post('admin/adduser', array('as' => 'post_adduser', 'uses' => 'admin@adduser'));
    Route::post('admin/selecteditdeveloper', array('as' => 'post_selecteditdeveloper', 'uses' => 'admin@selecteditdeveloper'));
});


// must be logged in + legit post
Route::group(array('before' => 'auth|csrf'), function()
{
    Route::post('admin/edituser', array('as' => 'post_edituser', 'uses' => 'admin@edituser'));

    Route::post('admin/editdeveloper', array('as' => 'post_editdeveloper', 'uses' => 'admin@editdeveloper'));

    Route::post('admin/selecteditgame', array('as' => 'post_selecteditgame', 'uses' => 'admin@selecteditgame'));
    Route::post('admin/editgame', array('as' => 'post_editgame', 'uses' => 'admin@editgame'));

    Route::post('admin/gamequeue', array('as' => 'post_gamequeue', 'uses' => 'admin@gamequeue'));
});


// must be legit post
Route::group(array('before' => 'csrf'), function()
{
    Route::post('admin/login', array('as' => 'post_login', 'uses' => 'admin@login'));
    Route::post('admin/lostpassword', array('as' => 'post_lostpassword', 'uses' => 'admin@lostpassword'));
    Route::post('admin/adddeveloper', array('as' => 'post_adddeveloper', 'uses' => 'admin@adddeveloper'));
    Route::post('admin/addgame', array('as' => 'post_addgame', 'uses' => 'admin@addgame'));
    Route::post('admin/reports', array('as' => 'post_reports', 'uses' => 'admin@reports'));
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
    return Response::error('404');
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

    // set some CONST

    if (Auth::check()) { // user is logged in
        $user = Auth::user();
        define('IS_LOGGED_IN', true);
        define("USER_ID", $user->id);

        if (Auth::user()->type == 'admin') {
            define('IS_ADMIN', true);
            define('IS_TRUSTED', true);
            define('IS_DEVELOPER', false);
            define('DEV_PROFILE_ID', 0);
        } else {
            define('IS_ADMIN', false);
            define('IS_DEVELOPER', true);
            define('DEV_PROFILE_ID', $user->developer->id);

            $is_trusted = false;

            if ($user->dev->privacy == 'public' && ! is_null($user->dev->games)) {   
                foreach ($user->dev->games as $game) {
                    if ($game->privacy == 'public') {
                        $is_trusted = true;
                        break;
                    }
                }
            }

            define('IS_TRUSTED', $is_trusted);
        }
    } else {
        define('IS_LOGGED_IN', false);
        define('USER_ID', 0);
        define('IS_ADMIN', false);
        define('IS_DEVELOPER', false);
        define('IS_TRUSTED', false);
        define("DEV_PROFILE_ID", 0);
    }


    $route = Request::$route;
    
    if ($route->controller != null) {
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
