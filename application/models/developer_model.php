<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Developere_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }


    // ----------------------------------------------------------------------------------

    /**
     * Insert a new developer in the database
     * @param assoc array $form The raw data from the developer_form view
     * @return int/bool The id of the newly inserted row or false
     */
    function insert_developer( $form ) {
        // $form is the raw data from the developer form
        // at this point we are sure we want to create the developer, 
        // but we need to encode the password
        // and format a few fields
        
        // encode password if it exist
        if( trim( $form['password'] ) != '' ) {
            $password = encode_password( $form['password'] );
            $form['password'] = $password['hash'];
            $form['salt'] = $password['salt'];
        }

        // make sure arrays exists, or format them into a CSV string
        $arrays = array( 'operatingsystems', 'technologies', 'devices', 'stores' );

        foreach( $arrays as $array_name ) {
            if( isset( $form[$array_name] ) )
                $form[$array_name] = implode( ',', $form[$array_name] );
            else // happens when nothing was put in the form, $form[$array_name] is null
                $form[$array_name] = '';
        }

        $form['socialnetworks'] = json_encode( $form['socialnetworks'] );

        $this->db->insert( 'developers', $form );
        return $this->db->insert_id();
    }


    // ----------------------------------------------------------------------------------

    /**
     * Update a developer in the database, but only the modified field
     * @param assoc array $form The raw data from the developer_form view
     * @param object $db_data The db object to check $form against
     */
    function update_developer( $form, $db_data ) {
        // encode the password if it exists
        if( trim( $form['password'] ) != '' ) {
            $password = encode_password( $form['password'] );
            $form['password'] = $password['hash'];
            $form['salt'] = $password['salt'];
        }

        // make sure arrays exists, or format them into a CSV string
        $arrays = array('operatingsystems', 'technologies', 'devices', 'stores');

        foreach( $arrays as $array_name ) {
            if( isset( $form[$array_name] ) )
                $form[$array_name] = implode( ',', $form[$array_name] );
            else // happens when nothing was put in the form, $form[$array_name] is null
                $form[$array_name] = '';
        }

        $form['socialnetworks'] = json_encode( $form['socialnetworks'] );

        // now that everything is nicely formatted for databse
        // lets compare what $form data is different to the $db_data
        // and update only what has changed
        $id = $form['developer_id'];

        foreach( $form as $field => $value ) {
            if( $value == $db_data->$field )
                unset( $form[$field] );
        }
        
        if( count($form) > 0 )
            $this->db->update( 'developers', $form, 'developer_id = '.$id );
    }
}

/* End of file developer_model.php */
/* Location: ./application/model/developer_model.php */