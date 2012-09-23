<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Featured extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index()
    {

        $data['infos'] = GetSiteData( true );
        
        $this->layout
        ->AddView( 'bodyStart', 'menu_view', array('page'=>'about'))
        ->AddView( 'bodyStart', 'featured_view', $data )
        ->Load();
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */