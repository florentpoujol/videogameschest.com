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
            'city' => 'honeypot',
        );
        $validation = Validator::make($input, $rules);
        
        if ($validation->passes()) {
            $user = User::create($input);
            return Redirect::to_route('get_home_page');
        }
        
        return Redirect::to_route('get_register_page')->with_errors($validation)->with_input();
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

    public function post_lostpassword()
    {
        $input = Input::all();
        
        $rules = array(
            'lost_password_username' => 'required|min:5',
            'city' => 'honeypot',
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
            
        return Redirect::to_route('get_login_page')->with_errors($validation)->with_input();
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
            'username' => 'required|min:5|alpha_dash_extended|unique:users',
            'email' => 'required|min:5|email|unique:users',
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
        
        $rules = array(
            'username' => 'required|min:5|alpha_dash_extended',
            'email' => 'required|min:5|email',
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
        if ( ! is_admin()) $input['id'] = user_id();
        $user = User::find($input['id']);

        $input['password'] = trim($input['password']);
        
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
    // ADD PROFILE
    
    public function get_profile_create($profile_type)
    {
        $this->layout->nest('page_content', 'logged_in/create'.$profile_type);
    }

    public function post_profile_create($profile_type)
    {
        $input = Input::all();
        $rules = Config::get('profiles_post_create_rules.'.$profile_type, array());
        $validation = Validator::make($input, $rules);
        
        if ($validation->passes()) {
            $profile = $profile_type::create($input);
            return Redirect::to_route('get_profile_update', array($profile_type, $profile->id));
        }

        return Redirect::back()->with_errors($validation)->with_input();
    }


    //----------------------------------------------------------------------------------
    // EDIT PROFILE

    public function post_profile_select($profile_type)
    {
        $input = Input::all();
        $name = $input['name'];
        $id = null;
        
        if (is_numeric($name)) {
            if ($profile_type::find($name) === null) {
                HTML::set_error(lang('profile.msg.profile_not_found', array(
                    'profile_type' => $profile_type,
                    'field_name' => 'id',
                    'field_value' => $id,
                )));
            } else { // $id is set in an else block so that it stays null when there is an error, and user is redirected to the select form
                $id = $name;
            }
        } else {
            $profile = $profile_type::where_name($name)->first();
            
            if ($profile === null) {
                HTML::set_error(lang('profile.msg.profile_not_found', array(
                    'profile_type' => $profile_type,
                    'field_name' => 'name',
                    'field_value' => $name,
                )));
            } else {
                $id = $profile->id;
            }
        }

        return Redirect::to_route('get_profile_update', array($profile_type, $id));
    }

    public function get_profile_update($profile_type, $profile_id = null)
    {
        $profiles = user()->{$profile_type.'s'}; 
        // can't use user()->profiles in the condition because it would always return an empty array

        if ( ! is_admin() && empty($profiles)) {
            return Redirect::to_route('get_profile_create', $profile_type);
        }

        if ($profile_id == null) {
            if ( ! is_admin() && count($profiles) == 1) {
                return Redirect::to_route('get_profile_update', array($profile_type, $profiles[0]->id));
            }

            $this->layout->nest('page_content', 'forms/profile_select', array('profile_type' => $profile_type));
            return;
        }

        $profile = $profile_type::find($profile_id);

        if ($profile === null) {
            HTML::set_error(lang('profile.msg.profile_not_found', array(
                'profile_type' => $profile_type,
                'field_name' => 'id',
                'field_value' => $profile_id
            )));

            return Redirect::to_route('get_profile_update', $profile_type);
        }

        if ( ! is_admin() && $profile->user_id != user_id()) {
            HTML::set_error(lang('common.msg.edit_other_users_profile_not_allowed'));
            return Redirect::to_route('get_profile_update', $profile_type);
        }

        $this->layout->nest('page_content', 'logged_in/update'.$profile_type, array('profile_id' => $profile_id));
    }

    public function post_profile_update($profile_type) 
    {
        $input = Input::all();

        if ( ! is_admin()) {
            // check that $input['id'] is one of the user's game profiles
            $forged = true;
            foreach (user()->{$profile_type} as $profile) {
                if ($profile->id == $input['id']) $forged = false;
            }

            if ($forged) { // a user try to edit a dev profile which does not own
                HTML::set_error(lang('common.msg.edit_other_users_proile_not_allowed'));
                return Redirect::back();
            }
        }

        // checking form
        $rules = Config::get('vgc.profiles_post_update_rules.'.$profile_type);
        $validation = Validator::make($input, $rules);
        
        if ( ! $validation->passes() || ! $profile_type::update($input['id'], $input)) {
            Input::flash();
            return Redirect::to_route('get_profile_update', array($profile_type, $input['id']))->with_errors($validation);
        }

        return Redirect::to_route('get_profile_update', array($profile_type, $input['id']));
    }


    //----------------------------------------------------------------------------------
    // VIEW PROFILE
    
    public function get_profile_preview($profile_type, $profile_id)
    {
        $profile = $profile_type::find($profile_id);
        $preview_profile = $profile->preview_profile;

        if ($preview_profile === null) {
            HTML::set_error("Preview profile not found for $profile_type profile '".$profile->name."' (id='".$profile->id."').");
            return Redirect::to_route('get_home_page');
        }

        if (is_admin() || $profile->user_id == user_id()) {
            $profile->update_with_preview_data();

            $this->layout
            ->with('preview_profile', true) // for the layout
            ->with('profile', $profile)
            ->nest('page_content', $profile_type, array('profile' => $profile, 'preview' => true));
        } else {
            HTML::set_error(lang('common.msg.access_not_allowed'));
            return Redirect::to_route('get_home_page');
        }
    }

    public function get_profile_view($profile_type, $name = null)
    {        
        if ($name == 'create') {
            return $this->get_profile_create($profile_type);
        }

        if (is_numeric($name)) {
            $profile = $profile_type::find($name);
            return Redirect::to_route('get_profile_view', array($profile_type, name_to_url($profile->name)));
        }

        $profile = $profile_type::where_name(url_to_name($name))->first();

        if (is_null($profile)) {
            $field_name = 'name';
            if (is_numeric($name)) $field_name = 'id';

            HTML::set_error(lang('profile.msg.profile_not_found', array(
                'type' => $profile_type,
                'field_name' => $field_name,
                'field_value' => $name
            )));

            return Redirect::to_route('get_search_page');
        }

        if ($profile->privacy == 'public' || is_admin() || $profile->user_id == user_id()) {
            $this->layout
            ->with('profile', $profile)
            ->nest('page_content', $profile_type, array('profile' => $profile));
        } else {
            HTML::set_error(lang('common.msg.access_not_allowed', array('page' => $profile_type.' profile '.$name)));
            return Redirect::to_route('get_search_page');
        }
    }


    //----------------------------------------------------------------------------------
    // REVIEWS

    public function get_review($review = null)
    {
        if ( ! is_admin()) {
            HTML::set_error(lang('common.msg.admin_only'));
            return Redirect::to_route('get_home_page');
        }

        $this->layout->nest('page_content', 'logged_in/review');
    }

    public function post_review()
    {
        if ( ! is_admin()) {
            HTML::set_error(lang('common.msg.admin_only'));
            return Redirect::to_route('get_home_page');
        }

        $input = Input::all();

        if ( ! isset($input['approved_profiles'])) $input['approved_profiles'] = array();

        $num = 0;
        foreach ($input['approved_profiles'] as $profile_type => $approved_profiles) {
            foreach ($approved_profiles as $id) {
                $profile_type::find($id)->passed_review();
                $num++;
            }
        }

        if ($num > 0) {
            HTML::set_success(lang('review.msg.profiles_approved', array('num' => $num)));

            Log::write('admin success review '.$input['review_type'],
            user()->type." '".user()->name."' (id='".user_id()."') has approved $num profiles in review.");
        }
        
        return Redirect::back();
    }


    //----------------------------------------------------------------------------------
    // REPORTS

    public function get_reports($report = null)
    {

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
            //$rules['recaptcha_response_field'] = 'required|recaptcha:'.Config::get('vgc.recaptcha_private_key');
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
