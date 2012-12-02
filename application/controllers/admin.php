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

    /*
     * Login form
     */
    public function get_login() 
    {
        $this->layout->nest('page_content', 'admin.login');
    }

    /**
     * Process the login form
     */
	public function post_login() 
	{
        $rules = array(
            "username" => "required",
            "password" => "required|min:5"
        );

        $validation = Validator::make(Input::all(), $rules);
        //Input::flash(); // in case of errors
          
        if ($validation->passes()) 
        {
            $username = Input::get("username", '');
            $field = "username";

            if (is_numeric($username)) {
                $field = "id";
            } elseif (strpos($username, "@")) { // the name is actually an email
                $field = "email";
            }

            $user = User::where($field, '=', $username)->first();
            
            if ($user != null)
            {
                if (Auth::attempt(array('username' => $user->username, 'password' => Input::get('password')))) {
                    return Redirect::to_route('get_admin_home');
                }
                else {
                    HTML::set_error("The password provided for user $field [$username] is incorrect.");
                }
            }
            else {
                HTML::set_error("No user with the $field [$username] has been found.");
            }
        } // end form is valid
        else {
            Former::withErrors($validation);
        }
        
		$this->layout->nest('page_content', 'admin/login');
	}

    /**
     * Process the lost password form
     */
    public function post_lostpassword()
    {
        $validation = Validator::make(Input::all(), array("username" => "required"));
        
        if ($validation->passes()) 
        {
            $username = Input::get("username", '');
            $field = "username";

            if (is_numeric($username)) {
                $field = "id";
            } elseif (strpos($username, "@")) { // the name is actually an email
                $field = "email";
            }
            
            $user = User::where($field, '=', $username)->first();
            
            if ($user != null)
            {
                // send email here
                $email = $user->email;
                HTML::set_success('An email with your credentials and a new temporary password has been sent to '.$email.'.');
            }
            else {
                HTML::set_error("No user with the $field [$username] has been found.");
            }

            return Redirect::to_route('get_login');
        } // end form is valid
        else {
            // Former::withErrors($validation);
            return Redirect::to_route('get_login')->with_errors($validation);
        }
    }

    /*
     * Disconnect the user
     */
    public function get_logout()
    {
        Auth::logout();
    	return Redirect::to_route('get_login');
    }


    // ----------------------------------------------------------------------------------

    /**
     * Page to add a user account
     */
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
        
        if ($validation->passes()) 
        {
            $user = User::create($input);
            return Redirect::to_route('get_edituser', array($user->id));
        }
        else {
            Former::withErrors($validation);
            $this->layout->nest('page_content', 'admin/adduser');
        }
    }


    //----------------------------------------------------------------------------------

    /**
     * Page to edit a user account
     */
    public function get_edituser($user_id = null)
    {
        if ($user_id == null || (IS_DEVELOPER && $user_id != USER_ID))
            return Redirect::to_route('get_edituser', array(USER_ID));

        if (User::find($user_id) == null) {
            HTML::set_error("Can't find user with id '$user_id' ! Using your user id '".USER_ID."'.");
            return Redirect::to_route('get_edituser', array(USER_ID));
        }

        $this->layout->nest('page_content', 'admin/edituser', array('user_id'=>$user_id));
    }

    public function post_edituser()
    {
        $input = Input::all();
        $input['password'] = trim($input['password']);
        $user = User::find($input['id']);
        
        // checking form
        $rules = array(
            'username' => 'required|min:5',
            'email' => 'required|min:5|email',
            'type' => 'required|in:dev,admin'
        );
        
        $validation = Validator::make($input, $rules);
        
        if ($validation->passes()) 
        {
            if ($input['password'] != '')
            {
                $old_password_ok = true;
                if ( ! Hash::check($input['old_password'], $user->password)) {
                    $old_password_ok = false;
                    HTML::set_error('The old password does not match your currently stored password !');
                }

                $rules = array(
                    'password' => 'required|min:5|confirmed',
                    'password_confirmation' => 'required|min:5',
                    'old_password' => 'required|min:5',
                );

                $pass_validation = Validator::make($input, $rules);
            
                if ($pass_validation->fails() || $old_password_ok == false) {
                    Input::flash('except', array('password', 'password_confirmation', 'old_password'));
                    return Redirect::to_route('get_edituser', array($user->id))->with_errors($pass_validation);
                }
            }

            User::update($input['id'], $input);
        }
        else {
            Input::flash('except', array('password', 'password_confirmation', 'old_password'));
            return Redirect::to_route('get_edituser', array($user->id))->with_errors($validation);
        }

        return Redirect::to_route('get_edituser', array($user->id));
        // $this->layout->nest('page_content', 'admin/edituser');
    }


    //----------------------------------------------------------------------------------

    /**
     * Check the data from the select developer form then redirect to the edit developer page
     */
    public function post_selecteditdeveloper()
    {
        $name = Input::get('dev_name');
        $id = null;
        
        if (is_numeric($name))
        {
            if (Profile::find($name, 'dev') == null) {
                HTML::set_error('No developer with id ['.$name.'] was found !');
            }
            else {
                $id = $name;
            }
        }
        else 
        {
            $profile = Profile::where('type', '=', 'dev')->where('name', '=', $name)->first();
            
            if ($profile == null) {
                HTML::set_error('No developer with name ['.$name.'] was found !');
            }
            else {
                $id = $profile->id;
            }
        }

        return Redirect::to_route('get_editdeveloper', array($id));
    }

    /**
     * Page to edit a developer profile account
     * @param  id $profile_id Profile's id
     */
    public function get_editdeveloper($profile_id = null)
    {
        if ($profile_id == null)
        {
            if (IS_DEVELOPER) {
                return Redirect::to_route('get_editdeveloper', array(DEV_PROFILE_ID));
            }
            else
            {
                $this->layout->nest('page_content', 'admin/selecteditdeveloper');
                return;
            }
        }

        if (IS_DEVELOPER && $profile_id != DEV_PROFILE_ID)
        {
            HTML::set_error("You are not allowed to edit other developer's profiles !");
            return Redirect::to_route('get_editdeveloper', array(DEV_PROFILE_ID));
        }

        var_dump($profile_id);
        //var_dump(Profile::find($profile_id, 'dev'));

        if (Profile::find($profile_id, 'dev') == null)
        {
            // $profile_id was set but no dev profile was found
            // this should only happens when user is an admin
            // since dev with bad dev profile id are already redirected

            if (IS_DEVELOPER) 
            {
                HTML::set_error("Can't find the developer profile with id '$profile_id' !");
                HTML::set_error('Remember that you are not allowed to edit other developer\'s profiles !');
                return Redirect::to_route('get_editdeveloper', array(DEV_PROFILE_ID));
            }
            else
            {
                HTML::set_error("Can't find the developer profile with id '$profile_id' !");
                return Redirect::to_route('get_editdeveloper');
            }
        }

        $this->layout->nest('page_content', 'admin/editdeveloper', array('profile_id'=>$profile_id));
    }

    public function post_editdeveloper()
    {

    }


    //----------------------------------------------------------------------------------

    /**
     * Check the data from the select game form then redirect to the edit game page
     */
    public function post_selecteditgame()
    {
        $name = Input::get('game_name');
        
        if (is_numeric($name))
        {
            $id = $name;

            if (Profile::find($id, 'game') == null)
            {
                HTML::set_error('No game with id ['.$id.'] was found !');
                return Redirect::to_route('get_admin_home');
            }
        }
        else 
        {
            $profile = Profile::where('type', '=', 'game')->where('name', '=', $name)->first();
            
            if ($profile == null) 
            {
                HTML::set_error('No game with name ['.$name.'] was found !');
                return Redirect::to_route('get_admin_home');
            }

            $id = $profile->id;
        }

        return Redirect::to_route('get_editgame', array($id));
    }
}
