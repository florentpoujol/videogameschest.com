<?php

class Profile extends ExtendedEloquent
{
    //public $_user = null;
    public $class_name = 'profile';
    public $type = 'profile';
    public $class_name_plural = 'profiles';

    public static $table_fields = array('id', 'privacy', 'name', 'data', 'created_at', 'updated_at');
    public static $regular_fields = array('id', 'privacy', 'name', 'created_at', 'updated_at');

    //----------------------------------------------------------------------------------
    // CONSTRUCTOR

    public function __construct($attributes = array(), $exists = false)
    {
        parent::__construct($attributes, $exists);

        $this->class_name = strtolower(get_class($this)); // get the child class name
        $this->class_name_plural = $this->class_name.'s';

        $this->type = $this->class_name;
    }


    //----------------------------------------------------------------------------------
    // CRUD METHODS

    /**
     * Create a new profile
     * @param  array $old_input Data comming from the form
     * @return Profile       The Profile instance
     */
    public static function create($input) 
    {
        $input = clean_form_input($input);

        $create_preview_version = true;
        if (isset($input['create_preview_version'])) {
            if ($input['create_preview_version'] == false) $create_preview_version = false;
            unset($input['create_preview_version']);
        }

        if ( ! isset($input['user_id'])) $input['user_id'] = user_id();
        if ( ! isset($input['privacy'])) $input['privacy'] = 'private';

        $profile = parent::create($input);

        // insert here mecanism to allow updates

        // msg
        $msg = lang('profile.msg.creation_success', array(
            'type' => $profile->type,
            'name' => $profile->name,
            'id' => $profile->id
        ));

        HTML::set_success($msg);
        Log::write('profile '.$profile->type.' create success', "User '".user()->name."' (id=".user_id().") has created a ".$profile->type." profile with name='".$profile->name."' and id='".$profile->id."'.");
        
        // email
        $subject = lang('emails.profile_created.subject');
        $html = lang('emails.profile_created.html', array(
            'user_name' => $profile->user->name,
            'profile_type' => $profile->type,
            'profile_name' => $profile->name,
        ));

        sendMail($profile->user->email, $subject, $html);

        // preview version
        if ($create_preview_version) {
            $input['privacy'] = 'publishing';

            $profile = $profile;
            $preview_profile = parent::create($input);

            Log::write('profile '.$preview_profile->type.' create success', "Preview version of ".$profile->type." profile with name='".$profile->name."' and id='".$profile->id."' has been created with id='".$preview_profile->id."'.");
        }

        return $profile;
    }

    /**
     * Update a developer profile
     * @param  int $id     The dev's id
     * @param  array $input The dev's data
     * @return Developer   The updated dev instance
     */
    public static function update($id, $input)
    {
        $input = clean_form_input($input);

        parent::update($id, $input);
        $profile = parent::find($id);

        $msg = lang('profile.msg.update_success', array(
            'type' => $profile->type,
            'name' => $profile->name,
            'id' => $profile->id
        ));

        HTML::set_success($msg);

        Log::write($profile->type.' profile update success', "User '".user()->name."' (id=".user_id().") has updated the ".$profile->type." profile with name='".$profile->name."' and id='".$profile->id."'.");

        return $profile;
    }


    //----------------------------------------------------------------------------------
    // REVIEWS

    /**
     * Do stuffs when a profile passed a review
     * Called from admin@post_reviews
     * @param  string $user The User
     */
    public function passed_review()
    {
        $review = $this->privacy;
        $profile = $this->class_name;
        
        if ($review == 'publishing') {
            $this->privacy = 'public';
        }

        $this->save();

        Log::write($profile.' success review', $profile.' profile passed the '.$review.' review.');

        // email's text :
        // it explain that the profile has passed the review and what can they do with it
        $subject = lang('emails.profile_passed_publishing_review.subject');
        
        $html = lang('emails.profile_passed_'.$review.'_review.html', array(
            'user_name' => $this->user->name,
            'profile_type' => $profile,
            'profile_name' => $this->name,
            'profile_link' => route('get_'.$profile, array(name_to_url($this->name))),
        ));

        sendMail($this->user->email, $subject, $html);
    }

    


    //----------------------------------------------------------------------------------
    // RELATIONSHIPS

    public function user()
    {
        return $this->belongs_to('User');
    }

    /**
     * Get all reports of the specified type linked to this profile
     * @param  string $type The report type
     * @return array       An array of reports
     */
    public function reports($report_type = null)
    {
        $foreign_key = $this->type.'_id';
        
        if (is_null($report_type)) return $this->has_many('Report', $foreign_key);
        else return $this->has_many('Report', $foreign_key)->where_type($report_type)->get();
    }

    
    //----------------------------------------------------------------------------------
    // CROSS PROMOTION

    /**
     * Sanitize the model to return an array with only relevant data for crosspromotion
     * @return array The model, sanitized and as array
     */
    public function to_crosspromotion_array()
    {
        $profile = $this->attributes;
        $allowed_fields = Config::get('vgc.crosspromotion_'.$this->class_name.'_allowed_fields');

        foreach ($profile as $field => $value) {
            if ( ! in_array($field, $allowed_fields)) unset($profile[$field]);
        }

        if ($this->class_name == 'game') $profile['developer_name'] = $this->developer_name;

        $profile['pitch_html'] = $this->get_parsed_pitch();
        $profile['url'] = route('get_'.$this->class_name, array(name_to_url($this->name)));

        //
        $class_name = $this->class_name;
        foreach ($profile as $field => $value) {
            if (in_array($field, $class_name::$json_fields)) $profile[$field] = json_decode($value, true);
        }
        
        return $profile;
    }


    //----------------------------------------------------------------------------------
    // GETTERS

    public function get_parsed_pitch() 
    {
        return nl2br(parse_bbcode(xssSecure($this->pitch)));
    }
}
