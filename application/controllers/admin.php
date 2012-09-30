<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
    
    function __construct() {
    	parent::__construct();

        set_page( 'admin' );
        set_admin_page( $this->router->fetch_method() );

    	$this->load->library('encrypt');
        
        $lang = userdata( 'language' );
        if( $lang )
            $this->lang->load( 'main', $lang );

    	$this->layout
        ->view( 'bodyStart', 'menu_view' )
        ->view( 'bodyStart', 'admin/admin_menu_view' );

        $this->load->library( 'form_validation' );
	}


    // ----------------------------------------------------------------------------------


    /**
     * Main hub with no content but the admin menu
     */
    function index() {
    	if( !userdata( 'is_logged_in' ) )
    		redirect( 'admin/login' );

    	


        /*if( userdata( 'is_admin' ) ) {
            $this->layout
            ->view( 'bodyStart', 'forms/select_developer_to_edit_form' )
            ->view( 'bodyStart', 'forms/admin_form' );
        }*/

    	$this->layout->load();
    }


    // ----------------------------------------------------------------------------------

    /**
     * the login screen
     */
    function login( $error = null ) {
    	// redirect if alredy logged in
    	if( userdata( 'is_logged_in' ) )
    		redirect( 'admin' );

    	$error = '';
    	$name = post( 'name' );

    	if( post( 'admin_login_form_submitted' ) ) {
            $field = 'name';

            if( is_numeric( $name ) )
                $field = 'id';
            elseif( strpos( $name, '@' ) ) // the name is actually an email
                $field = 'email';

            $is_admin = false;
	    	$user = get_db_row( 'developers', $field, $name );

            if( !$user ) {
                $user = get_db_row( 'administrators', $field, $name );
                $is_admin = true;
            }

	    	if( $user ) {
	    		if( $this->encrypt->sha1( post( 'password' ) ) == $user->password ) {
	    			$userdata = array( 'is_logged_in' => '1' );
	    			
	    			if( $is_admin )
	    				$userdata['is_admin'] = '1';
	    			else
	    				$userdata['is_developer'] = '1';

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
    	if( !userdata( 'is_logged_in' ) )
            redirect( 'admin/login' );

        if( !userdata( 'is_admin' ) )
            redirect( 'admin' );
    }


    // ----------------------------------------------------------------------------------

    /**
     * Main hub with no content but the admin menu
     */
    function adddeveloper() {
        if( !userdata( 'is_logged_in' ) )
            redirect( 'admin/login' );

        if( !userdata( 'is_admin' ) )
            redirect( 'admin' );

        

        if( post( 'developer_form_submitted' ) ) {
            // what to verify
            // password maybe
            // name not empty and does not yet exist
            // email not empty and does not yet exists

            $form = post( 'form' );

            $this->form_validation->set_rules( 'form[name]', 'Name', 'trim|required|min_length[5]' );
            $this->form_validation->set_rules( 'form[email]', 'Email', 'trim|required|min_length[5]|valid_email' );

            if( trim($form['password']) != '' ) {
                $this->form_validation->set_rules( 'form[password]', 'Password', 'min_length[5]' );
                $this->form_validation->set_rules( 'form[password2]', 'Password confirmation', 'min_length[5]' );
                
                if( $form['password'] != $form['password2'] )
                    $this->form_validation->set_rules( 'form[password2]', 'Password confirmation', 'matches[form[password]]' );
            }

            /*if( $form_data['name'] == '' )
                $form_data['errors'][] = 'The developer name is empty !';
            
            if( get_db_info( 'users', 'name', 'name', $form_data['name'] ) )
                $form_data['errors'][] = 'The name is already in use ! 
                That means that the developer account already exist. 
                The name may not be seen in the search if the account is still private.';
            
            if( $form_data['email'] == '' )
                $form_data['errors'][] = 'The email is empty !';

            if( get_db_info( 'users', 'email', 'email', $form_data['email'] ) )
                $form_data['errors'][] = 'The email is already in use ! 
                That means that the developer account already exist. 
                The developer may not be seen in the search if the account is still private.';*/
            

            // strip out empty url from the socialnetwork array
            for( $i = 0; $i < count( $form_data['socialnetworks']['url'] ); $i++ ) {
                if( trim( $form_data['socialnetworks']['url'][$i] ) == '' ) {
                    unset( $form_data['socialnetworks']['site'][$i] );
                    unset( $form_data['socialnetworks']['url'][$i] );
                }
            }

            // rebuilt the index, 
            // so that json_encode consider them as array an not object
            array_values( $form_data['socialnetworks']['url'] );
            array_values( $form_data['socialnetworks']['url'] );

            // save data if all is well
            if( count( $form_data['errors'] ) == 0 ) {

                unset( $data['password2'] );
                unset( $data['developer_form_submitted'] );
                unset( $data['errors'] );
                $id = $this->main_model->create_user( $form_data );
                
                redirect( 'admin/editdeveloper/'.$id );
                echo 'enreng db  redirect to edtdeveloper';
            }
        }
        else
            $this->layout->view( 'bodyStart', 'forms/developer_form' )->load();
    } // end of method adddeveloper()


    /**
     * Page to edit a developer account
     */
    function editdeveloper( $id = null ) {
        if( !userdata( 'is_logged_in' ) )
            redirect( 'admin/login' );

        // redirect developer to their edit page only
        if( userdata( 'is_developer' ) && $id != userdata( 'user_id' ) )
            redirect( 'admin/editdeveloper/'.userdata( 'user_id' ) );
        
        //
        if( post( "select_developer_to_edit_form_submitted" ) ) {
            $id = trim( post( 'developer_id_text' ) );

            if( $id == '' )
                $id = post( 'developer_id_select' );

            redirect( 'admin/editdeveloper/'.$id );
        }

        /*if( post( 'edit_own_account_form_submitted' ) )
            redirect( 'admin/editdeveloper/'.userdata( 'user_id' ) );*/
        
        if( $id != null ) {
            // make sure developers can't edit another account than their own
            //if( userdata( 'is_developer' ) && userdata( 'user_id' ) != $id ) // developer trying to edit an account he dosn't own
                //redirect( 'admin/editdeveloper/'.userdata( 'user_id' ) );

            $developer_data = get_db_row( 'developers', 'id', $id );

            $this->layout
            ->view( 'bodyStart', 'forms/developer_form', $developer_data )
            ->load();
        }
        else {
            // show to the admins the form to chose which devs to edit
            $this->layout
            ->view( 'bodyStart', 'forms/select_developer_to_edit_form' )
            ->load();
        }

    }


    // ----------------------------------------------------------------------------------

    /**
     * Page to edit an admin account
     */
    function editadmin() {
        if( !userdata( 'is_logged_in' ) )
            redirect( 'admin/login' );

        if( !userdata( 'is_admin' ) )
            redirect( 'admin' );

        // the has been submitted
        if( post( 'admin_form_submitted' ) ) {
            // checking form
            $this->form_validation->set_rules( 'form[name]', 'Name', 'trim|required|min_length[5]' );
            $this->form_validation->set_rules( 'form[email]', 'Email', 'trim|required|min_length[5]|valid_email' );
            
            $form = post( 'form' );

            if( trim($form['password']) != '' ) {
                $this->form_validation->set_rules( 'form[password]', 'Password', 'min_length[5]' );
                $this->form_validation->set_rules( 'form[password2]', 'Password confirmation', 'min_length[5]' );
                
                if( $form['password'] != $form['password2'] )
                    $this->form_validation->set_rules( 'form[password2]', 'Password confirmation', 'matches[form[password]]' );
            }
            
            // form OK
            if( $this->form_validation->run() ) {
                $form['success'] = 'Your administrator account has been successfully updated.';
                $form['password'] = '';
                $form['password2'] = '';

                $this->layout
                ->view( 'bodyStart', 'forms/admin_form' , 
                    array( 'form' => $form ) )
                ->load();
            }
            else {
                // just reload the form and let the form_validation class display the errors
                $form['password'] = '';
                $form['password2'] = '';

                $this->layout->view( 'bodyStart', 'forms/admin_form' , array('form' => post('form')) )
                ->load();
            }
        }
        // no form submitted
        else {
            $id = userdata( 'user_id' );
            $form = get_db_row( 'administrators', 'id', $id );
            $form->id = $id;
            $form->password = '';

            $this->layout->view( 'bodyStart', 'forms/admin_form' , array('form' => $form) )
            ->load();
        }
    }


    // ----------------------------------------------------------------------------------

    /**
     * Page to edit it's own account, redirect to the hub or editdeveloper
     */
    function edityouraccount() {
        if( !userdata( 'is_logged_in' ) )
            redirect( 'admin/login' );

        if( userdata( 'is_developer' ) )
            redirect( 'admin/editdeveloper/'.userdata( 'user_id') );
        else //is admin
            redirect( 'admin/editadmin' );
    }


    // ----------------------------------------------------------------------------------

    /**
     * Page to add a game
     */
    function addgame() {
        if( !userdata( 'is_logged_in' ) )
            redirect( 'admin/login' );

        $this->layout
        ->view( 'bodyStart', 'admin/admin_menu_view' )
        ->view( 'bodyStart', 'forms/game_form' )
        ->load();
    }


    /**
     * Page to edit a game
     */
    function editgame() {
        if( !userdata( 'is_logged_in' ) )
            redirect( 'admin/login' );

    	$this->layout
        ->view( 'bodyStart', 'admin/admin_menu_view' )
        ->view( 'bodyStart', 'forms/game_form' )
        ->load();
    }


    // ----------------------------------------------------------------------------------

    /**
     * Page to set the current language
     */ 
    function setlanguage( $lang = null ) {
        if( !in_array( $lang, get_site_data()->languages ) )
            $lang = $this->config->item( 'language' ); // default language

        set_userdata( 'language', $lang );
        redirect( 'admin' );
    }
}

/* End of file admin.php */
/* Location: ./application/controllers/admin.php */