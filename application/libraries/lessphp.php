<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lessphp
{
    public $instance;
    
    function __construct() {        
        include "application/libraries/lessphp/lessc.inc.php";
        $this->instance = new lessc();
    }
}

/* End of file lessphp.php */
/* Location: ./application/libraries/lessphp.php */