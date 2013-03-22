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


    public function get_reports_feed($user_id, $url_key)
    {
        $feed_type = 'rss';
        $user = User::where_id($user_id)->where_url_key($url_key)->first();
        $reports = array();
        
        if ( ! is_null($user)) {
            if ($user->type == 'admin') {
                $reports = Report::order_by('created_at', 'desc')->get();
            } else {
                $reports = $user->reports();
            }

            $feed = $this->getFeed()
                ->title('Report feed for user '.$user->username)
                ->permalink(route('get_reports_feed', array($user_id, $url_key)));

            foreach ($reports as $report) {
                $feed->entry()
                    ->published($report->created_at)
                    ->updated($report->updated_at)
                    ->permalink('report id '.$report->id)

                    ->title('New report on '.$report->profile->type.' profile "'.$report->profile->name.'"')

                    ->content()
                        ->add('text', 
                            $report->message.'
                            See all your reports on VideoGamesChest.com : '.route('get_reports')
                        )->up()

                    ->content()    
                        ->add('html', 
                            $report->message.' <br>
                            <a href="'.route('get_reports').'">See all your reports on VideoGamesChest.com</a> <br>'
                        )->up()
                ;
            }

            $this->publish($feed_type, $feed);
            // return Response::view('rss', array('feed_data' => $feed_data));
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
                $profile_link = route('get_pofile_view', array($profile->type, name_to_url($profile->name)));

                $feed->entry()
                    ->published($profile->created_at)
                    ->updated($profile->updated_at)
                    ->permalink($profile_link)

                    ->title('New '.$profile->type.' profile : '.$profile->name)

                    ->content()
                        ->add('text', 
                            'A new '.$profile->type.' profile has been published on VideoGamesChest.com.
                            You can see it at this url : 
                            '.$profile_link
                        )->up()

                    ->content()    
                        ->add('html', 
                            'A new '.$profile->type.' profile has been published on VideoGamesChest.com. <br>
                            You can see it at this url : <a href="'.$profile_link.'" title="Go to '.$profile->name.'\'s profile">'.$profile->name.'</a>'
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
                    $profile_link = route('get_profile_view', array($profile->type, name_to_url($profile_name)));

                    $feed->entry()
                        ->published($profile->created_at)
                        ->updated($profile->updated_at)
                        ->permalink($profile_link)

                        ->title('New '.$profile->type.' profile in '.$review_type.' review')

                        ->content()->add('text', 
                                'New '.$profile->type.' profile in '.$review_type.' review. '.$profile_link
                            )->up()

                        ->content()->add('html', 
                                'New '.$profile->type.' profile in '.$review_type.' review. <a href="'.$profile_link.'" title="Go to '.$profile->name.'\'s profile">'.$profile->name.'</a>'
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
