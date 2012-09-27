<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library( 'encrypt' );
    }


    // ----------------------------------------------------------------------------------

    public $cache = array();

    /**
     * 
     *
     * @param the table where to look
     * @param an assoc array with where criteria or a single key (string)
     * @param if the last parameter is a single key, this one is its value
     * 
     * @return the DB object or false if nothing is found
     */
    function get_from_cache( $table, $where = null, $value = null ) {
       $pattern = $this->built_pattern( $table, $where, $value );

        if( array_key_exists( $pattern, $this->cache ) )
            return $this->cache[$attern];
        else 
            return false;
    }


    /**
     * 
     *
     * @param the table where to look
     * @param an assoc array with where criteria or a single key (string)
     * @param if the last parameter is a single key, this one is its value
     * 
     * @return the DB object or false if nothing is found
     */
    function cache( $data, $table, $where = null, $value = null ) {
        $pattern = $this->built_pattern( $table, $where, $value );

        $this->cache[$pattern] = $data;
    }


    function built_pattern( $table, $where = null, $value = null ) {
        $pattern = $table.'|';

        if( $where == null )
            return $pattern;

        if( is_string( $where ) && isset( $value ) )
            $where = array( $where => $value );

        foreach( $where as $key => $value )
            $pattern .= "$key=$value|";

        return $pattern;
    }



    // ----------------------------------------------------------------------------------

    /**
     * Return the DB object related to several rows
     *
     * @param the table where to look
     * @param an assoc array with where criteria or a single key (string)
     * @param if the last parameter is a single key, this one is its value
     * 
     * @return the DB object or false if nothing is found
     */
    function get_rows( $table, $where = null, $value = null ) {
        /*$cache = $this->get_from_cache( $table, $where, $value );
        if( $cache != false )
            return $cache;*/

        $this->db->from( $table );

        if( isset( $where ) ) {
            if( !is_array( $where ) && isset( $value ) )
                $where = array( $where => $value );
            //var_dump($where);
            foreach( $where as $field => $value )
                $this->db->where( $field, $value );
        }

        $result = $this->db->get();

        if( $result->num_rows() <= 0 )
            return false;

        //$this->cache( $result, $table, $where, $value );
        return $result;
    }


    // ----------------------------------------------------------------------------------

    /**
     * Return the DB object related to one row
     *
     * @param the table where to look
     * @param an assoc array with where criteria or a single key (string)
     * @param if the last parameter is a single key, this one is its value
     * @param the row id to be returned
     * 
     * @return the DB object or false if nothing is found
     */
    function get_row( $table, $where, $value = null, $rowId = null ) {
        $result = $this->get_rows( $table, $where, $value );

        if( $result == false )
            return false;
        else
            return $result->row( $rowId );
 
    }


    // ----------------------------------------------------------------------------------

    /**
     *
     */
    function get_info( $table, $searched_field, $where, $value = null ) {
        $result = $this->get_row( $table, $where, $value );

        if( $result == false )
            return false;
        else
            return $result->$searched_field;
    }
    

    function get_data( $table, $where, $value = null ) {
        $result = $this->get_info( $table, 'data', $where, $value );

        if( $result == false )
            return false;
        else
            return json_decode( $result );
    }

    

    function create_user( $data ) {
        // data is th raw data from the developer form
        // at this point we are sure we want to create the user, 
        // but we need to encode the password
        // and encode a few field in JSON
        
        if( trim( $data['password'] ) != '' )
            $data['password'] = $this->encrypt->sha1( $data['password'] );

        unset( $data['password2'] );
        unset( $data['developer_form_submitted'] );
        unset( $data['errors'] );

        // these field are PHP array that contain ids
        // but we need to store a JSON array that contains names related to these ids
        $arrays = array( 'operatingsystems', 'engines', 'devices', 'stores', 'socialnetworks' );

        foreach( $arrays as $array_name )
            $data[$array_name] = json_encode( $data[$array_name] );
        
        //var_dump( $data );


        $this->db->insert( 'users', $data );
        return $this->get_info( 'users', 'id', 'name', $data['name'] );
    }

}

/* End of file main_model.php */
/* Location: ./application/model/main_model.php */