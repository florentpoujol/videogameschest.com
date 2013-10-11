<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
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


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
    if (Auth::guest()) {
        HTML::set_error(lang('common.msg.logged_in_only'));
        return Redirect::to_route('get_login_page');
    }
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
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

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	// if (Auth::check()) return Redirect::to('/');
    if ( ! Auth::guest()) {
        HTML::set_error(lang('common.msg.guest_only'));
        return Redirect::to_route('get_home_page');
    }
});


/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});