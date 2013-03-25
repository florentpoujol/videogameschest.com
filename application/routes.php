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
    Route::get('search/(:any?)', array('as' => 'get_search_page', function($search_id = null) use ($layout)
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
    }));

    Route::get('search/feed/(:num)', array('as' => 'get_search_feed', 'uses' => 'feed@search_feed'));

    // BROWSE
    Route::get('browse/(:any?)', array('as' => 'get_browse_page', function($search_id = null) use ($layout)
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
    }));


    // DISCOVER
    Route::get('discover/(:num?)', array('as' => 'get_discover_page', 'uses' => 'discover@index'));
    Route::get('discover/feed', array('as' => 'get_discover_feed_page', 'uses' => 'discover@feed_page'));
    Route::get('discover/feed/(:num)', array('as' => 'get_discover_feed_data', 'uses' => 'discover@feed_data'));
    Route::get('discover/newsletter', array('as' => 'get_discover_newsletter_page', 'uses' => 'discover@newsletter_page'));
    Route::get('discover/newsletter/(:num)/(:any)', array('as' => 'get_discover_newsletter_update', 'uses' => 'discover@newsletter_page'));


    // SUGGEST
    Route::get('suggest', array('as' => 'get_suggest_form', function() use ($layout)
    {
        return View::make('forms/suggest');
    }));


    // RSS FEEDS
    Route::get('reports/feed/(:num)/(:any)', array('as' => 'get_reports_feed', 'uses' => 'feed@reports_feed'));
        

    //VIEW PROFILE
    // see after must be logged in
    
    // REPORT FORM
    // for use by the colorbox
    Route::get('postreport/(:num)', array('as' => 'get_report_form', function($profile_id)
    {
        return View::make('forms/postreport', array('profile' => Game::find($profile_id)));
    }));


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

        
        Route::post('post_category_name', array('as' => 'post_category_name', function()
        {
            $input = Input::all();
            $rules = array(
                'category_name' => 'required|min:5|alpha_dash',
            );
            $validation = Validator::make($input, $rules);

            if ($validation->passes()) {
                if (is_guest()) {
                    $names = json_decode(Cookie::get('vgc_category_names', '{}'), true);
                    $names[$input['search_id']] = $input['category_name'];
                    Cookie::put('vgc_category_names', json_encode($names), 999999); // 999999 min = 694.4 days
                } else {
                    $names = user()->category_names;
                    //var_dump($names);
                    if ($names != '') $names = json_decode($names, true);
                    else $names = array();
                    //dd($names);
                    $names[$input['search_id']] = $input['category_name'];
                    user()->category_names = json_encode($names);
                    user()->save();
                }

                HTML::set_success(lang('vgc.search.msg.update_category_name_success', array('category_id' => $input['search_id'])));
                return Redirect::back();
            }
            
            return Redirect::back()->with_input()->with_errors($validation);
        }));


        Route::post('browse', array('as' => 'post_browse', function()
        {
            return Redirect::to_route('get_browse_page', array(Input::get('search_id')));
        }));

        // user subscribe to a promotion feed or email
        Route::post('discover/feed/create', array('as' => 'post_discover_feed_create', 'uses' => 'discover@feed_create'));
        Route::post('discover/newsletter/create', array('as' => 'post_discover_newsletter_create', 'uses' => 'discover@newsletter_create'));
        Route::post('discover/newsletter/update', array('as' => 'post_discover_newsletter_update', 'uses' => 'discover@newsletter_update'));

        // Suggest
        Route::post('suggest', array('as' => 'post_suggest', function()
        {
            
            $validation = Validator::make(Input::all(), array('url' => 'required|url'));

            if ($validation->passes()) {
                $input = array(
                    'url' => Input::get('url'),
                    'source' => 'user',
                );
                
                SuggestedProfile::create($input);
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
//  MUST BE LOGGED IN + view profile
//----------------------------------------------------------------------------------

    Route::group(array('before' => 'auth'), function()
    {
        Route::get('user', function()
        {
            return Redirect::to_route('get_user_update');
        });
        Route::get('user/update/(:num?)', array('as' => 'get_user_update', 'uses' => 'admin@user_update'));

        Route::get('logout', array('as' => 'get_logout', 'uses' => 'admin@logout'));
        
        Route::get(get_profile_types(true).'/create', array('as' => 'get_profile_create', 'uses' => 'admin@profile_create'));
        Route::get(get_profile_types(true).'/update/(:num?)', array('as' => 'get_profile_update', 'uses' => 'admin@profile_update'));
        Route::get(get_profile_types(true).'/preview/(:num)', array('as' => 'get_profile_preview', 'uses' => 'admin@profile_preview'));

        Route::get('reports', array('as' => 'get_reports', 'uses' => 'admin@reports'));
    });

    //VIEW PROFILE
    Route::get(get_profile_types(true).'/(:any)', array('as' => 'get_profile_view', 'uses' => 'admin@profile_view'));


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
        Route::get('review', array('as' => 'get_review', 'uses' => 'admin@review'));
        Route::get('review/feed/(:num)/(:any)', array('as' => 'get_review_feed', 'uses' => 'feed@review_feed'));

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


        /*Route::get('crawler/auto', array('as' => 'get_crawler_auto', function() use ($layout)
        {
            // get the first (oldest suggested or just oldest created) link
            $profile = SuggestedProfile::where_profile_id(0)->where_suggested(1)->order_by('created_at', 'asc')->first();

            if ($profile === null) $profile = SuggestedProfile::where_profile_id(0)->order_by('created_at', 'asc')->first();

            if ($profile === null) {
                HTML::set_info("No more profile to crawl.");
                return Redirect::to_route('get_crawler_page');
            }

            // crawl it
            $crawler = Crawler::crawl($profile);

            return $layout->nest('page_content', 'crawler', array('auto' => true));
        }));*/


        // RSS reader
        Route::get('crawler/readrss', array('as' => 'get_crawler_read_feed_urls', function() use ($layout)
        {
            $feed_urls = json_decode(DBConfig::get('crawler_feed_urls', '[]'), true);

            if (empty($feed_urls)) {
                HTML::set_error("No feed urls where found in the database.");
            }

            foreach ($feed_urls as $url) {
                $feed = RSSReader::read($url);
                if (empty($feed['items'])) {
                    HTML::set_error("No items in the feed url '".$url."'.");
                    continue;
                }

                $profiles_added = 0;

                foreach ($feed['items'] as $item) {
                    $item_url = $item['link'];

                    if (SuggestedProfile::where_url($item_url)->first() === null) {
                        if (strpos($item_url, 'indiedb.com') && strpos($item_url, '/news/')) {
                            // need to get the url of the game instead of the news
                            $item_url = Crawler::get_indiedb_profile_url_from_news($item_url);
                        }

                        $profile = new SuggestedProfile;
                        $profile->url = $item_url;
                        $profile->source = 'rss';
                        $profile->statut = 'waiting';
                        $profile->save();
                        $profiles_added++;
                    }
                }

                HTML::set_success("$profiles_added urls added from the feed url '".$url."'.");
            }

            return Redirect::to_route('get_crawler_page');
        }));



    });



//----------------------------------------------------------------------------------
//  MUST BE ADMIN AND LEGIT POST
//----------------------------------------------------------------------------------

    Route::group(array('before' => 'auth|is_admin|csrf'), function()
    {
        Route::post('user/create', array('as' => 'post_user_create', 'uses' => 'admin@user_create'));
        Route::post('review', array('as' => 'post_review', 'uses' => 'admin@review'));

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

        // post new rss url
        Route::post('crawler_add_feed_url', array('as' => 'post_crawler_add_feed_url', function()
        {
            $db_entry = DBConfig::where('_key', '=', 'crawler_feed_urls')->first();
            
            $feed_urls = json_decode($db_entry->value, true);
            $feed_urls[] = Input::get('feed_url');
            
            $db_entry->value = json_encode($feed_urls);
            $db_entry->save();
            
            return Redirect::to_route('get_crawler_page');
        }));

        Route::post('crawler_perform_actions', array('as' => 'post_crawler_perform_actions', function()
        {
            $profiles = Input::get('profiles');

            foreach ($profiles as $id => $profile) {
                if ($profile['action'] == 'add') {
                    $profile_data = Crawler::crawl_game($profile['url']);
                    
                    $game = Game::create($profile_data);

                    $suggested_profile = SuggestedProfile::find($id);
                    $suggested_profile->statut = 'added';
                    $suggested_profile->profile_type = 'game';
                    $suggested_profile->profile_id = $game->id;
                    $suggested_profile->save();
                } elseif ($profile['action']  == 'discard') {
                    SuggestedProfile::update($id, array('statut' => 'discarded'));
                }   
            }
            
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
