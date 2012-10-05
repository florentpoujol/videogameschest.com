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
     * Main hub with no content but the admin menu
     */
    function adddeveloper() {
        if( !userdata( 'is_logged_in' ) )
            redirect( 'admin/login' );

        if( !userdata( 'is_admin' ) )
            redirect( 'admin' );

        if( post( 'developer_form_submitted' ) ) {
            $form = post( 'form' );

            $this->form_validation->set_rules( 'form[name]', 'Name', 'trim|required|min_length[5]|is_unique[developers.name]' );
            $this->form_validation->set_rules( 'form[email]', 'Email', 'trim|required|min_length[5]|valid_email|is_unique[developers.email]' );

            /*if( trim($form['password']) != '' ) {
                $this->form_validation->set_rules( 'form[password]', 'Password', 'min_length[5]' );
                $this->form_validation->set_rules( 'form[password2]', 'Password confirmation', 'min_length[5]' );
                
                if( $form['password'] != $form['password2'] )
                    $this->form_validation->set_rules( 'form[password2]', 'Password confirmation', 'matches[form[password]]' );
            }*/
            unset( $form['password2'] );
            
            $form['socialnetworks'] = clean_socialnetworks( $form['socialnetworks'] );

            // save data if all is OK
            if( $this->form_validation->run() ) {
                $id = $this->main_model->insert_developer( $form );
                redirect( 'admin/editdeveloper/'.$id );
            }
            else {
                unset( $form['password'] );
                $this->layout
                ->view( 'bodyStart', 'forms/developer_form', array('form'=>$form) )
                ->load();
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

        
        if( post( 'developer_form_submitted' ) ) {
            $form = post( 'form' );
            $db_data = get_db_row( 'developers', 'id', $form['id'] );

            // cheking name
            $this->form_validation->set_rules( 'form[name]', 'Name', 'trim|required|min_length[5]' );
            if( $form['name'] != $db_data->name )
                $this->form_validation->set_rules( 'form[name]', 'Name', 'is_unique[developers.name]' );
            
            // cheking email
            $this->form_validation->set_rules( 'form[email]', 'Email', 'trim|required|min_length[5]|valid_email' );
            if( $form['email'] != $db_data->email )
                $this->form_validation->set_rules( 'form[email]', 'Email', 'is_unique[developers.email]' );
    
            // checking password
            /*if( trim($form['password']) != '' ) {
                $this->form_validation->set_rules( 'form[password]', 'Password', 'min_length[5]' );
                $this->form_validation->set_rules( 'form[password2]', 'Password confirmation', 'min_length[5]' );
                
                if( $form['password'] != $form['password2'] )
                    $this->form_validation->set_rules( 'form[password2]', 'Password confirmation', 'matches[form[password]]' );
            }*/

            unset( $form['password2'] );
            
            $form['socialnetworks'] = clean_socialnetworks( $form['socialnetworks'] );


            // update data if all is OK
            if( $this->form_validation->run() ) {
                $this->main_model->update_developer( $form, $db_data );
                
                unset( $form['password'] );
                $form['success'] = 'Your developer account has been successfully updated.';
                
                $this->layout
                ->view( 'bodyStart', 'forms/developer_form', array('form'=>$form) )
                ->load();
            }
            else {
                unset( $form['password'] );
                $this->layout
                ->view( 'bodyStart', 'forms/developer_form', array('form'=>$form) )
                ->load();
               
            }
        } // end if( post( 'developer_form_submitted' ) ) {

        // no form has been submitted, just show the form filled with data from the database
        elseif( $id != null ) { // if user is a developer, this will always be the case (see redirect above)
            $form = get_db_row( 'developers', 'id', $id );

            $this->layout
            ->view( 'bodyStart', 'forms/developer_form', array('form'=>$form) )
            ->load();
        }

        else { // show to the admins the form to chose which devs to edit
            $this->layout
            ->view( 'bodyStart', 'forms/select_developer_to_edit_form' )
            ->load();
        }
    }

    


    // ----------------------------------------------------------------------------------

    /**
     * Page to add a game
     */
    function addgame() {
        if( !userdata( 'is_logged_in' ) )
            redirect( 'admin/login' );

        if( post( 'game_form_submitted' ) ) {
            $form = post( 'form' );

            $this->form_validation->set_rules( 'form[name]', 'Name', 'trim|required|min_length[3]|is_unique[games.name]' );
            
            $form['data']['socialnetworks'] = clean_socialnetworks( $form['data']['socialnetworks'] );

            // save data if all is OK
            if( $this->form_validation->run() ) {
                $id = $this->main_model->insert_game( $form );
                redirect( 'admin/editgame/'.$id );
            }
            else {
                $this->layout
                ->view( 'bodyStart', 'forms/game_form', array('form'=>$form) )
                ->load();
            }
        }
        else
            $this->layout->view( 'bodyStart', 'forms/game_form' )->load();
    }


    /**
     * Page to edit a game
     */
    function editgame( $id = null ) {
        if( !userdata( 'is_logged_in' ) )
            redirect( 'admin/login' );


        if( post( "select_game_to_edit_form_submitted" ) ) {
            $id = trim( post( 'game_id_text' ) );

            if( $id == '' )
                $id = post( 'game_id_select' );

            redirect( 'admin/editgame/'.$id );
        }

        
        if( post( 'game_form_submitted' ) ) {
            $form = post( 'form' );
            $db_data = get_db_row( 'games', 'id', $form['id'] );

            // cheking name
            $this->form_validation->set_rules( 'form[name]', 'Name', 'trim|required|min_length[5]' );
            if( $form['name'] != $db_data->name )
                $this->form_validation->set_rules( 'form[name]', 'Name', 'is_unique[games.name]' );
            

            $form['data']['socialnetworks'] = clean_socialnetworks( $form['data']['socialnetworks'] );

            // update data if all is OK
            if( $this->form_validation->run() ) {
                $this->main_model->update_game( $form, $db_data );
                
                $form['success'] = 'Your game account has been successfully updated.';
                
                $this->layout
                ->view( 'bodyStart', 'forms/game_form', array('form'=>$form) )
                ->load();
            }
            else {
                $this->layout
                ->view( 'bodyStart', 'forms/game_form', array('form'=>$form) )
                ->load();
               
            }
        } // end if( post( 'game_form_submitted' ) ) {

        // no form has been submitted, just show the form filled with data from the database
        elseif( $id != null ) {
            // prevent developer to edit a game they don't own
            if( userdata( 'is_developer' ) ) {
                //$games = $this->main_model->get_dev_games( userdata( 'user_id' ) );
                $games = get_db_rows( 'games', 'developer_id', userdata( 'user_id' ) );
                $game_is_owned_by_dev = false;

                foreach( $games->results() as $game ) {
                    if( $game->id == userdata( 'user_id' ) )
                        $game_is_owned_by_dev = true;
                }

                if( !$game_is_owned_by_dev ) {
                    $form['errors'] = "The game with id [$id] does not belong to you, you can't edit it.";

                    $this->layout
                    ->view( 'bodyStart', 'forms/select_game_to_edit_form', array('form'=>$form) )
                    ->load();
                }
            }

            $form = get_db_row( 'games', 'id', $id );

            $this->layout
            ->view( 'bodyStart', 'forms/game_form', array('form'=>$form) )
            ->load();
        }
        // show the form to chose which game to edit
        else { 
            $this->layout
            ->view( 'bodyStart', 'forms/select_game_to_edit_form' )
            ->load();
        }
    }


    // ----------------------------------------------------------------------------------

    /**
     * Page to set the current language
     */ 
    function setlanguage( $lang = null ) {
        if( !in_array( $lang, get_site_data()->languages ) )
            $lang = $this->config->item( 'sitelanguage' ); // default language

        set_userdata( 'language', $lang );
        redirect( 'admin' );
    }


    // ----------------------------------------------------------------------------------

    /**
     * handle creating, deleting, and reading private messages
     */
    /*
    Système de ticket pour communiquer entre admin et devs

Les devs et les admins peuvent s'échanger des messages.

Les devs peuvent envoyer des messages aux admins et vice versa.
Les devs ne peuvent pas envoyer de message à d'autre devs.
Un admin peu envoyer des message vers les autres admins.

Tous les messages envoyés aux admins apparaissent dans une sections du panel avec les infos
 : nom (nom du dev ou « admin »), timestamp, message
*/
    function messages() {
        if( !userdata( 'is_logged_in' ) )
            redirect( 'admin/login' );

        $form = array();

        if( post( 'write_message_form_submitted' ) ) {
            $this->form_validation->set_rules( 'form[message]', 'Message text', 'trim|required|min_length[10]' );

            if( $this->form_validation->run() ) {
                $form = post( 'form' );

                if( $form['recipient_id'] == "admins" )
                    $form['recipient_id'] = 0;

                $form['sender_id'] = 0;
                if( userdata( 'is_developer' ) ) 
                    $form['sender_id'] = userdata( 'user_id' );
                
                $this->main_model->insert_message( $form );
                $form['success'] = 'Message sent successfully.';
            }
        }


        if( post( 'delete_inbox_form_submitted' ) || post( 'delete_outbox_form_submitted' ) ) {
            $this->main_model->delete_messages( post( 'delete' ) );
            $form['success'] = 'Message(s) deleted successfully.';
        }


        $this->layout
        ->view( 'bodyStart', 'forms/message_form', array('form'=>$form) )
        ->load();
    }
}

/* End of file admin.php */
/* Location: ./application/controllers/admin.php */