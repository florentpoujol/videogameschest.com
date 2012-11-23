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


Route::get('/', function()
{
	return Redirect::to_route('home');
});


Route::get('home', array('as' => 'home', 'do' => function()
{
	return View::make('home');
}));


Route::get('/adddeveloper', array('as' => 'adddeveloper', function()
{
	return "Add developer page";
}));

Route::get('/addgame', array('as' => 'addgame', function()
{
	return "Add game page";
}));


Route::get('/developer/(:any)', array('as' => 'developer', function()
{
	return "Developer page";
}));

Route::get('/game/(:any)', array('as' => 'game', function()
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

Route::get('admin/login', array('as' => 'admin_login', 'uses' => 'admin@login'));


// protected routes
Route::group(array('before' => 'auth'), function()
{
	Route::get('admin', array('as' => 'admin_home', 'uses' => 'admin@index'));

	
	Route::get('admin/logout', array('as' => 'admin_logout', 'uses' => 'admin@logout'));

	Route::get('admin/addduser', array('as' => 'admin_adduser', 'uses' => 'admin@adduser'));
	Route::get('admin/edituser', array('as' => 'admin_edituser', 'uses' => 'admin@edituser'));

	Route::get('admin/adddeveloper', array('as' => 'admin_adddeveloper', 'uses' => 'admin@adddeveloper'));
	Route::get('admin/editdeveloper', array('as' => 'admin_editdeveloper', 'uses' => 'admin@editdeveloper'));

	Route::get('admin/addgame', array('as' => 'admin_addgame', 'uses' => 'admin@addgame'));
	Route::get('admin/editgame', array('as' => 'admin_editgame', 'uses' => 'admin@editgame'));

	Route::get('admin/gamequeue', array('as' => 'admin_gamequeue', 'uses' => 'admin@gamequeue'));
	Route::get('admin/messages', array('as' => 'admin_messages', 'uses' => 'admin@messages'));
	Route::get('admin/reports', array('as' => 'admin_reports', 'uses' => 'admin@reports'));
});



Route::group(array('before' => 'csrf'), function()
{
	Route::post('admin/login', array('as' => 'post_login', 'uses' => 'admin@login'));
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

    if (Auth::check()) { // user is logged in
    	define('IS_LOGGED_IN', true);
    	define("USER_ID", Auth::user()->id);

    	if (Auth::user()->type == 'admin') {
    		define('IS_ADMIN', true);
    		define('IS_DEVELOPER', false);
    	}
    	else {
    		define('IS_ADMIN', false);
    		define('IS_DEVELOPER', true);
    	}
    }
    else {
    	define('IS_LOGGED_IN', false);
		define('USER_ID', 0);
		define('IS_ADMIN', false);
		define('IS_DEVELOPER', false);
    }




});

Route::filter('after', function($response)
{
	// Do stuff after every request to your application...
});

Route::filter('csrf', function()
{
	if (Request::forged()) return Response::error('500');
});

Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::to_route('admin_login');
});