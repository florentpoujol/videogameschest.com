<?php

class Profile extends ExtendedEloquent
{
    //public $_user = null;
    public $type = 'profile';
    public $types = 'profiles';

    
   /* public static $fields_to_remove = array('id', 'user_id', 'public_id', 'developer_id', 'created_at', 'updated_at', 'in_promotion_feed',
        'in_promotion_newsletter', 'crosspromotion_profiles', 'crosspromotion_key');
*/


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
            $preview_profile = PreviewProfile::create($profile);

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
        $profile = parent::find($id);

        $preview_data = static::strip_preview_data_from_input($input, $profile->type);

        if (count($input) > 0) parent::update($id, $input);
        $profile = parent::find($id);

        $preview_profile = $profile->preview_profile;
        PreviewProfile::update($preview_profile->id, array(
            'privacy' => 'publishing',
            'data' => $preview_data
        ));

        $msg = lang('profile.msg.update_success', array(
            'type' => $profile->type,
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

    /**
     * Update $input at the same time
     * @param  [type] $input [description]
     * @return [type]        [description]
     */
    protected static function strip_preview_data_from_input(&$input, $profile_type)
    {
        $preview_data = array();
        $preview_fields = array_merge(
            Config::get('vgc.profile_fields_to_review.common'),
            Config::get('vgc.profile_fields_to_review.'.$profile_type)
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
    // REVIEWS

    /**
     * Do stuffs when a profile passed a review
     * Called from admin@post_reviews
     */
    public function passed_review()
    {
        $send_email = false;
        if ($this->privacy == 'private') $send_email = true;

        $this->privacy = 'public'; // nessessary only the first time when the "real" profile goes from private to public
        $this->update_with_preview_data();
        $this->save();

        $preview_profile = $this->preview_profile;
        $review = $preview_profile->privacy;

        $preview_profile->data = array();
        $preview_profile->privacy = '';
        $preview_profile->save();

        $profile_type = $this->type;
        Log::write($profile_type.' success review', $profile_type.' profile passed the '.$review.' review.');

        if ($send_email) {
            // email's text :
            // it explain that the profile has passed the review and what can they do with it
            $subject = lang('emails.profile_passed_publishing_review.subject');
            
            $html = lang('emails.profile_passed_'.$review.'_review.html', array(
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

    public function preview_profile()
    {
        return $preview_profile = $this->has_one("PreviewProfile");
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
        $profile['url'] = route('get_profile_view', array($thi->type, name_to_url($this->name)));

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
        return Sparkdown\Markdown($this->pitch);
    }
}
