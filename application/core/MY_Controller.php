<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	function __construct() {
    	parent::__construct();

    	set_page( $this->router->fetch_class() );
                
        $lang = userdata("language");
        
        if ( ! $lang) {
        	$lang = $this->config->item("language");
        	set_userdata("language", $lang);
        }

        $this->lang->load("main", $lang);

        // set some CONST
        $keys = array("isloggedin", "isadmin", "isdeveloper", "id", "language");

        foreach ($keys as $key) {
            if (userdata($key))
                define(strtoupper($key), true);
            else
                define(strtoupper($key), false);
   		}


        define("CONTROLLER", $this->router->fetch_class());
        define("METHOD", $this->router->fetch_method());

        define("SITE", get_static_data('site'));
        define("FORM", get_static_data('forms'));
	}
}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */