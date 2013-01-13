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
// HOME
//----------------------------------------------------------------------------------

Route::get('/', function() use ($layout)
{
    return $layout->nest('page_content', 'home');
});


Route::get('home', array('as' => 'get_home', 'do' => function() use ($layout)
{
    return $layout->nest('page_content', 'home');
}));



//----------------------------------------------------------------------------------
// ADD DEVELOPER / GAME
//----------------------------------------------------------------------------------

/*Route::get('/adddeveloper', array('as' => 'get_adddeveloper', function() use ($layout)
{
    return $layout->nest('page_content', 'adddeveloper')->with('page_title', lang('developer.add.title'));
}));

Route::get('/addgame', array('as' => 'get_addgame', function() use ($layout)
{
    return $layout->nest('page_content', 'addgame')->with('page_title', lang('game.add.title'));
}));*/



//----------------------------------------------------------------------------------
// SEARCH PROFILE
//----------------------------------------------------------------------------------

Route::get('/search/(:num?)', array('as' => 'get_search', function($search_id = null) use ($layout)
{
    if ( ! is_null($search_id)) {
        $search = Search::get($search_id);
        if ( ! is_null($search)) {
            $profiles = Search::get_profiles($search->data);
            return $layout->nest('page_content', 'search', array('profiles'=>$profiles, 'search_data'=>$search->array_data));
        }
        else {
            HTML::set_error(lang('search.msg.id_not_found', array('id'=>$search_id)));
            return Redirect::to_route('get_search');
        }
    }

    return $layout->nest('page_content', 'search');
}));


// for post search, see below in CSRF group



//----------------------------------------------------------------------------------
// DISPLAY PROFILE
//----------------------------------------------------------------------------------

Route::get('/developer/(:any)', array('as' => 'get_developer', function($name = null) use ($layout)
{
    if (is_null($name)) return Redirect::to_route('get_search');
    
    if (is_numeric($name)) {
        $profile = Dev::find($name);
        return Redirect::to_route('get_developer', array(name_to_url($profile->name)));
    }
    else $profile = Dev::where_name(url_to_name($name))->first();

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
    if ($profile->privacy == 'public' || is_admin() ||
        is_trusted() && 
            ($profile->user_id == user_id() ||
            in_array($profile->privacy, Config::get('vgc.review.types')))
    ) {
        return $layout->nest('page_content', 'developerdisplay', array('profile' => $profile));
    } else {
        HTML::set_error(lang('errors.access_not_allowed', array('page' => 'Developer profile '.$name)));
        return Redirect::to_route('get_search');
    }
}));



Route::get('/game/(:any)', array('as' => 'get_game', function($name = null) use ($layout)
{
    if (is_null($name)) return Redirect::to_route('get_search');
    
    if (is_numeric($name)) {
        $profile = Game::find($name);
        return Redirect::to_route('get_game', array(name_to_url($profile->name)));
    }
    else $profile = Game::where_name(url_to_name($name))->first();

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
    if ($profile->privacy == 'public' || is_admin() ||
        is_trusted() && 
            ($profile->user_id == user_id() ||
            in_array($profile->privacy, Config::get('vgc.review.types')))
    ) {
        return $layout->nest('page_content', 'gamedisplay', array('profile' => $profile));
    } else {
        HTML::set_error(lang('errors.access_not_allowed', array('page' => 'Game profile '.$name)));
        return Redirect::to_route('get_search');
    }
}));



//----------------------------------------------------------------------------------
// SET LANGUAGE
//----------------------------------------------------------------------------------

Route::get('setlanguage/(:any?)', array('as' => 'get_set_language', 'do' => function($language = null) use ($layout)
{
    if (is_null($language)) {
        $language = Session::get('language', Config::get('language', 'en'));
    }

    Session::put('language', substr($language, 0, 2));
    return Redirect::back();
}));



//----------------------------------------------------------------------------------
// CROSS PROMOTION
//----------------------------------------------------------------------------------

Route::get('crosspromotion/(:num)/(:any)', array('as' => 'get_crosspromotion', 'do' => function($game_id, $user_secret_key)
{
    $game = Game::find($game_id);

    if (is_null($game)) {
        return Response::json(array('No game with id ['.$game_id.'] has been found'));
    }

    if ($game->user->secret_key != $user_secret_key) {
        return Response::json(array('The secret key ['.$user_secret_key.'] does not match the secret key of user the game is linked to'));
    }

    $promoted_games = $game->promoted_games;

    for ($i = 0; $i < count($promoted_games); $i++) {
        $promoted_games[$i] = Game::find($promoted_games[$i])->to_crosspromotion_array();
    }

    return Response::json($promoted_games);
}));



//----------------------------------------------------------------------------------
// ADVERTISING
//----------------------------------------------------------------------------------

