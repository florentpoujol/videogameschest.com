<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adddeveloper extends CI_Controller {
    
    function __construct() {
    	parent::__construct();

        $lang = userdata( 'language' );
        if( $lang )
            $this->lang->load( 'main', $lang );

        set_page( 'adddeveloper' );

        $this->load->library( 'form_validation' );
	}


    // ----------------------------------------------------------------------------------

    /**
     * Default method
     */
    function index() {
        $form = $this->session->flashdata( 'adddeveloper_form' );

        if( is_string( $form ) )
            $form = json_decode( $form, true );
        else
            $form = array();        
        
        $this->layout
        ->view( 'forms/developer_form', array('form'=>$form) )
        ->load();
    }
}

/* End of file adddeveloper.php */
/* Location: ./application/controllers/adddeveloper.php */