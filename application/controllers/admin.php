<?php

class Admin_Controller extends Base_Controller 
{
    public function __construct() 
    {
        parent::__construct();
       
    }

    public function get_index()
    {
        $this->layout->nest('page_content', 'admin/adminhome');
    }


    //----------------------------------------------------------------------------------
    // REGISTER USER ACCOUNT

    public function get_register() 
    {
        $this->layout->nest('page_content', 'register');
    }

    public function post_register() 
    {
        $input = Input::all();

        $rules = array(
            'username' => 'required|alpha_dash|min:2|unique:users',
            'email' => 'required|min:5|email|unique:users',
            'password' => 'required|min:5|confirmed',
            'password_confirmation' => 'required|min:5|required_with:password',
            //'captcha' => 'required|coolcaptcha',
            'city' => 'honeypot',
        );

        $validation = Validator::make($input, $rules);
        
        if ($validation->passes()) {
            $user = User::create($input);
            return Redirect::to_route('get_home');
        }
        
        return Redirect::to_route('get_register')->with_errors($validation)->with_input();
    }

    public function get_register_confirmation($user_id, $url_key) 
    {
        $user = User::where_id($user_id)->where_url_key($url_key)->where_activated(0)->first();

        if (is_null($user)) {
            $msg = lang('register.msg_confirmation_error', array(
                'id' => $user_id,
                'url_key' => $url_key,
            ));

            HTML::set_error($msg);
            Log::write('user activation confirmation error', $msg);

            return Redirect::to_route('get_home');
        }

        // if user is found
        $user->activate();

        return Redirect::to_route('get_login');
    }

    //----------------------------------------------------------------------------------
    // LOGIN

    public function get_login() 
    {
        $this->layout->nest('page_content', 'admin.login');
    }

    public function post_login() 
    {
        $rules = array(
            "username" => "required|alpha_dash|min:2",
            "password" => "required|min:5",
            //'captcha' => 'required|coolcaptcha',
            'city' => 'honeypot',
        );

        $validation = Validator::make(Input::all(), $rules);
          
        if ($validation->passes()) {
            $username = Input::get("username", '');
            $field = "username";

            if (strpos($username, "@")) { // the name is actually an email
                $field = "email";
            }

            $user = User::where($field, '=', $username)->first();
            
            if ($user != null) {
                if ($user->activated == 1) {
                    if (Auth::attempt(array('username' => $user->username, 'password' => Input::get('password')))) {
                        $keep_logged_in = Input::get('keep_logged_in', '0');

                        if ($keep_logged_in == '1') {
                            Cookie::put('user_logged_in', $user->id, 43200); //43200 min = 1 month
                        }

                        HTML::set_success(lang('login.msg.login_success', array(
                            'username' => $user->username
                        )));
                        Log::write('user login', 'User '.$user->username.' (id='.$user->id.') has logged in');
                        return Redirect::to_route('get_admin_home');
                    } else {
                        HTML::set_error(lang('login.msg.wrong_password', array(
                            'field' => $field,
                            'username' => $username
                        )));
                    }
                } else {
                    HTML::set_error(lang('login.msg.not_activated', array(
                        'field' => $field,
                        'username' => $username
                    )));
                }
            } else {
                HTML::set_error(lang('login.msg.user_not_found', array(
                    'field' => $field,
                    'username' => $username
                )));
            }
        }

        return Redirect::to_route('get_login')->with_errors($validation)->with_input();
    }

    public function post_lostpassword()
    {
        $input = Input::all();
        
        $rules = array(
            'lost_password_username' => 'required|min:5',
            'city' => 'honeypot',
            //'lost_password_captcha' => 'required|coolcaptcha',
        );
        
        $validation = Validator::make($input, $rules);
        
        if ($validation->passes()) {
            $username = $input["lost_password_username"];
            $field = "username";

            if (strpos($username, "@")) { // the name is actually an email
                $field = "email";
            }
            
            $user = User::where($field, '=', $username)->first();
            
            if ($user != null) {
                $user->setNewPassword(1); // step 1 : send conf email to user
            } else {
                HTML::set_error(lang('login.msg.user_not_found', array(
                    'field' => $field,
                    'username' => $username
                )));
            }
        }
            
        return Redirect::to_route('get_login')->with_errors($validation)->with_input();
    }

