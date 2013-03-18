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


    // SEARCH PROFILE
    Route::get('search/(:num?)', array('as' => 'get_search_page', function($search_id = null) use ($layout)
    {
        if ($search_id !== null) {
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
    }));

    Route::get('search/feed/(:num)', array('as' => 'get_search_feed', 'uses' => 'feed@search_feed'));

    // BROWSE
    Route::get('browse/(:num?)', array('as' => 'get_browse_page', function($search_id = null) use ($layout)
    {
        if ($search_id !== null) {
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
    }));


    // DISCOVER
    Route::get('discover/(:num?)', array('as' => 'get_discover_page', 'uses' => 'discover@index'));
    Route::get('discover/feed', array('as' => 'get_discover_feed_page', 'uses' => 'discover@feed_page'));
    Route::get('discover/feed/(:num)', array('as' => 'get_discover_feed_data', 'uses' => 'discover@feed_data'));
    Route::get('discover/newsletter', array('as' => 'get_discover_newsletter_page', 'uses' => 'discover@newsletter_page'));
    Route::get('discover/newsletter/(:num)/(:any)', array('as' => 'get_discover_newsletter_update', 'uses' => 'discover@newsletter_page'));


    // RSS FEEDS
    Route::get('reports/feed/(developer|admin)/(:num)/(:any)', array('as' => 'get_reports_feed', 'uses' => 'feed@reports_feed'));
    


    // PROMOTE
    Route::get('promote', array('as' => 'get_promote_page', 'uses' => 'promotion@index'));
    

    // BLOG
    Route::get('blog', array('as' => 'get_blog_page', function() use ($layout)
    {
        return $layout->nest('page_content', 'blog');
    }));

    Route::get('blog/post', function()
    {
        return Redirect::to_route('get_blog_page');
    });

    Route::get('blog/post/(:any)', array('as' => 'get_blog_post', function($post_title_url) use ($layout)
    {
        // $post_title_url may be the post id
        $post = null;

        if (is_numeric($post_title_url)) {
            $post = BlogPost::find($post_title_url);
        } else {
            $post = BlogPost::where_title_url($post_title_url)->first();
        }



        return $layout->nest('page_content', 'blog', array('display_posts' => array($post)));
    }));

    Route::get('blog/feed', array('as' => 'get_blog_feed', 'uses' => 'feed@blog_feed'));



//----------------------------------------------------------------------------------
//  MUST BE LEGIT POST
//----------------------------------------------------------------------------------

    Route::group(array('before' => 'csrf'), function()
    {
        Route::post('reports/create', array('as' => 'post_reports_create', 'uses' => 'admin@reports_create'));

        Route::post('search', array('as' => 'post_search', 'before' => 'csrf', function()
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

        Route::post('browse', array('as' => 'post_browse', 'before' => 'csrf', function()
        {
            return Redirect::to_route('get_browse_page', array(Input::get('search_id')));
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
//  MUST BE LOGGED IN
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
        
        Route::get(get_profile_types(true).'/create', array('as' => 'get_profile_create', 'uses' => 'admin@profile_create'));
        Route::get(get_profile_types(true).'/update/(:num?)', array('as' => 'get_profile_update', 'uses' => 'admin@profile_update'));
        Route::get(get_profile_types(true).'/preview/(:num)', array('as' => 'get_profile_preview', 'uses' => 'admin@profile_preview'));
        // I could also use
        // Route::get('(add|edit)developer', 'admin@(:1)developer');

        Route::get('reports/(:any?)', array('as' => 'get_reports', 'uses' => 'admin@reports'));
    });



//----------------------------------------------------------------------------------
//  MUST BE LOGGED IN AND LEGIT POST
//----------------------------------------------------------------------------------

    Route::group(array('before' => 'auth|csrf'), function()
    {
        Route::post('user/update', array('as' => 'post_user_update', 'uses' => 'admin@user_update'));
        Route::post('user/updatepassword', array('as' => 'post_password_update', 'uses' => 'admin@password_update'));
        Route::post('user/updateblacklist', array('as' => 'post_blacklist_update', 'uses' => 'admin@blacklist_update'));

        // get_profile_types(true) returns something like "(developer|game)"
        // it is used here to pass the profile type as argument of the method that handle the post request
        Route::post(get_profile_types(true).'/select', array('as' => 'post_profile_select', 'uses' => 'admin@profile_select'));
        Route::post(get_profile_types(true).'/create', array('as' => 'post_profile_create', 'uses' => 'admin@profile_create'));
        Route::post(get_profile_types(true).'/update', array('as' => 'post_profile_update', 'uses' => 'admin@profile_update'));
       
        Route::post('reports/update', array('as' => 'post_reports_update', 'uses' => 'admin@reports_update'));
    });



//----------------------------------------------------------------------------------
//  MUST BE ADMIN
//----------------------------------------------------------------------------------

    Route::group(array('before' => 'auth|is_admin'), function() use ($layout)
    {
        Route::get('user/create', array('as' => 'get_user_create', 'uses' => 'admin@user_create'));
        Route::get('reviews', array('as' => 'get_reviews', 'uses' => 'admin@reviews'));
        Route::get('reviews/feed/(:num)/(:any)', array('as' => 'get_reviews_feed', 'uses' => 'feed@reviews_feed'));

        Route::get('testadmincontroller/(:num)', array('as' => 'get_testadmin_controller', 'uses' => 'discover@FeedData'));
        Route::get('test/(:all?)', function($searches = null) use ($layout)
        {
            $url = 'http://www.indiedb.com/games/minecraft';
            $result = Crawler::crawl_game($url);
            dd($result);
            // echo '<pre>'.Crawler::crawl_game($url).'</pre>';;

            return $layout->nest('page_content', 'test');
        });

        Route::post('test', array('as' => 'post_test', function() use ($layout)
        {
            return $layout->nest('page_content', 'test');
        }));


        // BLOG

        Route::get('blog/post/create', array('as' => 'get_blog_post_create', function() use ($layout)
        {
            return $layout->nest('page_content', 'logged_in/createblogpost');
        }));

        Route::get('blog/post/update/(:any)', array('as' => 'get_blog_post_update', function($post_id) use ($layout)
        {
            $post = BlogPost::find($post_id);
            
            if (is_null($post)) {
                HTML::set_error("Blog post id '$post_id' not found.");
                return Redirect::to_route('get_blog_post_create');
            }

            return $layout->nest('page_content', 'logged_in/updateblogpost', array('post' => $post));
        }));


        // CRAWLER

        Route::get('crawler', array('as' => 'get_crawler_page', function() use ($layout)
        {
            return $layout->nest('page_content', 'crawler');
        }));


        Route::get('crawler/auto', array('as' => 'get_crawler_auto', function() use ($layout)
        {
            // get the first (oldest suggested or just oldest created) link
            $profile = ProfilesToCrawl::where_profile_id(0)->where_suggested(1)->order_by('created_at', 'asc')->first();

            if ($profile === null) $profile = ProfilesToCrawl::where_profile_id(0)->order_by('created_at', 'asc')->first();

            if ($profile === null) {
                HTML::set_info("No more profile to crawl.");
                return Redirect::to_route('get_crawler_page');
            }

            // crawl it
            $crawler = Crawler::crawl($profile);

            return $layout->nest('page_content', 'crawler', array('auto' => true));
        }));


        // RSS reader
        Route::get('crawler/readrss', array('as' => 'get_crawler_read_feed_links', function() use ($layout)
        {
            $feed_links = json_decode(DBConfig::get('crawler_feed_links', '[]'), true);

            if (empty($feed_links)) {
                HTML::set_error("No feed links where found in the database.");
            }

            foreach ($feed_links as $link) {
                $feed = RSSReader::read($link);
                if (empty($feed['entries'])) {
                    HTML::set_error("No entries in the feed link '".$link."'.");
                    continue;
                }

                $profiles_added = 0;

                foreach ($feed['entries'] as $entry) {
                    $entry_link = $item['link'];

                    if (ProfilesToCrawl::where_link($entry_link)->first() !== null) {
                        $profile = new ProfilesToCrawl;
                        $profile->link = $entry_link;
                        $profile->save();
                        $profiles_added++;
                    }
                }

                HTML::set_success("$profiles_added links added from the feed link '".$link."'.");
            }

            return $layout->nest('page_content', 'crawler');
        }));
    });



//----------------------------------------------------------------------------------
//  MUST BE ADMIN AND LEGIT POST
//----------------------------------------------------------------------------------

    Route::group(array('before' => 'auth|is_admin|csrf'), function()
    {
        Route::post('user/create', array('as' => 'post_user_create', 'uses' => 'admin@user_create'));
        Route::post('reviews', array('as' => 'post_reviews', 'uses' => 'admin@reviews'));

        Route::post('blog/post/create', array('as' => 'post_blog_post_create', function()
        {
            $input = clean_form_input(Input::all());
            $post = BlogPost::create($input);

            return Redirect::to_route('get_blog_post_update', array($post->id));
        }));

        Route::post('blog/post/update', array('as' => 'post_blog_post_update', function()
        {
            $input = clean_form_input(Input::all());
            BlogPost::update($input['id'], $input);

            return Redirect::to_route('get_blog_post_update', array($input['id']));
        }));


        // CRAWLER



        Route::post('crawler_add_feed_link', array('as' => 'post_crawler_add_feed_link', function()
        {
            $db_entry = DBConfig::where('_key', '=', 'crawler_feed_links')->first();
            
            $feed_links = json_decode($db_entry->value, true);
            $feed_links[] = Input::get('feed_link');
            
            $db_entry->value = json_encode($feed_links);
            $db_entry->save();
            
            return Redirect::to_route('get_crawler_page');
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
