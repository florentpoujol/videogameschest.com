<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_model extends CI_Model {
    
    /**
     * Update an administrator in the database
     * @param assoc array $form The raw data from the admin_form view
     */
    function update_admin( $form ) {
        if( trim( $form['password'] ) != '' )
            $form['password'] = $this->encrypt->sha1( $form['password'] );
    }


    // ----------------------------------------------------------------------------------

    /**
     * Insert a new message in the database
     * @param assoc array $form The raw data from the message_form view
     */
    function insert_message( $form ) {
        $form['date'] = DateTime::__construct('Now')->format('Y-m-d H:i:s'); // DateTime::__construct('Now') = date_create()
        
        // first insert the copy of the sender
        $form['owner_id'] = $form['sender_id'];
        $this->db->insert( 'messages', $form );

        // then the copy for the recipient
        $form['owner_id'] = $form['recipient_id'];
        $this->db->insert( 'messages', $form );
    }


    // ----------------------------------------------------------------------------------

    /**
     * Retrieve messages from the database
     * @param assoc array $where The WHERE criteria, usually the owner_id or recipient_id
     * @param string $join_field The $field in the messages table to join to the developers table
     * @return object/false the DB object or false if nothing is found
     */
    function get_messages( $where, $join_field = null ) {
        $this->db
        ->from( 'messages' )
        ->where( $where );

        if( $join_field != null )
            $this->db->join( 'developers', 'developers.developer_id = messages.'.$join_field );

        return $this->db->get();
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


    //----------------------------------------------------------------------------------

    /**
     * Insert a new report in the data base
     * @param  array $report_form The data comming from the form
     */
    function insert_report( $report_form ) {
        $date = new DateTime();
        $report_form["date"] = $date->format('Y-m-d H:i:s');
        $this->db->insert( "reports", $report_form );
    }
    function insert_report_old( $report_form ) {
        $type = $report_form["item_type"];
        $db_report = get_db_row( $type."s", $type."_id", $report_form["item_id"] );

        if ($db_report === false)
            return;

        $report_data = json_decode($db_report->report_data, true);
        $report_recipient = $report_form["recipient"];

        if ( ! isset( $report_data[$report_recipient] ) )
            $report_data[$report_recipient] = array();

        $report_data[$report_recipient][] = $report_form["description"];
        
        $count = $db_report->report_count;
        $count++;

        $db_report = array(
            "report_count" => $count,
            "report_data"  => json_encode($report_data)
        );

        $this->db->update( $type."s", $db_report, $type."_id = ".$report_form["item_id"] );
    }


    //----------------------------------------------------------------------------------

    /**
     * [get_reports description]
     * @param  [type] $table [description]
     * @param  string $what  [description]
     * @return [type]        [description]
     */
    function get_reports($what, $order_by) {
        $reports = array();

        $this->db
        ->from('reports')
        ->order_by($order_by);

        if ($what != 'both')
            $this->db->where("recipient", $what);

        $db_reports = $this->db->get();

        foreach ($db_reports->result() as $report) {
            $reports[] = $report;
        }

        return $reports;
    }


    function get_developer_reports( $dev_id, $get_admin_reports = false ) {
        $dev_reports = array();

        // first get the developer reports
        $this->db
        ->from("reports")
        ->where("profile_type", "developer")
        ->where("profile_id", $dev_id)
        ->sort_by("date", "asc");

        if ( ! $get_admin_reports)
            $this->db->where("recipient", "developer");

        $reports = $this->db->get();

        foreach ($reports->result() as $report) {
            $dev_reports[] = $report;
        }

        // get all games for this dev
        $games = $this->db->select("game_id")->from("games")
        ->where("developer_id", $dev_id)->get();

        foreach ($games->result() as $game) {
            // then get the reports for each games
            $this->db
            ->from("reports")
            ->where("profile_type", "game")
            ->where("profile_id", $game->game_id);

            if ( ! $get_admin_reports)
                $this->db->where("recipient", "developer");

            $reports = $this->db->get();

            foreach ($reports->result() as $report)
                $dev_reports[] = $report;
        }

        return $dev_reports;
    }
}

/* End of file admin_model.php */
/* Location: ./application/model/admin_model.php */