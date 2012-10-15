<?php if ( ! defined("BASEPATH")) exit('No direct script access allowed');

class Admin extends MY_Controller {
    
    function __construct() {
    	parent::__construct();
	}


    // ----------------------------------------------------------------------------------

    /**
     * Main hub with no content but the admin menu
     */
    function index() {
    	if ( ! IS_LOGGED_IN)
    		redirect("admin/login");

        if (IS_ADMIN)
            $this->layout->view("forms/select_developer_to_edit_form");

        $this->layout->view("forms/select_game_to_edit_form")->load();
    }


    // ----------------------------------------------------------------------------------

    /**
     * The login screen
     */
    function login() {
    	// redirect if alredy logged in
    	if (IS_LOGGED_IN)
    		redirect("admin");

    	$error = "";
    	$name = post("name");

    	if (post("admin_login_form_submitted")) {
            $field = "name";

            if (is_numeric($name))
                $field = "user_id";
            elseif (strpos($name, "@")) // the name is actually an email
                $field = "email";

            $is_admin = false;
	    	$user = get_db_row("*", "users", "$field = '$name'" );

	    	if ($user) {
	    		if (check_password( post("password"), $user->password ) ) {
	    			$userdata = array( "is_logged_in" => "1" );
	    			
	    			if ($user->type == "admin")
	    				$userdata["is_admin"] = "1";
	    			else
	    				$userdata["is_developer"] = "1";

                    $userdata["user_id"] = $user->user_id;
                    $userdata["user_key"] = $user->key;
	    			set_userdata($userdata);

	    			redirect("admin");
	    		}
	    		else
	    			$error = "The password provided for user $field [$name] is incorrect.";
	    	}
	    	else
	    		$error = "No user with the $field [$name] has been found.";
    	}


    	$this->layout
    	->view( 'forms/admin_login_form', array("error"=>$error, "name"=>$name ))
    	->load();
    }


    // ----------------------------------------------------------------------------------

    /**
     * Disconnect the user
     */
    function logout() {
    	$this->session->sess_destroy();
    	redirect("admin/login");
    }


    // ----------------------------------------------------------------------------------

    /**
     * Page to edit an admin account
     */
    function edituser() {
        if ( ! IS_LOGGED_IN)
            redirect("admin/login");

        // the has been submitted
        if (post("user_form_submitted")) {
            // checking form
            $this->form_validation->set_rules( 'form[name]', "Name", "trim|required|min_length[5]");
            $this->form_validation->set_rules( 'form[email]', "Email", "trim|required|min_length[5]|valid_email");
            
            $form = post("form");

            if (trim($form["password"]) != "" ) {
                $this->form_validation->set_rules("form[password]", "Password", "min_length[5]");
                $this->form_validation->set_rules("form[password2]", "Password confirmation", "min_length[5]");
                
                if ($form["password"] != $form["password2"])
                    $this->form_validation->set_rules("form[password2]", "Password confirmation", "matches[form[password]]");
            }

            
            // form OK
            if ($this->form_validation->run()) {
                unset($form["password2"]);
                // DO NOTHING, YET
                //$this->admin_model->edit_user($form);

                $form["success"] = 'Your user account has been successfully updated.';
                //$form["password"] = "";

                $this->layout->view( 'forms/admin_form', array("form"=>$form) )->load();
            }
            else {
                // just reload the form and let the form_validation class display the errors
                $form["password"] = "";
                $form["password2"] = "";

                $this->layout->view("forms/user_form", array("form"=>$form))->load();
            }
        }
        // no form submitted
        else {
            $form = get_db_row("*", "users", "user_id = '".USER_ID."'");
            $form->password = "";

            $this->layout->view("forms/user_form", array("form"=>$form))->load();
        }
    }


    // ----------------------------------------------------------------------------------

    /**
     * Page to edit it's own account, redirect to the hub or editdeveloper
     */
    function edityouraccount() {
        if ( ! IS_LOGGED_IN)
            redirect("admin/login");

        if (IS_DEVELOPER)
            redirect( 'admin/editdeveloper/'.userdata( "user_id") );
        else //is admin
            redirect( 'admin/editadmin' );
    }


    // ----------------------------------------------------------------------------------

