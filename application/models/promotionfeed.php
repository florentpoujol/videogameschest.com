<?php

class PromotionFeed extends ExtendedEloquent 
{
    public static $table = 'promotion_feeds';

    //----------------------------------------------------------------------------------

    public static function make($input)
    {
        $input = clean_form_input($input);
        $feed = null;

        if (is_logged_in()) {
            $feed = parent::where_user_id(user_id())->first();
        }

        // create new feed row
        if (is_null($feed)) { 
            //$input['last_pub_date'] = date_create();
            $feed = parent::create($input);

            // msg
            HTML::set_success(lang('discover.msg.create_feed_success'));

            $url = route('get_discover_feed_data', array($feed->id));
            HTML::set_info(lang('discover.msg.feed_url', array(
                'feed_url' => $url
            )));

            // Log
            $msg = "Guest";
            if ($feed->user_id > 0) {
                $msg = "User name='".user()->name."' id='".user_id()."'";
            }
            $msg .= " created feed id='".$feed->id."'";

            Log::write('create promotion feed success', $msg);
        } 
        // update feed row
        elseif ($feed->user_id != 0) { // update feed if user_id
            if ( ! isset($input['use_blacklist'])) $input['use_blacklist'] = 0;
            
            parent::update($feed->id, $input);
            $feed = parent::find($feed->id);

            // msg
            HTML::set_success(lang('discover.msg.update_feed_success'));

            // Log
            $msg = "User name='".user()->name."' id='".user_id()."' updated feed id='".$feed->id."'";
            Log::write('update promotion feed success', $msg);
        }

        return $feed;
    }
}
