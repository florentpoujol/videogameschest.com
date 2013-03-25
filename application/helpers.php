<?php

function antiBot($text_name = 'captcha')
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
 * strip out empty name/url in names/urls arrays (screenshots, videos, links)
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
            (isset($array['names'][$i]) && trim($array['names'][$i]) == '') ||
            (isset($array['urls'][$i]) && trim($array['urls'][$i]) == '')
        ) {
            unset($array['names'][$i]);
            unset($array['urls'][$i]);
            $i--; // go back one index since unsetting change the size of the array
        }
    }

    // rebuilt indexes so that json_encode consider them as array an not as object
    $array['names'] = array_values($array['names']);
    $array['urls'] = array_values($array['urls']);

    return $array;
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


function get_category_name($search_id)
{
    if (is_guest()) {
        $names = json_decode(Cookie::get('vgc_category_names', '{}'), true);
    } else {
        $names = user()->category_names;
        if ($names != '') $names = json_decode($names, true);
        else $names = array();
    }

    if (isset($names[$search_id])) return $names[$search_id];
    return null;
}

function get_category_id($name)
{
    if (is_guest()) {
        $names = json_decode(Cookie::get('vgc_category_names', '{}'), true);
    } else {
        $names = user()->category_names;
        if ($names != '') $names = json_decode($names, true);
        else $names = array();
    }

    foreach ($names as $id => $_name) {
        if ($name == $_name) return $id;
    }
    
    return null;
}


function get_language()
{
    return Session::get('language', Config::get('language', 'en'));
}


function get_profile_types($regex_style = false)
{
    $profile_types = Config::get('vgc.profile_types', array());
    
    if ($regex_style) {
        $profile_types = '('.implode('|', $profile_types).')';
    }

    return $profile_types;
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
    return ( ! Auth::guest() && Auth::user()->type == 'admin');
}

function is_guest()
{
    return Auth::guest();
}

function is_logged_in()
{
    return ( ! Auth::guest());
}

function is_user()
{
    return ( ! Auth::guest() && Auth::user()->type == 'user');
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

    $default_language = Config::get('application.language', 'en');
    // if the language is not set, look in the session first
    // then look in the config, or in last resort default to 'en'
    if (is_null($language)) $language = Session::get('language', $default_language);
    
    $key_parts = explode('.', $key);
    if ( ! in_array($key_parts[0], Config::get('vgc.language_files'))) $key = 'vgc.'.$key;

    $string = Lang::line($key, $replacements, $language)->get();

    if ($string == $key) {
        // if the key is not found in the current language, look for it the default language
        $string = Lang::line($key, $replacements, $default_language)->get();

        if ($string == $key) {
            // then if it is still not found, look for the 'language_key_not_found' key in the current language,
            if (isset($default_string)) $string = $default_string;
            else $string = "[language key '$key' not found]";
        }
    }
    
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
        Log::write('blacklist error', 'Tried to process blacklist of an unknow user id='.$user_id);
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
 * Wrapper around the SwiftMailer bundle
 */
function SendMail($email, $subject, $body_html, $body_text = null, $priority = null) 
{
    if (is_null($body_text)) $body_text = $body_html;

    $env = Config::get('vgc.environment');

    if ($env == 'local') {
        HTML::set_info($body_html);
        return;
    }

    //Using SwiftMailer

    // transport
    $transport = Swift_MailTransport::newInstance();
    // note : with Gandi, there is no need to set an smtp transport, because
    // IT SEEMS that the PHP mail function will take care of smtp with Gandi by itself
    /* $transport = Swift_SmtpTransport::newInstance($smtp_server, Config::get('vgc.smtp.server_port'))
            ->setUsername(Config::get('vgc.smtp.username'))
            ->setPassword(Config::get('vgc.smtp.password'));*/
    
    // check early if the trnsport is going to throw an exception
    try {
        $transport->start();
    } 
    catch (Exception $e) {
        var_dump($e);
        Log::write('email error', 'transport exception :'.$e);
    }

    // new mailer instance
    $mailer = Swift_Mailer::newInstance($transport);    
    // the laravel bundle doc suggest :
    // $mailer = IoC::resolve('mailer');

    // Construct the message
    $message = Swift_Message::newInstance()
        ->setFrom(array(Config::get('vgc.automatic_email_from') => Config::get('vgc.automatic_email_from_name')))
        ->setTo($email)
        ->setSubject($subject)
        ->setBody($body_html,'text/html')
        ->addPart($body_text,'text/plain');

    if (is_integer($priority)) $message->setPriority($priority);
        
    // Send the email
    $failures = array();
    $num_sent = $mailer->send($message, $failures);

    //HTML::set_info('Email sent : (to='.$email.') (subject='.$subject.') (body_text='.$body_text.')');
    if ($num_sent > 0) {
        Log::write('email', 'Email sent : (to='.$email.') (subject='.$subject.') (body_text='.$body_text.') (body_html='.$body_html.')');
    } else {
        Log::write('email error', 'Email NOT SENT : (to='.$email.') (subject='.$subject.') (body_text='.$body_text.') (body_html='.$body_html.')');
    }

    if ( ! empty($failures)) {
        $msg = 'The following adresses failed : '.implode(',', $failures).' <br>
        <br>
        ___________ <br>
        <br>
        subject : '.$subject.' <br>
        <br>
        body : <br>
        '.$body_html;

        Log::write('email error', $msg);
        sendMail(Config::get('admin_email'), 'Send mail recipient failures', $msg, $msg, 1);
    }
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

