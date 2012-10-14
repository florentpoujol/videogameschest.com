<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	function __construct() {
    	parent::__construct();
                
        $lang = userdata("language");
        
        if ( ! $lang) {
        	$lang = $this->config->item("language");
        	set_userdata("language", $lang);
        }

        $this->lang->load("main", $lang);
        define("LANGUAGE", $lang);

        // set some CONST
        $keys = array("is_logged_in", "is_admin", "is_developer");

        foreach ($keys as $key) {
            if (userdata($key))
                define(strtoupper($key), true);
            else
                define(strtoupper($key), false);
   		}

        if (is_numeric(userdata("user_id")))
            define("USER_ID", userdata("user_id"));
        else
            define("USER_ID", 0);

        define("CONTROLLER", $this->router->fetch_class());
        define("METHOD", $this->router->fetch_method());
	}
}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */