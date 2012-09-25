<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
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
    function get_rows( $table, $where, $value = null ) {
        $this->db->from( $table );

        if( $value != null )
            $where = array( $where => $value );

        foreach( $where as $field => $value )
            $this->db->where( $field, $value );

        /*if( is_numeric( $name ) )
            $this->db->where( 'id', $name )->where( 'statut', 'public' );
        else {

            $this->db->where( 'name', title_url( $name ) )->where( 'statut', 'public' );
        }*/

        $result = $this->db->get();

        if( $result->num_rows() <= 0 )
            return false;

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
    function get_infos( $table, $searched_field, $where, $value = null ) {
        $this->db
        ->select( $searched_field )
        ->from( $table );

        if( $value != null )
            $where = array( $where => $value );

        foreach( $where as $field => $value)
            $this->db->where( $field, $value );

        $result = $this->db->get();

        if( $result->num_rows() <= 0 )
            return false;

        return $result->row()->$searched_field;
    }

}

/* End of file main_model.php */
/* Location: ./application/model/main_model.php */