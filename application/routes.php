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
|		Route::get('hello', function()
|		{
|			return 'Hello World!';
|		});
|
| You can even respond to more than one URI:
|
|		Route::post(array('hello', 'world'), function()
|		{
|			return 'Hello World!';
|		});
|
| It's easy to allow URI wildcards using (:num) or (:any):
|
|		Route::put('hello/(:any)', function($name)
|		{
|			return "Welcome, $name.";
|		});
|
*/

View::name('layouts.main', 'layout');
$layout = View::of('layout');

Route::get('/', function() use ($layout)
{
	return $layout->nest('page_content', 'home');
});


Route::get('home', array('as' => 'get_home', 'do' => function() use ($layout)
{
	return $layout->nest('page_content', 'home');
}));


Route::get('/adddeveloper', array('as' => 'get_adddeveloper', function() use ($layout)
{
	return $layout->nest('page_content', 'adddeveloper');
}));

Route::get('/addgame', array('as' => 'get_addgame', function()
{
	return "Add game page";
}));


Route::get('/developer/(:any)', array('as' => 'get_developer', function()
{
	return "Developer page";
}));

Route::get('/game/(:any)', array('as' => 'get_game', function()
{
	return "Game page";
}));


// controller
// Route:controller('controlerName');

// custom route to a controller method :
// Route::get('customroute', 'controlerName@methodName');

// nammed route with controller
// Route::get('the route', array('as' => 'thenameoftheroute', 'uses' => 'controlerName@methodName'));


// ADMIN routes :

Route::get('admin/login', array('as' => 'get_login', 'uses' => 'admin@login'));


// must be logged in
Route::group(array('before' => 'auth'), function()
{
	Route::get('admin', array('as' => 'get_admin_home', 'uses' => 'admin@index'));

	Route::get('admin/logout', array('as' => 'get_logout', 'uses' => 'admin@logout'));

	Route::get('admin/edituser/(:num?)', array('as' => 'get_edituser', 'uses' => 'admin@edituser'));

	Route::get('admin/adddeveloper', array('as' => 'get_adddeveloper', 'uses' => 'admin@adddeveloper'));
	Route::get('admin/editdeveloper/(:num?)', array('as' => 'get_editdeveloper', 'uses' => 'admin@editdeveloper'));
	// I could also use
	// Route::get('admin/(add|edit)developer', 'admin@(:1)developer');

	Route::get('admin/addgame', array('as' => 'get_addgame', 'uses' => 'admin@addgame'));
	Route::get('admin/editgame/(:num?)', array('as' => 'get_editgame', 'uses' => 'admin@editgame'));

	Route::get('admin/gamequeue', array('as' => 'get_gamequeue', 'uses' => 'admin@gamequeue'));

	Route::get('admin/reports', array('as' => 'get_reports', 'uses' => 'admin@reports'));
});


// must be logged in + admin
Route::group(array('before' => 'admin|auth'), function()
{
	Route::get('admin/adduser', array('as' => 'get_adduser', 'uses' => 'admin@adduser'));
});


// must be logged in + admin + logit post
Route::group(array('before' => 'admin|auth|csrf'), function()
{
	Route::post('admin/adduser', array('as' => 'post_adduser', 'uses' => 'admin@adduser'));
});


// must be logged in + legit post
Route::group(array('before' => 'auth|csrf'), function()
{
	Route::post('admin/edituser/(:num?)', array('as' => 'post_edituser', 'uses' => 'admin@edituser'));

	Route::post('admin/editdeveloper/(:num?)', array('as' => 'post_editdeveloper', 'uses' => 'admin@editdeveloper'));

	Route::post('admin/editgame/(:num?)', array('as' => 'post_editgame', 'uses' => 'admin@editgame'));

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
|		Route::filter('filter', function()
|		{
|			return 'Filtered!';
|		});
|
| Next, attach the filter to a route:
|
|		Router::register('GET /', array('before' => 'filter', function()
|		{
|			return 'Hello World!';
|		}));
|
*/

Route::filter('before', function()
{
	// Do stuff before every request to your application...
	$lang = Session::get('language', 'en');
    define("LANGUAGE", $lang);

    // set some CONST

    if (Auth::check()) 
    { // user is logged in
    	define('IS_LOGGED_IN', true);
    	define("USER_ID", Auth::user()->id);

    	if (Auth::user()->type == 'admin') 
    	{
    		define('IS_ADMIN', true);
    		define('IS_DEVELOPER', false);
    		define("DEV_PROFILE_ID", 0);
    	}
    	else 
    	{
    		define('IS_ADMIN', false);
    		define('IS_DEVELOPER', true);
    		define("DEV_PROFILE_ID", Auth::user()->dev_profile->id);
    	}
    }
    else 
    {
    	define('IS_LOGGED_IN', false);
		define('USER_ID', 0);
		define('IS_ADMIN', false);
		define('IS_DEVELOPER', false);
		define("DEV_PROFILE_ID", 0);
    }

    $route = Request::$route;

    if ($route->controller != null) 
    {
    	define('CONTROLLER', $route->controller);
    	define('METHOD', $route->controller_action);
    }
    else
    {
    	$uri = $route->uri;
    	$segments = preg_split('#/#', $route->uri);
    	
    	define('CONTROLLER', $segments[0]);
    	
    	if ( ! isset($segments[1]))
    		$segments[1] = 'index';

    	define('METHOD', $segments[1]);
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
	if (Auth::guest()) 
	{
		HTML::set_error("You must be logged in to access this page !");
		return Redirect::to_route('get_login');
	}
});

Route::filter('admin', function()
{
	if ( ! Auth::guest()) 
	{
		if (Auth::user()->type != 'admin') 
		{
			HTML::set_error("You must be an administrator to access this page !");
			return Redirect::to_route('get_admin_home');
		}
	}
	else 
	{
		HTML::set_error("You must be a logged in administrator to access this page !");
		return Redirect::to_route('get_login');
	}
});