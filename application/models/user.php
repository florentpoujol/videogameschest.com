<?php

class User extends ExtendedEloquent 
{

	//----------------------------------------------------------------------------------
    // CRUD METHODS

    /**
     * Create a new user
     * @param  array $form data comming from the form, or the Developer::create() method
     * @return User       The user instance
     */
	public static function create($user) 
	{
        $user = clean_form_input($user);

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
            // dummy password
            $user['password'] = Hash::make(Config::get('dummie_password'));
            // the password will be updated by hand via th edituser page or
            // when a random password will be generated when the dev profile will 
            // have passed the submission review (in ExtendedEloquent.passed_review())
        }
        
        // secret key
        $user["secret_key"] = md5(mt_rand().mt_rand());

        while (parent::where('secret_key', '=', $user['secret_key'])->first() != null) {
            $user["secret_key"] = md5(mt_rand().mt_rand());
        }

        // type
        if ( ! isset($user['type'])) $user['type'] = 'dev';

        if ($user['type'] == 'admin') $user['is_trusted'] = 1;


        $user = parent::create($user);

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
    public static function update($id, $form)
    {
        $form = clean_form_input($form);

        if ($form['password'] != '') $form['password'] = Hash::make($form['password']);
        else unset($form['password']);

        parent::update($id, $form);
        $user = User::find($id);

        HTML::set_success('The user \"'.$user->username.'\" (id : '.$user->id.') has successfully been updated.');
        return $user;
    }


    //----------------------------------------------------------------------------------

    /**
     * Check if the user is now a trusted user, then send a mail if yes
     * @param  boolean $send_mail Do send an email to the user to let him know he is now trusted ?
     */
    public function update_trusted($send_mail = false)
    {
        $is_trusted = false;

        if ($this->dev->privacy == 'public' && ! is_null($this->dev->games)) {   
            foreach ($this->dev->games as $game) {
                if ($game->privacy == 'public') {
                    $is_trusted = true;
                    break;
                }
            }
        }

        $this->is_trusted = $is_trusted;
        $this->save();

        if ($is_trusted && $send_mail) {
            // @TODO send mail "You are now a trusted user, you have acces to the peer review !"
        }
    }


    //----------------------------------------------------------------------------------
    // RELATIONSHIPS

    public function developer()
    {
        return $this->has_one('Developer');
    }

    public function dev() 
    {
        return $this->has_one('Developer');
    }
}   
