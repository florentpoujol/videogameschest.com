<?php

class ExtendedEloquent extends Eloquent
{
    public static $timestamps = true;


    //----------------------------------------------------------------------------------
    // MAGIC METHODS

    // had to move them in Developer and Game classes since they use static var like $json_items that have differents values in Dev and Game
    

    //----------------------------------------------------------------------------------
    // REVIEWS

    /**
     * Do stuffs when a profile passed a review
     * @param  string $review  Review type
     * @param  string $profile Profile type
     */
    public function passed_review($review, $profile)
    {
        $password = get_random_string(10);

        if ($review == 'submission') {
            $this->privacy = 'private';
        } elseif ($review == 'publishing') {
            $this->privacy = 'public';
            
            // is the user now a trusted user ? send a mail :;
            $this->user->is_trusted(true);
        }

        $this->approved_by = '';
        $this->review_start_date = '0000-00-00 00:00:00';
        $this->save();

        // email's text :
        // it explain that the profile has passed the reviewand what can they do with it
        // if the review was 'submission'
        $html = lang('emails.'.$profile.'_passed_'.$review.'_review', array(
            'name' => $this->name,
            'id' => $this->id,
            'password' => $password,
        ));

        $email = $this->user->email;
        // @TODO : send mails 
    }

    /**
     * Do stuffs when a profile failed a review
     * @param  string $review  Review type
     * @param  string $profile Profile type
     */
    public function failed_review($review, $profile)
    {
        if ($review == 'submission') {
            if ($profile == 'developer') User::delete($this->user->id);
            $this->delete();
        } elseif ($review == 'publishing') {
            $this->privacy = 'private';
            $this->approved_by = '';
            $this->review_start_date = '0000-00-00 00:00:00';
            $this->save();

            // email's text :
            $html = lang('emails.'.$profile.'_failed_'.$review.'_review', array(
                'name' => $this->name,
                'id' => $this->id,
            ));

            $email = $this->user->email;

            // @TODO : send mails with 
        }
    }


    //----------------------------------------------------------------------------------
    // GETTERS
    
    //----------------------------------------------------------------------------------

    // for Former bundle
    public function __toString()
    {
        $name = $this->name; // developer and game

        if (is_null($name)) $name = $this->username; // user

        return $name;
    }
}