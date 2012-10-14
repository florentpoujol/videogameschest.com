<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Feed extends MY_Controller {

    function __construct() {
        parent::__construct();
    }


    //----------------------------------------------------------------------------------

    /**
     * Rss feed for public games
     * Get all last games in the database sorted by publication data
     * @return [type] [description]
     */
    function newgames() {
        $site_data = get_static_data('site');
        $games = $this->game_model->get_feed_games($site_data->feed->item_count);

        $channel["title"] = "New games on VideoGamesChest.com";
        $channel["link"] = site_url("feed/newgames");
        $channel["description"] = "The newly published games on VideoGamesChest.com";
        $channel["lastBuildDate"] = $games->row()->publication_date;

        $this->load->view("rss/game_dev_rss_view", 
            array("channel"=>$channel, "items"=>$games, "type"=>"game", "site_data"=>$site_data )
        );
    }
}

/* End of file feed.php */
/* Location: ./application/controllers/feed.php */