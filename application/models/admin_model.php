<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_model extends CI_Model {
    
    private $datetime_format = "" ;

    function __construct() {
        parent::__construct();
        $this->datetime_format = get_static_data('site')->date_formats->datetime_sql;
    }


    //----------------------------------------------------------------------------------
    
    /**
     * Update a user in the database
     * @param assoc array $form The raw data from the user_form view
     */
    function update_user( $form ) {
        if (isset($form["password"]) && trim($form["password"]) != "")
            $form["password"] = hash_password($form["password"]);

        unset($form["type"]);
        unset($form["key"]);
        unset($form["creation_date"]);
        $this->db->update("users", $form, "user_id = ".$form["user_id"]);
    }


    // ----------------------------------------------------------------------------------

    /**
     * Insert a new message in the database
     * @param assoc array $form The raw data from the message_form view
     */
    function insert_message( $form ) {
        $form['date'] = date_create()->format($this->datetime_format); // date_create() = new DateTime()
        $this->db->insert("messages", $form);
    }


    // ----------------------------------------------------------------------------------

    /**
     * Retrieve messages from the database
     * @param assoc array $where The WHERE criteria, usually the owner_id or recipient_id
     * @param string $join_field The $field in the messages table to join to the developers table
     * @return object/false the DB object or false if nothing is found
     */
    function get_messages( $where, $join_table, $join_field ) {
        return $this->db
        ->from("messages")
        ->where($where)
        ->join($join_table, $join_field)
        ->get();
    }


    // ----------------------------------------------------------------------------------

    /**
     * Delete  messages from the database
     * @param assoc array/int/string $ids An array with the message's ids to delete or a single id
     */
    function delete_messages( $ids ) {
        if( !is_array( $ids ) )
            $ids = array($ids);

        foreach( $ids as $id )
            $this->db->or_where( 'message_id', $id );

        $this->db->delete( 'messages' );
    }


    // ----------------------------------------------------------------------------------

    /**
     */
    function get_feed_messages( $item_count, $user ) {
        return;

        if ($user->is_admin == true) {
            $where = array(
                "administrator_id"=>USER_ID, "sent_by_developer"=>1,
                "developers", "developers.developer_id=messages.developer_id");
        }
        else {
            $where = array("developer_id" => USER_ID, "sent_by_developer"=>0,
                "administrators", "administrators.administrator_id=messages.administrator_id");
        }


        $this->db->from("messages")
        ->where($where)
        ->join($join_table, $join_field);



        return $this->db->limit($item_count)->get();
    }


    //----------------------------------------------------------------------------------

    /**
     * Insert a new report in the data base
     * @param  array $report_form The data comming from the form
     */
    function insert_report( $report_form ) {
        $report_form["date"] = date_create()->format($this->datetime_format);
        unset($report_form["url"]);

        $this->db->insert("reports", $report_form);
    }
    

    //----------------------------------------------------------------------------------

    /**
     * Get reports from the database
     * @param  string $what What report type (dev or admin)
     * @return array       The reports in an array
     */
    function get_reports($what = null) {
        $reports = array();

        $this->db
        ->select("*")
        ->from("reports")
        ->order_by("date asc")

        ->select("profiles.type as profile_type")
        ->select("profiles.name as profile_name")
        ->join("profiles", "reports.profile_id = profiles.profile_id")
        ;
        

        if (isset($what))
            $this->db->where("type", $what);

        $db_reports = $this->db->get();
        
        foreach ($db_reports->result() as $report)
            $reports[] = $report;
        
        return $reports;
    }


    //----------------------------------------------------------------------------------

    /**
     * Retrieve all reports for this developer and its games
     * @param  string/int   $dev_id      The developer id
     * @return array        $dev_reports The reports in an array
     */
    function get_developer_reports( $dev_id ) {
        $dev_reports = array();

        // first get the developer reports
        $db_reports = $this->db

        ->select("*")
        ->from("reports")
        ->where("reports.profile_id", $dev_id)
        ->where("reports.type", "dev")
        ->order_by("date asc")

        ->select("profiles.type as profile_type")
        ->select("profiles.name as profile_name")
        ->join("profiles", "reports.profile_id = profiles.profile_id")

        ->get();

        foreach ($db_reports->result() as $report) 
            $dev_reports[] = $report;
        

        // get all games for this dev ...
        $games = $this->db
        ->select("profile_id")
        ->from("profiles")
        ->where("user_id", $dev_id)
        ->get();

        foreach ($games->result() as $game) {
            // ... then get the reports for each games
            $db_reports = $this->db
            ->from("reports")
            ->where("type", "dev")
            ->where("profile_id", $game->profile_id)
            ->get();

            foreach ($reports->result() as $report)
                $dev_reports[] = $report;
        }

        return $dev_reports;
    }


    //----------------------------------------------------------------------------------

    /**
     * Delete reports from the database
     * @param assoc array/int/string $ids An array with the message's ids to delete or a single id
     */
    function delete_reports( $ids ) {
        if ( ! is_array($ids) )
            $ids = array($ids);

        foreach ($ids as $id)
            $this->db->or_where('report_id', $id);

        $this->db->delete('reports');
    }
}

/* End of file admin_model.php */
/* Location: ./application/model/admin_model.php */