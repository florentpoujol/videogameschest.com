<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
    
    function __construct() {
    	parent::__construct();

    	$this->load->library('encrypt');

		set_page( 'admin' );
		set_admin_page( $this->router->fetch_method() );

    	$this->layout->view( 'bodyStart', 'menu_view' );
	}


    // ----------------------------------------------------------------------------------

    /**
     * Main hub with no content but the admin menu
     */
    function index() {
    	if( !userdata( 'isloggedin' ) )
    		redirect( 'admin/login' );

    	set_admin_page( 'hub' );

    	$this->layout->view( 'bodyStart', 'admin/admin_menu_view' );

        if( userdata( 'is_admin' ) )
            $this->layout->view( 'bodyStart', 'forms/select_developer_to_edit_form' );

    	$this->layout->load();
    }


    // ----------------------------------------------------------------------------------

    /**
     * the login screen
     */
    function login( $error = null ) {
    	// redirect if alredy logged in
    	if( userdata( 'isloggedin' ) )
    		redirect( 'admin' );

    	$error = '';
    	$name = post( 'name' );

    	if( post( 'admin_login_form_submitted' ) ) {
            $field = 'name';

            if( is_numeric( $name ) )
                $field = 'id';
            elseif( strpos( '@', $name ) ) // the name is actually an email
                $field = 'email';

	    	$user = get_db_row( 'users', $field, $name );

	    	if( $user ) {
	    		if($this->encrypt->sha1( post( 'password' ) ) == $user->password) {
	    			$userdata = array( 'isloggedin' => 'true' );
	    			
	    			if($user->is_admin == 1 )
	    				$userdata['is_admin'] = 'true';
	    			else
	    				$userdata['is_developer'] = 'true';

                    $userdata['user_id'] = $user->id;
	    			set_userdata( $userdata );
	    			redirect( 'admin' );
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
    	if( !userdata( 'isloggedin' ) )
            redirect( 'admin/login' );

    }


    // ----------------------------------------------------------------------------------

    /**
     * Main hub with no content but the admin menu
     */
    function adddeveloper() {
        if( !userdata( 'is_admin' ) )
            redirect( 'admin' );

        $this->layout
        ->view( 'bodyStart', 'admin/admin_menu_view' )
        ->view( 'bodyStart', 'forms/developer_form' )
        ->load();
    }


    function editdeveloper( $id = null ) {
        if( !userdata( 'isloggedin' ) )
            redirect( 'admin/login' );

        //
        if( post( "select_developer_to_edit_form_submitted" ) ) {
            $id = trim( post( 'developer_id_text' ) );

            if( $id == '' )
                $id = post( 'developer_id_select' );

            redirect( 'admin/editdeveloper/'.$id );
        }

        if( post( 'edit_own_account_form_submitted' ) )
            redirect( 'admin/editdeveloper/'.userdata( 'user_id' ) );
        
        $this->layout->view( 'bodyStart', 'admin/admin_menu_view' );

        if( $id != null ) {
            if( userdata( 'is_developer' ) && userdata( 'user_id' ) != $id ) // developer trying to edit an account he dosn't own
                redirect( 'admin/editdeveloper/'.userdata( 'user_id' ) );

            $data['developer_data'] = get_db_row( 'users', 'id', $id );

            $this->layout
            ->view( 'bodyStart', 'forms/developer_form', $data )
            ->load();
        }
        else {
            if( userdata( 'is_developer' ) )
                redirect( 'admin/editdeveloper/'.userdata( 'user_id' ) );

            $this->layout
            ->view( 'bodyStart', 'forms/select_developer_to_edit_form' )
            ->load();
        }

    }


    // ----------------------------------------------------------------------------------

    /**
     * Main hub with no content but the admin menu
     */
    function addgame() {
        if( !userdata( 'isloggedin' ) )
            redirect( 'admin/login' );

        $this->layout
        ->view( 'bodyStart', 'admin/admin_menu_view' )
        ->view( 'bodyStart', 'forms/game_form' )
        ->load();
    }


    function editgame() {
        if( !userdata( 'isloggedin' ) )
            redirect( 'admin/login' );

    	$this->layout
        ->view( 'bodyStart', 'admin/admin_menu_view' )
        ->view( 'bodyStart', 'forms/game_form' )
        ->load();
    }

    // ----------------------------------------------------------------------------------
}

/* End of file admin.php */
/* Location: ./application/controllers/admin.php */