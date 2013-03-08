<?php

class Discover_Controller extends Base_Controller 
{
    public function get_index($search_id = null)
    {
        if ($search_id !== null) {
            $search = Search::get($search_id);
            if ($search !== null) {
                $this->layout->nest('page_content', 'discover', array('search_id' => $search_id));
                return;
            } else {
                HTML::set_error(lang('search.msg.id_not_found', array('id'=>$search_id)));
                return Redirect::to_route('get_discover_page');
            }
        }

        $this->layout->nest('page_content', 'discover');
    }

    //----------------------------------------------------------------------------------
    // FEED

    public function get_feed_page()
    {
        $this->layout->nest('page_content', 'discover', array('current_tab' => '#feed-pane'));
    }

    /**
     * Handle form form discover/feed page, for creation and update of feed rows
     */
    public function post_feed_create()
    {
        $input = Input::all();

        $rules = array(
            // 'type' => 'required|in:rss,atom',
            'frequency' => 'required|integer|min:12|max:744',
            'profile_count' => 'required|integer|min:1|max:500',
            'search_id' => 'required|integer|min:1',
        );
        
        $validation = Validator::make($input, $rules);

        if ($validation->passes()) {
            $feed = PromotionFeed::make($input);

            return Redirect::to_route('get_discover_page');
        } else {
            return Redirect::back()->with_input()->with_errors($validation);
        }
    }

    /**
     * When a feed url is checked for new content
     * The promotion feed is a feed with only one entry when last_pub_date + frequency < NOW
     */
    public function get_feed_data($feed_id)
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
                    // 'description' => 'description',
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

                $profiles = Search::make($feed->search_id)
                ->where_privacy('public')
                ->where_in_promotion_feed(1)
                ->get();

                if ($feed->use_blacklist == 1) {
                    $profiles = ProcessBlacklist($profiles, $feed->user_id);
                }

                $profiles = PickAtRandomInArray($profiles, $feed->profile_count);

                // content of the feed
                $feed_data['items'][] = array(
                    'title' => 'Promotion feed entry on '.$now->format(Config::get('vgc.date_formats.english')),
                    'pubDate' => $now_string,
                    'link' => route('get_discover_feed_page'),
                    'guid isPermalink="false"' => Str::random(40),
                    'description' => 'error creating profile list',
                );

                // build te description
                $description = View::make('partials/promotion_profile_list', array('profiles' => $profiles))->render();

                $feed_data['items'][0]['description'] = $description;
            }

            return Response::view('rss', array('feed_data' => $feed_data));
        } else {
            return 'Unknow promotion feed id';
        }
    }


    //----------------------------------------------------------------------------------
    // NEWSLETTER

    public function get_newsletter_page($newsletter_id = null, $url_key = null)
    {    
        if ( ! is_null($newsletter_id) && is_logged_in()) {
            return Redirect::to_route('get_discover_newsletter_page');
        }

        $this->layout->nest('page_content', 'discover',  array(
            'current_tab' => '#email-pane',
            'newsletter_id' => $newsletter_id,
            'url_key' => $url_key
        ));
    }

    public function post_newsletter_create()
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
            $newsletter = PromotionNewsletter::create($input);
            
            if (is_guest()) {
                return Redirect::to_route('get_discover_newsletter_update', array($newsletter->id, $newsletter->url_key));
            } else {
                return Redirect::to_route('get_discover_page');
            }
        } else {
            return Redirect::back()->with_input()->with_errors($validation);
        }
    }

    public function post_newsletter_update()
    {
        $input = Input::all();

        // unsubscribe
        if (isset($input['unsubscribe'])) {
            if (PromotionNewsletter::unsubscribe($input)) {
                return Redirect::to_route('get_discover_newsletter_page');
            } else {     
                if (is_guest()) {
                    return Redirect::to_route('get_discover_newsletter_update', array($input['newsletter_id'], $input['newsletter_url_key']));
                } else {
                    return Redirect::to_route('get_discover_page');
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

        if ($validation->passes() && PromotionNewsletter::update($input, null)) {
            if (is_guest()) {
                $newsletter = PromotionNewsletter::find($input['newsletter_id']);
                return Redirect::to_route('get_discover_newsletter_update', array($newsletter->id, $newsletter->url_key));
            } else {
                return Redirect::to_route('get_discover_page');
            }
        } else {            
            return Redirect::back()->with_input()->with_errors($validation);
        }
    }

} // end of Discover controller class
