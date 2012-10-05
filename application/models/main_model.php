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
     * Return only one information from a table
     *
     * @param string $table the table where to look
     * @param string $searched_field the name of the searched field
     * @param assoc array or string $where where criteria or a single key
     * @param string or null if $where is a single key, $value is its value
     * 
     * @return the DB object or false if nothing is found
     */
    function get_info( $table, $searched_field, $where, $value = null ) {
        $result = $this->get_row( $table, $where, $value );

        if( $result == false )
            return false;
        else
            return $result->$searched_field;
    }
    

    // ----------------------------------------------------------------------------------

    /**
     * Create a developer in the database
     * @param assoc array $data the raw data from the developer_form view
     * @return int or bool the id of the newly inserted row or false
     */
    function create_developer( $form ) {
        // data is the raw data from the developer form
        // at this point we are sure we want to create the user, 
        // but we need to encode the password
        // and format a few fields
        
        // encode password if it exist
        if( trim( $form['password'] ) != '' )
            $form['password'] = $this->encrypt->sha1( $form['password'] );

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

    /**
     * Update a developer in the database
     * @param assoc array $form the raw data from the developer_form view
     * @param assoc array $db_data the db object
     */
    function update_developer( $form, $db_data ) {
        // encode the password if it exists
        if( trim( $form['password'] ) != '' )
            $form['password'] = $this->encrypt->sha1( $form['password'] );

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
        // lets compare what form data is different to the db data
        // and update only what has changed
        $id = $form['id'];

        foreach( $form as $field => $value ) {
            if( $value == $db_data->$field )
                unset( $form[$field] );
        }
        
        if( count($form) > 0 )
            $this->db->update( 'developers', $form, 'id = '.$id );
    }


    // ----------------------------------------------------------------------------------

    /**
     * Create a game in the database
     * @param assoc array $data the raw data from the game_form view
     * @return int or bool the id of the newly inserted row or false
     */
    function create_game( $form ) {
        // data is the raw data from the game form
        // at this point we are sure we want to create the game, 
        // but we need to format a few fields
        
        // make sure arrays exists, or format them into a CSV string
        /*$arrays = array( 'operatingsystems', 'technologies', 'devices', 'stores',
        'nbplayers', 'themes', 'genres', 'tags', 'viewpoints' );

        foreach( $arrays as $array_name ) {
            if( isset( $form[$array_name] ) )
                $form[$array_name] = implode( ',', $form[$array_name] );
            else // happens when nothing was put in the form, $form[$array_name] is null
                $form[$array_name] = '';
        }*/

        $form['data'] = json_encode( $form['data'] );

        $this->db->insert( 'games', $form );
        return $this->db->insert_id();
    }

    /**
     * Update a game in the database
     * @param assoc array $form the raw data from the developer_form view
     * @param assoc array $db_data the db object
     */
    function update_game( $form, $db_data ) {
        // encode the password if it exists
        if( trim( $form['password'] ) != '' )
            $form['password'] = $this->encrypt->sha1( $form['password'] );

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
        // lets compare what form data is different to the db data
        // and update only what has changed
        $id = $form['id'];

        foreach( $form as $field => $value ) {
            if( $value == $db_data->$field )
                unset( $form[$field] );
        }
        
        if( count($form) > 0 )
            $this->db->update( 'developers', $form, 'id = '.$id );
    }


    // ----------------------------------------------------------------------------------

    /**
     * Update an administrator in the database
     * @param assoc array $form the raw data from the admin_form view
     */
    function update_admin( $form ) {
        if( trim( $form['password'] ) != '' )
            $form['password'] = $this->encrypt->sha1( $form['password'] );
    }
}

/* End of file main_model.php */
/* Location: ./application/model/main_model.php */