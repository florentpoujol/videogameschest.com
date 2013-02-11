<?php

class Feed_Controller extends Base_Controller
{
    public $layout = null; // prevent the main layout to be automatically included

    //----------------------------------------------------------------------------------

    public function getFeed()
    {
        $feed = Feed::make();

        $feed
        //->logo(asset('logo.png'))
        //->icon(URL::home().'favicon.ico')
        ->webmaster('VideoGamesChest contact@videogameschest.com '.URL::base())
        //->author   ('VideoGamesChest contact@videogameschest.com '.URL::base())
        //->rating('SFW')
        //->pubdate(time())
        //->ttl(60)
        
        
        ->copyright('(c) '.date('Y').' VideoGamesChest.com')
        
        //->category('PHP')
        //->language(Session::get('language', Config::get('language', 'en')))
        ->baseurl(URL::home());

        return $feed;
    }

    public function publish($feed_type, $feed)
    {
        if ($feed_type == 'atom') $feed->Atom();
        else $feed->Rss20();
    }

    //----------------------------------------------------------------------------------


    public function get_reports_feed($report_type, $user_id, $url_key)
    {
        $feed_type = 'rss';
        $user = User::where_id($user_id)->where_url_key($url_key)->first();
        $reports = array();
        
        if ( ! is_null($user)) {
            if ($user->type == 'admin') {
                if ( ! is_null($report_type)) {
                    $reports = Report::where_type($report_type)->order_by('created_at', 'desc')->get();
                } else {
                    $reports = Report::order_by('created_at', 'desc')->get();
                }
            } else {
                $reports = $user->reports('developer');
            }

            $feed_data = array( 
                'channel' => array(
                    'title' => 'Report feed for user '.$user->username,
                    'link' => URL::base(),
                    'permalink' => route('get_reports_feed', array($feed_type, $report_type, $user_id, $url_key)),
                    // 'lastBuildDate' => $last_pub_date->format('r'),
                ),

                'items' => array(),
            );

            foreach ($reports as $report) {
                $feed_data['items'][] = array(
                    'title' => 'New report on '.$report->profile->class_name.' profile "'.$report->profile->name.'"',
                    'pubDate' => $report->created_at,
                    // 'link' => route('get_reports'),
                    'guid isPermalink="false"' => 'report id '.$report->id,
                    'description' => 
                    $report->message.' <br>
                    <a href="'.route('get_reports').'">See all your reports on VideoGamesChest.com</a> <br>',
                );
            }

            return Response::view('rss', array('feed_data' => $feed_data));
        } else {
           return 'Unknow user or user id and url key do not match.';
        }
    }


    public function get_search_feed($search_id)
    {
        $feed_type = 'rss';
        $search = Search::find($search_id);
        
        if ( ! is_null($search)) {
            $profiles = Search::make($search->data)
            ->where_privacy('public')
            ->order_by('created_at', 'desc')
            ->take(20)
            ->get();

            $feed = $this->getFeed()
                ->title('New profiles feed for search ID '.$search_id)
                ->permalink(route('get_search_feed', array($search_id, $feed_type)));

            foreach ($profiles as $profile) {
                $class_name = $profile->class_name;
                $profile_name = $profile->name;
                $profile_link = route('get_'.$class_name, array(name_to_url($profile_name)));

                $feed->entry()
                    ->published($profile->created_at)
                    ->updated($profile->updated_at)
                    ->permalink($profile_link)

                    ->title('New '.$class_name.' profile : '.$profile_name)

                    ->content()
                        ->add('text', 
                            'A new '.$class_name.' profile has been published on VideoGamesChest.com.
                            You can see it at this url : 
                            '.$profile_link
                        )->up()

                    ->content()    
                        ->add('html', 
                            'A new '.$class_name.' profile has been published on VideoGamesChest.com. <br>
                            You can see it at this url : <a href="'.$profile_link.'" title="Go to '.$profile_name.'\'s profile">'.$profile_name.'</a>'
                        )->up()
                ;
            }

            $this->publish($feed_type, $feed);
        } else {
           return 'Unknow search id.';
        }
    }


    public function get_reviews_feed($review_type, $user_id, $url_key)
    {
        $feed_type = 'rss';
        $review_type = 'publishing';

        $user = User::where_id($user_id)->where_url_key($url_key)->first();
        
        if ( ! is_null($user)) {
            if ($user->type == 'admin') {
                $profiles = Developer::where_privacy($review_type)->get();
                $profiles = array_merge($profiles, Game::where_privacy($review_type)->get());

                $feed = $this->getFeed()
                    ->title('New profiles in '.$review_type.' review feed')
                    ->permalink(route('get_reviews_feed', array($review_type, $user_id, $url_key)));

                foreach ($profiles as $profile) {
                    $class_name = $profile->class_name;
                    $profile_name = $profile->name;
                    $profile_link = route('get_'.$class_name, array(name_to_url($profile_name)));

                    $feed->entry()
                        ->published($profile->created_at)
                        ->updated($profile->updated_at)
                        ->permalink($profile_link)

                        ->title('New '.$class_name.' profile in '.$review_type.' review')

                        ->content()->add('text', 
                                'New '.$class_name.' profile in '.$review_type.' review. '.$profile_link
                            )->up()

                        ->content()->add('html', 
                                'New '.$class_name.' profile in '.$review_type.' review. <a href="'.$profile_link.'" title="Go to '.$profile_name.'\'s profile">'.$profile_name.'</a>'
                            )->up()
                    ;
                }

                $this->publish($feed_type, $feed);
            } else {
                return 'Must be admin.';
            }
        } else {
           return 'Unknow user or user id and url key do not match.';
        }
    }


    public function get_blog_feed()
    {
        $posts = BlogPost::order_by('created_at', 'desc')->get();

        $feed = $this->getFeed()
            ->title('VideoGamesChest blog feed')
            ->permalink(route('get_blog_feed'));

        if ( ! is_null($posts)) {
            foreach ($posts as $post) {
                $feed->entry()
                    ->published($post->created_at)
                    ->updated($post->updated_at)
                    ->permalink($post->url)

                    ->title($post->title)

                    //->content()->add('text', e($post->content))->up()
                    ->content()->add('html', $post->content)->up()
                ;
            }
        } 

        $this->publish('rss', $feed);
    }
}
