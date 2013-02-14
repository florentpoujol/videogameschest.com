<?php

class Admin_Controller extends Base_Controller 
{
    public function get_index()
    {
        $this->layout->nest('page_content', 'logged_in/adminhome');
    }


    //----------------------------------------------------------------------------------
    // REGISTER USER ACCOUNT

    public function get_register_page() 
    {
        $this->layout->nest('page_content', 'register');
    }

    public function post_register() 
    {
        $input = Input::all();

        $rules = array(
            'username' => 'required|alpha_dash_extended|min:2|unique:users',
            'email' => 'required|min:5|email|unique:users',
            'password' => 'required|min:5|confirmed',
            'password_confirmation' => 'required|min:5|required_with:password',

            //'captcha' => 'required|coolcaptcha',
            'recaptcha_response_field' => 'required|recaptcha:'.Config::get('vgc.recaptcha_private_key'),
            'city' => 'honeypot',
        );

        $validation = Validator::make($input, $rules);
        
        if ($validation->passes()) {
            $user = User::create($input);
            return Redirect::to_route('get_home_page');
        }
        
        return Redirect::to_route('get_register_page')->with_errors($validation)->with_input();
    }

    public function get_register_confirmation($user_id, $url_key) 
    {
        $user = User::where_id($user_id)->where_url_key($url_key)->where_activated(0)->first();

        if (is_null($user)) {
            $msg = lang('register.msg.confirmation_error', array(
                'id' => $user_id,
                'url_key' => $url_key,
            ));

            HTML::set_error($msg);
            Log::write('user activation confirmation error', $msg);

            return Redirect::to_route('get_home_page');
        }

        // if user is found
        $user->activate();

        return Redirect::to_route('get_login_page');
    }

    //----------------------------------------------------------------------------------
    // LOGIN

    public function get_login_page() 
    {
        $this->layout->nest('page_content', 'login');
    }

