<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/


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


$layout = View::make('layouts.main');


//----------------------------------------------------------------------------------
// NO FILTERS
//----------------------------------------------------------------------------------

    // HOME

    Route::get('/', array('as' => 'get_home_page', function() use ($layout)
    {
        // return View::make('layouts.main2');
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
    Route::get('reports/feed/{url_key}', array('as' => 'get_reports_feed', 'uses' => 'FeedController@getReportsFeed'))
    ->where('url_key', '[A-Za-z_]+');
        

    //VIEW PROFILE
    Route::get('profiles/{id}', array('as' => 'get_profile_view', 'uses' => 'AdminController@getProfileView'))
    ->where('id', '[0-9]+');
    

    // REPORT FORM
    // for use by the colorbox
    /*Route::get('postreport/(:num)', array('as' => 'get_report_form', function($profile_id)
    {
        return View::make('forms/postreport', array('profile' => Game::find($profile_id)));
    }));*/



//----------------------------------------------------------------------------------
//  MUST BE LEGIT POST
//----------------------------------------------------------------------------------

    Route::group(array('before' => 'csrf'), function()
    {
        Route::post('reports/create', array('as' => 'post_reports_create', 'uses' => 'AdminController@postReportsCreate'));

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
            return Redirect::route('get_'.$action.'_page', array($search->id));
        }));


        Route::post('browse', array('as' => 'post_browse', function()
        {
            return Redirect::route('get_browse_page', array(Input::get('search_id')));
        }));
        
        // Suggest
        Route::post('suggestion/create', array('as' => 'post_suggestion_create', 'uses' => 'AdminController@postSuggestionCreate'));
    });



//----------------------------------------------------------------------------------
//  MUST BE GUEST
//----------------------------------------------------------------------------------

    Route::group(array('before' => 'guest'), function()
    {
        Route::get('login', array('as' => 'get_login_page', 'uses' => 'AdminController@getLoginPage'));
        Route::get('user/lostpassword/{user_id}/{url_key}', array('as' => 'get_lostpassword_confirmation', 'uses' => 'AdminController@getLostpasswordConfirmation'))
        ->where(array('user_id' => '[0-9]+', 'url_key' => '[A-Za-z_]+'));
    });



//----------------------------------------------------------------------------------
//  MUST BE GUEST AND LEGIT POST
//----------------------------------------------------------------------------------

    Route::group(array('before' => 'guest|csrf'), function()
    {
        // Route::post('register', array('as' => 'post_register', 'uses' => 'AdminController@register'));
        Route::post('login', array('as' => 'post_login', 'uses' => 'AdminController@postLogin'));
        Route::post('lostpassword', array('as' => 'post_lostpassword', 'uses' => 'AdminController@postLostpassword'));
    });



//----------------------------------------------------------------------------------
//  MUST BE LOGGED IN
//----------------------------------------------------------------------------------

    Route::group(array('before' => 'auth'), function() use ($layout)
    {
        Route::get('logout', array('as' => 'get_logout', 'uses' => 'AdminController@getLogout'));

        Route::get('user', function()
        {
            return Redirect::route('get_user_update'); // no regular profile user
        });
        Route::get('user/create', array('as' => 'get_user_create', 'uses' => 'AdminController@getUserCreate'));
        Route::get('user/update/{user_id?}', array('as' => 'get_user_update', 'uses' => 'AdminController@getUserUpdate'))
        ->where(array('user_id' => '[0-9]+'));
        
        Route::get('profiles/create', array('as' => 'get_profile_create', 'uses' => 'AdminController@getProfileCreate'));
        Route::get('profiles/update/{id?}', array('as' => 'get_profile_update', 'uses' => 'AdminController@getProfileUpdate'))
        ->where(array('id' => '[0-9]+'));

        Route::get('privateprofiles', array('as' => 'get_private_profiles', function() use ($layout) {
            return $layout->nest('page_content', 'privateprofiles');
        }));
        

        Route::get('reports', array('as' => 'get_reports', 'uses' => 'AdminController@getReports'));

        // CRAWLER
        Route::get('suggestions', array('as' => 'get_suggestions_page', 'uses' => 'AdminController@getSuggestions'));
        Route::get('suggestions/readfeed', array('as' => 'get_read_suggestions_feeds', 'uses' => 'AdminController@getReadSuggestionsFeeds'));


        Route::get('test/{data?}', function($data = null) use ($layout)
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

        
    });



//----------------------------------------------------------------------------------
//  MUST BE ADMIN AND LEGIT POST
//----------------------------------------------------------------------------------

    Route::group(array('before' => 'auth|csrf'), function()
    {
        Route::post('user/create', array('as' => 'post_user_create', 'uses' => 'AdminController@postUserCreate'));
        Route::post('user/update', array('as' => 'post_user_update', 'uses' => 'AdminController@postUserUpdate'));
        Route::post('user/updatepassword', array('as' => 'post_password_update', 'uses' => 'AdminController@postPasswordUpdate'));
        
        Route::post('review', array('as' => 'post_review', 'uses' => 'AdminController@review'));

        Route::post('profiles/select', array('as' => 'post_profile_select', 'uses' => 'AdminController@postProfileSelect'));
        Route::post('profiles/create', array('as' => 'post_profile_create', 'uses' => 'AdminController@postProfileCreate'));
        Route::post('profiles/update', array('as' => 'post_profile_update', 'uses' => 'AdminController@postProfileUpdate'));
       
        Route::post('reports/update', array('as' => 'post_reports_update', 'uses' => 'AdminController@postReportsUpdate'));

        // CRAWLER
        Route::post('suggestionfeed/update', array('as' => 'post_suggestion_feeds_update', 'uses' => 'AdminController@postSuggestionFeedsUpdate'));

        Route::post('suggestions/update', array('as' => 'post_suggestions_update', 'uses' => 'AdminController@postSuggestionsUpdate'));
    });




Event::listen('laravel.query', function($sql, $bindings, $time) {
    //var_dump($sql);
});




Event::listen('404', function()
{
    HTML::set_error(lang('common.msg.page_not_found'));
    // Log::write('error 404', 'Page not found : '.URL::current());
    return Redirect::route('get_home_page');
});

Event::listen('500', function()
{
    // Log::write('error 500', 'Error 500 have been caught.');
    return Response::error('500');
});