    /**
     * Main hub with no content but the admin menu
     */
    function adddeveloper() {
        if (post("developer_form_submitted")) {
            $form = post("form");

            $this->form_validation->set_rules( 'form[name]', "Name", 'trim|required|min_length[5]|is_unique[developers.name]' );
            $this->form_validation->set_rules( 'form[email]', "Email", 'trim|required|min_length[5]|valid_email|is_unique[developers.email]' );

            if (trim($form["password"]) != '' ) {
                $this->form_validation->set_rules( 'form[password]', "Password", 'min_length[8]' );
                $this->form_validation->set_rules( 'form[password2]', 'Password confirmation', 'min_length[8]' );
                
                if ($form["password"] != $form["password2"])
                    $this->form_validation->set_rules( 'form[password2]', 'Password confirmation', 'matches[form[password]]' ); // this fails when passwords matches ???
            }

            unset( $form["password2"] );
            $form["data"]["socialnetworks"] = clean_names_urls_array( $form["data"]["socialnetworks"] );

            // save data if all is OK
            if ($this->form_validation->run() ) {
                $id = $this->developer_model->insert_developer( $form );
                
                if (post("from_adddeveloper_page")) {
                    $form = array("success" => lang("adddeveloper_form_success"));
                    $this->session->set_flashdata( "adddeveloper_form", json_encode($form) );

                    redirect("adddeveloper");
                }
                else
                    redirect( 'admin/editdeveloper/'.$id );
            }
            else { // error
                unset($form["password"]);

                if (post("from_adddeveloper_page")) {
                    $form["errors"] = validation_errors(); // get errors from the form_validation class
                    $this->session->set_flashdata( "adddeveloper_form", json_encode($form) );
                    redirect("adddeveloper");
                }
                else
                    $this->layout->view( 'forms/developer_form', array("form"=>$form) )->load();
            }
        }
        elseif (IS_ADMIN) 
            $this->layout->view( 'forms/developer_form' )->load();
        else
            redirect("adddeveloper");
    }


    // ----------------------------------------------------------------------------------
    
    /**
     * Page to edit a developer account
     * @param int $id The id of the developer to edit
     */
    function editdeveloper( $id = null ) {
        if ( ! IS_LOGGED_IN)
            redirect("admin/login");

        // redirect developer to their edit page only
        if (IS_DEVELOPER && $id != USER_ID )
            redirect("admin/editdeveloper/".USER_ID);
        
        //
        if (post( "select_developer_to_edit_form_submitted" )) {
            $id = trim( post("developer_id_text") );

            if ($id == "")
                $id = post("developer_id_select");

            redirect("admin/editdeveloper/$id");
        }

        
        if (post("developer_form_submitted")) {
            $form = post("form");
            $db_data = get_db_row( "developers", "developer_id", $form["developer_id"] );

            // cheking name
            $this->form_validation->set_rules('form[name]', "Name", 'trim|required|min_length[5]');
            if ($form["name"] != $db_data->name)
                $this->form_validation->set_rules('form[name]', "Name", 'is_unique[developers.name]');
            
            // cheking email
            $this->form_validation->set_rules( 'form[email]', "Email", 'trim|required|min_length[5]|valid_email' );
            if ($form["email"] != $db_data->email)
                $this->form_validation->set_rules( 'form[email]', "Email", 'is_unique[developers.email]' );
    
            // checking password
            if (trim($form["password"]) != "") {
                $this->form_validation->set_rules("form[password]", "Password", "min_length[5]");
                $this->form_validation->set_rules("form[password2]", "Password confirmation", "min_length[5]");
                
                if ($form["password"] != $form["password2"])
                    $this->form_validation->set_rules("form[password2]", "Password confirmation", "matches[form[password]]");
            }
            else
                unset($form["password"]);

            unset($form["password2"]);

            $form["data"]["socialnetworks"] = clean_names_urls_array($form["data"]["socialnetworks"]);

            // update data if all is OK
            if ($this->form_validation->run()) {
                $this->developer_model->update_developer($form, $db_data);
                
                unset($form["password"]);
                $form["success"] = 'Your developer account has been successfully updated.';
                
                $this->layout->view( 'forms/developer_form', array("form"=>$form) )->load();
            }
            else {
                unset( $form["password"] );
                $this->layout->view( 'forms/developer_form', array("form"=>$form) )->load();
            }
        } // end if form submitted

        // no form has been submitted, just show the form filled with data from the database
        elseif ($id != null) { // if user is a developer, this will always be the case (see redirect above)
            $form = get_db_row("developers", "developer_id", $id );

            $this->layout
            ->view( 'forms/developer_form', array("form"=>$form) )
            ->load();
        }

        else // show to the admins the form to chose which devs to edit
            $this->layout->view( 'forms/select_developer_to_edit_form' )->load();
    }


    // ----------------------------------------------------------------------------------

    /**
     * Page to add a game
     */
    function addgame() {
        if (post("game_form_submitted")) {
            $form = post("form");

            $this->form_validation->set_rules( 'form[name]', "Name", 'trim|required|min_length[3]|is_unique[games.name]' );
            
            $form["data"]["screenshots"] = clean_names_urls_array( $form["data"]["screenshots"] );
            $form["data"]["videos"] = clean_names_urls_array( $form["data"]["videos"] );

            $form["data"]["socialnetworks"] = clean_names_urls_array( $form["data"]["socialnetworks"] );
            $form["data"]["stores"] = clean_names_urls_array( $form["data"]["stores"] );

            // save data if all is OK
            if ($this->form_validation->run() ) {
                $id = $this->game_model->insert_game( $form );

                if (post("from_addgame_page")) {
                    $form = array("success" => lang("addgame_form_success"));
                    $this->session->set_flashdata( "addgame_form", json_encode($form) );
                    redirect("addgame");
                }
                else
                    redirect( 'admin/editgame/'.$id );
            }
            else {
                if (post("from_addgame_page")) {
                    $form["errors"] = validation_errors(); // get errors from the form_validation class
                    $this->session->set_flashdata( "addgame_form", json_encode($form) ); // a coockie can only hold 4Kb of data
                    redirect("addgame");
                }
                else {
                    $this->layout
                    ->view( 'forms/game_form', array("form"=>$form) )
                    ->load();
                }
            }
        }
        elseif (IS_ADMIN) 
            $this->layout->view( 'forms/game_form' )->load();
        else
            redirect("addgame");
    }


    //----------------------------------------------------------------------------------

    /**
     * Page to edit a game
     * @param int $id The id of the game to edit
     */
    function editgame( $id = null ) {
        if ( ! IS_LOGGED_IN)
            redirect("admin/login");


        if (post( "select_game_to_edit_form_submitted" )) {
            $id = trim( post("game_id_text") );

            if ($id == '')
                $id = post("game_id_select");

            redirect( 'admin/editgame/'.$id );
        }

        
        if (post("game_form_submitted")) {
            $form = post("form");
            $db_data = get_db_row( "games", "game_id", $form["game_id"] );

            // cheking name
            $this->form_validation->set_rules( 'form[name]', "Name", 'trim|required|min_length[5]' );
            if ($form["name"] != $db_data->name)
                $this->form_validation->set_rules( 'form[name]', "Name", 'is_unique[games.name]' );
            
            $form["data"]["screenshots"] = clean_names_urls_array( $form["data"]["screenshots"] );
            $form["data"]["videos"] = clean_names_urls_array( $form["data"]["videos"] );

            $form["data"]["socialnetworks"] = clean_names_urls_array( $form["data"]["socialnetworks"] );
            $form["data"]["stores"] = clean_names_urls_array( $form["data"]["stores"] );

            // update data if all is OK
            if ($this->form_validation->run() ) {
                $this->game_model->update_game( $form, $db_data );
                
                $form["success"] = 'The game profile has been successfully updated.';
                
                $this->layout->view( 'forms/game_form', array("form"=>$form) );
            }
            else
                $this->layout->view( 'forms/game_form', array("form"=>$form) );
        } // end if (post("game_form_submitted")) {

        // no form has been submitted, just show the form filled with data from the database
        elseif ($id != null) {
            // prevent developer to edit a game they don't own
            if (IS_DEVELOPER) {
                //$games = $this->main_model->get_dev_games( USER_ID );
                $games = get_db_rows( "games", "developer_id", USER_ID );
                $game_is_owned_by_dev = false;

                foreach( $games->result() as $game ) {
                    if ($game->developer_id == USER_ID)
                        $game_is_owned_by_dev = true;
                }

                if ( ! $game_is_owned_by_dev) {
                    $form["errors"] = "The game with id [$id] does not belong to you, you can't edit it.";
                    $this->layout->view( 'forms/select_game_to_edit_form', array("form"=>$form) );
                }
            }

            $form = get_db_row( "games", "game_id", $id );

            if ($form == false) {
                $form = array("errors"=>"No game with id [$id] was found.");
                $this->layout->view( 'forms/select_game_to_edit_form', array("form"=>$form) );
            }
            else
                $this->layout->view( 'forms/game_form', array("form"=>$form) );
        }
        // show the form to chose which game to edit
        else
            $this->layout->view( 'forms/select_game_to_edit_form' );

        $this->layout->load();
    }


    // ----------------------------------------------------------------------------------

    /**
     * Page to set the current language
     */ 
    function setlanguage( $infos = null ) {
        $infos = explode(":", $infos);
        $lang = $infos[0];
        $url = index_page();

        if (isset($infos[1])) {
            unset($infos[0]);
            $url = implode("/", $infos); // rebuilt the url
        }

        if ( ! in_array($lang, get_static_data("site")->languages))
            $lang = $this->config->item("language"); // default language

        set_userdata("language", $lang);
        redirect($url);
    }


    // ----------------------------------------------------------------------------------

    /**
     * Handle creating, deleting, and reading private messages
     */
    function messages() {
        if ( ! IS_LOGGED_IN)
            redirect("admin/login");

        $form = array();

        if (post("write_message_form_submitted")) {
            $this->form_validation->set_rules('form[message]', 'Message text', 'trim|required|min_length[10]');

            if ($this->form_validation->run()) {
                $this->admin_model->insert_message(post("form"));
                $form["success"] = 'Message sent successfully.';
            }
        }


        if (post("delete_inbox_form_submitted") || post("delete_outbox_form_submitted")) {
            $this->admin_model->delete_messages(post("delete"));
            $form["success"] = 'Message(s) deleted successfully.';
        }

        $messages = array("inbox"=>array(), "outbox"=>array());

        // inbox
        if (IS_ADMIN) {
            $messages["inbox"] = $this->admin_model->get_messages(
                array("administrator_id"=>USER_ID, "sent_by_developer"=>1),
                "developers", "developers.developer_id=messages.developer_id");
        }
        else {
            $messages["inbox"] = $this->admin_model->get_messages(
                array("developer_id" => USER_ID, "sent_by_developer"=>0),
                "administrators", "administrators.administrator_id=messages.administrator_id");
        }

        // outbox
        if (IS_ADMIN) {
            $messages["outbox"] = $this->admin_model->get_messages(
                array("administrator_id"=>USER_ID, "sent_by_developer"=>0),
                "developers", "developers.developer_id=messages.developer_id");
        }
        else {
            $messages["outbox"] = $this->admin_model->get_messages(
                array("developer_id" => USER_ID, "sent_by_developer"=>1),
                "administrators", "administrators.administrator_id=messages.administrator_id");
        }

        $this->layout
        ->view( 'forms/message_form', array("form"=>$form, "messages"=>$messages) )
        ->load();
    }


    // ----------------------------------------------------------------------------------

    /**
     * Handle creating, deleting, and reading reports
     * @param  string $infos Url get parameters which ciontin the type of item and the id
     * ie : "developer:5" "game:10"
     */
    function reports( $infos = null ) {

        if (post("new_report_form_submitted")) {
            $report_form = post("report_form");

            if (trim($report_form["description"]) != "") {
                $this->admin_model->insert_report($report_form);
                $this->session->set_flashdata("report_success", lang("report_form_success"));
            }

            redirect($report_form["profile_type"].'/'.$report_form["profile_id"]."#report_form");
        }

        elseif (IS_LOGGED_IN) {
            $reports = array();

            if (post("delete_report_form_submitted")) {
                $this->admin_model->delete_reports( post("delete") );
                //$reports["success"] = 'Reports(s) deleted successfully.';
            }

            if (IS_ADMIN) {
                $what = "both";
                $order_by = "date asc";
                $reports = $this->admin_model->get_reports($what, $order_by);
            }
            else
                $reports = $this->admin_model->get_developer_reports(USER_ID);
        
            $this->layout
            ->view("forms/admin_report_form", array("reports"=>$reports))
            ->load();
        }

        else
            redirect('admin/login');
    }
}

/* End of file admin.php */
/* Location: ./application/controllers/admin.php */