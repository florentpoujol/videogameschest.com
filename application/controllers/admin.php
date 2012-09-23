<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
    
    private $siteData = null;

    function __construct() {
    	parent::__construct();

    	$this->load->library('encrypt');

    	get_site_data();

		set_page( 'admin' );
		set_admin_page( $this->router->fetch_method() );

    	$this->layout->view( 'bodyStart', 'menu_view' );
	}


    // ----------------------------------------------------------------------------------

    /**
     * Main hub with no content but the admin menu
     */
    function index() {
    	if(!userdata( 'isloggedin' ))
    		redirect( 'admin/login' );

    	set_admin_page( 'hub' );

    	$this->layout
    	->view( 'bodyStart', 'admin/admin_menu_view' )
    	->load();
    }


    // ----------------------------------------------------------------------------------

    /**
     * the login screen
     */
    function login( $error = null ) {
    	// redirect if alredy logged in
    	if(userdata('isloggedin'))
    		redirect('admin/hub');

    	$error = '';
    	$name = post( 'name' );

    	if( post( 'admin_login_form_submit' ) ) {
	    	$user = $this->main_model->get_row( 'users', 'name', $name );

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
    	->view( 'bodyStart', 'forms/admin_login_form', array('error'=>$error, 'name'=>$name ))
    	->load();
    }


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
    function adddeveloper() {
        $this->layout
        ->view( 'bodyStart', 'admin/admin_menu_view' )
        ->view( 'bodyStart', 'forms/developer_form' )
        ->load();
    }


    function editdeveloper( $id = null ) {
        $data['account_data'] = $this->db->get_row( 'developers', 'id', $id );

        $this->layout
        ->view( 'bodyStart', 'admin/admin_menu_view' )
        ->view( 'bodyStart', 'forms/developer_form' )
        ->load();
    }


    // ----------------------------------------------------------------------------------

    /**
     * Main hub with no content but the admin menu
     */
    function addgame() {
        $this->layout
        ->view( 'bodyStart', 'admin/admin_menu_view' )
        ->view( 'bodyStart', 'forms/game_form' )
        ->load();
    }


    function editgame() {
    	$this->layout
        ->view( 'bodyStart', 'admin/admin_menu_view' )
        ->view( 'bodyStart', 'forms/game_form' )
        ->load();
    }

    // ----------------------------------------------------------------------------------
}

/* End of file admin.php */
/* Location: ./application/controllers/admin.php */