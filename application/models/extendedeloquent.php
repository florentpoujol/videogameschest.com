<?php

class ExtendedEloquent extends Eloquent
{
    public static $timestamps = true;

    public static $json_items = array();
    public static $array_items = array();
    public static $names_urls_items = array();

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
        if (in_array($key, static::$json_items)) {
            if (in_array($key, static::$names_urls_items)) {
                $value = clean_names_urls_items($value);
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
        if (in_array($key, static::$json_items)) return json_decode($this->get_attribute($key), true);
        else return parent::__get($key);
    }
    

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
            
            // check if the user is now thrusted and send a mail
            // if ($profile == 'developer') $this->user->is_trusted(true);
            // elseif( $profile == 'game') $this->dev->user->is_trusted(true);
            $this->user->is_trusted(true);
        }

        $this->approved_by = '';
        $this->review_start_date = '0000-00-00 00:00:00';
        $this->save();

        // email's text :
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