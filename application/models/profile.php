<?php

class Profile extends ExtendedEloquent
{
    public $type = 'profile';
    public $types = 'profiles';


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

            Log::write('profile '.$preview_profile->type.' create success', "Preview version of ".$profile->type." profile with name='".$profile->name."' and id='".$profile->id."' has been created with id='".$preview_profile->id."'.");
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
            'data' => $preview_data
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
        $preview_profile->privacy = '';
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
        return $this->has_many('Report', $this->type.'_id');
    }

    public function preview_profile()
    {
        return $preview_profile = $this->has_one("PreviewProfile");
    }

}
