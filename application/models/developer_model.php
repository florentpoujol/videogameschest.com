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


    // ----------------------------------------------------------------------------------

    /**
     * Return developers from the database
     * Make sure that all potential data keys exists and have a default value
     * @param array/string $where An assoc array with where criteria or a single key as string
     * @param string $value=null If the $where parameter is a single key, this one is its value
     * @return object/false the DB object or false if nothing is found
     */
    function get_developer( $where, $value = null ) {
        $dev = $this->main_model->get_row( 'developers', $where, $value );

        if( $dev == false )
            return false;

        $data = json_decode( $dev->data, true );

        // make sure keys exists and set a default value if needed
        $string_keys = array( 'pitch', 'logo', 'blogfeed', 'website', 'country' );

        foreach( $string_keys as $key ) {
            if( !isset( $data[$key] ) )
                $data[$key] = '';
        }

        // arrays
        $array_keys = array('technologies', 'operatingsystems', 'devices', 'stores' );

        foreach( $array_keys as $key ) {
            if( !isset( $data[$key] ) )
                $data[$key] = array();
        }

        // array( 'names'=>array(), 'urls'=>array() )
        $names_urls_array_keys = array('socialnetworks');

        foreach( $names_urls_array_keys as $key ) {
            if( !isset( $data[$key] ) )
                $data[$key] = array( 'names' => array() );
        }

        $dev->data = $data;
        $dev->report_data = json_decode($dev->report_data, true);

        return $dev;
    }
}

/* End of file developer_model.php */
/* Location: ./application/model/developer_model.php */