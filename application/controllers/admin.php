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

	public function post_login() 
	{
        if (Input::has('lostpassword'))
        {
            $validation = Validator::make(Input::all(), array("username" => "required"));
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
                    // send email here
                    $email = $user->email;
                    HTML::set_success('An email with your credentials and a new temporary password has been sent to '.$email.'.');
                }
                else {
                    HTML::set_error("No user with the $field [$username] has been found.");
                }
            } // end form is valid
            else {
                Former::withErrors($validation);
            }
        }

        elseif (Input::has('login_form_submitted'))
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
                    if (Auth::attempt(array($field => $username, 'password' => Input::get('password')))) {
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
        }
        
		$this->layout->nest('page_content', 'admin/login');
	}


    /*
     * Disconnect the user
     */
    public function get_logout()
    {
        Auth::logout();
    	return Redirect::to_route('get_admin_login');
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
        unset($input['csrf_token']);

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
            // HTML::set_error($validation->errors);
            Former::withErrors($validation);
            // just reload the form and let the form_validation class display the errors
            $form["password"] = "";
            $form["password_confirmation"] = "";

            $this->layout->nest('page_content', 'admin/adduser', array('form' => $form));
        }
    }


    //----------------------------------------------------------------------------------

    /**
     * Page to edit a user account
     */
    public function get_edituser($user_id = null)
    {
        if (IS_ADMIN && isset($user_id)) {
            $user = User::find($user_id);
        }
        else {
            $user = User::find(USER_ID);
        }
        
        $this->layout->nest('page_content', 'admin/edituser', array('user'=>$user));
    }

    public function post_edituser()
    {
        //$form = Input::get('form');
        $input = Input::all();
        unset($input['csrf_token']);
        $user = User::find($input['id']);
        
        // checking form
        $rules = array(
            'username' => 'required|min:5',
            'email' => 'required|min:5|email',
            'password' => 'min:5|confirmed',
            'password_confirmation' => 'min:5|required_with:password',
            'oldpassword' => 'min:5|required_with:password',
            'type' => 'required|in:dev,admin'
        );
        
        $validation = Validator::make($form, $rules);
        
        if ($validation->passes()) 
        {
            //@todo   : make sure the new username or email is unique
            // $rules = array();
            
            // $validation = Validator::make($form, $rules);


            if (trim($form['oldpassword']) != '' && ! Hash::check($form['oldpassword'], User::find($form['id'])->password)) {
                $old_password_ok = false;
                HTML::set_error('The old password does not match your currently stored password !');
            }
            else {
                User::update($form['id'], $form);
            }
        }
        /*else {
            Former::withErrors($validation);
            //Input::flash('except', array('password', 'password_confirmation', 'oldpassword'));
        }*/
        
        return Redirect::to_route('get_edituser', array($user->id))->with_errors($validation);

        /*$form["password"] = "";
        $form["password_confirmation"] = "";
        $form["oldpassword"] = "";
        $this->layout->nest('page_content', 'admin/edituser', array("form"=>$form));*/
    }
}
