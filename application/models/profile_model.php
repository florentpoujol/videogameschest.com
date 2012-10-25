<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile_model extends CI_Model {
    
    private $datetime_format = "" ;

    function __construct() {
        parent::__construct();
        $this->datetime_format = $this->static_model->site->date_formats->datetime_sql;
        $this->date_format = $this->static_model->site->date_formats->date_sql;
    }


    //----------------------------------------------------------------------------------

    /**
     * Insert a new profile in the database
     * @param assoc array $form The raw data from the developer_form or game_form view
     * @return int/bool The id of the newly inserted row or false
     */
    function insert_profile( $form ) {
        // create user first if it is a dev
        if (isset($form["type"]) && $form["type"] == "dev") {
            $user_infos = $form;
            unset($user_infos["data"]);
            
            $form["user_id"] = $this->admin_model->insert_user("users", $user_infos);
        }
        elseif ( ! isset($form["type"]) )
            $form["type"] = "game";

        // now insert profile
        unset($form["email"]);
        unset($form["password"]);
        $form["privacy"] = "private";
        $form["creation_date"] = date_create()->format($this->date_format);

        $form["data"] = json_encode($form["data"]);
        
        $this->db->insert("profiles", $form);
        return $this->db->insert_id();
    }


    // ----------------------------------------------------------------------------------

    /**
     * Update a developer in the database, but only the modified field
     * @param assoc array $form The raw data from the developer_form view
     * @param object $db_data The db object to check $form against
     */
    function update_profile( $form, $db_data ) {
        $id = $form["id"];
        $form["data"] = json_encode($form["data"]);

        //if (isset($form["privacy"]) && $form["privacy"] == "public")
        //    $form["publication_date"] = date_create()->format($this->datetime_format);

        foreach ($form as $field => $value) {
            if ($value == $db_data->$field)
                unset($form[$field]);
        }
        
        if (count($form) > 0)
            $this->db->update("profiles", $form, "id = '$id'");
    }


    // ----------------------------------------------------------------------------------

    /**
     * Return a developer from the database
     * @param array $where The WHERE criteria
     * @return array The array containing all the developer profile's infos or false
     */
    function get_developer( $where ) {
        $dev = $this->main_model->get_row("*", "profiles", $where);

        if ($dev == false)
            return false;

        return check_dev_infos($dev);
    }


    // ----------------------------------------------------------------------------------

    /**
     * Return game from the database
     * @param array $where The WHERE criteria
     * @return array/false The array containing all the game profile's infos or false
     */
    function get_game( $where ) {
        $game = $this->main_model->get_row("*", "profiles", $where);

        if ($game == false)
            return false;

        return check_game_infos($game);
    }


    //----------------------------------------------------------------------------------

    /**
     * Return the new profiles from the database to be put in a rss feed
     * @param  int  $item_count The number of games to returns
     * @param  string $profile_type The profile type (dev or game)
     * @return object The database object
     */
    function get_new_profiles( $item_count, $profile_type ) {
        return $this->db
        ->from("profiles")
        ->where("type", $profile_type)
        ->where("privacy", "public")
        ->order_by("publication_date", "asc")
        ->limit($item_count)
        ->get();
    }
}

/* End of file developer_model.php */
/* Location: ./application/model/developer_model.php */