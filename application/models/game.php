<?php

class Game extends Profile
{
    public static $json_fields = array('approved_by', 'promoted_games',
        'languages', 'technologies', 'operatingsystems', 'devices', 'genres', 'themes',
        'viewpoints', 'nbplayers', 'tags', 'socialnetworks', 'stores', 'screenshots', 'videos', 'reviews');
    
    public static $array_fields = array('devices', 'operatingsystems', 'genres', 'themes',
     'viewpoints', 'nbplayers', 'tags', 'languages', 'technologies',  );

    public static $names_urls_fields = array('socialnetworks', 'stores', 'screenshots', 'videos', 'reviews');

    public static $secured_fields = array('name', 'pitch', 'cover', 'website', 'blogfeed', 'presskit', 'country');

    //----------------------------------------------------------------------------------
    // CONSTRUCTOR

    /*public function __construct($attributes = array(), $exists = false)
    {
        parent::__construct($attributes, $exists);
    }*/

	//----------------------------------------------------------------------------------
    // CRUD METHODS

    /**
     * Create a new game profile
     * @param  array $input Data comming from the form
     * @return Game       The Game instance
     */
	public static function create($input) 
	{
        $input = clean_form_input($input);

        $dev = Dev::where_name($input['developer_name'])->first();
        if ( ! is_null($dev)) {
            $input['developer_name'] = '';
            $input['developer_id'] = $dev->id;
        }
        else $input['developer_id'] = 0;

        if ( ! isset($game['privacy'])) $game['privacy'] = 'private';

        $input['approved_by'] = array();
                
        $game = parent::create($input);
        

        $msg = lang('game.msg.addgame_success', array(
            'name'=>$game->name,
            'id' => $game->id
        ));
        HTML::set_success($msg);
        Log::write('game create success', $msg);

        return $game;
    }

    /**
     * Update a game profile
     * @param  int $id         The game id
     * @param  array $input The game's data
     * @return Game The updated game instance
     */
    public static function update($id, $input)
    {
        $input = clean_form_input($input);

        // checking name change
        $game = parent::find($id);
        if (isset($input['name']) && $game->name != $input['name']) {  // the user want to change the name, must check is the name is not taken
            if (parent::where_name($input['name'])->first() != null) {
                HTML::set_error(
                    lang('messages.editgame_nametaken', array(
                        'name'=>$game->name,
                        'id'=>$game->id,
                        'newname'=>$input['name'])
                    )
                );

                return false;
            }
        }


        $dev = Dev::where_name($input['developer_name'])->first();
        if ( ! is_null($dev)) {
            $input['developer_name'] = '';
            $input['developer_id'] = $dev->id;
        } 
        else $input['developer_id'] = 0;
        
        $game = parent::update($id, $input); // 
        $game = Game::find($id);
        
        HTML::set_success(lang('messages.editgame_success'
            ,array('name'=>$game->name, 'id'=>$game->id))
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
    public function passed_review($user = null)
    {
        parent::passed_review($this->dev->user);
    }

    
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
        if (in_array($key, static::$json_fields)) {
            if (in_array($key, static::$names_urls_fields)) {
                $value = clean_names_urls_array($value);
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
        if (in_array($key, static::$json_fields)) {
            $attr = $this->get_attribute($key);
            $data = json_decode($attr, true);
        }
        
        /*elseif (in_array($key, static::$secured_items)) {
            $data Security::xss_clean(e($this->get_attribute($key)));
        }*/

        else $data = parent::__get($key);

        return $data; // I could also use the helper e() (html_entities())
    }


    //----------------------------------------------------------------------------------
    // RELATIONSHIPS

    public function user()
    {
        return $this->belongs_to('User');
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
