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
    if ( ! isset($array['names']) || ! isset($array['urls'])) return $array;

    $max_count = count($array['names']);

    for ($i = 0; $i < $max_count; $i++) {
        if (
            isset($array['names'][$i]) &&
            (trim($array['names'][$i]) == '' || trim($array['urls'][$i]) == '')
        ) {
            unset($array['names'][$i]);
            unset($array['urls'][$i]);
            $i--; // go back one index since unsetting change the size of the array and the keys of the remaining values
        }
    }

    // rebuilt indexes so that json_encode consider them as array an not as object
    $array['names'] = array_values($array['names']);
    $array['urls'] = array_values($array['urls']);

    return $array;
}


/**
 * Parse bbCode
 * @param string $input the input text
 * @return string the input string with the bbCode replaced by html tags
 */
function parse_bbcode($input)
{
    $input = preg_replace( "#\[b\](.+)\[/b\]#", "<strong>$1</strong>" ,$input);
    $input = preg_replace( "#\[i\](.+)\[/i\]#", "<em>$1</em>" ,$input);
    $input = preg_replace( "#https?://[^ ]+#i", '<a href="$0">$0</a>' ,$input);
    //$input = preg_replace( "#\[url\](.+)\[/url\]#", '<a href="$1">$1</a>' ,$input);
    $input = preg_replace( "#\[url=(.+)\](.+)\[/url\]#", '<a href="$1" title="$2">$2</a>' ,$input);
    $input = preg_replace( "#/n#", '<br>' ,$input);
    return $input;
}


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


/**
 * Replace dashes and %20 by spaces
 * @param string $url the url segment
 * @return the name
 */
function url_to_name($url)
{
    $name = str_replace(array('-', '%20'), ' ', $url);
    return $name;
}

/**
 * Replace spaces by hyphen
 * @param string $name the name
 * @return the url
 */
function name_to_url( $name )
{
    $url = str_replace(array(' '), '-', $name);
    return $url;
}


/**
 * Wrapped around Auth::guest()
 * @return boolean True if the user is a guest (not logged in)
 */
function is_guest()
{
    return Auth::guest();
}

/**
 * Wrapped around Auth::user()
 * @return User The currently logged in user
 */
function user()
{
    return Auth::user();
}

/**
 * Wrapped around Auth::user()->dev
 * @return Developer The developer profile that belongs to the current user
 */
function dev()
{
    return Auth::user()->developer;
}

/**
 * Wrapped around Auth::user()->dev->games
 * @return array Array of games
 */
function games()
{
    return Auth::user()->developer->games;
}


/**
 * Wrapper around json_decode($data, true)
 * Decode a json string
 * @param  string  $string The json string to decode
 * @param  boolean $to_array Return the result as array or object ?
 * @return mixed          The array or object returned by json_decode()
 */
function json_to_array($string, $to_array = true)
{
    return json_decode($string, $to_array);
}


/**
 * Wrapper around json_encode()
 * Encode an array or object to json
 * @param  mixed $data The array or object to encode
 * @return  string The json string
 */
function to_json($data)
{
    return json_encode($data);
}


/**
 * Return a random string of the requested length
 * @param int $length The length of the random string to be returned
 * @return string The generated string
 */
function get_random_string($length)
{
    $alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789&#'{([-|_@)]°}=+-*/,?;.:!§%£¤µ";
    $string = "";

    for ($i = 0; $i < $length; $i++) {
        $string .= $alphabet[mt_rand(0, strlen($alphabet)-1)];
    }
    
    return $string;
}


/**
 * Remove from the $input
 * usually called from a model
 * @param  array $input The input array (often the)
 * @param  array $sup_attrs Supplementary attributes to remove from the înput array
 * @return array        The cleaned input
 */
function clean_form_input($input, $supl_attributes = array())
{
    $attributes_to_clean = Config::get('vgc.form_attributes_to_clean');
    $attributes_to_clean = array_merge($attributes_to_clean, $supl_attributes);
    
    foreach ($attributes_to_clean as $attr) unset($input[$attr]);

    return $input;
}


/**
 * Wrapper around the SwiftMailer bundle
 * @param  string $message The email's corps
 * @return [type]          [description]
 */
function send_mail($email, $message) 
{
    HTML::set_infos($email.' '.$message);
}


/**
 * Create an assoc array from an array where keys = values
 * @param  array $array The input array
 * @return array        The output array
 */
function array_set_values_as_keys($array) 
{
    $new_array = array();
    foreach ($array as $key => $value) {
        $new_array[$value] = $value;
    }

    return $new_array;
}


function icon($icon, $icon_white = '')
{
    $icon = strtolower($icon);

    $vgc_icons = Config::get('vgc.icons');

    $glyhpicons = Config::get('vgc.glyphicons');

    if (array_key_exists($icon, $vgc_icons)) {
        $html = '<img src="'.URL::to($vgc_icons[$icon]).'" alt="$icon icon" width="14px" height="14px"> ';
    } elseif (in_array($icon, $glyhpicons)) {
        if ($icon_white === true) $icon_white = ' icon-white';
        $html = '<i class="icon-'.$icon.$icon_white.'"></i> ';
    }
    else $html = '';

    return $html;
}

