<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends MY_Controller {
    
    function __construct() {
        parent::__construct();
    }
    
    public function index()
    {
    	

        $this->layout
        ->view( 'forms/search_form' )
        ->load();
    }
}

/* End of file search.php */
/* Location: ./application/controllers/search.php */