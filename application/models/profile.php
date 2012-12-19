<?php

class Profile extends ExtendedEloquent
{
    public $_user = null;
    public $class_name = 'profile';


    //----------------------------------------------------------------------------------
    // CONSTRUCTOR

    public function __construct($attributes = array(), $exists = false)
    {
        parent::__construct($attributes, $exists);

        $this->class_name = strtolower(get_class($this));
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
        $profile = $this->$class_name;
        
        if ($review == 'submission') {
            $this->privacy = 'private';
            $password = get_random_string(10);
        } elseif ($review == 'publishing') {
            $this->privacy = 'public';
            
            // is the user now a trusted user ? send a mail : do nothing ;
            $user->update_trusted(true);
        }

        $this->approved_by = '';
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

    /**
     * Do stuffs when a profile failed a review
     * @param  string $review  Review type
     * @param  string $profile Profile type
     */
    public function failed_review($review, $profile, $user)
    {
        if ($review == 'submission') {
            if ($profile == 'developer') User::delete($user->id);
            $this->delete();
        } elseif ($review == 'publishing') {
            $this->privacy = 'private';
            $this->approved_by = '';
            $this->save();

            // email's text :
            $html = lang('emails.'.$profile.'_failed_'.$review.'_review', array(
                'name' => $this->name,
                'id' => $this->id,
            ));

            $email = $user->email;

            // @TODO : send mails with 
        }
    }


    //----------------------------------------------------------------------------------
    // RELATIONSHIPS

    /**
     * Get ll reports linked to this profile
     * @param  string $type The report type
     * @return array       An array of reports
     */
    public function reports($type = null)
    {
        $foreign_key = $this->class_name.'_id';
        
        if (is_null($type)) return $this->has_many('Report', $foreign_key);
        else return $this->has_many('Report', $foreign_key)->where_type($type)->get();
    }

    
}
