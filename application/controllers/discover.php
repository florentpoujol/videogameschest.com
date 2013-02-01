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
     * The promotion feed is a feed with only one entry when last_pub_date + frequency < NOW
     */
    public function get_FeedData($feed_id)
    {
        $feed = PromotionFeed::find($feed_id);

        if ( ! is_null($feed)) {
            $created_at = new DateTime($feed->created_at);
            $last_pub_date = new DateTime($feed->last_pub_date);

            // default feed infos
            $feed_data = array( 
                'channel' => array(
                    'title' => 'VideoGamesChest Promotion feed ID '.$feed->id,
                    'link' => URL::base(),
                    'description' => 'description',
                    'permalink' => route('get_discover_feed_data', array($feed->id)),
                    'pubDate' => $created_at->format('r'),
                    'lastBuildDate' => $last_pub_date->format('r'),
                ),

                'items' => array(),
            );

            // check time
            $interval = new DateInterval('PT'. $feed->frequency .'H');
            $last_pub_date->add($interval);
            $now = new DateTime();
            $now_string = $now->format('r');
            
            if ($last_pub_date < $now) {
                $feed->last_pub_date = $now;
                $feed->save();

                $profiles = Search::get_profiles($feed->search_id);

                if ($feed->use_blacklist == 1) {
                    $profiles = ProcessBlacklist($profiles, $feed->user_id);
                }

                // content of the feed
                $feed_data['items'][] = array(
                    'title' => 'Promotion feed entry on '.$now->format(Config::get('vgc.date_formats.english')),
                    'pubDate' => $now_string,
                    //'link' => '',
                    'guid isPermaLink="false"' => Str::random(40),
                    'description' => 'error creating profile list',
                );

                // build te description
                $description = Response::view('partials/promotion_profile_list', array('profiles' => $profiles));

                $feed_data['items'][0]['description'] = $description;
            }

            return Response::view('rss', array('feed_data' => $feed_data));
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
