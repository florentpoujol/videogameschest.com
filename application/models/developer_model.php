<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Developer_model extends CI_Model {
    
    private $date_format = "" ;

    function __construct() {
        parent::__construct();
        $this->date_format = get_static_data('site')->date_formats->date_sql;
    }


    //----------------------------------------------------------------------------------

    /**
     * Insert a new developer in the database
     * @param assoc array $form The raw data from the developer_form view
     * @return int/bool The id of the newly inserted row or false
     */
    function insert( $form ) {
        $form["type"] = "dev";

        if ( ! isset($form["user_id"])) // $form comes from adddeveloper, need to add a new user first
            $form["user_id"] = $this->user_model->insert($form);
        
        $form["privacy"] = "private";
        $form["creation_date"] = date_create()->format($this->date_format);
        unset($form["email"]);
        unset($form["password"]);

        if (isset($form["data"])) // data is not set when adding a dev profil from admin/adduser
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
    function update( $form, $db_data ) {
        $id = $form["id"];
        $form["data"] = json_encode($form["data"]);

        foreach ($form as $field => $value) {
            if ($value == $db_data->$field)
                unset($form[$field]);
        }
        
        if (count($form) > 0)
            $this->db->update("profiles", $form, "id = '$id'");
    }


    // ----------------------------------------------------------------------------------

    /**
     * Return developer from the database
     * @param array $where The WHERE criteria
     * @param boolean   $prep_data_for_form  Tell wether preparing the data 
     * @return array/false The array containing all the game profile's infos or false
     */
    function get( $where, $prep_data_for_form = false ) {
        $where["type"] = "dev";
        $devs = $this->db->from("profiles")->where($where)->get();

        if ($devs == false)
            return false;

        if ($devs->num_rows() == 1)
            $devs = $devs->row();

        if ($prep_data_for_form)
            $devs = init_dev_infos($devs);
        
        return $devs;
    }


    //----------------------------------------------------------------------------------

    /**
     * Return the new developers from the database to be put in a rss feed
     * @param  int  $item_count The number of games to returns
     * @return object The database object
     */
    function get_feed_developers( $item_count ) {
        return $this->db
        ->from("developers")
        ->where("is_public", "1")
        ->order_by("publication_date", "asc")
        ->limit($item_count)
        ->get();
    }
}

/* End of file developer_model.php */
/* Location: ./application/model/developer_model.php */