<?php

class User extends Eloquent {
	
    public static $timestamps = true;

	//----------------------------------------------------------------------------------

    /**
     * Create a new user
     * @param  array $form data comming from the form
     * @return User       The user instance
     */
	public static function create($user) 
	{
        unset($user['csrf_token']);
        unset($user['password_confirmation']);

        // password
        if (isset($user["password"]) && trim($user["password"]) != "") {
            $user["password"] = Hash::make($user["password"]);
        }
        
        // secret key
        $user["secret_key"] = md5(mt_rand().mt_rand());

        while (User::where('secret_key', '=', $user['secret_key'])->first() != null) {
            $user["secret_key"] = md5(mt_rand().mt_rand());
        }


        $user = parent::create($user);

        if ($user->type == "dev") // create at the same time the developer profile
        { 
            $user->profiles()->insert(array(
                'name' => $user->username,
                'type' => 'dev',
            ));
        }
        
        // @TODO send mail to user with password
        HTML::set_success('The user \"'.$user->username.'\" (id : '.$user->id.') has successfully been created.');
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
        //unset($attributes['id']);
        unset($attributes['csrf_token']);
        unset($attributes['password_confirmation']);
        unset($attributes['old_password']);

        if ($attributes['password'] != '')
            $attributes['password'] = Hash::make($attributes['password']);
        else
            unset($attributes['password']);


        $user = User::find($id);
        
        foreach ($attributes as $field => $attr) {
            $user->$field = $attr;
        }

        $user->save();

        HTML::set_success('The user \"'.$user->username.'\" (id : '.$user->id.') has successfully been updated.');
        return $user;
    }

	
    //----------------------------------------------------------------------------------

    /**
     * Relationship method with the Profles table
     * @return [type] [description]
     */
    public function profiles()
    {
        return $this->has_many('Profile');
    }
}   