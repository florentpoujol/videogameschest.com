<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends MY_Controller {
    
    function __construct() {
        parent::__construct();
    }
    
    public function index()
    {
    	$criteria = array(
            "from" => "games",
    		"select" => "name",
    		"where" => array("developer_id" => 1),
    		
    	);

    	var_dump(get_db_rows($criteria));

        $this->layout
        ->view( 'forms/search_form' )
        ->load();
    }
}

/* End of file search.php */
/* Location: ./application/controllers/search.php */