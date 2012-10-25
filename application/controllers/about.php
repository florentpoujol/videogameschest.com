<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class About extends MY_Controller {
    
    function index()
    {
        

        $this->layout->view("about_view")->load();
    }
}

/* End of file about.php */
/* Location: ./application/controllers/about.php */