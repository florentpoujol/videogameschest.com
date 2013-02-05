<?php

class Promotion_Controller extends Base_Controller 
{
    public function get_index()
    {
        //return Redirect::to_route('get_promotion_feed_page');
        //return Redirect::to_route('get_crosspromotion');
        $this->layout->nest('page_content', 'promote');
    }


    //----------------------------------------------------------------------------------
    // CROSS PROMOTION

    public function get_crosspromotion_page()
    {
        $this->layout->nest('page_content', 'promotion/crosspromotion');
    }

    /**
     * When the user activate or deactivate the cross promotion
     */
    public function post_crosspromotion_update()
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
    public function post_crosspromotion_game_update()
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


} // end of Promotion controller class
