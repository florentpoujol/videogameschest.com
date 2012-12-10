<?php

class Game extends Eloquent
{
	public static $timestamps = true;
    
    public static $json_items = array(
        'languages', 'technologies', 'operatingsystems', 'devices', 'genres', 'themes',
        'viewpoints', 'nbplayers', 'tags', 'socialnetworks', 'stores', 'screenshots', 'videos');
    
    public static $array_items = array(
    'languages', 'technologies', 'operatingsystems', 'devices', 'genres', 'themes', 'viewpoints', 'nbplayers', 'tags');

    public static $names_urls_items = array('socialnetworks', 'stores', 'screenshots', 'videos');


	//----------------------------------------------------------------------------------

    /**
     * Create a new game profile
     * @param  array $game Data comming from the form
     * @return Game       The Game instance
     */
	public static function create($game) 
	{
        unset($game['csrf_token']);

        foreach (static::$array_items as $item) {
            if (isset($game[$item])) {
                $game[$item] = json_encode($game[$item]);
            }
        }

        foreach (static::$names_urls_items as $item) {
            if (isset($game[$item])) {
                $game[$item] = json_encode(clean_names_urls_array($game[$item]));
            }
        }

        $game['privacy'] = 'private';
        
        $game = parent::create($game);
        
        HTML::set_success(lang('messages.addgame_success',array('name'=>$game->name)));
        return $game;
    }

    /**
     * Update a developer profile
     * @param  int $id         The developer id
     * @param  array $attributes The dev's data
     * @param  Developer $game The dev instance
     * @return User The updateddev instance
     */
    public static function update($id, $form)
    {
        unset($form['csrf_token']);

        $game = Game::find($id);

        if ($game->name != $form['name']) { // the user wan to change the name, must check is the name is not taken
            if (Game::where('name', '=', $form['name'])->first() != null) {
                HTML::set_error(
                    lang('messages.editgame_nametaken', array(
                        'name'=>$game->name,
                        'id'=>$game->id,
                        'newname'=>$form['name'])
                    )
                );

                return false;
            }
        }
        
        foreach ($form as $field => $attr) {
            if (in_array($field, static::$array_items)) {
                $attr = json_encode($attr);
            } elseif (in_array($field, static::$names_urls_items)) { // must sanitise the array, remove items with blank url
                $attr = json_encode(clean_names_urls_array($attr));
            }
            
            

            $game->$field = $attr;
        }

        $game->save();

        HTML::set_success(lang('messages.editgame_success', 
            array('name'=>$game->name, 'id'=>$game->id))
        );
        return true;
    }


	//----------------------------------------------------------------------------------
    // REVIEWS

    /**
     * Do stuffs when the profile passed the submission review
     */
    public function submission_review_success()
    {
        $this->privacy = 'private';
        $this->approved_by = '';
        $this->review_start_date = '0000-00-00 00:00:00';
        $this->save();

        // @TODO send mail to dev with text emails.game_submission_review_success
    }

    /**
     * Do stuffs when the profile failed at the submission review
     */
    public static function submission_review_fail($game)
    {
        Game::delete($game->id);
    }

    /**
     * Do stuffs when the profile passed the publishing review
     */
    public function publishing_review_success()
    {
        $this->privacy = 'public';
        $this->approved_by = '';
        $this->review_start_date = '0000-00-00 00:00:00';
        $this->save();

        // @TODO send mail to dev with text emails.developer_publishing_review_success
    }

    /**
     * Do stuffs when the profile failed at the publishing review
     */
    public static function publishing_review_fail($game)
    {
        $this->privacy = 'private';
        $this->approved_by = '';
        $this->review_start_date = '0000-00-00 00:00:00';
        $this->save();

        // @TODO send mail to dev with text emails.game_publishing_review_success
    }


    //----------------------------------------------------------------------------------
    // GETTERS

    public function json_to_array($attr)
    {
        return json_decode($this->get_attribute($attr), true);
    }


    //----------------------------------------------------------------------------------
    // RELATIONSHIPS

	public function developer()
    {
        return $this->belongs_to('Developer');
    }

    public function dev()
    {
        return $this->developer();
    }


    //----------------------------------------------------------------------------------

    // for Former bundle
    public function __toString()
    {
        return $this->name;
    }
}
