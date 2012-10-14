<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class About extends MY_Controller {
    
    function __construct() {
        parent::__construct();
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