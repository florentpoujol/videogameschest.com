<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main_model extends CI_Model {
    
    /**
     * Return the DB object related to several rows
     * @param  string/array $select   The SELECT criterion or an array of criteria
     * @param  string       $from     The FROM criterion, the table to search in
     * @param  string/array $where    The WHERE criteria
     * @param  string       $order_by The ORDER_BY criteria
     * @param  string       $limit    The LIMIT criterion
     * @param  string       $limit_end The LIMIT end criterion
     * @return Object/boolean $result The database object, or false if nothing is found
     */
    function get_rows( $select, $from = null, $where = null, $order_by = null, $limit = null, $limit_end = null ) {
        $criteria = array();

        // build criteria
        if (is_array($select))
            $criteria = $select;
        else {
            $args = array("select", "from", "where", "order_by");
            foreach ($args as $arg) {
                if (isset(${$arg}))
                    $criteria[$arg] = ${$arg};
            }

            if (isset($limit_end))
                $this->db->limit($limit, $limit_end);
            elseif (isset($limit))
                $this->db->limit($limit);
        }

        // built SQL request
        foreach ($criteria as $criterion_name => $criterion_values) {
            if ( ! is_array($criterion_values))
                $this->db->$criterion_name($criterion_values);
            else { // is array
                foreach ($criterion_values as $key => $value)
                    $this->db->$criterion_name($key, $value);
            }
        }

        $result = $this->db->get();

        if ($result->num_rows() <= 0)
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
    function get_row( $select, $from = null, $where = null, $order_by = null, $limit = null, $limit_end = null ) {
        $result = $this->get_rows( $select, $from, $where, $order_by, $limit, $limit_end );

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
    /*function get_info( $select, $from, $where, $order_by, $limit, $limit_end ) {
        $result = $this->get_row( $select, $from, $where, $order_by, $limit, $limit_end );

        if( $result == false )
            return false;
        else
            return $result->$searched_field;
    }  */
}

/* End of file main_model.php */
/* Location: ./application/model/main_model.php */