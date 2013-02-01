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

        // password
        if (isset($input["password"]) && trim($input["password"]) != "") {
            $input["password"] = Hash::make($input["password"]);
        } else {
            // not use anymore since all users set their password when registering

            // dummy password
            $input['password'] = Hash::make(Config::get('dummie_password'));
            // the password will be updated by hand via th edituser page or
            // when a random password will be generated when the dev profile will 
            // have passed the submission review (in ExtendedEloquent.passed_review())
        }
        
        
        $input["url_key"] = Str::random(40);

        if ( ! isset($input['type'])) $input['type'] = 'user';
        if ($input['type'] == 'admin') $input['is_trusted'] = 1;

        $input['crosspromotion_active'] = 1;


        $user = parent::create($input);

        // Log
        HTML::set_success(lang('register.msg.register_success', array('username'=>$user->username)));

        Log::write('user create success', 'New user created (id='.$user->id.') (username='.$user->username.') (email='.$user->email.')');

        // email
        $link = URL::to_route('get_register_confirmation', array(
            'user_id' => $user->id,
            'url_key' => $user->url_key
        ));

        $subject = lang('emails.register_confirmation.subject');

        $html = lang('emails.register_confirmation.html', array(
            'username' => $user->username,
            'link' => $link
        ));

        sendMail($user->email, $subject, $html);

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

        $msg = lang('register.msg.confirmation_success', array('username' => $this->username));
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
                'login_link' => URL::to_route('get_login'),
            ));

            sendMail($this->email, $subject, $html);
        }
    }


    public static function updateBlacklist($input)
    {
        $user = User::find($input['id']);
        $profile_type = $input['profile_type'];
        $profile_type_plural = $profile_type.'s';

        // adding profiles in the blacklist
        if (isset($input['add'])) {
            $ids = array();
            $names = explode(',', $input['name']);
            $names = array_map('trim', $names);

            foreach ($names as $name) {
                
                if (is_numeric($name)) {
                    $profile = $profile_type::where_id($name)->first('id');
                }
                elseif (is_string($name)) {
                    $profile = $profile_type::where_name($name)->first('id');
                } 

                if ( ! is_null($profile)) {
                    $ids[] = $profile->id;
                } else {
                    // message
                }
            }

            $list = $user->blacklist;
            $original_count = count($list[$profile_type_plural]);

            for ($i = 0; $i < count($ids); $i++) { 
                if ( ! in_array($ids[$i], $list[$profile_type_plural])) {
                    $list[$profile_type_plural][] = $ids[$i];
                }
            }
            
            $diff = count($list[$profile_type_plural]) - $original_count;
            
            $user->blacklist = $list;
            $user->save();

            HTML::set_success(lang('blacklist.msg.add_success', array(
                'num' => $diff,
                'type' => $profile_type,
            )));

            Log::write('user blacklist add success', 'User (name : '.user()->name.') (id : '.user_id().') added '.$diff.' '.$profile_type.' to the blacklist of user (name : '.$user->username.') (id : '.$user->id.').');
        }

        if (isset($input['delete']) && isset($input['ids_to_delete'])) {
            $ids = $input['ids_to_delete'];

            $list = $user->blacklist;
            $original_count = count($list[$profile_type_plural]);

            for ($i = 0; $i < count($ids); $i++) { 
                $key = array_search($ids[$i], $list[$profile_type_plural]);

                if ($key !== false) { // the value was found
                    unset($list[$profile_type_plural][$key]);
                }
            }

            $list[$profile_type_plural] = array_values($list[$profile_type_plural]); // rebuilt the indexes

            $diff = $original_count - count($list[$profile_type_plural]);

            $user->blacklist = $list;
            $user->save();

            HTML::set_success(lang('blacklist.msg.delete_success', array(
                'num' => $diff,
                'type' => $profile_type,
            )));

            Log::write('user blacklist delete success', 'User (name : '.user()->name.') (id : '.user_id().') deleted '.$diff.' '.$profile_type.' from the blacklist of user (name : '.$user->username.') (id : '.$user->id.').');
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
                $type .= 's';

                if ( ! array_key_exists($type, $list)) {
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
            $type .= 's';

            if ( ! array_key_exists($type, $list)) {
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
        return $this->developers();
    }

    public function games()
    {
        return $this->has_many('Game');
    }

    /**
     * Get all reports of the specified type for all profiles linked to this user
     * 
     * NOTE : This method does not return a relationships, it's a regular method
     * So it can't be called without the parenthesis 
     * and the get() or first() methods are not needed when called with parameters
     * 
     * @param  string $type The report type
     * @return array       The array of Report model
     */
    public function reports($type = null, $order = 'asc')
    {
        if ($type == 'asc' || $type == 'desc') {
            $order = $type;
            $type = null;
        }

        $profiles = $this->devs;
        $profiles = array_merge($profiles, $this->games);

        $reports = array();
        foreach ($profiles as $profile) {
            $reports = array_merge($reports, $profile->reports($type)->get());
        }

        // $reports are ordered by dev_id and game_id then report_id
        
        // now we are ordering them by created_at
        $date_report = array();

        foreach ($reports as $report) {
            if ( ! array_key_exists($report->created_at, $date_report)) {
                $date_report[$report->created_at] = array(); // this allow to have several reports with the exact same creation date (higtly unlikely)
            }

            $date_report[$report->created_at][] = $report;
        }

        // asort() put the first at the begining, that means older dates are put first
        if ($order == 'asc') asort($date_report);
        else arsort($date_report);

        $ordered_reports = array();

        foreach ($date_report as $reports) {
            $ordered_reports = array_merge($ordered_reports, $reports);
        }

        return $ordered_reports;
    }

    public function promotionFeed()
    {
        return $this->has_one('PromotionFeed');
    }

    public function promotionEmail()
    {
        return $this->has_one('PromotionEmail');
    }
}   
