<?php

class Profile extends ExtendedEloquent
{
    protected $_user = null;

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
        $profile = get_class(); // return the name of the child class
        
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

    public function reports()
    {
        return $this->has_many('Report');
    }

    public function admin_reports() 
    {
        $reports = $this->reports;
        $a_reports = array();

        foreach ($reports as $report) {
            if ($report->type == 'admin') $a_reports[] = $report;
        }

        if (count($a_reports) == 0) $a_reports = null;
        
        return $a_reports;
    }

    public function dev_reports() 
    {
        return reports()->where_type('dev')->get();
    }
}
