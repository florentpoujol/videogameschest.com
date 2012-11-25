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
        // password
        if (isset($user["password"]) && trim($user["password"]) != "") {
            $user["password"] = Hash::make($user["password"]);
        }
        unset($user['password_confirmation']);

        // secret key
        $user["key"] = md5(mt_rand().''.mt_rand());

        while (User::where('key', '=', $user['key'])->first() != null) {
            $user["key"] = md5(mt_rand().''.mt_rand());
        }

        $user['key'] = 'testkey';

        $user = parent::create($user);

        if ($user->type == "dev") { // create at the same time the developer profile
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
        unset($attributes['password_confirmation']);
        unset($attributes['oldpassword']);

        if ($attributes['password'] != '')
            $attributes['password'] = Hash::make($attributes['password']);
        else
            unset($attributes['password']);


        $user = User::find($id);
        // update user's data
        foreach ($attributes as $field => $attr) {
            $user->$field = $attr;
        }

        $user->id = $id; // why the fuck do I have to do that
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