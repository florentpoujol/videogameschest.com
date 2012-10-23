<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Developer extends MY_Controller {
    
    /**
     * Default method
     */
    function index( $name_or_id = null ) {
    	$where = array();
    	if (is_numeric($name_or_id))
    		$where["id"] = $name_or_id;
    	else
    		$where["name"] = url_to_name($name_or_id);

        $db_dev = $this->developer_model->get($where, true);
        
        if ($db_dev === false)
            redirect("home/404/developernotfound:$name_or_id");

        // display page when the dev is public or the visitor an admin
        if ($db_dev["privacy"] == "public" || IS_ADMIN || (IS_DEVELOPER && $db_dev["user_id"] == USER_ID)) {
            // get feed infos
            $db_dev["feed_items"] = $this->rssreader->parse( $db_dev["data"]["blogfeed"] )->get_feed_items(6);
            
            $this->layout
            ->set_title($db_dev["name"]." - VideoGamesChest")
            ->view("full_developer_view", array("db_dev"=>$db_dev))
            ->view("forms/report_form")
            ->load();
        }
        else
            redirect("home/404/developerprivate:$name_or_id");
    }
}

/* End of file developer.php */
/* Location: ./application/controllers/developer.php */