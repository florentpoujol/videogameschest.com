<?php

class User extends ExtendedEloquent 
{

	//----------------------------------------------------------------------------------
    // CRUD METHODS

    /**
     * Create a new user
     * @param  array $input data comming from the form, or the Developer::create() method
     * @return User       The user instance
     */
	public static function create($input) 
	{
        $input = clean_form_input($input);
        unset($input['do_not_display_success_msg']);

        // password
        if (isset($input["password"]) && trim($input["password"]) != "") {
            $input["password"] = Hash::make($input["password"]);
        } else {
            // dummy password
            $input['password'] = Hash::make(Config::get('dummie_password'));
            // the password will be updated by hand via th edituser page or
            // when a random password will be generated when the dev profile will 
            // have passed the submission review (in ExtendedEloquent.passed_review())
        }
        
        // secret key
        $input["url_key"] = Str::random(20);
        /*$try = 0;
        while ($try < 10000 && parent::where('secret_key', '=', $input['secret_key'])->first() != null) {
            $input["secret_key"] = Str::random(20);
            $try++;
        }*/

        // type
        if ( ! isset($input['type'])) $input['type'] = 'user';

        if ($input['type'] == 'admin') $input['is_trusted'] = 1;

        // temp key
        $input['temp_key'] = Str::random(20);

        $user = parent::create($input);

        // Log
        HTML::set_success(lang('register.msg_register_success', array('username'=>$user->username)));
        Log::write('user create success', 'New user created (id='.$user->id.') (username='.$user->username.') (email='.$user->email.') (temp_key='.$user->temp_key.')');

        // email
        $link = URL::to_route('get_register_confirmation', array(
            'user_id' => $user->id,
            'temp_key' => $user->temp_key
        ));

        $text = lang('email.register_confirmation', array(
            'username' => $user->username,
            'link' => $link
        ));

        sendMail($user->email, 'mail.register_confirmation', $text);

        return $user;
    }

    /**
     * Update a user
     * @param  int $id         The user id
     * @param  array $input The user's data
     * @return User The user instance
     */
    public static function update($id, $input)
    {
        $input = clean_form_input($input);

        if ($input['password'] != '') $input['password'] = Hash::make($input['password']);
        else unset($input['password']);

        if ( ! isset($input['crosspromotion_subscription'])) $input['crosspromotion_subscription'] = 0;
        else $input['crosspromotion_subscription'] = 1;

        $input['url_key'] = str_replace('/', '', $input['url_key']);

        parent::update($id, $input);
        $user = User::find($id);

        if ($user->id != user_id()) $msg = 'The user \"'.$user->username.'\" (id='.$user->id.') has successfully been updated.';
        else $msg = lang('user.msg_update_success');
        HTML::set_success($msg);
        Log::write('user update success', 'The user \"'.$user->username.'\" (id='.$user->id.') has successfully been updated.');

        return $user;
    }


    //----------------------------------------------------------------------------------

    public function activate() 
    {
        $this->activated = 1;
        $this->temp_key = '';
        $this->save();

        $msg = lang('register.msg_confirmation_success', array('username' => $this->username));
        html::set_success($msg);
        Log::write('user activation confirmation success', $msg);
    }

    public function set_new_password($step = 2) 
    {
        // step 1 : send conf email to user
        if ($step == 1) {
            $this->temp_key = Str::random(20);
            $this->save();

            // message
            HTML::set_success(lang('lostpassword.msg.confirmation_email_sent'));
            Log::write('user lostpassword info', 'User "'.$this->username.'" asked for a new password. (id='.$this->id.') (email='.$this->email.') (temp_key='.$this->temp_key.')');

            // email
            $link = URL::to_route('get_lostpassword_confirmation', array($this->id, $this->temp_key));

            $text = lang('email.lostpassword_confirmation', array(
                'username' => $this->username,
                'link' => $link
            ));

            sendMail($this->email, lang('email.lostpassword_confirmation_subject'), $text);
        } 
        
        // setp 2 : generate new password then send by mail
        else {
            $password = Str::random(20);

            $this->password = Hash::make($password);
            $this->temp_key = '';
            $this->save();

            // message
            HTML::set_success(lang('lostpassword.msg.new_password_success'));
            Log::write('user lostpassword success', 'A new password for user "'.$this->username.'" (id='.$this->id.') as successfully been generated.');

            // email
            $text = lang('email.lostpassword_success', array(
                'username' => $this->username,
                'password' => $password,
                'login_link' => URL::to_route('get_login'),

            ));
            sendMail($this->email, lang('email.lostpassword_success_subject'), $text);
        }
    }

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
    // GETTERS

    public function get_name()
    {
        return $this->get_attribute('username');
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

    public function developers()
    {
        return $this->has_many('Developer');
    }

    public function devs() 
    {
        return $this->has_many('Developer');
    }

    public function games()
    {
        return $this->has_many('Game');
    }
}   
