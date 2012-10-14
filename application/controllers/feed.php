<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Feed extends MY_Controller {

    private $item_count;

    function __construct() {
        parent::__construct();
        $this->item_count = get_static_data('site')->feed->item_count;
    }


    //----------------------------------------------------------------------------------

    /**
     * Rss feed for public games
     * Get all last games in the database sorted by publication data
     */
    function newgames() {
        $games = $this->game_model->get_feed_games($this->item_count);

        $channel["title"] = "New games on VideoGamesChest.com";
        $channel["link"] = site_url("feed/newgames");
        $channel["description"] = "The newly published game profiles on VideoGamesChest.com";
        $channel["lastBuildDate"] = $games->row()->publication_date;

        $this->load->view("rss/game_dev_rss_view", 
            array("channel"=>$channel, "items"=>$games, "type"=>"game", "site_data"=>$site_data )
        );
    }


    //----------------------------------------------------------------------------------

    /**
     * Rss feed for public developer
     * Get all last developer in the database sorted by publication data
     */
    function newdevelopers() {
        $devs = $this->developer_model->get_feed_developers($this->item_count);

        $channel["title"] = "New Developers on VideoGamesChest.com";
        $channel["link"] = site_url("feed/newdevelopers");
        $channel["description"] = "The newly published developer profiles on VideoGamesChest.com";
        $channel["lastBuildDate"] = $devs->row()->publication_date;

        $this->load->view("rss/game_dev_rss_view", 
            array("channel"=>$channel, "items"=>$devs, "type"=>"developer", "site_data"=>$site_data )
        );
    }


    //----------------------------------------------------------------------------------

    /**
     * Rss feed for public developer
     * Get all last developer in the database sorted by publication data
     */
    function newmessages( $user_id = 0, $profile_key = "" ) {
        $user = get_db_row("developers", array("developer_id"=>$user_id, "profile_key"=>$profile_key));
        $is_admin = false;

        if ( ! $user) {
            $user = get_db_row("administrators", array("administrator_id"=>$user_id, "profile_key"=>$profile_key));
            $is_admin = true;

            if ( ! $user) {
                echo "No user found with id=$user_id and profile_key=$profile_key";
                return;
            }
        }

        $user->is_admin = $is_admin;
        $msgs = $this->admin_model->get_feed_messagess($this->item_count, $user);

        $channel["title"] = "New messages on VideoGamesChest.com";
        $channel["lastBuildDate"] = $msgs->row()->publication_date;

        $this->load->view("rss/messages_rss_view", 
            array("channel"=>$channel, "items"=>$devs, "type"=>"message", "site_data"=>$site_data )
        );
    }
}

/* End of file feed.php */
/* Location: ./application/controllers/feed.php */