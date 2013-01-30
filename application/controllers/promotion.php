<?php

class Promotion_Controller extends Base_Controller 
{
    public function get_index()
    {
        return Redirect::to_route('get_promotion_feed_page');
        //return Redirect::to_route('get_crosspromotion');
        $this->layout->nest('page_content', 'promotion');
    }


    //----------------------------------------------------------------------------------
    // CROSS PROMOTION

    public function get_crosspromotion()
    {
        $this->layout->nest('page_content', 'promotion/crosspromotion');
    }

    /**
     * When the user activate or deactivate the cross promotion
     */
    public function post_crosspromotion()
    {
        $current_state = user()->crosspromotion_active;
        $new_state = Input::get('crosspromotion_active', 0);

        if ($new_state != $current_state) {
            user()->update_crosspromotion($new_state);
        }

        return Redirect::to_route('get_crosspromotion');
    }

    /**
     * When the user edit the promoted profiles for one of its game (from the edit game form)
     */
    public function post_crosspromotion_editgame()
    {
        $input = Input::all();

        if (is_not_admin()) {
            // check that $input['id'] is one of the user's game profiles
            $forged = true;
            foreach (user()->games as $game) {
                if ($game->id == $input['id']) $forged = false;
            }

            if ($forged) {
                HTML::set_error(lang('common.msg.edit_other_users_proile_not_allowed'));
                return Redirect::back();
            }
        }

        Game::update_crosspromotion($input);
        
        return Redirect::to_route('get_editgame', array($input['id']));
    }

    /**
     * the games that want their promoted profiles arrives here
     */
    public function get_crosspromotion_from_game($game_id, $crosspromotion_key)
    {
        $game = Game::where_id($game_id)->where_crosspromotion_key($crosspromotion_key)->first();

        if (is_null($game)) {
            return Response::json(array('errors' => array('No game with id \''.$game_id.'\' and crosspromotion key \''.$crosspromotion_key.'\' was found.')));
        }

        if ($game->user->crosspromotion_active == 0) {
            return Response::json(array('errors' => array('The cross-promotion service is not active for the user (name : '.user()->name.') to which this game is linked.')));
        }
     
        $promoted_profiles = $game->crosspromotion_profiles;

        foreach ($promoted_profiles as $profile_type => $profiles) {
            
            for ($i = 0; $i < count($profiles); $i++) {
                $profiles[$i] = Game::find($profiles[$i])->to_crosspromotion_array();
                // replace the ID by the Model
            }

            $promoted_profiles[$profile_type] = $profiles;
        }

        return Response::json($promoted_profiles);
    }


    //----------------------------------------------------------------------------------
    // FEED

    public function get_feed_page()
    {
        $this->layout->nest('page_content', 'promotion/feed');
    }

    public function post_create_feed()
    {
        $input = Input::all();
        if (is_logged_in()) $input['user_id'] = user_id();

        $rules = array(
            'type' => 'required|in:rss,atom',
            'frequency' => 'required|integer|min:12|max:744',
            'profile_count' => 'required|integer|min:1|max:500',
            'search_id' => 'integer|min:1',
        );

        $validation = Validator::make($input, $rules);

        if ($validation->passes()) {
            $feed = PromotionFeed::make($input);
            
            $url = route('get_promotion_feed_data', array($feed->id));

            HTML::set_info(lang('discover.msg.feed_url', array(
                'feed_url' => $url
            )));

            return Redirect::back()->with_input();
        } else {
            return Redirect::back()->with_input()->with_errors($validation);
        }
    }


    /**
     * When a feed url is checked for new content
     * route get_promotion_feed_data
     */
    public function get_feed_data($feed_id)
    {

    }


    //----------------------------------------------------------------------------------
    // FEED

    public function get_email_page()
    {
        $this->layout->nest('page_content', 'promotion/email');
    }

    public function post_create_email()
    {
        $input = Input::all();
        if (is_logged_in()) $input['user_id'] = user_id();

        $rules = array(
            'email' => 'required|email',
            'frequency' => 'required|integer|min:12|max:744',
            'profile_count' => 'required|integer|min:1|max:500',
            'search_id' => 'integer|min:1',
        );

        $validation = Validator::make($input, $rules);

        if ($validation->passes()) {
            $email = PromotionEmail::make($input);
            
            $url = route('get_promotion_feed_data', array($feed->id));

            HTML::set_info(lang('discover.msg.feed_url', array(
                'feed_url' => $url
            )));

            return Redirect::back()->with_input();
        } else {
            return Redirect::back()->with_input()->with_errors($validation);
        }
    }

} // end of promotion controller class