    public function post_editblacklist()
    {
        $input = Input::all();

        if ( ! is_admin()) $input['id'] = user_id();

        User::updateBlacklist($input);
        
        return Redirect::to_route('get_edituser');
    }

    public function get_lostpassword_confirmation($user_id, $url_key)
    {
        $user = User::where_id($user_id)->where_url_key($url_key)->where_activated(1)->first();

        if (is_null($user)) {
            $msg = lang('lostpassword.msg.confirmation_error', array(
                'id' => $user_id,
                'url_key' => $url_key,
            ));
            HTML::set_error($msg);
            Log::write('user lostpassword confirmation error', $msg);

            return Redirect::to_route('get_home');
        }

        // if user is found
        $user->setNewPassword(2); // setp 2 : generate new password then send by mail

        return Redirect::to_route('get_login');
    }

    public function get_logout()
    {
        HTML::set_success(lang('login.msg.logout_success'));

        $user = user();
        Log::write('user logout', 'User '.$user->username.' (id='.$user->id.') has logged out.');

        Cookie::forget('user_logged_in');
        Auth::logout();
        return Redirect::to_route('get_login');
    }


    // ----------------------------------------------------------------------------------
    // ADD USER

    public function get_adduser()
    {
        $this->layout->nest('page_content', 'admin/adduser');
    }

    public function post_adduser()
    {
        $input = Input::all();
        
        // checking form
        $rules = array(
            'username' => 'required|min:5|unique:users',
            'email' => 'required|min:5|unique:users|email',
            'password' => 'required|min:5|confirmed',
            'password_confirmation' => 'required|min:5',
            'type' => 'required|in:dev,admin'
        );

        $validation = Validator::make($input, $rules);
        
        if ($validation->passes()) {
            $user = User::create($input);
            return Redirect::to_route('get_edituser', array($user->id));
        } else {
            Former::withErrors($validation);
            $this->layout->nest('page_content', 'admin/adduser');
        }
    }


    //----------------------------------------------------------------------------------
    // EDIT USER

    public function get_edituser($user_id = null)
    {
        if ($user_id == null || ( ! is_admin() && $user_id != user_id()))
            return Redirect::to_route('get_edituser', array(user_id()));

        if (User::find($user_id) == null) {
            HTML::set_error("Can't find user with id '$user_id' ! Using your user id '".user_id()."'.");
            return Redirect::to_route('get_edituser', array(user_id()));
        }

        $this->layout->nest('page_content', 'admin/edituser', array('user_id'=>$user_id));
    }

    public function post_edituser()
    {
        $input = Input::all();

        if ( ! is_admin()) $input['id'] = user_id();

        $user = User::find($input['id']);
        
        // checking form
        $rules = array(
            'username' => 'required|alpha_dash|min:2',
            'email' => 'required|min:5|email',
            //'url_key' => 'min:10|alpha_num',
        );
        
        $validation = Validator::make($input, $rules);
        
        if ($validation->fails() || ! User::update($input['id'], $input)) {
            return Redirect::to_route('get_edituser', array($user->id))
            ->with_errors($validation)
            ->with_input('except', array('password', 'password_confirmation', 'old_password'));
        }

        return Redirect::to_route('get_edituser', array($user->id));
    }

