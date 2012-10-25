<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class About extends MY_Controller {
    
    function __construct() {
        parent::__construct();
    }
    
    public function index()
    {
        echo "test";
        //$this->twiggy->display();
        //echo $this->load->view("about_view", array("data"=>"this is a data"), true);

        //$this->layout->view( 'about_view' )->load();
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */