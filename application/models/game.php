<?php

class Game extends Profile
{
    // fields which data is stored as json
    public static $json_fields = array( 'crosspromotion_profiles',
        'languages', 'technologies', 'operatingsystems', 'devices', 'genres', 'looks', 'periods',
        'viewpoints', 'nbplayers', 'tags', 'socialnetworks', 'stores', 'screenshots', 'videos', 'press');
    
    // text fields which data is stored as json array
    public static $array_fields = array('devices', 'operatingsystems', 'genres', 'looks', 'periods',
     'viewpoints', 'nbplayers', 'tags', 'languages', 'technologies',  );

    // text fields which data is stored as json object with a 'names' and 'urls' keys containing an array ot items
    public static $names_urls_fields = array('socialnetworks', 'stores', 'screenshots', 'videos', 'press');

    // fields to secure against XSS before displaying
    public static $secured_fields = array('name', 'pitch', 'cover', 'website', 'blogfeed', 'presskit',);

	//----------------------------------------------------------------------------------
    // CRUD METHODS

    /**
     * Create a new game profile
     * @param  array $input Data comming from the form
     * @return Game       The Game instance
     */
	public static function create($input) 
	{
        $dev = Dev::where_name($input['developer_name'])->first();
        if ( ! is_null($dev)) {
            $input['developer_name'] = '';
            $input['developer_id'] = $dev->id;
        } else $input['developer_id'] = 0;

        //$input['crosspromotion_profiles'] = array('developers'=>array(),'games'=>array());
        $input['crosspromotion_key'] = Str::random(40);
                
        return parent::create($input);
    }

    /**
     * Update a game profile
     * @param  int $id         The game id
     * @param  array $input The game's data
     * @return Game The updated game instance
     */
    public static function update($id, $input)
    {
        // checking name change
        $game = parent::find($id);
        if (isset($input['name']) && $game->name != $input['name']) {  // the user want to change the name, must check is the name is not taken
            if (parent::where_name($input['name'])->first() != null) {
                HTML::set_error(
                    lang('game.msg.editgame_nametaken', array(
                        'name'=>$game->name,
                        'id'=>$game->id,
                        'newname'=>$input['name'])
                    )
                );

                return false;
            }
        }

        if (isset($input['developer_name'])) {
            $dev = Dev::where_name($input['developer_name'])->first();
            if ( ! is_null($dev)) {
                $input['developer_name'] = '';
                $input['developer_id'] = $dev->id;
            } else $input['developer_id'] = 0;
        }
        
        $game = parent::update($id, $input);
        return true;
    }


    //----------------------------------------------------------------------------------

    public static function update_crosspromotion($input)
    {   
        $profiles = array(
            'developers' => isset($input['developers']) ? $input['developers'] : array(),
            'games' => isset($input['games']) ? $input['games'] : array(),
        );

        $game = Game::find($input['id']);
        $game->crosspromotion_profiles = $profiles;
        $game->save();

        HTML::set_success(lang('crosspromotion.msg.update_profiles_success', array('game_name'=>$game->name)));
        Log::write('game crosspromotion update success', 'The promoted profiles for the game (name : '.$game->name.') (id : '.$game->id.') have been updated.');
    }

    /**
     * Get the "preview version" of the pofiles
     */
    public static function preview_version()
    {
        return Game::where_privacy('publishing')->or_where('privacy', '=', 'preview');
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

            if (in_array($key, static::$array_fields)) {
                // make sure $attr is a json array and not an empty string, so that json_decode return an array
                if (trim($attr) == '') $attr = '[]';
            }

            return json_decode($attr, true);
        }

        return XssSecure(parent::__get($key));
    }


    //----------------------------------------------------------------------------------
    // RELATIONSHIPS
    // user and reports relationships are in Profile model

    public function developer()
    {
        return $this->belongs_to('Developer');
    }

    public function dev()
    {
        return $this->developer();
    }


    //----------------------------------------------------------------------------------
    // GETTER

    public function get_actual_developer_name()
    {
        if ($this->developer_id == 0) return $this->get_attribute('developer_name');
        else return $this->dev->name;
    }

    public function get_crosspromotion_profiles()
    {
        $promoted_profiles = $this->get_attribute('crosspromotion_profiles');

        foreach (Config::get('vgc.profiles_types') as $profile_type) {
            if ( ! isset($promoted_profiles[$profile_type.'s'])) {
                $promoted_profiles[$profile_type.'s'] = array();
            }
        }

        return $promoted_profiles;
    }
}
