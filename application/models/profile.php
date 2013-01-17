<?php

class Profile extends ExtendedEloquent
{
    //public $_user = null;
    public $class_name = 'profile';


    //----------------------------------------------------------------------------------
    // CONSTRUCTOR

    public function __construct($attributes = array(), $exists = false)
    {
        parent::__construct($attributes, $exists);

        $this->class_name = strtolower(get_class($this)); // get the child class name
    }

    //----------------------------------------------------------------------------------
    // REVIEWS

    /**
     * Do stuffs when a profile passed a review
     * @param  string $user The User
     */
    public function passed_review($user)
    {
        $password = '';
        $review = $this->privacy;
        $profile = $this->class_name;
        
        if ($review == 'submission') {
            $this->privacy = 'private';
            $password = get_random_string(10);
        } elseif ($review == 'publishing') {
            $this->privacy = 'public';
            
            // is the user now a trusted user ? send a mail : do nothing ;
            $user->update_trusted(true);
        }

        $this->approved_by = array();
        $this->save();

        // email's text :
        // it explain that the profile has passed the review and what can they do with it
        // if the review was 'submission'
        $html = lang('emails.'.$profile.'_passed_'.$review.'_review', array(
            'name' => $this->name,
            'id' => $this->id,
            'password' => $password,
        ));

        $email = $user->email;
        // @TODO : send mails 
    }


    //----------------------------------------------------------------------------------
    // RELATIONSHIPS

    /**
     * Get all reports linked to this profile
     * @param  string $type The report type
     * @return array       An array of reports
     */
    public function reports($type = null)
    {
        $foreign_key = $this->class_name.'_id';
        
        if (is_null($type)) return $this->has_many('Report', $foreign_key);
        else return $this->has_many('Report', $foreign_key)->where_type($type)->get();
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
        return nl2br(parse_bbcode($this->pitch));
    }
}
