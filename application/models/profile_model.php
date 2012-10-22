<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile_model extends CI_Model {
    
    private $datetime_format = "" ;

    function __construct() {
        parent::__construct();
        $this->datetime_format = get_static_data('site')->date_formats->datetime_sql;
        $this->date_format = get_static_data('site')->date_formats->date_sql;
    }


    //----------------------------------------------------------------------------------

    /**
     * Insert a new developer in the database
     * @param assoc array $form The raw data from the developer_form view
     * @return int/bool The id of the newly inserted row or false
     */
    function insert_developer( $form ) {
        // encode password if it exist
        if (isset($form["password"]) && trim( $form['password'] ) != '' )
            $form['password'] = hash_password( $form['password'] );
        
        $form["creation_date"] = date_create()->format($this->datetime_format);
        $form["key"] = md5(mt_rand());
        $form["type"] = "dev";

        $user_infos = $form;
        unset($user_infos["data"]);
        
        $this->db->insert("users", $user_infos);

        $form["user_id"] = $this->db->insert_id();
        $form["privacy"] = "private";
        unset($form["email"]);
        unset($form["password"]);
        unset($form["key"]);

        $form["data"] = json_encode( $form["data"] );
        
        $this->db->insert("profiles", $form);
        return $this->db->insert_id();
    }
    function machin
    {

    }

    // ----------------------------------------------------------------------------------

    /**
     * Update a developer in the database, but only the modified field
     * @param assoc array $form The raw data from the developer_form view
     * @param object $db_data The db object to check $form against
     */
    function update_developer( $form, $db_data ) {
        // encode the password if it exists
        if (isset($form["password"]) && trim($form["password"] ) != "")
            $form["password"] = hash_password($form["password"]);


        $id = $form["developer_id"];
        $form["data"] = json_encode($form["data"]);

        if (isset($form["is_public"]) && $form["is_public"] == "1")
            $form["publication_date"] = date_create()->format($this->datetime_format);

        foreach( $form as $field => $value ) {
            if( $value == $db_data->$field )
                unset( $form[$field] );
        }
        
        if( count($form) > 0 )
            $this->db->update( 'developers', $form, 'developer_id = '.$id );
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