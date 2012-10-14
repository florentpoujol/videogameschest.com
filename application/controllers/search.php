<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends MY_Controller {
    
    function __construct() {
        parent::__construct();
    }
    
    public function index()
    {
    	$criteria = array(
    		"select" => "name",
    		"where" => array("developer_id" => 1),
    		"limit" => 1
    	);

    	var_dump($this->main_model->get_rows("games", $criteria)->row()->name);

        $this->layout
        ->view( 'forms/search_form' )
        ->load();
    }
}

/* End of file search.php */
/* Location: ./application/controllers/search.php */