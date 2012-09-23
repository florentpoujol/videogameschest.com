<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Developer extends CI_Controller {
    
    public function index($name = null)
    {
        $data['infos'] = GetSiteData( true );
        
        $this->layout
        ->AddView( 'bodyStart', 'menu_view', array('page'=>'developer'))
        ->AddView( 'bodyStart', 'full_developer_view', $data )
        ->Load();
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */