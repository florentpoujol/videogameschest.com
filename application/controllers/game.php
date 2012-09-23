<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Game extends CI_Controller {
    
    function index($nameOrId = null)
    {
    	$data['siteData'] = GetSiteData();

    	$where = array();
    	if( is_numeric( $nameOrId ) )
    		$where['id'] = $nameOrId;
    	else
    		$where['name'] = title_url( $nameOrId );

        $DBInfos = $this->main_model->GetRow( 'games', $where );
        if( $DBInfos == false )
        	redirect( 'featured/404/gamenotfound:'.$nameOrId );
        
		$gameData = json_decode( $DBInfos->data );

        $data['DBInfos'] = $DBInfos;
        $data['gameData'] = $gameData;
        
        $this->layout
        ->AddView( 'bodyStart', 'menu_view', array('page'=>'game'))
        ->AddView( 'bodyStart', 'full_game_view', $data )
        ->Load();
    }
}

/* End of file game.php */
/* Location: ./application/controllers/game.php */