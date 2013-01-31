<?php

class Discover_Controller extends Base_Controller 
{
    public function get_index()
    {
        // return Redirect::to_route('get_discover_feed_page');
        $this->layout->nest('page_content', 'discover');
    }

    //----------------------------------------------------------------------------------
    // FEED

    public function get_FeedPage()
    {
        $this->layout->nest('page_content', 'discover', array('current_tab' => '#feed-pane'));
    }

    /**
     * Handle form form discover/feed page, for creation and update of feed rows
     */
    public function post_CreateFeed()
    {
        $input = Input::all();

        $rules = array(
            'type' => 'required|in:rss,atom',
            'frequency' => 'required|integer|min:12|max:744',
            'profile_count' => 'required|integer|min:1|max:500',
            'search_id' => 'required|integer|min:1',
        );
        
        $validation = Validator::make($input, $rules);

        if ($validation->passes()) {
            $feed = PromotionFeed::make($input);

            return Redirect::back()->with_input();
        } else {
            return Redirect::back()->with_input()->with_errors($validation);
        }
    }

    /**
     * When a feed url is checked for new content
     * route get_discover_feed_data
     */
    public function get_FeedData($feed_id)
    {
        $feed = PromotionFeed::find($feed_id);

        if ( ! is_null($feed)) {
            /*$last_check_date = new DateTime(DBConfig::get('review_check_date'));
            $interval = new DateInterval('PT'. Config::get('vgc.review.check_interval') .'M');
            $last_check_date->add($interval);
            $now = new DateTime();
            
            if ($last_check_date < $now) {*/
                $profiles = Search::get_profiles($feed->search_id);

                if ($feed->use_blacklist == 1) {
                    $profiles = ProcessBlacklist($profiles, $feed->user_id);
                }

                // contant of the rss flux
                $feed_data = array( 
                    'channel' => array(
                        'title' => 'Promotion feed ID '.$feed->id,
                        'description' => 'description',
                        'permalink' => route('get_discover_feed_data', array($feed->id)),
                        'pubDate' => $feed->created_at,
                        'lastBuildDate' => $feed->updated_at,
                    ),

                    'items' => array(
                        'title' => 'Promotion feed entry on '.date_create()
                    ),
                );

                foreach ($profiles as $profile) {
                    $class_name = $profile->class_name;
                    $profile_name = $profile->name;
                    $profile_link = route('get_'.$class_name, array(name_to_url($profile_name)));


                }

                View::make('rss', array('feed_data' => $feed_data));
            //}
        } else {
            return 'Unknow promotion feed id';
        }
    }


    //----------------------------------------------------------------------------------
    // NEWSLETTER

    public function get_EmailPage($email_id = null, $url_key = null)
    {    
        if ( ! is_null($email_id) && is_logged_in()) {
            return Redirect::to_route('get_discover_email_page');
        }

        $this->layout->nest('page_content', 'discover',  array(
            'current_tab' => '#email-pane',
            'email_id' => $email_id,
            'url_key' => $url_key
        ));
    }

    public function post_CreateEmail()
    {
        $input = Input::all();

        $rules = array(
            'email' => 'required|email',
            'frequency' => 'required|integer|min:12|max:744',
            'profile_count' => 'required|integer|min:1|max:500',
            'search_id' => 'required|integer|min:1',
        );

        if (is_logged_in()) $rules['email'] = 'email'; // disabled email field prevent the field to be sent

        $validation = Validator::make($input, $rules);

        if ($validation->passes()) {
            $newsletter = PromotionEmail::create($input);
            
            if (is_guest()) {
                return Redirect::to_route('get_discover_update_email_page', array($newsletter->id, $newsletter->url_key));
            } else {
                return Redirect::back();
            }
        } else {
            return Redirect::back()->with_input()->with_errors($validation);
        }
    }

    public function post_UpdateEmail()
    {
        $input = Input::all();

        // unsubscribe
        if (isset($input['unsubscribe'])) {
            if (PromotionEmail::unsubscribe($input)) {
                return Redirect::to_route('get_discover_email_page');
            } else {     
                if (is_guest()) {
                    return Redirect::to_route('get_discover_update_email_page', array($input['newsletter_id'], $input['newsletter_url_key']));
                } else {
                    return Redirect::back();
                }
            }
        }

        // update
        $rules = array(
            'email' => 'required|email',
            'frequency' => 'required|integer|min:12|max:744',
            'profile_count' => 'required|integer|min:1|max:500',
            'search_id' => 'required|integer|min:1',
        );

        if (is_logged_in()) $rules['email'] =  'email'; // disabled email field prevent the field to be sent


        $validation = Validator::make($input, $rules);

        if ($validation->passes() && PromotionEmail::update($input, null)) {
            if (is_guest()) {
                return Redirect::to_route('get_discover_update_email_page', array($email->id, $email->url_key));
            } else {
                return Redirect::back();
            }
        } else {            
            return Redirect::back()->with_input()->with_errors($validation);
        }
    }

} // end of Discover controller class
