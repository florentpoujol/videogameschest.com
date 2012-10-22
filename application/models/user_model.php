<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model {
    
    private $date_format = "" ;

    function __construct() {
        parent::__construct();
        $this->date_format = get_static_data('site')->date_formats->date_sql;
    }


    //----------------------------------------------------------------------------------
    
    /**
     * Insert a user in the database
     * @param assoc array $form The raw data from the user_form view
     */
    function insert( $form ) {
        if (isset($form["password"]) && trim($form["password"]) != "")
            $form["password"] = hash_password($form["password"]);
        else
            $form["password"] = hash_password(get_random_string(10));


        $form["key"] = md5(mt_rand());

        while ($this->main_model->get_row("key", "users", "key = '".$form["key"]."'") !== false)
            $form["key"] = md5(mt_rand());

        $form["creation_date"] = date_create()->format($this->date_format);
        unset($form["data"]);

        $this->db->insert("users", $form);
        return $this->db->insert_id();
    }


    //----------------------------------------------------------------------------------
    
    /**
     * Update a user in the database
     * @param assoc array $form The raw data from the user_form view
     */
    function update( $form ) {
        if (isset($form["password"]) && trim($form["password"]) != "")
            $form["password"] = hash_password($form["password"]);

        unset($form["key"]);
        unset($form["creation_date"]);
        $this->db->update("users", $form, "id = '".$form["id"]."'");
    }


    // ----------------------------------------------------------------------------------

    /**
     * Return user(s) from the database
     * @param array $where The WHERE criteria
     * @return array/false The array containing all the users infos or false
     */
    function get( $where ) {
        return $this->db->from("users")->where($where)->get()->row();
    }
}

/* End of file user_model.php */
/* Location: ./application/model/user_model.php */