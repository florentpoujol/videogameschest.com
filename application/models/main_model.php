<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper( 'file' );
    }

    public function GetSiteData( $returnRawData = false ) {
        $siteData = null;
        $filePath = 'cache/siteData.json';

        if( file_exists( APPPATH.$filePath ) )
            $siteData = read_file( $filePath );

        if( !$siteData ) { // $siteData is false because the reding was not successful
            $siteData = $this->db
            ->select( 'data' )
            ->from( 'items' )
            ->where( 'type', 'sitedata' )
            ->get()->row()->data;
            
            if( $returnRawData )
                return $siteData;
        }

        return json_decode( $siteData );
    }
}

/* End of file main_model.php */
/* Location: ./application/model/main_model.php */