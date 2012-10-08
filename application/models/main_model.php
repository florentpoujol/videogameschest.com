<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }


    // ----------------------------------------------------------------------------------

    /**
     * Return the DB object related to several rows
     * @param string $table The table where to look
     * @param array/string $where=null An assoc array with where criteria or a single key as string
     * @param string $value=null If the $where parameter is a single key, this one is its value
     * @return object/false the DB object or false if nothing is found
     */
    function get_rows( $table, $where = null, $value = null ) {
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
    

    // ----------------------------------------------------------------------------------

    /**
     * Insert a new developer in the database
     * @param assoc array $form The raw data from the developer_form view
     * @return int/bool The id of the newly inserted row or false
     */
    function insert_developer( $form ) {
        $this->load->library( 'encrypt' );
        
        // $form is the raw data from the developer form
        // at this point we are sure we want to create the developer, 
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


    // ----------------------------------------------------------------------------------

    /**
     * Update a developer in the database, but only the modified field
     * @param assoc array $form The raw data from the developer_form view
     * @param object $db_data The db object to check $form against
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


    // ----------------------------------------------------------------------------------

    /**
     * Create a game in the database
     * @param assoc array $data the raw data from the game_form view
     * @return int or bool the id of the newly inserted row or false
     */
    function insert_game( $form ) {
        // $form is the raw data from the game form
        // at this point we are sure we want to create the game
        $form['data'] = json_encode( $form['data'] );

        $this->db->insert( 'games', $form );
        return $this->db->insert_id();
    }


    // ----------------------------------------------------------------------------------

    /**
     * Update a game in the database
     * @param assoc array $form The raw data from the developer_form view
     * @param object $db_data The db object to check $form against
     */
    function update_game( $form, $db_data ) {
        $id = $form['game_id'];
        $form['data'] = json_encode( $form['data'] );

        foreach( $form as $field => $value ) {
            if( $value == $db_data->$field )
                unset( $form[$field] );
        }

        if( count($form) > 0 )
            $this->db->update( 'games', $form, 'game_id = '.$id );
    }


    // ----------------------------------------------------------------------------------

    /**
     * Return games from the database
     * Make sure that all potential data keys exists and have a default value
     * @param array/string $where An assoc array with where criteria or a single key as string
     * @param string $value=null If the $where parameter is a single key, this one is its value
     * @return object/false the DB object or false if nothing is found
     */
    function get_game( $where, $value = null ) {
        $game = $this->get_row( 'games', $where, $value );

        if( $game == false )
            return false;

        $data = json_decode( $game->data, true );

        // make sure keys exists and set a default value if needed
        $string_keys = array( 'pitch', 'logo', 'blogfeed', 'website', 'country',
        'publishername', 'price', 'soundtrack' );

        foreach( $string_keys as $key ) {
            if( !isset( $data[$key] ) )
                $data[$key] = '';
        }

        // arrays
        $array_keys = array('technologies', 'operatingsystems', 'devices',
         'genres', 'themes', 'viewpoints', 'nbplayers',  'tags' );

        foreach( $array_keys as $key ) {
            if( !isset( $data[$key] ) )
                $data[$key] = array();
        }

        // array( 'names'=>array(), 'urls'=>array() )
        $names_urls_array_keys = array('screenshots', 'videos', 'socialnetworks', 'stores');

        foreach( $names_urls_array_keys as $key ) {
            if( !isset( $data[$key] ) )
                $data[$key] = array( 'names' => array() );
        }

        $game->data = $data;
        return $game;
    }


    // ----------------------------------------------------------------------------------

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
}


// ----------------------------------------------------------------------------------

    /*public $cache = array();

    /*
     * 
     *
     * @param the table where to look
     * @param an assoc array with where criteria or a single key (string)
     * @param if the last parameter is a single key, this one is its value
     * 
     * @return the DB object or false if nothing is found
     *
    function get_from_cache( $table, $where = null, $value = null ) {
       $pattern = $this->built_pattern( $table, $where, $value );

        if( array_key_exists( $pattern, $this->cache ) )
            return $this->cache[$attern];
        else 
            return false;
    }


    /*
     * 
     *
     * @param the table where to look
     * @param an assoc array with where criteria or a single key (string)
     * @param if the last parameter is a single key, this one is its value
     * 
     * @return the DB object or false if nothing is found
     *
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
    */

/* End of file main_model.php */
/* Location: ./application/model/main_model.php */