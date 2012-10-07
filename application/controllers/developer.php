<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Developer extends CI_Controller {
    
    function __construct() {
        parent::__construct();

        set_page( 'developer' );
        
        $lang = userdata( 'language' );
        if( $lang )
            $this->lang->load( 'main', $lang );
    }


    // ----------------------------------------------------------------------------------

    /**
     * Main hub with no content but the admin menu
     */
    function index( $name_or_id = null ) {
    	$where = array();
    	if( is_numeric( $name_or_id ) )
    		$where['id'] = $name_or_id;
    	else
    		$where['name'] = title_url( $name_or_id );


        $db_dev = get_db_row( 'developers', $where );
        if( $db_dev == false )
        	redirect( 'home/404/developernotfound:'.$name_or_id );

        if( $db_dev->is_public == 0 )
            redirect( 'home/404/developerprivate' );

        unset( $db_dev->password );
        
        $this->layout
        ->view( 'full_developer_view', array('db_dev'=>$db_dev) )
        ->load();
    }
}

/* End of file developer.php */
/* Location: ./application/controllers/developer.php */