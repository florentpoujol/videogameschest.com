<?php

class User extends Eloquent {
	
    public static $timestamps = true;

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

        $display_msg = true;
        if (isset($user['do_not_display_success_msg']))
            $display_msg = false;
        unset($user['do_not_display_success_msg']);

        // password
        if (isset($user["password"]) && trim($user["password"]) != "") {
            $user["password"] = Hash::make($user["password"]);
        }
        else {
            // @TODO : generate an random password
            $user['password'] = Hash::make('testtest');
        }
        
        // secret key
        $user["secret_key"] = md5(mt_rand().mt_rand());

        while (User::where('secret_key', '=', $user['secret_key'])->first() != null) {
            $user["secret_key"] = md5(mt_rand().mt_rand());
        }

        if ( ! isset($user['type']))
            $user['type'] = 'dev';

        $user = parent::create($user);
        
        // @TODO send mail to user with password

        if ($display_msg) {
            HTML::set_success('The user with name \''.$user->username.'\' has successfully been created.');
        }

        return $user;
    }


    //----------------------------------------------------------------------------------

    /**
     * Update a user
     * @param  int $id         The user id
     * @param  array $attributes The user's data
     * @return User The user instance
     */
    public static function update($id, $attributes)
    {
        unset($attributes['csrf_token']);



        $dev = Dev::find($id);
        
        foreach ($attributes as $field => $attr) {
            $user->$field = $attr;
        }

        $dev->save();

        HTML::set_success('The user \"'.$user->username.'\" (id : '.$user->id.') has successfully been updated.');
        return $user;
    }

	
    //----------------------------------------------------------------------------------

    /**
     * Relationship method with the Profiles table
     * @return Profile The Profiles instance linked to this user
     */
    public function developer()
    {
        return $this->has_one('Developer');
    }
}   