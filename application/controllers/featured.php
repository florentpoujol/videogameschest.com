<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Featured extends CI_Controller {
    
    function index()
    {
        
        $data = array();

        $this->layout
        ->AddView( 'bodyStart', 'menu_view', array('page'=>'featured'))
        ->AddView( 'bodyStart', 'featured_view', $data )
        ->Load();
    }

    function pagenotfound( $reason = "noreason" ) {
        $data = array();
        $reasons = preg_split("#:#", $reason);
        $_404 = array();

        switch( $reasons[0] ) {
            case "gamenotfound": $_404['reason'] = 'The game with id or name ['.$reasons[1].'] was not found !';
            break;
        }
        
        
        $this->layout
        ->AddView( 'bodyStart', 'menu_view', array('page'=>'featured404'))
        ->AddView( 'bodyStart', '404_view', $_404)
        ->AddView( 'bodyStart', 'featured_view', $data )
        ->Load();
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */