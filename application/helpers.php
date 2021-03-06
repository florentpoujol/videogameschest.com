<?php

function antiBot($text_name = 'captcha')
{
    $html = '';

    if (is_guest()) {
        // Recaptcha
        $html = Recaptcha\Recaptcha::recaptcha_get_html(Config::get('vgc.recaptcha_public_key'));
        
        // cool captcha
        /*$html = Form::text($text_name, '', array(
            'class' => 'captchainput',
            'placeholder' => lang('common.insert_captcha'),
            'required' => true
        ));
        $html .= Form::image(CoolCaptcha\Captcha::img(), 'captchaimg', array('class' => 'captchaimg'));
        $html .= '';
        return $html;*/

        // honeyPot field
        $html .= Former::text('city', '')->placeholder('The city where do you lives')->id('city_form');
    }

    return $html;
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
 * strip out empty name/url in names/urls arrays (socialnetworks, stores, screenshots, videos, reviews)
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


function DisplaySoundTrack($url)
{
    /* 
    bandcamp 
    http://morusque.bandcamp.com/album/blocks-that-matter
    <iframe width="300" height="400" style="position: relative; display: block; width: 300px; height: 410px;"
    src="http://bandcamp.com/EmbeddedPlayer/v=2/album=519219781/size=grande3/bgcol=FFFFFF/linkcol=4285BB/"
    allowtransparency="true" frameborder="0">
    <a href="http://morusque.bandcamp.com/album/blocks-that-matter">Blocks that matter by Morusque</a>
    </iframe>

    https://soundcloud.com/awintory/sets/journey
    <iframe width="100%" height="400" scrolling="no" frameborder="no" 
    src="https://w.soundcloud.com/player/?url=http%3A%2F%2Fapi.soundcloud.com%2Fplaylists%2F1738337">
    </iframe>
    http://api.soundcloud.com/playlists/1738337
    */
   
    $html = 'Error processing soundtrack url : '.$url;

    if (strpos($url, 'bandcamp.com/EmbeddedPlayer') !== false) {
        $url = preg_replace("#^(.+/album=[0-9]+)/?.*#", '$1/size=grande3/bgcol=FFFFFF/linkcol=4285BB', $url);

        
        $html = '<iframe width="500" height="540" style="position: relative; display: block;"
        src="'.$url.'" allowtransparency="true" frameborder="0"></iframe>';
    }
    elseif (strpos($url, 'bandcamp.com') !== false && strpos($url, 'bandcamp.com/EmbeddedPlayer') === false) {
        $html = '<a href="'.$url.'">Get the soundtrack on Bandcamp</a>';
    }


    elseif (strpos($url, 'api.soundcloud.com/') !== false && strpos($url, 'w.soundcloud.com/player') === false) {
        $html = '<iframe width="100%" height="400" scrolling="no" frameborder="no" 
        src="https://w.soundcloud.com/player/?url='.$url.'"></iframe>';
    }
    elseif (strpos($url, 'w.soundcloud.com/player') !== false) {
        $html = '<iframe width="100%" height="400" scrolling="no" frameborder="no" 
        src="'.$url.'"></iframe>';
    }
    elseif (strpos($url, 'soundcloud.com') !== false) {
        $html = '<a href="'.$url.'">Get the soundtrack on Soundcloud</a>';
    }

    return $html;
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


function get_language()
{
    return Session::get('language', Config::get('language', 'en'));
}


function get_profiles_types()
{
    return Config::get('vgc.profile_types', array());
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


function is_developer()
{
    return ( ! Auth::guest() && Auth::user()->type == 'developer');
}

function is_guest()
{
    return Auth::guest();
}


function is_logged_in()
{
    return ( ! Auth::guest());
}


function is_not_admin()
{
    return ( ! Auth::guest() && Auth::user()->type != 'admin');
}


/*function is_trusted()
{
    return ( ! Auth::guest() && (is_admin() || Auth::user()->is_trusted == 1));
}*/

function is_standard_user()
{
    return ( ! Auth::guest() && Auth::user()->type == 'user');
}


/*
 * From http://stackoverflow.com/questions/1048487/phps-json-encode-does-not-escape-all-json-control-characters
 */
function json_escape($string) 
{   
    $search = array("\n", "\r", "\u", "\t", "\f", "\b", "/", '"');
    $replace = array("\\n", "\\r", "\\u", "\\t", "\\f", "\\b", "\/", '\"');
    $string = str_replace($search, $replace, $string);
    return $string;
    // does not work at all for double gillemets "
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
    $url = str_replace(array(' '), '-', $name);
    return $url;
}


/**
 * Parse bbCode
 * @param string $input the input text
 * @return string the input string with the bbCode replaced by html tags
 */
function parse_bbcode($input)
{
    $input = preg_replace( "#\[b\](.+)\[/b\]#i", "<strong>$1</strong>", $input);
    $input = preg_replace( "#\[i\](.+)\[/i\]#i", "<em>$1</em>", $input);
    $input = preg_replace( "#\[h1\](.+)\[/h1\]#i", "<h3>$1</h3>", $input);
    $input = preg_replace( "#\[h2\](.+)\[/h2\]#i", "<h4>$1</h4>", $input);
    //$input = preg_replace( "#https?://[^ ]+#i", '<a href="$0">$0</a>', $input);
    //$input = preg_replace( "#\[url\](.+)\[/url\]#", '<a href="$1">$1</a>', $input);
    //$input = preg_replace( "#\[url=(.+)\](.+)\[/url\]#", '<a href="$1" title="$2">$2</a>', $input);
    //$input = preg_replace( "#\[br\]#", '<br>', $input);
    return $input;
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

function Plural($text)
{
    return Str::plural($text);
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

/*function sendMail($email, $subject, $body_html, $body_text = null, $priority = null) 
{
    SendMail($email, $subject, $body_html, $body_text, $priority);
}*/


function shortenUrl($url)
{
    return preg_replace("#(https?://[^/]+/)(.*)#i", "$1...", $url);
}


function Singular($text)
{
    return Str::singular($text);
}

/**
 * Set the embed code for the soundtrack
 * @param  string $link 
 * @return string       The HTML code of the embeded player
 */
function soundtrackFrame($link)
{
    // soundcloud
    // standard urls : soundcloud.com/{band}/{track} or api.soundcloud.com/tracks/{track_id}
    // embed url : https://w.soundcloud.com/player/?url=https://api.soundcloud.com/tracks/{track_id}
    // https://w.soundcloud.com/player/?url=https://soundcloud.com/{band}/{track}
    if (strpos($link, 'soundcloud.com') !== false) {
        return '<iframe width="100%" height="166" scrolling="no" frameborder="no" 
        src="https://w.soundcloud.com/player/?url='.$link.'"></iframe>';
        
    }

    // bandcamp
    // standard url is no good for embeding, need the album id
    // embed url : http://bandcamp.com/EmbeddedPlayer/v=2/album=4077462542/size=grande3/bgcol=FFFFFF/linkcol=4285BB/
    if (strpos($link, 'bandcamp.com') !== false) {
        return '<iframe width="300" height="410" style="position: relative; display: block; width: 300px; height: 410px;" 
        src="'.$link.'" allowtransparency="true" frameborder="0"></iframe>';
    } 

    return '<a href="'.$link.'">'.$link.'</a>';
}


/**
 * Wrapper around json_encode()
 * Encode an array or object to json
 * @param  mixed $data The array or object to encode
 * @return  string The json string
 */
function to_json($data)
{
    if (is_string($data)) $data = json_escape($data);
    
    return json_encode($data);
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



/*function GetVideoEmbedLink($link)
{
    $embed_link = "original link : $link";

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

    return $embed_link;
}*/




function xssSecure($string)
{
    if (is_string($string)) return e($string);
    else return $string;
}