    public function post_editpassword()
    {
        $input = Input::all();
        $input['password'] = trim($input['password']);

        if ( ! is_admin()) $input['id'] = user_id();

        $user = User::find($input['id']);
        
        // checking form
        if ($input['password'] != '') {
            $old_password_ok = true;
            if ( ! is_admin() && ! Hash::check($input['old_password'], $user->password)) {
                $old_password_ok = false;
                HTML::set_error(lang('user.msg.wrong_old_password'));
            }

            $rules = array(
                'password' => 'required|min:5|confirmed',
                'password_confirmation' => 'required|min:5',
                'oldpassword' => 'required|min:5',
            );
            if (is_admin()) unset($rules['oldpassword']);

            $validation = Validator::make($input, $rules);
        
            if ($validation->fails() || $old_password_ok == false) {
                return Redirect::to_route('get_edituser', array($user->id))
                ->with_errors($validation)
                ->with_input('except', array('password', 'password_confirmation', 'old_password'));
            }

            User::update($input['id'], $input);
        }

        return Redirect::to_route('get_edituser', array($user->id));
    }


    //----------------------------------------------------------------------------------
    // ADD DEVELOPER

    public function get_adddeveloper()
    {
        $this->layout->nest('page_content', 'adddeveloper');
    }

    public function post_adddeveloper()
    {
        $input = Input::all();

        // checking form
        $rules = array(
            'name' => 'required|no_slashes|min:2|unique:developers',
            'email' => 'min:5|email',
            'logo' => 'url',
            'website' => 'url',
            'blogfeed' => 'url',
            'presskit' => 'url',
            'teamsize' => 'integer|min:1'
        );

        $validation = Validator::make($input, $rules);
        
        if ($validation->passes()) {
            $dev = Dev::create($input);

            return Redirect::to_route('get_editdeveloper', array($dev->id));
        }

        return Redirect::back()->with_errors($validation)->with_input();
    }


    //----------------------------------------------------------------------------------
    // EDIT DEVELOPER

    public function post_selecteditdeveloper()
    {
        $name = Input::get('dev_name');
        $id = null;
        
        if (is_numeric($name)) {
            if (Dev::find($name) == null) {
                HTML::set_error(lang('developer.msg.select_editdev_id_not_found', array('id'=>$name)));
            } else {
                $id = $name;
            }
        } else {
            $profile = Dev::where_name($name)->first();
            
            if ($profile == null) {
                HTML::set_error(lang('developer.msg.select_editdev_name_not_found', array('name'=>$name)));
            } else {
                $id = $profile->id;
            }
        }

        return Redirect::to_route('get_editdeveloper', array($id));
    }

    public function get_editdeveloper($profile_id = null)
    {
        $devs = user()->devs;
        // can't use user()->ddevs in the condition because it would always return an empty array

        if ( ! is_admin() && empty($devs)) {
            return Redirect::to_route('get_adddeveloper');
        }

        if (is_null($profile_id)) {
            if ( ! is_admin() && count($devs) == 1) { 
                // if user has only one profile, redirect to it, or show the select form
                $profile_id = $devs[0]->id;
                return Redirect::to_route('get_editdeveloper', array($profile_id));
            }

            $this->layout->nest('page_content', 'admin/selecteditdeveloper');
            return;
        }

        $dev = Dev::find($profile_id);

        if ($dev == null) {
            HTML::set_error(lang('developer.msg.profile_not_found', array('id'=>$profile_id)));
            return Redirect::to_route('get_editdeveloper');
        }

        if ( ! is_admin() && $dev->user_id != user_id()) {
            HTML::set_error(lang('common.msg.edit_other_users_profile_not_allowed'));
            return Redirect::to_route('get_editdeveloper');
        }

        $this->layout->nest('page_content', 'admin/editdeveloper', array('profile_id'=>$profile_id));
    }

