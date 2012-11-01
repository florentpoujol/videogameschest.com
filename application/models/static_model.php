<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Static_model extends CI_Model {
    
    private $data = array();


    private $files = array("site", "form");

    function __construct()
    {
        parent::__construct();

        $files_path = APPPATH . "/static_data/json/";
        
        foreach ($this->files as $file_name) {
            $file_path = $files_path.$file_name.".json";
            // DO NOT USE base_url() !!
            // file_exists will return false, even if the file is accessible
         
            if ( ! file_exists($file_path)) {
                die("No file exists at path : $file_path");
            }
            
            $string_data = file_get_contents($file_path);

            if ($string_data == false) { // $string_data may be false because read_file() failed, otherwise it is a string
                die("[$file_path] could not be read !");
            }

            $this->data[$file_name] = json_decode($string_data);
        }
    }


    //----------------------------------------------------------------------------------

    /**
     * __get magic method
     * Retrive the content of $data
     * @param  string $key The data key (the name of the file the data was in)
     * @return mixed       The data (json object or null)
     */
    public function __get( $key )
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        } else {
            return null;
        }
    }

    public function get($key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        } else {
            return null;
        }
    }
}

/* End of file static_model.php */
/* Location: ./application/model/static_model.php */