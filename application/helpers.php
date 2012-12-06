<?php

/**
 * Retrieve a language string.
 *
 * @param  string  $key
 * @param  array   $replacements
 * @param  string  $language
 * @return string
 */
function lang($key, $replacements = array(), $language = null)
{
    $default_language = Config::get('application.language', 'en');
    // if the language is not set, look in the session first
    // then look in the config, or in last resort default to 'en'
    if (is_null($language)) $language = Session::get('language', $default_language);
    
    $key = 'vgc.'.$key;
    $string = Lang::line($key, $replacements, $language)->get();

    if ($string == $key) {
        // if the key is not found in the current language, look for it the default language
        $string = Lang::line($key, $replacements, $default_language)->get();

        if ($string == $key) {
            // then if it is still not found, look for the 'language_key_not_found' key in the current language,
            $string = "[language key '$key' not found]";
        }
    }
    
    return $string;
}

/**
 * strip out empty name/url in names/urls arrays (socialnetworks, stores, screenshots, videos)
 * then rebuilt it's index
 * @param assocarray $array
 * @return the cleaned up array
 */
function clean_names_urls_array($array)
{
    $max_count = count($array['names']);

    for ($i = 0; $i < $max_count; $i++) 
    {
        if (
            isset($array['names'][$i]) &&
            (trim($array['names'][$i]) == '' || trim($array['urls'][$i]) == '')
        ) 
        {
            unset($array['names'][$i]);
            unset($array['urls'][$i]);
            $i--; // go back one index since unsetting change the size of the array and the keys of the remaining values
        }
    }

    // rebuilt indexes so that json_encode consider them as array an not as object
    if(isset($array['names']))
    {
        array_values($array['names']);
        array_values($array['urls']);
    }

    return $array;
}


// ----------------------------------------------------------------------------------

/**
 * Parse bbCode
 * @param string $input the input text
 * @return string the input string with the bbCode replaced by html tags
 */
function parse_bbcode( $input )
{
    $input = preg_replace( "#\[b\](.+)\[/b\]#", "<strong>$1</strong>" ,$input);
    $input = preg_replace( "#\[i\](.+)\[/i\]#", "<em>$1</em>" ,$input);
    $input = preg_replace( "#https?://[^ ]+#i", '<a href="$0">$0</a>' ,$input);
    //$input = preg_replace( "#\[url\](.+)\[/url\]#", '<a href="$1">$1</a>' ,$input);
    $input = preg_replace( "#\[url=(.+)\](.+)\[/url\]#", '<a href="$1">$2</a>' ,$input);
    $input = preg_replace( "#/n#", '<br>' ,$input);
    return $input;
}


//----------------------------------------------------------------------------------

/**
 * Return an array whose keys are provided in the first argument and the corresponding values are the corresponding localized string
 * @param  array $array_keys Array containing localization keys
 * @param  string $lang_key  A prefix to the localization key
 * @return assoc array       The assoc array containing the keys/localization strings
 */
function get_array_lang($array_keys, $lang_key)
{
    $array = array();

    foreach ($array_keys as $key) {
        $array[$key] = lang("$lang_key$key");
    }

    asort($array);
    return $array;
}


// ----------------------------------------------------------------------------------

/**
 * Turn an array to an associative array where keys = value
 * @param the array
 * @return the associative array
 */
function get_assoc_array($array)
{
    $assoc_array = array();
    foreach ($array as $value) {
        $assoc_array[$value] = $value;
    }
    return $assoc_array;
}


// ----------------------------------------------------------------------------------

/**
 * Does the opposite of url_title() from the url helper
 * Replace dashes and %20 by spaces
 * @param the url segment
 * @return the name
 */
function url_to_name( $url )
{
    $url = str_replace( array( '-', '%20' ), ' ', $url );
    return $url;
}
function name_to_url( $name )
{
    return url_title( $name );
}


