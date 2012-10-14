<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Game_model extends CI_Model {
    
    private $datetime_format = "" ;

    function __construct() {
        parent::__construct();
        $this->datetime_format = get_static_data('site')->date_formats->datetime_sql;
    }


    //----------------------------------------------------------------------------------

    /**
     * Create a game in the database
     * @param assoc array $data the raw data from the game_form view
     * @return int or bool the id of the newly inserted row or false
     */
    function insert_game( $form ) {
        // $form is the raw data from the game form
        // at this point we are sure we want to create the game
        $form['data'] = json_encode( $form['data'] );
        $form["creation_date"] = date_create()->format($this->datetime_format);

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
     * Method called by the review proccess when a game become public
     * @param  [type] $game_id [description]
     * @return [type]          [description]
     */
    function publish_game( $game_id ) {
        $db = array( 
            "profile_privacy" => "public",
            "publication_date" => date_create()->format($this->datetime_format)
            );

        $this->db->update("games", $db, "game_id=$game_id");
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
        $game = $this->main_model->get_row( 'games', $where, $value );

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


    //----------------------------------------------------------------------------------

    /**
     * Return games from the database to be put in a rss feed
     * Only public games, sorted by date Asc
     * @param  int $item_count The number of games to returns
     * @return object The databse object
     */
    function get_feed_games( $item_count ) {
        return $this->db
        ->from("games")
        ->where("profile_privacy", "public")
        ->order_by("publication_date", "asc")
        ->limit($item_count)
        ->get();
    }
}

/* End of file game_model.php */
/* Location: ./application/model/game_model.php */