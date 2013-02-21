<?php

class Tool extends Profile
{
    // fields which data is stored as json
    public static $json_fields = array('socialnetworks', 'screenshots', 'videos', 'press',
        'tool_works_on_os', 'devices', 'operatingsystems');
    
    // text fields which data is stored as json array
    public static $array_fields = array('tool_works_on_os', 'operatingsystems', 'devices', );

    // text fields which data is stored as json object with a 'names' and 'urls' keys containing an array ot items
    public static $names_urls_fields = array('socialnetworks', 'screenshots', 'videos', 'press');

	//----------------------------------------------------------------------------------
    // CRUD METHODS

    /**
     * Update a game profile
     * @param  int $id         The game id
     * @param  array $input The game's data
     * @return Game The updated game instance
     */
    public static function update($id, $input)
    {
        // checking name change
        $profile = parent::find($id);
        if (isset($input['name']) && $profile->name != $input['name']) {  // the user want to change the name, must check is the name is not taken
            if (parent::where_name($input['name'])->first() != null) {
                HTML::set_error(
                    lang('profile.msg.update_nametaken', array(
                        'type' => $profile->type,
                        'name' => $profile->name,
                        'id' => $profile->id,
                        'newname' => $input['name'])
                    )
                );

                return false;
            }
        }

        foreach (static::$names_urls_fields as $field) {
            $input[$field] = clean_names_urls_array($input[$field]);
        }
        
        $profile = parent::update($id, $input);
        return true;
    }


    //----------------------------------------------------------------------------------

      
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

            if (in_array($key, static::$array_fields) || $key == "meta_data") {
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

    public function games()
    {
        return $this->has_many('Game');
    }


    //----------------------------------------------------------------------------------
    // GETTER

}
