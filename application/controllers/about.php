<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class About extends CI_Controller {
    
    function __construct() {
        parent::__construct();

        $lang = userdata( 'language' );
        if( $lang )
            $this->lang->load( 'main', $lang );

        set_page( 'about' );
    }
    
    public function index()
    {
        
        $this->layout
        ->view( 'about_view' )
        ->load();
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */