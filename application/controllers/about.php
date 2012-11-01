<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class About extends MY_Controller {
    
    function index()
    {
        $object = new stdClass();
        $object->member = "awesome !";

        $this->layout->view("about_view", array(
        	'data' => 'a piece fo data',
        	'array' => array('azrray 1', 'array 2', 'array 3', 'key'=>'value'),
        	'arraykey' => array('thekey'=>'thevalue'),
        	'object' =>$object
        ))->load();
    }
}

/* End of file about.php */
/* Location: ./application/controllers/about.php */