<?php

class Game extends Profile
{
    // fields which data is stored as json
    public static $json_fields = array(
        'languages', 'technologies', 'operatingsystems', 'devices', 'genres', 'looks', 'periods',
        'viewpoints', 'nbplayers', 'tags', 'links', 'screenshots', 'videos');
    
    // text fields which data is stored as json array
    public static $array_fields = array('devices', 'operatingsystems', 'genres', 'looks', 'periods',
     'viewpoints', 'nbplayers', 'tags', 'languages', 'technologies',  );

    // text fields which data is stored as json object with a 'names' and 'urls' keys containing an array ot items
    public static $names_urls_fields = array('links', 'screenshots', 'videos');

    
    //----------------------------------------------------------------------------------
    // CRUD METHODS

    public static function update($id, $input)
    {
        // checking name change
        $game = parent::find($id);
        if (isset($input['name']) && $game->name != $input['name']) {  // the user want to change the name, must check if the name is not taken
            if (parent::where_name($input['name'])->first() != null) {
                HTML::set_error(
                    lang('profile.msg.update_nametaken', array(
                        'profile_type' => 'game',
                        'name' => $game->name,
                        'id' => $game->id,
                        'newname' => $input['name'])
                    )
                );

                return false;
            }
        }

        foreach (static::$names_urls_fields as $field) {
            $input[$field] = clean_names_urls_array($input[$field]);
        }
        
        $game = parent::update($id, $input);
        return true;
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
    // user, reports and preview_profile relationships are in Profile model
}
