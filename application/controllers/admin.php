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
            'username' => 'required|min:5|unique:users',
            'email' => 'required|min:5|email|unique:users',
            'password' => 'required|min:5|confirmed',
            'password_confirmation' => 'min:5|required|required_with:password',
        );

        $validation = Validator::make($input, $rules);
        
        if ($validation->passes()) {
            $user = User::create($input);
            return Redirect::to_route('get_home');
        } else {
            Former::withErrors($validation);
            $this->layout->nest('page_content', 'register');
        }
    }

    public function get_register_confirmation($user_id, $temp_key) 
    {
        $user = User::where_id($user_id)->where_temp_key($temp_key)->where_activated(0)->first();

        if (is_null($user)) {
            $msg = lang('register.msg_confirmation_error', array(
                'id' => $user_id,
                'temp_key' => $temp_key,
            ));
            html::set_error($msg);
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
            "username" => "required",
            "password" => "required|min:5"
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
                      'username' => $username
                    )));
                }
            } else {
                HTML::set_error(lang('login.msg.user_not_found', array(
                    'field' => $field,
                    'username' => $username
                )));
            }
        } else {
            Former::withErrors($validation);
        }
        
        return Redirect::to_route('get_login');
    }

    

    public function post_lostpassword()
    {
        $input = Input::all();
        $validation = Validator::make($input, array("username" => "required"));
        
        if ($validation->passes()) {
            $username = $input["username"];
            $field = "username";

            if (strpos($username, "@")) { // the name is actually an email
                $field = "email";
            }
            
            $user = User::where($field, '=', $username)->first();
            
            if ($user != null) {
                $user->set_new_password(1); // step 1 : send conf email to user
            } else {
                HTML::set_error(lang('login.msg.user_not_found', array(
                    'field' => $field,
                    'username' => $username
                )));
            }

            return Redirect::to_route('get_login');
        } else {
            return Redirect::to_route('get_login')->with_errors($validation);
        }
    }

    public function get_lostpassword_confirmation($user_id, $temp_key)
    {
        $user = User::where_id($user_id)->where_temp_key($temp_key)->where_activated(1)->first();

        if (is_null($user)) {
            $msg = lang('lostpassword.msg.confirmation_error', array(
                'id' => $user_id,
                'temp_key' => $temp_key,
            ));
            HTML::set_error($msg);
            Log::write('user lostpassword confirmation error', $msg);

            return Redirect::to_route('get_home');
        }

        // if user is found
        $user->set_new_password(2); // setp 2 : generate new password then send by mail

        return Redirect::to_route('get_login');
    }

    public function get_logout()
    {
        HTML::set_success(lang('login.logout_success'));

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
        $input['password'] = trim($input['password']);

        if ( ! is_admin()) $input['id'] = user_id();

        $user = User::find($input['id']);
        
        // checking form
        $rules = array(
            'username' => 'required|min:5',
            'email' => 'required|min:5|email',
        );
        
        $validation = Validator::make($input, $rules);
        
        if ($validation->passes()) {
            if ($input['password'] != '') {
                $old_password_ok = true;
                if ( ! Hash::check($input['old_password'], $user->password)) {
                    $old_password_ok = false;
                    HTML::set_error(lang('user.wrong_old_password'));
                }

                $rules = array(
                    'password' => 'required|min:5|confirmed',
                    'password_confirmation' => 'required|min:5|required_with:password',
                    'old_password' => 'required|min:5',
                );

                $pass_validation = Validator::make($input, $rules);
            
                if ($pass_validation->fails() || $old_password_ok == false) {
                    Input::flash('except', array('password', 'password_confirmation', 'old_password'));
                    return Redirect::to_route('get_edituser', array($user->id))->with_errors($pass_validation);
                }
            }

            User::update($input['id'], $input);
        } else {
            Input::flash('except', array('password', 'password_confirmation', 'old_password'));
            return Redirect::to_route('get_edituser', array($user->id))->with_errors($validation);
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
            'name' => 'required|min:5|unique:developers',
            'email' => 'min:5|email',
            'logo' => 'url',
            'website' => 'url',
            'blogfeed' => 'url',
            'presskit' => 'url',
            'teamsize' => 'min:1',
        );

        $validation = Validator::make($input, $rules);
        
        if ($validation->passes()) {
            $dev = Dev::create($input);

            return Redirect::to_route('get_editdeveloper', array($dev->id));
        } else {
            Input::flash();
            return Redirect::back()->with_errors($validation);
        }
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
        if ( ! is_admin() && empty(user()->devs)) {
            return Redirect::to_route('get_adddeveloper');
        }

        if ($profile_id == null) {
            $this->layout->nest('page_content', 'admin/selecteditdeveloper');
            return;
        }

        $dev = Dev::find($profile_id);

        if ($dev == null) {
            HTML::set_error(lang('developer.msg.profile_not_found'));
            return Redirect::to_route('get_editdeveloper');
        }

        if ( ! is_admin() && $dev->user_id != user_id()) {
            HTML::set_error(lang('common.msg.edit_other_users_proile_not_allowed'));
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
            foreach ($user()->devs() as $dev) {
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
            'name' => 'required|min:5',
            'logo' => 'url',
            'website' => 'url',
            'blogfeed' => 'url',
            'presskit' => 'url',
            'teamsize' => 'min:1'
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
            'name' => 'required|min:5|unique:games',
            'developer_id' => 'required|exists:developers,id',
            'logo' => 'url',
            'website' => 'url',
            'blogfeed' => 'url',
            'soundtrackurl' => 'url',
            'publishername' => 'min:2|required_with:publisherurl',
            'publisherurl' => 'url|required_with:publishername',
            'price' => 'min:0',
        );

        $validation = Validator::make($input, $rules);
        
        if ($validation->passes()) {
            $game = Game::create($input);
            
            if (is_admin())
                return Redirect::to_route('get_editgame', array($game->id));
            else
                return Redirect::to_route('get_home');
        } else {
            Input::flash();
            return Redirect::back()->with_errors($validation);
        }
    }


    //----------------------------------------------------------------------------------
    // EDIT GAME
   
    public function post_selecteditgame()
    {
        $id = Input::get('game_id', null);

        if (Game::find($id) == null) {
            HTML::set_error('No game with id ['.$id.'] was found !');
        }

        return Redirect::to_route('get_editgame', array($id));
    }

    public function get_editgame($profile_id = null)
    {
        if ($profile_id == null) {
            $this->layout->nest('page_content', 'admin/selecteditgame');
            return;
        }

        $game = Game::find($profile_id);

        if ($game == null) {
            // $profile_id was set but no game profile was found
            HTML::set_error(lang('messages.game_profile_not_found', array('profile_id'=>$profile_id)));
            return Redirect::to_route('get_editgame');
        }

        if (! is_admin() && $game->developer_id != DEVELOPER_ID) {
            HTML::set_error(lang('messages.can_not_edit_others_games'));
            return Redirect::to_route('get_editgame');
        }

        $this->layout->nest('page_content', 'admin/editgame', array('profile_id'=>$profile_id));
    }

    public function post_editgame() 
    {
        $input = Input::all();
        
        // checking form
        $rules = array(
            'name' => 'required|min:5',
            'logo' => 'url',
            'website' => 'url',
            'blogfeed' => 'url',
            'presskit' => 'url',
            'soundtrackurl' => 'url',
            'publishername' => 'min:2',
            'publisherurl' => 'url|required_with:publishername',
        );
        
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
        if ( ! IS_TRUSTED) {
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
        if ( ! IS_TRUSTED) {
            HTML::set_error(lang('messages.user_not_trusted'));
            return Redirect::to_route('get_admin_home');
        }

        $input = Input::all();
        $profile_ids = $input['approved_profiles'];

        if (is_admin()) {
            foreach ($input['approved_profiles'] as $id) {
                $input['profile_type']::find($id)->passed_review();
            }
        } else {
            $num = 0;
            foreach ($input['approved_profiles'] as $id) {
                $profile = $input['profile_type']::find($id);

                $approved_by = $profile->approved_by;
                $approved_by[] = user_id();
                $profile->approved_by = $approved_by;
                $profile->save();
                $num++;
            }

            HTML::set_success(lang('reviews.msg.profiles_approved', array('num' => $num)));

        }

        return Redirect::back();
    }


    //----------------------------------------------------------------------------------
    // REPORTS

    public function get_reports($report = null)
    {
        $reports = array('dev', 'admin');

        if ( ! in_array($report, $reports)) {
            return Redirect::to_route('get_reports', array('dev'));
        }

        if (! is_admin() && $report != 'dev') {
            return Redirect::to_route('get_reports', array('dev'));
        }

        $this->layout->nest('page_content', 'admin.reports', array('report_type' => $report));
    }

    public function post_reports()
    {
        $input = Input::all();

        if ($input['action'] == 'create') {
            $rules = array('message' => 'required|min:10');

            $validation = Validator::make($input, $rules);

            if ($validation->passes()) {
                Report::create($input);
            } else {
                Input::flash();
                return Redirect::back()->with_errors($validation);
            }
        }

        elseif ($input['action'] == 'delete') {
            foreach ($input['reports'] as $report_id) {
                Report::find($report_id)->delete();
            }
        
            HTML::set_success(lang('reports.msg.delete_success'));
        }

        return Redirect::back();
    }
} // end of Admin controller class
