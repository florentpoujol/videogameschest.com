<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Game extends MY_Controller {
    
    function __construct() {
        parent::__construct();
    }


    // ----------------------------------------------------------------------------------

    /**
     * Default method
     */
    function index( $name_or_id = null ) {
    	$where = array();
    	if( is_numeric( $name_or_id ) )
    		$where['game_id'] = $name_or_id;
    	else
    		$where['name'] = url_to_name( $name_or_id );

        $db_game = $this->game_model->get_game( $where );
        
        if( $db_game == false )
        	redirect( 'home/404/gamenotfound:'.$name_or_id );

        // display page when :
        // the game is public
        // user is admin (whathever profile_privacy)
        // user is developer and the game is in review
        // user is developer and the game is its own (even if the game is still private)
        if( 
            $db_game->profile_privacy == 'public' || IS_ADMIN ||
            (
                IS_DEVELOPER &&
                (
                    $db_game->profile_privacy == 'in_review' || $db_game->developer_id == ID
                )
            )
        ) 
        {
            // get feed infos
            $db_game->feed_items = $this->rssreader->parse( $db_game->data['blogfeed'] )->get_feed_items(6);
            
            $this->layout
            ->view( 'full_game_view', array('db_game'=>$db_game) )
            ->view("forms/report_form")
            ->load();
        }
        else
            redirect( 'home/404/gameprivate' );
    }
}

/* End of file game.php */
/* Location: ./application/controllers/game.php */