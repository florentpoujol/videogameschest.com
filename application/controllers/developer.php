<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Developer extends CI_Controller {
    
    function __construct() {
        parent::__construct();

        set_page('developer');
        
        $lang = userdata('language');
        if ($lang)
            $this->lang->load( 'main', $lang );
    }


    // ----------------------------------------------------------------------------------

    /**
     * Main hub with no content but the admin menu
     */
    function index( $name_or_id = null ) {
    	$where = array();
    	if( is_numeric( $name_or_id ) )
    		$where['developer_id'] = $name_or_id;
    	else
    		$where['name'] = url_to_name( $name_or_id );

        $db_dev = $this->developer_model->get_developer( $where );
        
        if ($db_dev === false)
            redirect( 'home/404/developernotfound:'.$name_or_id );

        // display page when the dev is public or the visitor an admin
        if ($db_dev->is_public == 'public' || userdata('is_admin')) 
        {
            // get feed infos
            $this->load->library('RSSReader');
            $db_dev->feed_items = $this->rssreader->parse( $db_dev->data['blogfeed'] )->get_feed_items(6);
            
            $this->layout
            ->view( 'full_developer_view', array('db_dev'=>$db_dev) )
            ->view("forms/report_form")
            ->load();
        }
        else
            redirect( 'home/404/developerprivate' );
    }
}

/* End of file developer.php */
/* Location: ./application/controllers/developer.php */