Route::get('advertising/', array('as' => 'get_advertising', 'do' => function()
{
    return "advertising";
}));



//----------------------------------------------------------------------------------
//  ADMIN CONTROLLER ROUTES
//----------------------------------------------------------------------------------

// controller
// Route::controller('controlerName');
// custom route to a controller method :
// Route::get('customroute', 'controlerName@methodName');
// nammed route with controller
// Route::get('the route', array('as' => 'thenameoftheroute', 'uses' => 'controlerName@methodName'));



// must be guest
Route::group(array('before' => 'is_guest'), function()
{
    Route::get('login', array('as' => 'get_login', 'uses' => 'admin@login'));
    Route::get('register', array('as' => 'get_register', 'uses' => 'admin@register'));
    Route::get('register/confirmation/(:num)/(:any)', array('as' => 'get_register_confirmation', 'uses' => 'admin@register_confirmation'));
    Route::get('lostpassword/(:num)/(:any)', array('as' => 'get_lostpassword_confirmation', 'uses' => 'admin@lostpassword_confirmation'));
});


// must be logged in
Route::group(array('before' => 'auth'), function()
{
    Route::get('admin', array('as' => 'get_admin_home', 'uses' => 'admin@index'));

    Route::get('admin/logout', array('as' => 'get_logout', 'uses' => 'admin@logout'));

    Route::get('admin/user/edit/(:num?)', array('as' => 'get_edituser', 'uses' => 'admin@edituser'));
    
    Route::get('admin/developer/add', array('as' => 'get_adddeveloper', 'uses' => 'admin@adddeveloper'));
    Route::get('admin/developer/edit/(:num?)', array('as' => 'get_editdeveloper', 'uses' => 'admin@editdeveloper'));
    // I could also use
    // Route::get('admin/(add|edit)developer', 'admin@(:1)developer');

    Route::get('admin/game/add', array('as' => 'get_addgame', 'uses' => 'admin@addgame'));
    Route::get('admin/game/edit/(:num?)', array('as' => 'get_editgame', 'uses' => 'admin@editgame'));

    Route::get('admin/reviews/(:any?)', array('as' => 'get_reviews', 'uses' => 'admin@reviews'));    

    Route::get('admin/reports/(:any?)', array('as' => 'get_reports', 'uses' => 'admin@reports'));
});


// must be logged in + admin
Route::group(array('before' => 'auth|admin'), function()
{
    Route::get('admin/user/add', array('as' => 'get_adduser', 'uses' => 'admin@adduser'));
    
});


// must be logged in + admin + logit post
Route::group(array('before' => 'auth|admin|csrf'), function()
{
    Route::post('admin/adduser', array('as' => 'post_adduser', 'uses' => 'admin@adduser'));
});


// must be logged in + legit post
Route::group(array('before' => 'auth|csrf'), function()
{
    Route::post('admin/edituser', array('as' => 'post_edituser', 'uses' => 'admin@edituser'));

    Route::post('admin/selecteditdeveloper', array('as' => 'post_selecteditdeveloper', 'uses' => 'admin@selecteditdeveloper'));
    Route::post('admin/developer/add', array('as' => 'post_adddeveloper', 'uses' => 'admin@adddeveloper'));
    Route::post('admin/editdeveloper', array('as' => 'post_editdeveloper', 'uses' => 'admin@editdeveloper'));

    Route::post('admin/selecteditgame', array('as' => 'post_selecteditgame', 'uses' => 'admin@selecteditgame'));
    Route::post('admin/game/add', array('as' => 'post_addgame', 'uses' => 'admin@addgame'));
    Route::post('admin/editgame', array('as' => 'post_editgame', 'uses' => 'admin@editgame'));

    Route::post('admin/reviews', array('as' => 'post_review', 'uses' => 'admin@reviews'));
});


// must be guest and legit post
Route::group(array('before' => 'is_guest|csrf'), function()
{
    Route::post('register', array('as' => 'post_register', 'uses' => 'admin@register'));
    Route::post('login', array('as' => 'post_login', 'uses' => 'admin@login'));
    Route::post('lostpassword', array('as' => 'post_lostpassword', 'uses' => 'admin@lostpassword'));
});


// must be legit post
Route::group(array('before' => 'csrf'), function()
{
    Route::post('admin/reports', array('as' => 'post_reports', 'uses' => 'admin@reports'));

    // SEARCH
    Route::post('/search', array('as' => 'post_search', 'before' => 'csrf', function()
    {
        $input = Input::all();
        $search = Search::create($input);
        return Redirect::to_route('get_search', array($search->id));
    }));
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


    // checking success of reviews
    // check number of approvals
    // will also be called by a cron tab job
    $last_check_date = new DateTime(DBConfig::get('review_check_date'));
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
