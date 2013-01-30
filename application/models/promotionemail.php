<?php

class PromotionEmail extends ExtendedEloquent 
{
    public static $table = 'promotion_emails';

    //----------------------------------------------------------------------------------

    /**
     * Create or update a feed row
     * Each time a visitor submit the form, a new row is created
     * @todo Check feeds that are not used  :    NOW - updated_at > frequency
     */
    public static function make($input)
    {
        $input = clean_form_input($input);
        $feed = null;

        if (isset($input['user_id'])) {
            $feed = parent::where_user_id($input['user_id'])->first();
        } else {
            /*$feed = parent::where(function($query) use ($input)
            {
                foreach ($input as $field => $value) {
                    $query->where($field, '=', $value);
                }
            })->first();*/

            /*$feed = parent::
                where_type($input['type'])
                ->where_frequency($input['frequency'])
                ->where_profile_count($input['profile_count'])
                ->where_search_id($input['search_id'])
                ->first();*/
        }

        // create new feed row
        if (is_null($feed)) { 
            //$input['last_pub_date'] = date_create();
            $feed = parent::create($input);
        } 
        // update feed row
        elseif ($feed->user_id != 0) { // update feed if user_id
            if ( ! isset($input['use_blacklist'])) {
                $input['use_blacklist'] = 0;
            }

            parent::update($feed->id, $input);
            $feed = parent::find($feed->id);
        }

        return $feed;
    }
}
