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
    
    $key_parts = explode('.', $key);
    if ( ! in_array($key_parts[0], Config::get('vgc.language_files'))) $key = 'vgc.'.$key;

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
        if (isset($array['names'][$i]) && trim($array['names'][$i]) == '') {
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
function name_to_url($name)
{
    $url = str_replace(array(' '), '-', $name);
    return $url;
}


//----------------------------------------------------------------------------------


function is_guest()
{
    return Auth::guest();
}
function is_logged_in()
{
    return ( ! Auth::guest());
}

function is_admin()
{
    return ( ! Auth::guest() && Auth::user()->type == 'admin');
}
function is_not_admin()
{
    return ( ! Auth::guest() && Auth::user()->type != 'admin');
}
function is_trusted()
{
    return ( ! Auth::guest() && (is_admin() || Auth::user()->is_trusted == 1));
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

function devs()
{
    return Auth::user()->developers;
}

function games()
{
    return Auth::user()->games;
}


//----------------------------------------------------------------------------------
// JSON

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
    if (is_string($data)) $data = json_escape($data);
    
    return json_encode($data);
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
 */
function sendMail($email, $subject, $body_html, $body_text = null, $priority = null) 
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
        $msg = 'The following adresses failed : '.var_dump($failures).' <br>
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
        $html = '<img src="'.URL::to($vgc_icons[$icon]).'" alt="$icon icon" width="'.$size.'px" height="'.$size.'px" '.$title.'> ';
    } elseif (in_array($icon, $glyphicons)) {
        $html = '<i class="icon-'.$icon.'" '.$title.'></i> ';
    } else $html = '';

    return $html;
}



/**
 * return a list of checkboxes
 * @param  string $field_name The array field name
 * @param  array  $values     The values to be checked comming from the DB or the old form
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

        $checkboxes[$lang] = array('value' => $item);

        if (in_array($item, $values)) {
            $checkboxes[$lang]['checked'] = 'checked';
        }
    }

    if (is_null($name)) $name = $field_name.'[]';

    return Former::checkbox($name, '')->checkboxes($checkboxes);
}


function xssSecure($string)
{
    if (is_string($string)) return e($string);
    else return $string;
}



function videoFrame($link, $width = null, $height = null)
{
    // ration wdith/height : 1.77
    $ratio = 1.77;
    if (is_null($width) && is_null($height)) {
        $width = 530; // w530 = h300
        $height = $width/$ratio;
    } elseif ( ! is_null($width) && is_null($height)) {
        $height = $width/$ratio;
    } elseif (is_null($width) && ! is_null($height)) {
        $width = $height*$ratio;
    }

    // youtube
    // standard urls : youtube.com/watch?v=B0ewUjc3wbs youtu.be/B0ewUjc3wbs  
    // embed : http://www.youtube.com/embed/B0ewUjc3wbs http://youtube.googleapis.com/v/0l9oOGCie4w
    if (strpos($link, 'youtu') && ! strpos($link, 'youtube.googleapis.com') && ! strpos($link, '/embed/')) {
        $embed_link = preg_replace("#(.*)(/watch\?v=|\.be/|/v/)([a-zA-Z0-9]+)((&|/)?.*)#", 'http://www.youtube.com/embed/$3', $link);
    }

    // vimeo
    // stadard : http://vimeo.com/57183688
    // embed : http://player.vimeo.com/video/57183688
    elseif (strpos($link, 'vimeo.com')) {
        $embed_link = preg_replace("#(.*)(vimeo\.com/)([0-9]{8})(.*)#", 'http://player.vimeo.com/video/$3?title=0&amp;byline=0&amp;portrait=0', $link);
    }

    // dailymotion
    // standard : http://www.dailymotion.com/video/{id}_{long text}
    // embed : http://www.dailymotion.com/embed/video/{id}
    elseif (strpos($link, 'dailymotion.com')) {
        $embed_link = preg_replace("#(.*)(video/)([a-zA-Z0-9]+)(_.*)#", 'http://www.dailymotion.com/embed/video/$3', $link);
    }

    if ( ! is_null($embed_link)) {
        return '<iframe width="'.$width.'" height="'.$height.'" src="'.$embed_link.'" frameborder="0"
        allowfullscreen></iframe>';
    } else return '';
}



function antiBot($text_name = 'captcha')
{
    if (is_guest()) {
        // Recaptcha
        //return Recaptcha\Recaptcha::recaptcha_get_html(Config::get('vgc.recaptcha_public_key'));
        
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
        return Former::text('city', '')->placeholder('The city where do you lives')->id('city_form');
    }

    return '';
}
function captcha($text = null)
{
    return antiBot();
}

function shortenUrl($url)
{
    return preg_replace("#(https?://[^/]+/)(.*)#i", "$1...", $url);
}

