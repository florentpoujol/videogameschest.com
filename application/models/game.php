<?php

class Game extends ExtendedEloquent
{
    public static $json_items = array('approved_by',
        'languages', 'technologies', 'operatingsystems', 'devices', 'genres', 'themes',
        'viewpoints', 'nbplayers', 'tags', 'socialnetworks', 'stores', 'screenshots', 'videos');
    
    public static $array_items = array(
    'languages', 'technologies', 'operatingsystems', 'devices', 'genres', 'themes', 'viewpoints', 'nbplayers', 'tags');

    public static $names_urls_items = array('socialnetworks', 'stores', 'screenshots', 'videos');


	//----------------------------------------------------------------------------------
    // CRUD METHODS

    /**
     * Create a new game profile
     * @param  array $game Data comming from the form
     * @return Game       The Game instance
     */
	public static function create($form) 
	{
        $form = clean_form_input($form);

        if ( ! isset($game['privacy'])) $game['privacy'] = 'private';
        
        $game = parent::create($form);
        
        HTML::set_success(lang('messages.addgame_success',array('name'=>$game->name)));
        return $game;
    }

    /**
     * Update a game profile
     * @param  int $id         The game id
     * @param  array $attributes The game's data
     * @return Game The updated game instance
     */
    public static function update($id, $form)
    {
        $form = clean_form_input($form);

        // checking name change
        $game = parent::find($id);
        if (isset($form['name'])) {
            
            if ($game->name != $form['name']) { // the user want to change the name, must check is the name is not taken
                if (parent::where('name', '=', $form['name'])->first() != null) {
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
        }

        $game = parent::update($id, $form);

        HTML::set_success(lang('messages.editgame_success', 
            array('name'=>$game->name, 'id'=>$game->id))
        );
        return $game;
    }


	//----------------------------------------------------------------------------------
    // REVIEWS

    /**
     * Do stuffs when the profile passed a review
     * @param  string $review  Review type
     * @param  string $profile The profile type (this arg is useless here but overriding passed_review() with less params than in the parent did cause issue (Class 'Log' not found))
     */
    public function passed_review($review, $profile = 'game')
    {
        parent::passed_review($review, $profile);
    }

    /**
     * Do stuffs when the profile failed a review
     * @param  string $review       Review type
     * @param  string $profile The profile type
     */
    public function failed_review($review, $profile = 'game')
    {
        parent::failed_review($review, $profile);
    }


    //----------------------------------------------------------------------------------
    // GETTERS

    
    //----------------------------------------------------------------------------------
    // MAGIC METHODS

    /**
     * Handle the dynamic setting of attributes.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public function __set($key, $value)
    {
        if (in_array($key, static::$json_items)) {
            if (in_array($key, static::$names_urls_items)) {
                $value = clean_names_urls_items($value);
            }

            $this->set_attribute($key, json_encode($value));
        } else parent::__set($key, $value);
    }

    /**
     * Handle the dynamic retrieval of attributes and associations.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        if (in_array($key, static::$json_items)) return json_decode($this->get_attribute($key), true);
        else return parent::__get($key);
    }


    //----------------------------------------------------------------------------------
    // RELATIONSHIPS

	/*public function developer()
    {
        return $this->belongs_to('Developer');
    }*/

    public function dev()
    {
        return $this->belongs_to('Developer');
    }
}
