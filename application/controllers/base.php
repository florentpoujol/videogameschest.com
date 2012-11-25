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

	public $layout = 'layouts.main';
	public $restful = true;

	//----------------------------------------------------------------------------------

	/**
	 * Set some constant
	 */
	public function __construct() 
    {
        parent::__construct();

        /*$title = Str::title(METHOD).' - '.Str::title(CONTROLLER);
        if (METHOD == 'index')
        	$title = Str::title(CONTROLLER);*/
        $title = '';
        switch (METHOD)
        {
        	case 'index': $title = Str::title(CONTROLLER);
        	break;

        	case 'adduser': $title = 'Add a user account';
        	break;
        	case 'edituser': $title = 'Edit your user account';
        	break;

        	default: $title = Str::title(METHOD);
        }

    	$this->layout
    	->with('page_content', '')
    	->with('page_title', $title)
    	;
    }
}