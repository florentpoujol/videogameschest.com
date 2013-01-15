<?php

class Advertising_Controller extends Base_Controller 
{
    public function get_index()
    {
        return Redirect::to_route('get_crosspromotion');
    }


    //----------------------------------------------------------------------------------
    // CROSS PROMOTION

    public function get_crosspromotion()
    {
        $this->layout->nest('page_content', 'advertising/crosspromotion');
    }

    public function post_crosspromotion()
    {
        return 'post advertising';
    }


    /**
     * the games that want their promoted profiles arrives here
     */
    public function get_crosspromoted_profiles($game_id, $crosspromotion_key)
    {
        $game = Game::where_id($game_id)->where_crosspromotion_key($crosspromotion_key)->first();

        if (is_null($game)) {
            return Response::json(array('No game with id "'.$game_id.'" and crosspromotion key "'.$crosspromotion_key.'" was found.'));
        }

        $promoted_profiles = $game->crosspromotion_profiles;

        foreach ($promoted_profiles as $profile_type => $profiles) {
            for ($i = 0; $i < count($profiles); $i++) {
                $profiles[$i] = Game::find($profiles[$i])->to_crosspromotion_array();
                // replace the ID by the Model
            }
        }

        return Response::json($promoted_profiles);
    }




} // end of Advertising controller class