    public function post_editdeveloper() 
    {
        $input = Input::all();

        if (is_not_admin()) {
            // check that $input['id'] is one of the user's dev profiles
            $forged = true;
            foreach (user()->devs as $dev) {
                if ($dev->id == $input['id']) $forged = false;
            }

            if ($forged) { // a user try to edit a dev profile which does not own
                HTML::set_error(lang('common.msg.edit_other_users_proile_not_allowed'));
                Input::flash();
                return Redirect::back();
            }
        }
        
        // checking form
        $rules = array(
            'name' => 'required|no_slashes|min:2',
            'email' => 'email',
            'logo' => 'url',
            'website' => 'url',
            'blogfeed' => 'url',
            'presskit' => 'url',
            'teamsize' => 'integer|min:1'
        );
        
        $validation = Validator::make($input, $rules);
        
        if ( ! $validation->passes() || ! Dev::update($input['id'], $input)) {
            Input::flash();
            return Redirect::to_route('get_editdeveloper', array($input['id']))->with_errors($validation);
        }

        return Redirect::to_route('get_editdeveloper', array($input['id']));
    }


    //----------------------------------------------------------------------------------
    // ADD GAME

    public function get_addgame()
    {
        $this->layout->nest('page_content', 'addgame');
    }

    public function post_addgame()
    {
        $input = Input::all();

        // checking form
        $rules = array(
            'name' => 'required|no_slashes|min:2|unique:games',
            'developer_name' => 'required|no_slashes|min:2',
            'developer_url' => 'url',
            'publisher_name' => 'min:2',
            'publisher_url' => 'url',
            'website' => 'url',
            'blogfeed' => 'url',
            'presskit' => 'url',

            'profile_background' => 'url',
            'cover' => 'url',
            'soundtrack' => 'url',
        );

        $validation = Validator::make($input, $rules);
        
        if ($validation->passes()) {
            $game = Game::create($input);
            
            return Redirect::to_route('get_editgame', array($game->id));
        }

        return Redirect::back()->with_errors($validation)->with_input();
    }


    //----------------------------------------------------------------------------------
    // EDIT GAME
   
    public function post_selecteditgame()
    {
        $name = Input::get('game_name');
        $id = null;
        
        if (is_numeric($name)) {
            if (Game::find($name) == null) {
                HTML::set_error(lang('game.msg.select_editgame_id_not_found', array('id'=>$name)));
            } else {
                $id = $name;
            }
        } else {
            $profile = Game::where_name($name)->first();
            
            if ($profile == null) {
                HTML::set_error(lang('game.msg.select_editgame_name_not_found', array('name'=>$name)));
            } else {
                $id = $profile->id;
            }
        }

        return Redirect::to_route('get_editgame', array($id));
    }

    public function get_editgame($profile_id = null)
    {
        $games = user()->games; 
        // can't use user()->games in the condition because it would always return an empty array

        if ( ! is_admin() && empty($games)) {
            return Redirect::to_route('get_addgame');
        }

        if (is_null($profile_id)) {
            if ( ! is_admin() && count($games) == 1) {
                $profile_id = $games[0]->id;
                return Redirect::to_route('get_editgame', array($profile_id));
            }

            $this->layout->nest('page_content', 'admin/selecteditgame');
            return;
        }

        $game = Game::find($profile_id);

        if (is_null($game)) {
            HTML::set_error(lang('game.msg.profile_not_found', array('id'=>$profile_id)));
            return Redirect::to_route('get_editgame');
        }

        if ( ! is_admin() && $game->user_id != user_id()) {
            HTML::set_error(lang('common.msg.edit_other_users_profile_not_allowed'));
            return Redirect::to_route('get_editgame');
        }

        $this->layout->nest('page_content', 'admin/editgame', array('profile_id'=>$profile_id));
    }

