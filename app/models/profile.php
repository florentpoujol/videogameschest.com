<?php

class Profile extends Eloquent
{
    // fields which data is stored as json
    public static $json_fields = array('links', 'medias');
    
    // text fields which data is stored as json array
    public static $array_fields = array( );

    // text fields which data is stored as json object with a 'names' and 'urls' keys containing an array ot items
    public static $names_urls_fields = array('links', 'medias');

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = array();


    //----------------------------------------------------------------------------------
    // CRUD METHODS

    public static function create(array $input = array()) 
    {
        $input = clean_form_input($input);
        $tags = $input['tags'];
        unset($input['tags']);

        $input['links'] = json_encode( clean_names_urls_array( $input['links'] ) );
        $input['medias'] = json_encode( clean_names_urls_array( $input['medias'] ) );

        $profile = parent::create($input);
        
        $profile->tags()->sync( $tags );

        // msg
        $msg = lang('profile.msg.creation_success', array(
            'name' => $profile->name,
            'id' => $profile->id
        ));

        HTML::set_success($msg);
        Log::info("profile create success User '".user()->name."' (id=".user_id().") has created a profile with name='".$profile->name."' and id='".$profile->id."'.");
        
        return $profile;
    }

    public function update(array $input = array())
    {
        $profile = $this;
        
        $input = clean_form_input($input);
        $profile->tags()->sync($input['tags']);
        unset($input['tags']);

        $input['links'] = json_encode( clean_names_urls_array( $input['links'] ) );
        $input['medias'] = json_encode( clean_names_urls_array( $input['medias'] ) );

        $update = parent::update($input);
        
        if (!$update) {
            $msg = lang('profile.msg.update_error', array(
                'name' => $profile->name,
                'id' => $profile->id
            ));
            HTML::set_success($msg);
            Log::error("profile update error User '".user()->name."' (id=".user_id().") had trouble updating profile with name='".$profile->name."' and id='".$profile->id."'.");
            return false;
        }

        $msg = lang('profile.msg.update_success', array(
            'name' => $profile->name,
            'id' => $profile->id
        ));
        HTML::set_success($msg);
        Log::info("profile update success User '".user()->name."' (id=".user_id().") has updated the profile with name='".$profile->name."' and id='".$profile->id."'.");
        return $update;
    }

    
    //----------------------------------------------------------------------------------
    // RELATIONSHIPS


    public function reports()
    {
        return $this->hasMany('Report');
    }

    public function tags()
    {
        return $this->belongsToMany('Tag');
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

            $this->setAttribute($key, json_encode($value));
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
            $attr = $this->getAttribute($key);

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
