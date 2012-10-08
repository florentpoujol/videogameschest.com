<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends CI_Controller {
    
    function __construct() {
        parent::__construct();

        $lang = userdata( 'language' );
        if( $lang )
            $this->lang->load( 'main', $lang );

        set_page( 'search' );  
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