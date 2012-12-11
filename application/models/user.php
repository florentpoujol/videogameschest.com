<?php

class User extends ExtendedEloquent 
{

	//----------------------------------------------------------------------------------

    /**
     * Create a new user
     * @param  array $form data comming from the form, or the Developer::create() method
     * @return User       The user instance
     */
	public static function create($user) 
	{
        unset($user['csrf_token']);
        unset($user['password_confirmation']);

        // display success msg ?
        $display_msg = true;

        if (isset($user['do_not_display_success_msg'])) {
            $display_msg = false;
        }

        unset($user['do_not_display_success_msg']);

        // password
        if (isset($user["password"]) && trim($user["password"]) != "") {
            $user["password"] = Hash::make($user["password"]);
        } else {
            // @TODO : generate an random password
            // NO > do that only when the profile will have passed the submission review
            
            // $user['password'] = Hash::make('testtest');
        }
        
        // secret key
        $user["secret_key"] = md5(mt_rand().mt_rand());

        while (User::where('secret_key', '=', $user['secret_key'])->first() != null) {
            $user["secret_key"] = md5(mt_rand().mt_rand());
        }

        // type
        if ( ! isset($user['type']))
            $user['type'] = 'dev';

        $user = parent::create($user);
        

        // @TODO send mail to user with password, if field do_not_send_email is set

        if ($display_msg) {
            HTML::set_success('The user with name \''.$user->username.'\' has successfully been created.');
        }

        return $user;
    }


    /**
     * Update a user
     * @param  int $id         The user id
     * @param  array $attributes The user's data
     * @return User The user instance
     */
    public static function update($id, $attributes)
    {
        unset($attributes['csrf_token']);

        /*$user = parent::find($id);
        
        foreach ($attributes as $field => $attr) {
            $user->$field = $attr;
        }

        $user->save();*/
        $user = parent::update($id, $attributes);

        HTML::set_success('The user \"'.$user->username.'\" (id : '.$user->id.') has successfully been updated.');
        return $user;
    }


    //----------------------------------------------------------------------------------

    /**
     * Check if the user is now a trusted user, then send a mail if yes
     * @param  boolean $send_mail Do send an email to the user to let him know he is now trusted ?
     * @return boolean            Wether or not the user is trusted
     */
    public function is_trusted($send_mail = false)
    {
        if ($this->type == 'admin') return true;

        $is_trusted = false;

        if ($this->dev->privacy == 'public' && ! is_null($this->dev->games)) {   
            foreach ($this->dev->games as $game) {
                if ($game->privacy == 'public') {
                    $is_trusted = true;
                    break;
                }
            }
        }

        return $is_trusted;
    }



    //----------------------------------------------------------------------------------
    // RELATIONSHIPS

    /**
     * Relationship method with the Profiles table
     * @return Profile The Profiles instance linked to this user
     */
    public function developer()
    {
        return $this->has_one('Developer');
    }

    public function dev() 
    {
        return $this->developer();
    }

    public function games() 
    {
        return $this->developer()->games;
    }
}   
