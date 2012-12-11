<?php

class Game extends ExtendedEloquent
{
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

        if ( ! isset($game['privacy'])) $game['privacy'] = 'private';
        
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

        $game = parent::find($id);

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
        
        /*foreach ($form as $field => $attr) {
            if (in_array($field, static::$array_items)) {
                $attr = json_encode($attr);
            } elseif (in_array($field, static::$names_urls_items)) { // must sanitise the array, remove items with blank url
                $attr = json_encode(clean_names_urls_array($attr));
            }

            $game->$field = $attr;
        }

        $game->save();*/
        foreach ($form as $field => $attr) {
            if (in_array($field, static::$json_items)) {
                if (in_array($field, static::$names_urls_items)) { // must sanitise the array, remove items with blank url
                    $attr = clean_names_urls_array($attr);
                }

                $form[$field] = json_encode($attr);
            }
        }

        $game = parent::update($id, $form);

        HTML::set_success(lang('messages.editgame_success', 
            array('name'=>$game->name, 'id'=>$game->id))
        );
        return true;
    }


	//----------------------------------------------------------------------------------
    // REVIEWS

    /**
     * Do stuffs when the profile passed a review
     * @param  string $review       Review type
     */
    public function passed_review($review)
    {
        parent::passed_review($review, 'game');
    }

    /**
     * Do stuffs when the profile failed a review
     * @param  string $review       Review type
     */
    public function failed_review($review)
    {
        parent::failed_review($review, 'game');
    }


    //----------------------------------------------------------------------------------
    // GETTERS

    
    //----------------------------------------------------------------------------------
    // RELATIONSHIPS

	public function user()
    {
        return $this->developer()->user;
    }

    public function developer()
    {
        return $this->belongs_to('Developer');
    }

    public function dev()
    {
        return $this->developer();
    }
}
