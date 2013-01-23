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
        ->author   ('VideoGamesChest contact@videogameschest.com '.URL::base())
        ->rating('SFW')
        ->pubdate(time())
        ->ttl(60)
        
        
        ->copyright('(c) '.date('Y').' VideoGamesChest.com')
        
        //->category('PHP')
        ->language(Session::get('language', Config::get('language', 'en')))
        ->baseurl(URL::home());

        return $feed;
    }

    public function publish($feed_type, $feed)
    {
        if ($feed_type == 'atom') $feed->Atom();
        else $feed->Rss20();
    }

    //----------------------------------------------------------------------------------


    public function get_reports_feed($feed_type, $report_type, $user_id, $url_key)
    {
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

           $feed = $this->getFeed();

           $feed
           ->title('Report feed for user '.$user->username)
           ->permalink(route('get_reports_feed', array($feed_type, $report_type, $user_id, $url_key)));

           foreach ($reports as $report) {
                $feed->entry()
                
                ->published($report->created_at)
                //->updated($report->updated_at)
                ->permalink($report->id)
                
                ->title('New report on '.$report->profile->class_name.' profile '.$report->profile->name)
                
                ->content()
                    ->add('text', '"'.$report->message.'"
                    See all your reports on VideoGamesChest.com :
                    '.route('get_reports'))->up()
                    
                    ->add('html', 
                        $report->message.' <br> <br>
                        <a href="'.route('get_reports').'">See all your reports on VideoGamesChest.com</a> <br>'
                        
                    )->up()

                ;
            }

            $this->publish($feed_type, $feed);
        } else {
           return 'Unknow user or user id and url key do not match.';
        }
    }


    public function get_search_feed($feed_type, $search_id)
    {
        $search = Search::find($search_id);
        
        if ( ! is_null($search)) {
            $profiles = Search::get_profiles($search->data);

            $feed = $this->getFeed()
                ->title('New profiles feed for search ID '.$search_id)
                ->permalink(route('get_search_feed', array($feed_type, $search_id)));

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

                    ->content()    ->add('html', 
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


    public function get_reviews_feed($feed_type, $review_type, $user_id, $url_key)
    {
        $review_type = 'publishing';

        $user = User::where_id($user_id)->where_url_key($url_key)->first();
        
        if ( ! is_null($user)) {
            if ($user->type == 'admin') {
                $profiles = Developer::where_privacy($review_type)->get();
                $profiles = array_merge($profiles, Game::where_privacy($review_type)->get());

                $feed = $this->getFeed()
                    ->title('New profiles in '.$review_type.' feed')
                    ->permalink(route('get_reviews_feed', array($feed_type, $review_type, $user_id, $url_key)));

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

}
