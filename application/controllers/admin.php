<?php

class Admin_Controller extends Base_Controller 
{

	public function action_index()
	{
		return "admin index";
	}

	public function action_login($name = 'Florent') 
	{
		return View::make('login_form')->with('name', $name);
	}

}