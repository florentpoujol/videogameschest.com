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
        if (isset($input["password"]) && trim($input["password"]) != "") {
            $input["password"] = Hash::make($input["password"]);
        }        
        
        $input["url_key"] = Str::random(40);

        if ( ! isset($input['type'])) $input['type'] = 'admin';

        $user = parent::create($input);

        // Log
        HTML::set_success(lang('register.msg.register_success', array('username'=>$user->username)));
        Log::write('user create success', 'New user created (id='.$user->id.') (username='.$user->username.') (email='.$user->email.')');

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
        $user = parent::find($id);
        // checking name change
        if (isset($input['username']) && $user->username != $input['username']) { // the user want to change the dev name, must check is the name is not taken
            if (parent::where_username($input['username'])->first() != null) {
                HTML::set_error(
                    lang('user.msg.edituser_nametaken', array(
                        'username' => $user->username,
                        'id' => $user->id,
                        'newname' => $input['username'])
                    )
                );

                return false;
            }
        }

        if (isset($input['password']) && trim($input['password']) != '') $input['password'] = Hash::make($input['password']);
        else unset($input['password']);

        parent::update($id, $input);
        $user = User::find($id);

        $log_msg = "The user '".$user->username."' (id : '".$user->id."') has successfully been updated.";
        if ($user->id != user_id()) $html_msg = $log_msg;
        else $html_msg = lang('user.msg.update_success');
        HTML::set_success($html_msg);
        Log::write('user update success', $log_msg);

        return $user;
    }


    //----------------------------------------------------------------------------------

    /**
     * When the user as lost its password
     * @param integer $step
     */
    public function setNewPassword($step = 2) 
    {
        // step 1 : send conf email to user
        if ($step == 1) {
            // message
            HTML::set_success(lang('lostpassword.msg.confirmation_email_sent'));
            Log::write('user lostpassword info', 'User "'.$this->username.'" asked for a new password. (id='.$this->id.') (email='.$this->email.') (url_key='.$this->url_key.')');

            // email
            $link = URL::to_route('get_lostpassword_confirmation', array($this->id, $this->url_key));
            $subject = lang('emails.lostpassword_confirmation.subject');
            $html = lang('emails.lostpassword_confirmation.html', array(
                'username' => $this->username,
                'link' => $link
            ));

            sendMail($this->email, $subject, $html);
        } 
        
        // setp 2 : generate new password then send by mail
        else {
            $password = Str::random(20);

            $this->password = Hash::make($password);
            $this->save();

            // message
            HTML::set_success(lang('lostpassword.msg.new_password_success'));
            Log::write('user lostpassword success', 'A new password for user "'.$this->username.'" (id='.$this->id.') as successfully been generated.');

            // email
            $subject = lang('emails.lostpassword_success.subject');
            $html = lang('emails.lostpassword_success.html', array(
                'username' => $this->username,
                'password' => $password,
                'login_link' => URL::to_route('get_login_page'),
            ));

            sendMail($this->email, $subject, $html);
        }
    }


    //----------------------------------------------------------------------------------
    // GETTERS

    public function get_name()
    {
        return $this->get_attribute('username');
    }
}   
