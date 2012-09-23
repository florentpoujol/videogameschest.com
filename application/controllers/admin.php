<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
    
    public function index()
    {
        $data['infos'] = GetSiteData( true );
        
        $this->layout
        ->AddView( 'bodyStart', 'menu_view', array('page'=>'admin'))
        ->AddView( 'bodyStart', 'admin_view', $data )
        ->Load();
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */