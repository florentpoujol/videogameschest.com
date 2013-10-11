<?php

class Profile extends ExtendedEloquent
{
    // fields which data is stored as json
    public static $json_fields = array('links', 'medias');
    
    // text fields which data is stored as json array
    public static $array_fields = array( );

    // text fields which data is stored as json object with a 'names' and 'urls' keys containing an array ot items
    public static $names_urls_fields = array('links', 'medias');

    //----------------------------------------------------------------------------------
    // CONSTRUCTOR

    public function __construct($attributes = array(), $exists = false)
    {
        parent::__construct($attributes, $exists);
    }


    //----------------------------------------------------------------------------------
    // CRUD METHODS

    public static function create($input) 
    {
        $input = clean_form_input($input);
        $profile = parent::create($input);
        
        // msg
        $msg = lang('profile.msg.creation_success', array(
            'name' => $profile->name,
            'id' => $profile->id
        ));

        HTML::set_success($msg);
        Log::write('profile create success', "User '".user()->name."' (id=".user_id().") has created a profile with name='".$profile->name."' and id='".$profile->id."'.");
        
        return $profile;
    }

    public static function update($id, $input)
    {
        $profile = parent::find($id);
        
        $input = clean_form_input($input);

        foreach (static::$names_urls_fields as $field) {
            $input[$field] = clean_names_urls_array($input[$field]);
        }

        parent::update($id, $input);
        $profile = parent::find($id);

        $msg = lang('profile.msg.update_success', array(
            'name' => $profile->name,
            'id' => $profile->id
        ));

        HTML::set_success($msg);

        Log::write('profile update success', "User '".user()->name."' (id=".user_id().") has updated the profile with name='".$profile->name."' and id='".$profile->id."'.");
        return $profile;
    }

    
    //----------------------------------------------------------------------------------
    // RELATIONSHIPS


    public function reports()
    {
        return $this->has_many('Report', 'profile_id');
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

            if (in_array($key, static::$array_fields) && trim($attr) == '') {
                $attr = '[]'; // make sure $attr is a json array and not an empty string, so that json_decode return an array
            }

            $data = json_decode($attr, true);
            if ($data === null) $data = array();
            return $data;
        }

        return XssSecure(parent::__get($key));
    }
}
