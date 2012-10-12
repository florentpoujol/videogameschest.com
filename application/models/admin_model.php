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
        $type = $report_form["item_type"];
        $db_report = get_db_row( $type."s", $type."_id", $report_form["item_id"] );

        if ($db_report === false)
            return;

        $report_data = json_decode($db_report->report_data, true);
        $report_type = $report_form["type"];
        
        if ( ! isset( $report_data[$report_type] ) )
            $report_data[$report_type] = array();

        $report_data[$report_type][] = $report_form["description"];
        
        $count = $db_report->report_count;
        $count++;

        $db_report = array(
            "report_count" => $count,
            "report_data"  => json_encode($report_data)
        );

        $this->db->update( $type."s", $db_report, $type."_id = ".$report_form["item_id"] );
    }
}

/* End of file admin_model.php */
/* Location: ./application/model/admin_model.php */