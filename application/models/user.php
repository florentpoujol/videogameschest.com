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

        if ( ! isset($input['type'])) $input['type'] = 'user';

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


    /**
     * 
     */
    public static function updateBlacklist($input)
    {
        $user = User::find($input['user_id']);
        $profile_type = $input['profile_type'];
        $profile_type_plural = $profile_type.'s';
        $blacklist = $user->blacklist;

        // adding profiles in the blacklist
        if (isset($input['add'])) {
            $ids = array();
            $names = explode(',', $input['profile_names']);

            foreach ($names as $name) {
                $name = trim($name);

                if (is_numeric($name)) {
                    $profile = $profile_type::where_id($name)->first('id');
                } elseif (is_string($name)) {
                    $profile = $profile_type::where_name($name)->first('id');
                }

                if ($profile !== null) {
                    $ids[] = $profile->id;
                } else {
                    $msg = lang('vgc.blacklist.msg.profile_name_or_id_not_found', array(
                        'profile_type' => $profile_type,
                        'name' => $name,
                    ));
                    HTML::set_error($msg);

                    if (is_guest()) {
                        Log::write('user blacklist add error', "User with id=".$input['user_id']." (guest, form url) : ".$msg);
                    } else {
                        Log::write('user blacklist add error', "User with name='".user()->name."' and id=".user_id()." : ".$msg);
                    }
                }
            }

            $original_count = count($blacklist[$profile_type_plural]);
            foreach ($ids as $id) {
                if ( ! in_array($id, $blacklist[$profile_type_plural])) {
                    $blacklist[$profile_type_plural][] = $id;
                }
            }
            $diff = count($blacklist[$profile_type_plural]) - $original_count;
            $user->blacklist = $blacklist;
            $user->save();

            $action = 'set_success';
            if ($diff == 0) $action = 'set_info';
            HTML::$action(lang('blacklist.msg.add_success', array(
                'num' => $diff,
                'type' => $profile_type,
            )));

            if (is_guest()) {
                Log::write('user blacklist add success', "User with id=".$input['user_id']." (guest, form url) added $diff $profile_type to the blacklist of user with id=".$input['user_id'].".");
            } elseif (is_admin()) {
                Log::write('user blacklist add success', "Admin with name='".user()->name."' and id=".user_id()." added $diff $profile_type to the blacklist of user with id=".$input['user_id'].".");
            } else {
                Log::write('user blacklist add success', "User with name='".user()->name."' and id=".user_id()." added $diff $profile_type to its blacklist.");
            }
        }

        if (isset($input['delete']) && isset($input['ids_to_delete'])) {
            $ids = $input['ids_to_delete'];
            $original_count = count($blacklist[$profile_type_plural]);
            foreach ($ids as $id) {
                $key = array_search($id, $blacklist[$profile_type_plural]);
                if ($key !== false) { // the value was found
                    unset($blacklist[$profile_type_plural][$key]);
                }
            }
            $blacklist[$profile_type_plural] = array_values($blacklist[$profile_type_plural]); // rebuilt the indexes
            $diff = $original_count - count($blacklist[$profile_type_plural]);
            $user->blacklist = $blacklist;
            $user->save();

            $action = 'set_success';
            if ($diff == 0) $action = 'set_info';
            HTML::$action(lang('blacklist.msg.delete_success', array(
                'num' => $diff,
                'type' => $profile_type,
            )));

            if (is_guest()) {
                Log::write('user blacklist delete success', "User with id=".$input['user_id']." (guest, form url) deleted $diff $profile_type from the blacklist of user with id=".$input['user_id'].".");
            } elseif (is_admin()) {
                Log::write('user blacklist delete success', "Admin with name='".user()->name."' and id=".user_id()." deleted $diff $profile_type from the blacklist of user with id=".$input['user_id'].".");
            } else {
                Log::write('user blacklist delete success', "User with name='".user()->name."' and id=".user_id()." deleted $diff $profile_type from its blacklist.");
            }
        }
    }


    //----------------------------------------------------------------------------------
    // GETTERS

    public function get_name()
    {
        return $this->get_attribute('username');
    }

    public function get_blacklist()
    {
        $list = json_decode($this->get_attribute('blacklist'), true);
        if ( ! is_array($list)) $list = array();

        foreach (Config::get('vgc.profile_types') as $type) {
            $type .= 's';
            if ( ! isset($list[$type])) $list[$type] = array();
        }

        return $list;
    }

    public function set_blacklist($blacklist)
    {
        $this->set_attribute('blacklist', json_encode($blacklist));
    }


    //----------------------------------------------------------------------------------
    // RELATIONSHIPS

    public function games()
    {
        return $this->has_many('Game');
    }

    /**
     * Get all reports for all profiles linked to this user
     * 
     * NOTE : This method does not return a relationships, it's a regular method
     * So it can't be called without the parenthesis 
     * and the get() or first() methods are not needed when called with parameters
     * 
     * @return array       The array of Report model
     */
    public function reports($order = 'asc')
    {
        $profiles = $this->games;

        $reports = array();
        foreach ($profiles as $profile) {
            $reports = array_merge($reports, $profile->reports);
        }

        // $reports are ordered by game_id then report_id
        
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

    public function PromotionFeed()
    {
        return $this->has_many('PromotionFeed');
    }

    public function PromotionNewsletter()
    {
        return $this->has_many('PromotionNewsletter');
    }
}   
