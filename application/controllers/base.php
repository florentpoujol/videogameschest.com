<?php

class Base_Controller extends Controller {

	/**
	 * Catch-all method for requests that can't be matched.
	 *
	 * @param  string    $method
	 * @param  array     $parameters
	 * @return Response
	 */
	public function __call($method, $parameters)
	{
		return Response::error('404');
	}


	//----------------------------------------------------------------------------------

	public $layout = 'layout.main';


	//----------------------------------------------------------------------------------

	/**
	 * Set some constant
	 */
	public function __construct()
	{
		parent::__construct();



        // echo Request::uri();
        // echo '<br>';
        // echo Request::method();
        //define("CONTROLLER", $this->router->fetch_class());
        //define("METHOD", $this->router->fetch_method());
	}
}