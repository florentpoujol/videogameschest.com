<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Developer_model extends CI_Model {
    
    private $datetime_format = "" ;

    function __construct() {
        parent::__construct();
        $this->datetime_format = get_static_data('site')->date_formats->datetime_sql;
    }


    //----------------------------------------------------------------------------------

    /**
     * Insert a new developer in the database
     * @param assoc array $form The raw data from the developer_form view
     * @return int/bool The id of the newly inserted row or false
     */
    function insert_developer( $form ) {
        // $form is the raw data from the developer form
        
        // encode password if it exist
        if (trim( $form['password'] ) != '' )
            $form['password'] = hash_password( $form['password'] );
        

        $form["data"] = json_encode( $form["data"] );
        $form["creation_date"] = date_create()->format($this->datetime_format);
        $form["profile_key"] = md5(mt_rand());

        $this->db->insert("developers", $form);
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
        if (trim($form["password"] ) != "")
            $form["password"] = hash_password($form["password"]);
        else
            unset($form["password"]);

        $id = $form["developer_id"];
        $form["data"] = json_encode($form["data"]);

        if (isset($form["is_public"]) && $form["is_public"] == "1")
            $form["publication_date"] = date_create()->format($this->datetime_format);

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


    //----------------------------------------------------------------------------------

    /**
     * Return the new developers from the database to be put in a rss feed
     * @param  int  $item_count The number of games to returns
     * @return object The database object
     */
    function get_feed_developers( $item_count ) {
        return $this->db
        ->from("developers")
        ->where("is_public", "1")
        ->order_by("publication_date", "asc")
        ->limit($item_count)
        ->get();
    }
}

/* End of file developer_model.php */
/* Location: ./application/model/developer_model.php */