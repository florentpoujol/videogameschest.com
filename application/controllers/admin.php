<?php

class Admin_Controller extends Base_Controller 
{

	public function action_index()
	{
		return View::make('admin/home');
	}

	public function action_login() 
	{
		if (IS_LOGGED_IN) {
            return Redirect::to_route('admin_home');
        }

        $username = Input::get("username", '');

        if (Input::has('admin_login_form_submitted'))
        {
            $field = "username";

            if (is_numeric($username)) {
                $field = "id";
            } elseif (strpos($username, "@")) { // the name is actually an email
                $field = "email";
            }

            $rules = array(
            	"username" => "required",
            	"password" => "required|required_with:username|min:4"
            );

            $validation = Validator::make(Input::all(), $rules);
            Input::flash(); // in case of
              
            if ($validation->passes()) 
            {
                $user = User::where($field, '=', $username)->first();
                
                if ($user != null)
                {
	                if (Auth::attempt(array($field => $username, 'password' => Input::get('password')))) {
	                    return Redirect::to_route('admin_home');
	                }
	                else {
	                    Form::set_error("The password provided for user $field [$username] is incorrect.");
	                }
                }
                else {
                    Form::set_error("No user with the $field [$username] has been found.");
                }
            } // end form is valid
            else {
            	Form::set_error($validation->errors);
            }
        } // end if form submitted

		return View::make('admin/login');
	}


    /*
     * Disconnect the user
     */
    public function action_logout()
    {
        Auth::logout();
    	return Redirect::to_route('admin_login');
    }
}