    public function post_login() 
    {
        $rules = array(
            "username" => "required|alpha_dash_extended|min:2",
            "password" => "required|min:5",
            //'captcha' => 'required|coolcaptcha',
            'recaptcha_response_field' => 'required|recaptcha:'.Config::get('vgc.recaptcha_private_key'),
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
                        return Redirect::to_route('get_home_page');
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

        return Redirect::to_route('get_login_page')->with_errors($validation)->with_input();
    }

    public function get_logout()
    {
        HTML::set_success(lang('login.msg.logout_success'));

        $user = user();
        Log::write('user logout', 'User '.$user->username.' (id='.$user->id.') has logged out.');

        Cookie::forget('user_logged_in');
        Auth::logout();
        return Redirect::to_route('get_login_page');
    }

    //----------------------------------------------------------------------------------
    // LOST PASSWORD

    public function get_lostpassword_page() 
    {
        $this->layout->nest('page_content', 'lostpassword');
    }

    public function post_lostpassword()
    {
        $input = Input::all();
        
        $rules = array(
            'lost_password_username' => 'required|min:5',
            'city' => 'honeypot',
            'recaptcha_response_field' => 'required|recaptcha:'.Config::get('vgc.recaptcha_private_key'),
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
            
        return Redirect::to_route('get_lostpassword_page')->with_errors($validation)->with_input();
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

            return Redirect::to_route('get_home_page');
        }

        // if user is found
        $user->setNewPassword(2); // setp 2 : generate new password then send by mail

        return Redirect::to_route('get_login_page');
    }


    // ----------------------------------------------------------------------------------
    // ADD USER

    public function get_user_create()
    {
        $this->layout->nest('page_content', 'logged_in/createuser');
    }

    public function post_user_create()
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
            return Redirect::to_route('get_user_update', array($user->id));
        } else {
            Former::withErrors($validation);
            $this->layout->nest('page_content', 'logged_in/createuser');
        }
    }


    //----------------------------------------------------------------------------------
    // EDIT USER

    public function get_user_update($user_id = null)
    {
        if ($user_id == null || ( ! is_admin() && $user_id != user_id()))
            return Redirect::to_route('get_user_update', array(user_id()));

        if (User::find($user_id) == null) {
            HTML::set_error("Can't find user with id '$user_id' ! Using your user id '".user_id()."'.");
            return Redirect::to_route('get_user_update', array(user_id()));
        }

        $this->layout->nest('page_content', 'logged_in/updateuser', array('user_id'=>$user_id));
    }

    public function post_user_update()
    {
        $input = Input::all();

        if ( ! is_admin()) $input['id'] = user_id();

        $user = User::find($input['id']);
        
        // checking form
        $rules = array(
            'username' => 'required|alpha_dash_extended|min:2',
            'email' => 'required|min:5|email',
            //'url_key' => 'min:10|alpha_num',
        );
        
        $validation = Validator::make($input, $rules);
        
        if ($validation->fails() || ! User::update($input['id'], $input)) {
            return Redirect::to_route('get_user_update', array($user->id))
            ->with_errors($validation)
            ->with_input('except', array('password', 'password_confirmation', 'old_password'));
        }

        return Redirect::to_route('get_user_update', array($user->id));
    }

    public function post_password_update()
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
                'old_password' => 'required|min:5',
            );
            if (is_admin()) unset($rules['old_password']);

            $validation = Validator::make($input, $rules);
        
            if ($validation->fails() || $old_password_ok == false) {
                return Redirect::to_route('get_user_update', array($user->id))
                ->with_errors($validation)
                ->with_input('except', array('password', 'password_confirmation', 'old_password'));
            }

            User::update($input['id'], $input);
        }

        return Redirect::to_route('get_user_update', array($user->id));
    }

    public function post_blacklist_update()
    {
        $input = Input::all();

        if ( ! is_admin()) $input['id'] = user_id();

        User::updateBlacklist($input);
        
        return Redirect::to_route('get_user_update');
    }


    //----------------------------------------------------------------------------------
    // ADD DEVELOPER

    public function get_developer_create()
    {
        $this->layout->nest('page_content', 'logged_in/createdeveloper');
    }

    public function post_developer_create()
    {
        $input = Input::all();

        // checking form
        $rules = array(
            'name' => 'required|alpha_dash_extended|min:2|unique:developers',
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

            return Redirect::to_route('get_developer_update', array($dev->id));
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

        return Redirect::to_route('get_developer_update', array($id));
    }

    public function get_developer_update($profile_id = null)
    {
        $devs = user()->devs;

        if ( ! is_admin() && empty($devs)) {
            return Redirect::to_route('get_developer_create');
        }

        if (is_null($profile_id)) {
            if ( ! is_admin() && count($devs) == 1) { 
                // if user has only one profile, redirect to it, or show the select form
                $profile_id = $devs[0]->id;
                return Redirect::to_route('get_developer_update', array($profile_id));
            }

            $this->layout->nest('page_content', 'forms/selecteditdeveloper');
            return;
        }

        $dev = Dev::find($profile_id);

        if ($dev == null) {
            HTML::set_error(lang('developer.msg.profile_not_found', array('id'=>$profile_id)));
            return Redirect::to_route('get_developer_update');
        }

        if ( ! is_admin() && $dev->user_id != user_id()) {
            HTML::set_error(lang('common.msg.edit_other_users_profile_not_allowed'));
            return Redirect::to_route('get_developer_update');
        }

        // profile ans auth is ok, now get the preview profile

        $this->layout->nest('page_content', 'logged_in/updatedeveloper', array('profile_id'=>$profile_id));
    }

    public function post_developer_update() 
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
            'name' => 'required|alpha_dash_extended|min:2',
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
            return Redirect::to_route('get_developer_update', array($input['id']))->with_errors($validation);
        }

        return Redirect::to_route('get_developer_update', array($input['id']));
    }


    //----------------------------------------------------------------------------------
    // ADD GAME

    public function get_game_create()
    {
        $this->layout->nest('page_content', 'logged_in/creategame');
    }

    public function post_game_create()
    {
        $input = Input::all();

        // checking form
        $rules = array(
            'name' => 'required|alpha_dash_extended|min:2|unique:games',
            'developer_name' => 'required|alpha_dash_extended|min:2',
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
            
            return Redirect::to_route('get_game_update', array($game->id));
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

        return Redirect::to_route('get_game_update', array($id));
    }

    public function get_game_update($profile_id = null)
    {
        $games = user()->games; 
        // can't use user()->games in the condition because it would always return an empty array

        if ( ! is_admin() && empty($games)) {
            return Redirect::to_route('get_game_create');
        }

        if (is_null($profile_id)) {
            if ( ! is_admin() && count($games) == 1) {
                $profile_id = $games[0]->id;
                return Redirect::to_route('get_game_update', array($profile_id));
            }

            $this->layout->nest('page_content', 'forms/selecteditgame');
            return;
        }

        $game = Game::find($profile_id);

        if (is_null($game)) {
            HTML::set_error(lang('game.msg.profile_not_found', array('id'=>$profile_id)));
            return Redirect::to_route('get_game_update');
        }

        if ( ! is_admin() && $game->user_id != user_id()) {
            HTML::set_error(lang('common.msg.edit_other_users_profile_not_allowed'));
            return Redirect::to_route('get_game_update');
        }

        $this->layout->nest('page_content', 'logged_in/updategame', array('profile_id'=>$profile_id));
    }

    public function post_game_update() 
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
                'name' => 'required|alpha_dash_extended|min:2',
                'developer_name' => 'required|alpha_dash_extended|min:2',
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
            return Redirect::to_route('get_game_update', array($input['id']))->with_errors($validation);
        }

        return Redirect::to_route('get_game_update', array($input['id']));
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
        $this->layout->nest('page_content', 'logged_in/reviews', array('review' => $review));
    }

    public function post_reviews()
    {
        if ( ! is_admin()) {
            HTML::set_error(lang('messages.user_not_trusted'));
            return Redirect::to_route('get_home_page');
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
        $reports = array('developer', 'admin');

        if ( ! in_array($report, $reports) || (! is_admin() && $report != 'developer')) {
            return Redirect::to_route('get_reports', array('developer'));
        }

        $this->layout->nest('page_content', 'logged_in/reports', array('report_type' => $report));
    }

    public function post_reports_create()
    {
        $input = Input::all();

        $rules = array(
            'message' => 'required|min:10',
            
        );

        if (is_guest()) {
            $rules['recaptcha_response_field'] = 'required|recaptcha:'.Config::get('vgc.recaptcha_private_key');
            $rules['city'] = 'honeypot';
        }

        $validation = Validator::make($input, $rules);

        if ($validation->passes()) {
            Report::create($input);
            return Redirect::back();
        }

        return Redirect::back()->with_errors($validation)->with_input();
    }

    public function post_reports_update()
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
