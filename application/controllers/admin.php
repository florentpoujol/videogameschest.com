<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
    
    private $siteData = null;

    function __construct() {
    	parent::__construct();

    	$this->load->library('encrypt');

    	$this->sitedata = GetSiteData();

    	$this->layout->AddView( 'bodyStart', 'menu_view', array('page'=>'admin'));

    	// prevent acces to the admin page when the user is not logged in AND the login form is not send
    	// allow acces when logged in
        //if( !userdata( 'isloggedin' ) && (!post( 'admin_login_form_submit' ) || !post( 'admin_login_form_lostpassword' )) )
        	//redirect( 'admin/login' );
    	$page = 'admin';
    }


    // ----------------------------------------------------------------------------------

    /**
     * Main hub with no content but the admin menu
     */
    function in_dex() {
    	redirect( 'admin/hub' );
    }


    // ----------------------------------------------------------------------------------

    /**
     * Main hub with no content but the admin menu
     */
    function index() {
    	if(!userdata( 'isloggedin' ))
    		redirect( 'admin/login' );
    	$adminPage = "hub";
    	$this->layout
    	->AddView( 'bodyStart', 'admin/admin_menu_view', )
    	->Load();
    }


    // ----------------------------------------------------------------------------------

    /**
     * the login screen
     */
    function login( $error = null ) {
    	// redirect if alredy logged in
    	if(userdata('isloggedin'))
    		redirect('admin/hub');

    	$adminPage = 'login';
    	$error = '';
    	$name = post( 'name' );

    	if( post( 'admin_login_form_submit' ) ) {
	    	$user = $this->main_model->GetRow( 'users', 'name', $name );

	    	if($user) {
	    		if($this->encrypt->sha1( post( 'password' ) ) == $user->password) {
	    			$userdata = array( 'isloggedin' => 'true' );
	    			
	    			if($user->statut == 'admin')
	    				$userdata['isadmin'] = 'true';
	    			else
	    				$userdata['isdev'] = 'true';

	    			$this->session->set_userdata( $userdata );
	    			redirect ("admin");
	    		}
	    		else
	    			$error = 'The password provided for user ['.$name.'] is incorrect.';
	    	}
	    	else
	    		$error = 'No user with the name ['.$name.'] have been found.';
    	}


    	$this->layout
    	->AddView( 'bodyStart', 'forms/admin_login_form', array('error'=>$error, 'name'=>$name ))
    	->Load();
    }


    // ----------------------------------------------------------------------------------

    /**
     * Disconnect the user
     */
    function logout() {
    	$this->session->sess_destroy();
    	redirect( 'admin/login' );
    }


    // ----------------------------------------------------------------------------------

    /**
     * Page that show the reports
     */
    function reports() {
    	if(!userdata('isadmin'))
    		redirect('admin');

    }


    // ----------------------------------------------------------------------------------

    /**
     * Main hub with no content but the admin menu
     */
    function addgame() {
    	$data['siteData'] = $siteData;
        
        $this->layout
        ->AddView( 'bodyStart', 'menu_view', array('page'=>'admin'))
        ->AddView( 'bodyStart', 'admin_menu_view', array('page'=>'addgame'))
        ->AddView( 'bodyStart', 'game_form', $data )
        ->Load();
    }


    function editgame() {
    	
    }
}

/* End of file admin.php */
/* Location: ./application/controllers/admin.php */