<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main_model extends CI_Model {
    
    /**
     * Return the DB object related to several rows
     * @param string $table The table where to look
     * @param array/string $where=null An assoc array with where criteria or a single key as string
     * @param string $value=null If the $where parameter is a single key, this one is its value
     * @return object/false the DB object or false if nothing is found
     */
    function get_rows( $table, $criteria = null ) {
        $this->db->from($table);

        if (isset($criteria)) {
            foreach ($criteria as $criterion_name => $criterion_values) {
                if ( ! is_array($criterion_values))
                    $this->db->$criterion_name($criterion_values);
                else { // is array
                    foreach ($criterion_values as $key => $value)
                        $this->db->$criterion_name($key, $value);
                }
            }
        }

        $result = $this->db->get();

        if ($result->num_rows() <= 0)
            return false;

        return $result;
    }

    /**
     * Return the DB object related to several rows
     * @param string $table The table where to look
     * @param array/string $where=null An assoc array with where criteria or a single key as string
     * @param string $value=null If the $where parameter is a single key, this one is its value
     * @return object/false the DB object or false if nothing is found
     */
    function get_rows_old( $table, $where = null, $value = null ) {
        $this->db->from( $table );

        if( isset( $where ) ) {
            if( !is_array( $where ) && isset( $value ) )
                $where = array( $where => $value );
            
            foreach( $where as $field => $value )
                $this->db->where( $field, $value );
        }

        $result = $this->db->get();

        if( $result->num_rows() <= 0 )
            return false;

        return $result;
    }


    // ----------------------------------------------------------------------------------

    /**
     * Return the DB object related to one row
     * @param string $table The table where to look
     * @param array/string $where=null An assoc array with where criteria or a single key as string
     * @param string $value=null If the $where parameter is a single key, this one is its value
     * @return object/false the DB object or false if nothing is found
     */
    function get_row( $table, $where, $value = null ) {
        $result = $this->get_rows( $table, $where, $value );

        if( $result == false )
            return false;
        else
            return $result->row();
 
    }


    // ----------------------------------------------------------------------------------

    /**
     * Return only one information from a table
     * @param string $table The table where to look
     * @param string $searched_field The field where to look
     * @param array/string $where An assoc array with where criteria or a single key as string
     * @param string $value=null If the $where parameter is a single key, this one is its value
     * @return object/false the DB object or false if nothing is found
     */
    function get_info( $table, $searched_field, $where, $value = null ) {
        $result = $this->get_row( $table, $where, $value );

        if( $result == false )
            return false;
        else
            return $result->$searched_field;
    }  
}

/* End of file main_model.php */
/* Location: ./application/model/main_model.php */