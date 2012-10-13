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
        $keys = array("is_logged_in", "is_admin", "is_developer", "user_id", "language");

        foreach ($keys as $key) {
            if (userdata($key))
                Define(strtoupper($key), true);
            else
                Define(strtoupper($key), false);
   		}
	}
}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */