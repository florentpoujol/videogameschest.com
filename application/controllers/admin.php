<?php if ( ! defined("BASEPATH")) exit('No direct script access allowed');

class Admin extends MY_Controller {
    
    function __construct() {
    	parent::__construct();

        $method = $this->router->fetch_method();
        if ($method == "index")
            $method = "admin_index";
        
        define("METHOD", $method);
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
                $field = "id";
            elseif (strpos($name, "@")) // the name is actually an email
                $field = "email";

            $is_admin = false;
	    	$user = $this->main_model->get_row("id, password, type, key", "users", "$field = '$name'");

	    	if ($user) {
	    		if (check_password( post("password"), $user->password ) ) {
	    			$userdata = array( "is_logged_in" => "1" );
	    			
	    			if ($user->type == "admin")
	    				$userdata["is_admin"] = "1";
	    			else
	    				$userdata["is_developer"] = "1";

                    $userdata["user_id"] = $user->id;
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
    	->view( "forms/login_form", array("error"=>$error, "name"=>$name ))
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
     * Page to add a user account
     */
    function adduser() {
        if ( ! IS_LOGGED_IN)
            redirect("admin/login");

        if ( ! IS_ADMIN)
            redirect("admin");

        // the has been submitted
        if (post("user_form_submitted")) {
            $form = post("form");
            
            // checking form
            $this->form_validation->set_rules( "form[name]", "Name", "trim|required|min_length[5]|is_unique[users.name]");
            $this->form_validation->set_rules( "form[email]", "Email", "trim|required|min_length[5]|valid_email|is_unique[users.email]");
                   
            if (trim($form["password"]) != "") {
                $this->form_validation->set_rules("form[password]", "Password", "min_length[5]");
                $this->form_validation->set_rules("form[password2]", "Password confirmation", "min_length[5]");

                if ($form["password"] != $form["password2"])
                    $this->form_validation->set_rules("form[password2]", "Password confirmation", "matches[form[password]]");
            }
            
            // form OK
            if ($this->form_validation->run()) {
                unset($form["password2"]);
                $id = $this->user_model->insert_user($form);

                if ($form["type"] == "dev") {
                    $form["user_id"] = $id;
                    $this->developer_model->insert($form);
                }

                redirect("admin/edituser/$id");
            }
            else {
                // just reload the form and let the form_validation class display the errors
                $form["password"] = "";
                $form["password2"] = "";
                $this->layout->view("forms/user_form", array("form"=>$form))->load();
            }
        }

        // no form submitted
        else
            $this->layout->view("forms/user_form")->load();
    }

    // ----------------------------------------------------------------------------------

    /**
     * Page to edit an admin account
     */
    function edituser( $user_id = null ) {
        if ( ! IS_LOGGED_IN)
            redirect("admin/login");

        // the form has been submitted
        if (post("user_form_submitted")) {
            $form = post("form");
            $db_user = $this->user_model->get(array("id" => USER_ID));

            // checking form
            $this->form_validation->set_rules( "form[name]", "Name", "trim|required|min_length[5]");
            if ($form["name"] != $db_user->name)
                $this->form_validation->set_rules("form[name]", "Name", 'is_unique[users.name]');

            $this->form_validation->set_rules( "form[email]", "Email", "trim|required|min_length[5]|valid_email");
            if ($form["name"] != $db_user->name)
                $this->form_validation->set_rules("form[name]", "Email", 'is_unique[users.email]');
            
            
            $old_password_ok = true;

            if (trim($form["password"]) != "") {
                $this->form_validation->set_rules("form[password]", "Password", "min_length[5]");
                $this->form_validation->set_rules("form[password2]", "Password confirmation", "min_length[5]");
                $this->form_validation->set_rules("form[oldpassword]", "Old Password", "min_length[5]");
                
                if ($form["password"] != $form["password2"])
                    $this->form_validation->set_rules("form[password2]", "Password confirmation", "matches[form[password]]");

                if ( ! check_password($form["oldpassword"], $db_user->password)) {
                       $form["errors"] = "The oldpassword field does not match your current password.";
                    $old_password_ok = false;
                }
            }
            else
                unset($form["password"]);
            
            // form OK
            if ($this->form_validation->run() && $old_password_ok) {
                unset($form["password2"]);
                unset($form["oldpassword"]);
                $this->admin_model->update_user($form);

                $form["success"] = 'Your user account has been successfully updated.';
                $form["password"] = "";

                $this->layout->view("forms/user_form", array("form"=>$form))->load();
            }
            else {
                // just reload the form and let the form_validation class display the errors
                $form["password"] = "";
                $form["password2"] = "";
                $form["oldpassword"] = "";

                $this->layout->view("forms/user_form", array("form"=>$form))->load();
            }
        }

        // no form submitted
        // edit the current user or any user if user is an admin
        else {
            if (IS_ADMIN && isset($user_id))
                $form = $this->user_model->get(array("id" => $user_id));
            else
                $form = $this->user_model->get(array("id" => USER_ID));
            
            $form->password = "";
            $this->layout->view("forms/user_form", array("form"=>$form))->load();
        }
    }


    // ----------------------------------------------------------------------------------

    /**
     * Main hub with no content but the admin menu
     */
    function adddeveloper() {
        if (post("developer_form_submitted")) {
            $form = post("form");

            $this->form_validation->set_rules("form[name]", "Name", "trim|required|min_length[5]|is_unique[users.name]" );
            
            if ( ! isset($form["user_id"])) // $form comes from /addeveloper    but if user_id isset, $form comes from admin/adddeveloper
                $this->form_validation->set_rules("form[email]", "Email", "trim|required|min_length[5]|valid_email|is_unique[users.email]" );

            if (isset($form["password"]) && trim($form["password"]) != "") {
                $this->form_validation->set_rules("form[password]", "Password", "min_length[5]");
                $this->form_validation->set_rules("form[password2]", 'Password confirmation', "min_length[5]");
                
                if ($form["password"] != $form["password2"])
                    $this->form_validation->set_rules("form[password2]", "Password confirmation", "matches[form[password]]"); // this fails when passwords matches ???
            }

            unset($form["password2"]);
            $form["data"]["socialnetworks"] = clean_names_urls_array($form["data"]["socialnetworks"]);

            // save data if all is OK
            if ($this->form_validation->run()) {
                $id = $this->developer_model->insert($form);
                
                if (post("from_adddeveloper_page")) {
                    $form = array("success" => lang("adddeveloper_form_success"));
                    $this->session->set_flashdata( "adddeveloper_form", json_encode($form) );

                    redirect("adddeveloper");
                }
                else
                    redirect("admin/editdeveloper/$id");
            }
            else { // error
                unset($form["password"]);

                if (post("from_adddeveloper_page")) {
                    $form["errors"] = validation_errors(); // get errors from the form_validation class
                    $this->session->set_flashdata("adddeveloper_form", json_encode($form));
                    redirect("adddeveloper");
                }
                else
                    $this->layout->view("forms/developer_form", array("form"=>$form))->load();
            }
        }
        elseif (IS_ADMIN) 
            $this->layout->view("forms/developer_form")->load();
        else
            redirect("adddeveloper");
    }


    // ----------------------------------------------------------------------------------
    
    /**
     * Page to edit a developer account
     * @param int $profile_id The profile id of the developer to edit
     */
    function editdeveloper( $profile_id = null ) {
        if ( ! IS_LOGGED_IN)
            redirect("admin/login");


        if (post("select_developer_to_edit_form_submitted")) {
            $profile_id = trim(post("developer_id_text"));

            if ($profile_id == "") {
                $user_id = post("developer_id_select");
                $dev_profile = $this->developer_model->get(array("user_id"=>$user_id));
                $profile_id = $dev_profile->id;
            }

            redirect("admin/editdeveloper/$profile_id");
        }

        
        if (post("developer_form_submitted")) {
            $form = post("form");
            $db_data = $this->developer_model->get(array("id" => $form["id"]));

            // cheking name
            $this->form_validation->set_rules("form[name]", "Name", "trim|required|min_length[5]");
            if ($form["name"] != $db_data->name)
                $this->form_validation->set_rules("form[name]", "Name", "is_unique[profiles.name]");
            

            $form["data"]["socialnetworks"] = clean_names_urls_array($form["data"]["socialnetworks"]);

            // update data if all is OK
            if ($this->form_validation->run()) {
                $this->developer_model->update($form, $db_data);
                
                unset($form["password"]);
                $form["success"] = 'Your developer account has been successfully updated.';
                
                $this->layout->view( "forms/developer_form", array("form"=>$form) )->load();
            }
            else {
                unset($form["password"]);
                $this->layout->view("forms/developer_form", array("form"=>$form))->load();
            }
        } // end if form submitted

        // no form has been submitted, just show the form filled with data from the database
        elseif ($profile_id != null) { // if user is a developer, this will always be the case (see redirect above)
            if (IS_DEVELOPER)
                $profile_id = $this->user_model->get(array("id"=>USER_ID))->dev_profile_id;

            $dev = $this->developer_model->get(array("id"=>$profile_id));

            if ( ! $dev) {
                $dev["errors"] = "There is no developer with the profile id [$profile_id].";
                $this->layout->view("forms/select_developer_to_edit_form", array("form"=>$dev) )->load();
                return;
            }

            $this->layout
            ->view("forms/developer_form", array("form"=>$dev))
            ->load();
        }

        else // show to the admins the form to chose which devs to edit
            $this->layout->view("forms/select_developer_to_edit_form")->load();
    }


    // ----------------------------------------------------------------------------------

    /**
     * Page to add a game
     */
    function addgame() {
        if (post("game_form_submitted")) {
            $form = post("form");

            $this->form_validation->set_rules( "form[name]", "Name", 'trim|required|min_length[3]|is_unique[profiles.name]' );
            
            $form["data"]["screenshots"] = clean_names_urls_array( $form["data"]["screenshots"] );
            $form["data"]["videos"] = clean_names_urls_array( $form["data"]["videos"] );

            $form["data"]["socialnetworks"] = clean_names_urls_array( $form["data"]["socialnetworks"] );
            $form["data"]["stores"] = clean_names_urls_array( $form["data"]["stores"] );

            // save data if all is OK
            if ($this->form_validation->run() ) {
                $id = $this->game_model->insert($form);

                if (post("from_addgame_page")) {
                    $form = array("success" => lang("addgame_form_success"));
                    $this->session->set_flashdata( "addgame_form", json_encode($form) );
                    redirect("addgame");
                }
                else
                    redirect( "admin/editgame/".$id );
            }
            else {
                if (post("from_addgame_page")) {
                    $form["errors"] = validation_errors(); // get errors from the form_validation class
                    $this->session->set_flashdata("addgame_form", json_encode($form) ); // a coockie can only hold 4Kb of data
                    redirect("addgame");
                }
                else {
                    $this->layout
                    ->view( "forms/game_form", array("form"=>$form) )
                    ->load();
                }
            }
        }
        elseif (IS_ADMIN) 
            $this->layout->view( "forms/game_form" )->load();
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


        if (post("select_game_to_edit_form_submitted")) {
            $id = trim(post("game_id_text"));

            if ($id == "")
                $id = post("game_id_select");

            redirect("admin/editgame/$id");
        }

        
        if (post("game_form_submitted")) {
            $form = post("form");
            $db_data = $this->game_model->get(array("id" => $form["id"]));

            // cheking name
            $this->form_validation->set_rules( "form[name]", "Name", "trim|required|min_length[5]" );
            if ($form["name"] != $db_data->name)
                $this->form_validation->set_rules( "form[name]", "Name", 'is_unique[profiles.name]' );
            
            $form["data"]["screenshots"] = clean_names_urls_array( $form["data"]["screenshots"] );
            $form["data"]["videos"] = clean_names_urls_array( $form["data"]["videos"] );

            $form["data"]["socialnetworks"] = clean_names_urls_array( $form["data"]["socialnetworks"] );
            $form["data"]["stores"] = clean_names_urls_array( $form["data"]["stores"] );

            // update data if all is OK
            if ($this->form_validation->run() ) {
                $this->game_model->update($form, $db_data);
                
                $form["success"] = 'The game profile has been successfully updated.';
                
                $this->layout->view( "forms/game_form", array("form"=>$form) );
            }
            else
                $this->layout->view( "forms/game_form", array("form"=>$form) );
        } // end if (post("game_form_submitted")) {

        // no form has been submitted, just show the form filled with data from the database
        elseif ($id != null) {
            $game = $this->game_model->get(array("id"=>$id));

            if ( ! $game) {
                $form["errors"] = "There is no game with the profile id [$id].";
                $this->layout->view("forms/select_game_to_edit_form", array("form"=>$form) )->load();
                return;
            }

            // prevent developer to edit a game they don't own
            if (IS_DEVELOPER && $game->user_id != USER_ID) {
                $form["errors"] = "The game with id [$id] does not belong to you, you can't edit it.";
                $this->layout->view("forms/select_game_to_edit_form", array("form"=>$form) )->load();
                return;
            }

            if ($game === false) {
                $form = array("errors"=>"No game with id [$id] was found.");
                $this->layout->view("forms/select_game_to_edit_form", array("form"=>$form));
            }
            else
                $this->layout->view("forms/game_form", array("form"=>$game));
        }
        // show the form to chose which game to edit
        else
            $this->layout->view("forms/select_game_to_edit_form");

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
            $this->form_validation->set_rules("form[message]", 'Message text', "trim|required|min_length[10]");

            if ($this->form_validation->run()) {
                $this->admin_model->insert_message(post("form"));
                $form["success"] = "Message sent successfully.";
            }
        }


        if (post("delete_inbox_form_submitted") || post("delete_outbox_form_submitted")) {
            $this->admin_model->delete_messages(post("delete"));
            $form["success"] = "Message(s) deleted successfully.";
        }

        $messages = array("inbox"=>array(), "outbox"=>array());

        // inbox
        if (IS_ADMIN) {
            $messages["inbox"] = $this->admin_model->get_messages(
                array("administrator_id" => USER_ID, "sent_by_developer" => 1),
                "developer_id");
        }
        else {
            $messages["inbox"] = $this->admin_model->get_messages(
                array("developer_id" => USER_ID, "sent_by_developer" => 0),
                "administrator_id");
        }

        // outbox
        if (IS_ADMIN) {
            $messages["outbox"] = $this->admin_model->get_messages(
                array("administrator_id" => USER_ID, "sent_by_developer" => 0),
                "developer_id");
        }
        else {
            $messages["outbox"] = $this->admin_model->get_messages(
                array("developer_id" => USER_ID, "sent_by_developer" => 1),
                "administrator_id");
        }

        $this->layout
        ->view( "forms/message_form", array("form"=>$form, "messages"=>$messages) )
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
            $this->form_validation->set_rules("report_form[description]", 'Report description', "trim|required|min_length[10]");

            if ($this->form_validation->run()) {
                $this->admin_model->insert_report($report_form);
                $this->session->set_flashdata("report_success", lang("report_form_success"));
            }
            else {
                $this->session->set_flashdata("report_errors", validation_errors());

            }

            redirect($report_form["url"]."#report_form");
        }

        elseif (IS_LOGGED_IN) {
            $reports = array();
            $report_delete_success = false;

            if (post("delete_report_form_submitted")) {
                $this->admin_model->delete_reports(post("delete"));
                $report_delete_success = true;
            }

            if (IS_ADMIN)
                $reports = $this->admin_model->get_reports();
            else
                $reports = $this->admin_model->get_developer_reports(USER_ID);
            
            $success = "";
            if ($report_delete_success)
                $success = 'Reports(s) deleted successfully.';

            $this->layout->view("forms/admin_report_form", array("reports"=>$reports, "success"=>$success))->load();
        }

        else
            redirect("admin/login");
    }
}

/* End of file admin.php */
/* Location: ./application/controllers/admin.php */