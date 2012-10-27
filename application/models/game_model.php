<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Game_model extends CI_Model {
    
    private $date_format = "" ;

    function __construct() {
        parent::__construct();
        $this->date_format = get_static_data("site")->date_formats->date_sql;
    }


    //----------------------------------------------------------------------------------

    /**
     * Create a game in the database
     * @param assoc array $data the raw data from the game_form view
     * @return int or bool the id of the newly inserted row or false
     */
    function insert( $form ) {
        // $form is the raw data from the game form
        // at this point we are sure we want to create the game
        $form["type"] = "game";
        $form["privacy"] = "private";
        $form["creation_date"] = date_create()->format($this->date_format);
        $form["data"] = json_encode($form["data"]);

        $this->db->insert("profiles", $form );
        return $this->db->insert_id();
    }


    // ----------------------------------------------------------------------------------

    /**
     * Update a game in the database
     * @param assoc array $form The raw data from the developer_form view
     * @param object $db_data The db object to check $form against
     */
    function update( $form, $db_data ) {
        $id = $form["id"];
        $form["data"] = json_encode($form["data"]);

        foreach ($form as $field => $value) {
            if ($value == $db_data->$field)
                unset($form[$field]);
        }

        if (count($form) > 0)
            $this->db->update("profiles", $form, "id = $id");
    }


    // ----------------------------------------------------------------------------------

    /**
     * Method called by the review proccess when a game become public
     * @param  [type] $game_id [description]
     * @return [type]          [description]
     */
    function publish( $game_id ) {
        $db = array( 
            "privacy" => "public",
            "publication_date" => date_create()->format($this->date_format)
            );

        $this->db->update("profiles", $db, "id = '$game_id'");
    }


    // ----------------------------------------------------------------------------------

    /**
     * Return game from the database
     * @param array $where The WHERE criteria
     * @param boolean   $prep_data_for_form  Tell wether preparing the data 
     * @return array/false The array containing all the game profile's infos or false
     */
    function get( $where, $prep_data_for_form = false ) {
        $where["type"] = "game";
        $games = $this->db->from("profiles")->where($where)->get();

        if ($games->num_rows() == 0)
            return false;

        if ($games->num_rows() == 1)
            $games = $games->row();

        if ($prep_data_for_form)
            $games = set_default_game_infos($games);
        
        return $games;
    }


    //----------------------------------------------------------------------------------

    /**
     * Return the last games from the database to be put in a rss feed
     * @param  int $item_count The number of games to returns
     * @return object The database object
     */
    function get_feed_games( $item_count ) {
        return $this->db
        ->from("profiles")
        ->where("privacy", "public")
        ->order_by("publication_date", "asc")
        ->limit($item_count)
        ->get();
    }
}

/* End of file game_model.php */
/* Location: ./application/model/game_model.php */