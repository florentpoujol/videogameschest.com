<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Developer_model extends CI_Model {
    
    /**
     * Insert a new developer in the database
     * @param assoc array $form The raw data from the developer_form view
     * @return int/bool The id of the newly inserted row or false
     */
    function insert_developer( $form ) {
        // $form is the raw data from the developer form
        // at this point we are sure we want to create the developer, 
        
        // encode password if it exist
        if( trim( $form['password'] ) != '' )
            $form['password'] = hash_password( $form['password'] );

        $form['data'] = json_encode( $form['data'] );

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
        if( trim( $form['password'] ) != '' )
            $form['password'] = hash_password( $form['password'] );


        $id = $form['developer_id'];
        $form['data'] = json_encode( $form['data'] );

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