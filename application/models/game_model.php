<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
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
}

/* End of file game_model.php */
/* Location: ./application/model/game_model.php */