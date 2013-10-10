<?php

class Profile extends ExtendedEloquent
{
    public $type = 'profile';
    public $types = 'profiles';

    // fields which data is stored as json
    public static $json_fields = array('languages', 'technologies', 'operatingsystems', 'devices', 'genres', 'looks', 'periods',
        'viewpoints', 'nbplayers', 'tags', 'links', 'screenshots', 'videos');
    
    // text fields which data is stored as json array
    public static $array_fields = array('languages', 'technologies', 'operatingsystems', 'devices', 'genres', 'looks', 'periods',
        'viewpoints', 'nbplayers', 'tags' );

    // text fields which data is stored as json object with a 'names' and 'urls' keys containing an array ot items
    public static $names_urls_fields = array('links', 'screenshots', 'videos');

    //----------------------------------------------------------------------------------
    // CONSTRUCTOR

    public function __construct($attributes = array(), $exists = false)
    {
        parent::__construct($attributes, $exists);

        $this->type = strtolower(get_class($this)); // get the child class name
        $this->types = $this->type.'s';
    }


    //----------------------------------------------------------------------------------
    // CRUD METHODS

    public static function create($input) 
    {
        $create_preview_version = true;
        if (isset($input['create_preview_version']) && $input['create_preview_version'] == false) {
            $create_preview_version = false;
            unset($input['create_preview_version']);
        }

        if ( ! isset($input['user_id'])) $input['user_id'] = user_id();
        if ( ! isset($input['privacy'])) $input['privacy'] = 'private';

        $profile = parent::create($input);
        
        // msg
        $msg = lang('profile.msg.creation_success', array(
            'profile_type' => $profile->type,
            'name' => $profile->name,
            'id' => $profile->id
        ));

        HTML::set_success($msg);
        Log::write('profile '.$profile->type.' create success', "User '".user()->name."' (id=".user_id().") has created a ".$profile->type." profile with name='".$profile->name."' and id='".$profile->id."'.");
        
        // email
        if ( ! is_admin()) {
            $subject = lang('emails.profile_created.subject');
            $html = lang('emails.profile_created.html', array(
                'user_name' => $profile->user->name,
                'profile_type' => $profile->type,
                'profile_name' => $profile->name,
            ));

            sendMail($profile->user->email, $subject, $html);
        }

        // preview version
        if ($create_preview_version) {
            $preview_profile = PreviewProfile::create($profile);

            $msg = "Preview version of ".$profile->type." profile with name='".$profile->name."' and id='".$profile->id."' has been created with id='".$preview_profile->id."'.";
            if (is_admin()) HTML::set_success($msg);
            Log::write('profile '.$preview_profile->type.' create success', $msg);
        }

        return $profile;
    }

    public static function update($id, $input)
    {
        $profile = parent::find($id);

        $preview_data = static::strip_preview_data_from_input($input, $profile->type);

        if (count($input) > 0) parent::update($id, $input);
        $profile = parent::find($id);

        $preview_profile = $profile->preview_profile;
        PreviewProfile::update($preview_profile->id, array(
            'data' => $preview_data,
            'in_review' => 1
        ));

        $msg = lang('profile.msg.update_success', array(
            'profile_type' => $profile->type,
            'name' => $profile->name,
            'id' => $profile->id
        ));

        HTML::set_success($msg);

        Log::write($profile->type.' profile update success', "User '".user()->name."' (id=".user_id().") has updated the public ".$profile->type." profile with name='".$profile->name."' and id='".$profile->id."'.");
        return $profile;
    }


    //----------------------------------------------------------------------------------

    /**
     * Update this profile instance with the stored preview data
     * So that this instance looks like the proile if the preview data was saved
     */
    public function update_with_preview_data()
    {
        $preview_data = $this->preview_profile->data;

        foreach ($preview_data as $key => $value) {
            //$this->set_attribute($key, $value);
            $this->$key = $value;
        }
    }

    protected static function strip_preview_data_from_input(&$input, $profile_type)
    {
        $preview_data = array();
        $preview_fields = array_merge(
            Config::get('vgc.profile_fields_to_review.common'),
            Config::get('vgc.profile_fields_to_review.'.$profile_type, array())
        );
        
        foreach ($preview_fields as $field) {
            if (isset($input[$field])) {
                $preview_data[$field] = $input[$field];
                unset($input[$field]);
            }
        }

        return $preview_data;
    }


    //----------------------------------------------------------------------------------
    // REVIEW

    /**
     * Do stuffs when a profile passed a review
     * Called from admin@post_review
     */
    public function passed_review()
    {
        $send_email = false;
        if ($this->privacy == 'private') $send_email = true;

        $this->privacy = 'public'; // nessessary only the first time when the "real" profile goes from private to public
        $this->update_with_preview_data();
        $this->save();

        $preview_profile = $this->preview_profile;

        $preview_profile->data = array();
        $preview_profile->in_review = 0;
        $preview_profile->save();

        $profile_type = $this->type;
        Log::write($profile_type.' success review', $profile_type.' profile passed the review.');

        if ($send_email) {
            // email's text :
            // it explain that the profile has passed the review and what can they do with it
            $subject = lang('emails.profile_passed_publishing_review.subject');
            
            $html = lang('emails.profile_passed_review.html', array(
                'user_name' => $this->user->name,
                'profile_type' => $profile_type,
                'profile_name' => $this->name,
                'profile_link' => route('get_profile_view', array($profile_type,name_to_url($this->name))),
            ));

            sendMail($this->user->email, $subject, $html);
        }
    }

    
    //----------------------------------------------------------------------------------
    // RELATIONSHIPS

    public function user()
    {
        return $this->belongs_to('User', 'user_id');
    }

    public function reports()
    {
        return $this->has_many('Report', 'profile_id')->where_profile_type($this->type);
    }

    public function preview_profile()
    {
        return $this->has_one("PreviewProfile");
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