    public function post_editgame() 
    {
        $input = Input::all();
        
        if (is_not_admin()) {
            // check that $input['id'] is one of the user's game profiles
            $forged = true;
            foreach (user()->games as $game) {
                if ($game->id == $input['id']) $forged = false;
            }

            if ($forged) { // a user try to edit a dev profile which does not own
                HTML::set_error(lang('common.msg.edit_other_users_proile_not_allowed'));
                return Redirect::back();
            }
        }

        // checking form
        if (isset($input['name'])) { // general pane
            $rules = array(
                'name' => 'required|no_slashes|min:2',
                'developer_name' => 'required|no_slashes|min:2',
                'developer_url' => 'url',
                'publisher_name' => 'min:2',
                'publisher_url' => 'url',
                'website' => 'url',
                'blogfeed' => 'url',
                'presskit' => 'url',
            );
        } else {
            $rules = array(
                'profile_background' => 'url',
                'cover' => 'url',
                'soundtrack' => 'url',
            );
        }
        
        $validation = Validator::make($input, $rules);
        
        if ( ! $validation->passes() || ! Game::update($input['id'], $input)) {
            Input::flash();
            return Redirect::to_route('get_editgame', array($input['id']))->with_errors($validation);
        }

        return Redirect::to_route('get_editgame', array($input['id']));
    }


    //----------------------------------------------------------------------------------
    // REVIEWS

    public function get_reviews($review = null)
    {
        if ( ! is_admin()) {
            HTML::set_error(lang('messages.user_not_trusted'));
            return Redirect::to_route('get_admin_home');
        }

        $review_types = Config::get('vgc.review.types');

        if ( ! in_array($review, $review_types)) {
            return Redirect::to_route('get_reviews', array(head($review_types)));
        }

        // return View::make('admin.reviews')->with();
        $this->layout->nest('page_content', 'admin.reviews', array('review' => $review));
    }

    public function post_reviews()
    {
        if ( ! is_admin()) {
            HTML::set_error(lang('messages.user_not_trusted'));
            return Redirect::to_route('get_admin_home');
        }

        $input = Input::all();

        
        if ( ! isset($input['approved_profiles'])) $input['approved_profiles'] = array();
        // if ( ! isset($input['approved_games'])) $input['approved_games'] = array();

        if (is_admin()) {
            $num = 0;
            foreach ($input['approved_profiles'] as $profile_type => $approved_profiles) {
                foreach ($approved_profiles as $id) {
                    $profile_type::find($id)->passed_review();
                    $num++;
                }
            }

            if ($num > 0) {
                HTML::set_success(lang('reviews.msg.profiles_approved', array('num' => $num)));

                Log::write('admin success review '.$input['review_type'],
                'Admin (name : '.user()->name.') (id : '.user_id().') has approved '.$num.' profiles in '.$input['review_type'].' review.');
            }
        } else {
            /*foreach ($input['approved_profiles'] as $id) {
                $profile = $input['profile_type']::find($id);

                $approved_by = $profile->approved_by;
                $approved_by[] = user_id();
                $profile->approved_by = $approved_by;
                $profile->save();
            }

            $num = count($input['approved_profiles']);
            HTML::set_success(lang('reviews.msg.profiles_approved', array('num' => $num)));
            Log::write('approve review '.$input['profile_type'].' '.$input['review_type'],
            'User (name : '.user()->name.') (id : '.user_id().') has approved '.$num.' profiles in '.$input['review_type'].' review.');
            */
        }

        return Redirect::back();
    }


    //----------------------------------------------------------------------------------
    // REPORTS

    public function get_reports($report = null)
    {
        $reports = array('dev', 'admin');

        if ( ! in_array($report, $reports) || (! is_admin() && $report != 'dev')) {
            return Redirect::to_route('get_reports', array('dev'));
        }

        $this->layout->nest('page_content', 'admin.reports', array('report_type' => $report));
    }

    public function post_addreport()
    {
        $input = Input::all();

        $rules = array(
            'message' => 'required|min:10',
            
        );

        $validation = Validator::make($input, $rules);

        if ($validation->passes()) {
            Report::create($input);
            return Redirect::back();
        }

        return Redirect::back()->with_errors($validation)->with_input();
    }

    public function post_editreports()
    {
        $reports = Input::get('reports', array());

        if (! empty($reports)) {
            foreach ($reports as $report_id) {
                Report::find($report_id)->delete();
            }
            
            HTML::set_success(lang('reports.msg.delete_success'));
        }
        return Redirect::back();
    }

} // end of Admin controller class
