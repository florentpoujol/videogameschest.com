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
        
        
        $input["url_key"] = Str::random(20);

        // type
        if ( ! isset($input['type'])) $input['type'] = 'user';
        if ($input['type'] == 'admin') $input['is_trusted'] = 1;
        $input['crosspromotion_active'] = 1;
        $input['blacklist'] = array('developers' => array(), 'games' => array());


        $user = parent::create($input);

        // Log
        HTML::set_success(lang('register.msg_register_success', array('username'=>$user->username)));
        Log::write('user create success', 'New user created (id='.$user->id.') (username='.$user->username.') (email='.$user->email.') (temp_key='.$user->temp_key.')');

        // email
        $link = URL::to_route('get_register_confirmation', array(
            'user_id' => $user->id,
            'url_key' => $user->url_key
        ));

        $text = lang('emails.register_confirmation', array(
            'username' => $user->username,
            'link' => $link
        ));

        sendMail($user->email, lang('emails.register_confirmation_subject'), $text);

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

        //$input['url_key'] = str_replace('/', '', $input['url_key']);

        parent::update($id, $input);
        $user = User::find($id);

        if ($user->id != user_id()) $msg = 'The user \"'.$user->username.'\" (id : '.$user->id.') has successfully been updated.';
        else $msg = lang('user.msg.update_success');
        HTML::set_success($msg);
        Log::write('user update success', 'The user \"'.$user->username.'\" (id : '.$user->id.') has successfully been updated.');

        return $user;
    }


    //----------------------------------------------------------------------------------

    public function activate() 
    {
        $this->activated = 1;
        $this->save();

        $msg = lang('register.msg_confirmation_success', array('username' => $this->username));
        HTML::set_success($msg);
        Log::write('user activation confirmation success', $msg);
    }

    public function setNewPassword($step = 2) 
    {
        // step 1 : send conf email to user
        if ($step == 1) {
            // message
            HTML::set_success(lang('lostpassword.msg.confirmation_email_sent'));
            Log::write('user lostpassword info', 'User "'.$this->username.'" asked for a new password. (id='.$this->id.') (email='.$this->email.') (url_key='.$this->url_key.')');

            // email
            $link = URL::to_route('get_lostpassword_confirmation', array($this->id, $this->url_key));

            $text = lang('emails.lostpassword_confirmation', array(
                'username' => $this->username,
                'link' => $link
            ));

            sendMail($this->email, lang('emails.lostpassword_confirmation_subject'), $text);
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
            $text = lang('emails.lostpassword_success', array(
                'username' => $this->username,
                'password' => $password,
                'login_link' => URL::to_route('get_login'),
            ));

            sendMail($this->email, lang('emails.lostpassword_success_subject'), $text);
        }
    }


    public static function updateBlacklist($input)
    {
        $user = User::find($input['id']);

        if ( ! is_null($user)) {
            $profile_type = $input['profile_type'];

            if (isset($input['add'])) {
                $ids = array();
                $names = explode(',', $input['name']);

                foreach ($names as $name) {
                    if (is_string($name)) {
                        $profile = $profile_type::where_name(trim($name))->get('id');
                    } elseif (is_numeric($name)) {
                        $profile = $profile_type::where_id($name)->get('id');
                    }

                    if ( ! is_null($profile)) {
                        dd($profile);
                        $ids[] = $profile->id;
                    } else {
                        // message
                    }
                }

                $list = $user->blacklist;
                $list[$profile_type.'s'] = array_merge($list[$profile_type.'s'], $ids);
                $user->blacklist = $list;
                $user->save();

                HTML::set_error(lang('blacklist.msg.add_success', array(
                    'num' => $diff,
                    'type' => $profile_type,
                )));
                Log::write('user blacklist add success', 'User (name : '.user()->name.') (id : '.user_id().') added '.$diff.' '.$profile_type.' to the blacklist of user (name : '.$user->username.') (id : '.$user->id.').');
            }

            if (isset($input['delete']) && isset($input['ids_to_delete'])) {
                $list = $user->blacklist;
                $ids_to_delete = $input['ids_to_delete'];
                $before_num = count($list[$profile_type]);

                $list[$profile_type.'s'] = array_diff($list[$profile_type.'s'], $ids_to_delete);
                // array_diff() returns the difference between two arrays
                // in that case, it will be the ids found in $list[$profile_type] but not in $ids_to_delete (if $ids_to_delete does not contains id that are not in $list[$profile_type])
                // which means that array_diff() will have substracted $ids_to_delete from $list[$profile_type]

                $diff = $before_num - count($list[$profile_type.'s']);

                $user->blacklist = $list;
                $user->save();

                HTML::set_error(lang('blacklist.msg.delete_success', array(
                    'num' => $diff,
                    'type' => $profile_type,
                )));
                Log::write('user blacklist delete success', 'User (name : '.user()->name.') (id : '.user_id().') deleted '.$diff.' '.$profile_type.' from the blacklist of user (name : '.$user->username.') (id : '.$user->id.').');
            }
        } else {
            // message user not found
            HTML::set_error(lang('user.msg.user_not_found', array('id'=>$input['id'])));
            Log::write('user blacklist error', 'User id "'.$input['id'].'" not found when user (name : '.user()->name.') (id : '.user_id().') tried to edit the blacklist.');
        }
    }

    /**
     * Check if the user is now a trusted user, then send a mail if yes
     * @param  boolean $send_mail Do send an email to the user to let him know he is now trusted ?
     */
    /*public function update_trusted($send_mail = false)
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
    }*/

    /*public function update_crosspromotion($new_state)
    {
        $this->crosspromotion_active = $new_state;
        $this->save();

        if ($new_state == 1) {
            HTML::set_success(lang('crosspromotion.msg.activation_success'));
            Log::write('crosspromotion activation success', 'User "'.$this->name.' (id : '.$this->id.') activated the cross-promotion.');
        } elseif($new_state == 0) {
            HTML::set_success(lang('crosspromotion.msg.deactivation_success'));
            Log::write('crosspromotion deactivation success', 'User "'.$this->name.' (id : '.$this->id.') DEactivated the cross-promotion.');
        }
    }*/


    //----------------------------------------------------------------------------------
    // GETTERS

    public function get_name()
    {
        return $this->get_attribute('username');
    }


    public function get_blacklist()
    {
        $list = json_decode($this->get_attribute('blacklist'), true);
        
        if (is_array($list)) {
            foreach (Config::get('vgc.profile_types') as $type) {
                if ( ! array_key_exists($type, $profile_types)) {
                    $list[$type] = array();
                }
            }

            return $list;
        }

        return array('developers' => array(), 'games' => array());
    }

    public function set_blacklist($list)
    {
        foreach (Config::get('vgc.profile_types') as $type) {
            if ( ! array_key_exists($type, $profile_types)) {
                $list[$type] = array();
            }
        }

        $this->set_attribute('blacklist', json_encode($list));
    }


    //----------------------------------------------------------------------------------
    // RELATIONSHIPS

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
