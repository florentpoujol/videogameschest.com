<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adddeveloper extends CI_Controller {
    
    function __construct() {
    	parent::__construct();

        set_page( 'adddeveloper' );
        $this->layout
        ->view( 'bodyStart', 'menu_view' );

        $this->load->library( 'form_validation' );
	}


    // ----------------------------------------------------------------------------------

    /**
     * Default method
     */
    function index() {
        $form = userdata( 'adddeveloper_form' );

        if( is_string( $form ) )
            $form = json_decode( $form );
        else
            $form = array();        
        
        $this->layout
        ->view( 'bodyStart', 'forms/developer_form', array('form'=>$form) )
        ->load();
    }

}

/* End of file adddeveloper.php */
/* Location: ./application/controllers/adddeveloper.php */