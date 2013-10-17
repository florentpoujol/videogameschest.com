<?php

function antiBot()
{
    $html = '<div id="antibot">';

    if (is_guest()) {
        // Recaptcha
        //$html = Recaptcha\Recaptcha::recaptcha_get_html(Config::get('vgc.recaptcha_public_key'));

        // honeyPot field
        $html .= '<div id="honeypot">'.Former::text('city', "Please don't put anything in this field")->placeholder('The city where you lives')->id('city_form').'</div>';
    }

    return $html.'</div> <!-- /#antibot -->';
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


/**
 * return a list of checkboxes
 * @param  string $field_name The array field name
 * @param  array  $values     The values to be checked comming from the DB or the old form
 * @param  string $name     The form name of list of checkboxes
 * @return HTML             The formated checkboxes
 */
function array_to_checkboxes($field_name, $values = null, $name = null) 
{
    if (is_null($values)) $values = array(); // some times null is passed

    $temp_items = Config::get('vgc.'.$field_name);
    $checkboxes = array();

    for ($i = 0; $i < count($temp_items); $i++) {
        $item = $temp_items[$i];

        $lang = lang($field_name.'.'.$item);
        $help = lang($field_name.'.'.$item.'_help', '');
        
        if ($help != '') $lang .= ' '.tooltip($help);

        $checkboxes[$lang] = array('value' => $item);

        if (in_array($item, $values)) {
            $checkboxes[$lang]['checked'] = 'checked';
        }
    }

    if (is_null($name)) $name = $field_name.'[]';

    return Former::checkbox($name, '')->checkboxes($checkboxes);
}


/**
 * Remove some keys from the $input array
 * usually called from a model to removes keys added by a form but nt needed nor wanted by the models and DB
 * @param  array $input The input array (often the)
 * @param  array $sup_attrs Supplementary attributes to remove from the înput array
 * @return array        The cleaned input
 */
function clean_form_input($input, $supl_attributes = array())
{
    $attributes_to_clean = Config::get('vgc.form_attributes_to_clean');
    $attributes_to_clean = array_merge($attributes_to_clean, $supl_attributes);
    
    foreach ($attributes_to_clean as $attr) 
        unset($input[$attr]);

    return $input;
}

 
/**
 * strip out empty name/url in names/urls arrays (screenshots, videos, links)
 * then rebuilt it's index
 * @param assocarray $array
 * @return the cleaned up array
 */
function clean_names_urls_array($items)
{
    $new_items = $items;
    foreach ($items as $index => $item) {
        if (
            (isset($item['name']) && trim($item['name']) == '') || 
            (isset($item['url']) && trim($item['url']) == '')) {
            unset($new_items[$index]);
        }
    }

    // rebuilt indexes so that json_encode consider them as array an not as object
    $new_items = array_values($new_items);

    return $new_items;
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

function get_updated_fields_string( $input )
{
    $updated_fields = "";
    foreach ($input as $key => $value) {
        if ($updated_fields != "")
            $updated_fields .= ", ";
        
        $updated_fields .= $key." '".$value."'";
    }
    return $updated_fields;
}


/**
 * Return the html to display an icon, whether a glyphicon or a custom icon
 * @param  string $icon       The icon name
 * @param  bool $icon_white   Use the white version of the glyphicons
 * @return string             The html
 */
function icon($icon, $title = null, $size = null)
{
    $icon = strtolower($icon);

    if (is_string($title) && trim($title) != '') $title = ' title="'.$title.'"';
    else $title = '';

    if (is_null($size)) $size = '14';

    $vgc_icons = Config::get('vgc.icons');
    $glyphicons = Config::get('vgc.glyphicons');

    if (array_key_exists($icon, $vgc_icons)) {
        $html = '<img src="'.URL::to($vgc_icons[$icon]).'" alt="$icon icon" width="'.$size.'px" height="'.$size.'px" '.$title.'>';
    } elseif (in_array($icon, $glyphicons)) {
        $html = '<i class="icon-'.$icon.'" '.$title.'></i>';
    } else $html = '';

    return $html;
}


function is_admin()
{
    return (Auth::check() && Auth::user()->type == 'admin');
}

function is_user()
{
    return (Auth::check() && Auth::user()->type == 'user');
}

function is_guest()
{
    return Auth::guest();
}

function is_logged_in()
{
    return Auth::check();
}



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
    if (is_string($replacements)) {
        $default_string = $replacements;
        $replacements = array();
    }
    
    $key_parts = explode('.', $key);
    if ( ! in_array($key_parts[0], Config::get('vgc.language_files'))) $key = 'vgc.'.$key;

    $string = Lang::get($key, $replacements, 'en');
    
    return $string;
}


/**
 * Replace spaces by hyphen
 * @param string $name the name
 * @return the url
 */
function name_to_url($name)
{
    return strtolower(str_replace(array(' '), '-', $name));
}


/**
 * Pick random entries in array and return the new array
 * @param [type] $array [description]
 * @param [type] $count [description]
 */
function PickAtRandomInArray($array, $count)
{
    $new_array = array();
    shuffle($array);

    if ($count > count($array)) $count = count($array);

    $keys = array_rand($array, $count);

    if (is_array($keys)) {
        foreach ($keys as $key) {
            $new_array[] = $array[$key];
        }
    } else return $array[$keys];

    return $new_array;
}


function popover($text, $data_placement = 'top')
{
    return '<i class="icon-question-sign" rel="popover" data-placement="'.$data_placement.'" title="'.$text.'"></i>';
}


/**
 * Removes profiles in the blacklist from the provided profiles
 */
function ProcessBlacklist($profiles, $user_id = null) 
{
    $user = User::find($user_id);

    if (is_null($user)) {
        // Log::write('blacklist error', 'Tried to process blacklist of an unknow user id='.$user_id);
        return $profiles;
    }

    $blacklist = $user->blacklist;

    for ($i = 0; $i < count($profiles); $i++) {
        $profile = $profiles[$i];

        if (in_array($profile->id, $blacklist[$profile->class_name.'s'])) {
            unset($profiles[$i]);
        }
    }

    return $profiles;
}


/**
 * Wrapper around the Mailer class
 * only used by user model
 */
function send_mail($email, $subject, $body_html) 
{
    $function = "send";
    $env = Config::get('vgc.environment');
    if ($env == 'local') {
        HTML::set_info($body_html);
        $function = "pretend";
    }

    Mail::$function(array('html' => 'email', 'text' => 'email'), array('email_body' => $body_html), function($message)
    {
        $message
        ->from(Config::get('vgc.automatic_email_from'), Config::get('vgc.automatic_email_from_name'))
        ->to($email)
        ->subject($subject);
    });
}


function tooltip($text, $data_placement = 'top')
{
    return '<i class="icon-question-sign tooltipicon" rel="tooltip" data-placement="'.$data_placement.'" title="'.$text.'"></i>';
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


function user()
{
    return Auth::user();
}


function user_id()
{
    if (Auth::guest()) return 0;
    return Auth::user()->id;
}


function user_type()
{
    if (Auth::guest()) return '';
    return Auth::user()->type;
}


/**
 * Set the embed code for the videos
 * @param  string $link 
 * @return string       The HTML code of the embeded player
 */
function VideoFrame($link, $width = null, $height = null)
{
    // ration wdith/height : 1.77
    $ratio = 1.77;
    if (is_null($width) && is_null($height)) {
        $width = Config::get('vgc.video.default_width', '400'); // w530 = h300
        $height = $width/$ratio;
    } elseif ( ! is_null($width) && is_null($height)) {
        $height = $width/$ratio;
    } elseif (is_null($width) && ! is_null($height)) {
        $width = $height*$ratio;
    }

    // youtube
    // standard urls : youtube.com/watch?v=B0ewUjc3wbs youtu.be/B0ewUjc3wbs  
    // embed : http://www.youtube.com/embed/B0ewUjc3wbs http://youtube.googleapis.com/v/0l9oOGCie4w
    if (strpos($link, 'youtu') !== false && strpos($link, 'youtube.googleapis.com') === false && strpos($link, '/embed/') === false) {
        $embed_link = preg_replace("#(.*)(/watch\?v=|\.be/|/v/)([a-zA-Z0-9]+)((&|/)?.*)#", 'http://www.youtube.com/embed/$3', $link);
    }

    // vimeo
    // stadard : http://vimeo.com/57183688
    // embed : http://player.vimeo.com/video/57183688
    elseif (strpos($link, 'vimeo.com') !== false) {
        $embed_link = preg_replace("#(.*)(vimeo\.com/)([0-9]{8})(.*)#", 'http://player.vimeo.com/video/$3?title=0&amp;byline=0&amp;portrait=0', $link);
    }

    // dailymotion
    // standard : http://www.dailymotion.com/video/{id}_{long text}
    // embed : http://www.dailymotion.com/embed/video/{id}
    elseif (strpos($link, 'dailymotion.com') !== false) {
        $embed_link = preg_replace("#(.*)(video/)([a-zA-Z0-9]+)(_.*)#", 'http://www.dailymotion.com/embed/video/$3', $link);
    }

    $id = Str::random(40);

    if ( ! is_null($embed_link)) {
        return '<iframe width="'.$width.'" height="'.$height.'" src="'.$embed_link.'" id="'.$id.'" frameborder="0"
        allowfullscreen></iframe>';
    } else return '<a href="'.$link.'" id="'.$id.'">'.$link.'</a>';
}


function XssSecure($string)
{
    if (is_string($string)) return e($string);
    else return $string;
}

