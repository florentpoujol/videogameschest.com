<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Lang extends CI_Lang {

	public $default_language = null;

    function load($langfile = '', $idiom = '', $return = FALSE, $add_suffix = TRUE, $alt_path = '')
    {
        $langfile = str_replace('.php', '', $langfile);

        if ($add_suffix == TRUE)
        {
            $langfile = str_replace('_lang.', '', $langfile).'_lang';
        }

        $langfile .= '.php';

        /*if (in_array($langfile, $this->is_loaded, TRUE))
        {
            return;
        }*/

        $config =& get_config();

        if ($idiom == '')
        {
            $deft_lang = ( ! isset($config['language'])) ? 'english' : $config['language'];
            $idiom = ($deft_lang == '') ? 'english' : $deft_lang;
        }

        // Determine where the language file is and load it
        if ($alt_path != '' && file_exists($alt_path.'language/'.$idiom.'/'.$langfile))
        {
            include($alt_path.'language/'.$idiom.'/'.$langfile);
        }
        else
        {
            $found = FALSE;

            foreach (get_instance()->load->get_package_paths(TRUE) as $package_path)
            {
                if (file_exists($package_path.'language/'.$idiom.'/'.$langfile))
                {
                    include($package_path.'language/'.$idiom.'/'.$langfile);
                    $found = TRUE;
                    break;
                }
            }

            if ($found !== TRUE)
            {
                show_error('Unable to load the requested language file: language/'.$idiom.'/'.$langfile);
            }
        }


        if ( ! isset($lang))
        {
            log_message('error', 'Language file contains no data: language/'.$idiom.'/'.$langfile);
            return;
        }

        if ($return == TRUE)
        {
            return $lang;
        }

        $this->is_loaded[] = $langfile;
        $this->language = array_merge($this->language, $lang);
        unset($lang);

        log_message('debug', 'Language file loaded: language/'.$idiom.'/'.$langfile);
        return TRUE;
    }

    function line( $key = '' ) {
    	$line = '';

    	if( isset( $this->language[$key] ) )
    		$line = $this->language[$key];

    	else {
            if( !isset( $this->default_language ) )
                $this->default_language = $this->load( 'main', get_instance()->config->item( 'language' ), true );

            if( isset( $this->default_language[$key] ) )
                $line = $this->default_language[$key];
        }
    	
		if( $line == '' )
			log_message('error', 'Could not find the language line "'.$line.'" in the current nor in the default language');

		return $line;
    }
}