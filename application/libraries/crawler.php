<?php

class Crawler 
{
    public static $patterns = array(
        'meta_keywords' => '\<meta name="keywords" content="(.*)"',
        'meta_description' => '\<meta name="description" content="(.*)"',
    );
    
    public static function crawl($profile_to_crawl)
    {
        $link = $profile_to_crawl->url;
        $profile_type = $profile_to_crawl->profile_type;

        $func_name = "crawl_$profile_type";
        return static::$func_name($link);
    }


    public static function get_indiedb_profile_url_from_news($url) {
        $game_url = '';

        $html = file_get_html($url);
        $body = $html->find("body", 0);
        $sidecolumn = $body->find("div[class=sidecolumn]", 0);
        $boxes = $sidecolumn->find("div[class=normalbox]");

        foreach ($boxes as $box) {
            $heading = $box->find("div[class=title] span[class=heading]", 0);
            
            if (trim($heading->plaintext) == "Related Games") {
                $row = $box->find("div[class=row]", 0);
                $game_url = $row->find('a', 0)->href;
                break;
            }
        }

        return $game_url;
    }

    public static function crawl_game($url)
    {
        // get the type of url
        $profile = array(
            'links' => array(
                array(
                    'name' => 'IndieDB',
                    'url' => $url,
                ),
            ),
            'screenshots' => array(),
        );

        // INDIE DB
        if (strpos($url, 'indiedb.com') !== false) {
            // get name
            preg_match("#/games/([^/]+)#i", $url, $name);
            if (isset($name[1])) $profile['name'] = ucfirst(url_to_name($name[1]));

            // use simple_html_dom_parser
            $html = file_get_html($url);
            $body = $html->find("body", 0);

            $description_block = $body->find("div[class=headernormalbox normalbox] div[class=inner] div[class=body]", 0);
            $profile['pitch'] = trim($description_block->plaintext);

            $sidecolumn = $body->find("div[class=sidecolumn]", 0);
            
            // profile
            $box = $sidecolumn->find("div[class=normalbox]", 0);
            $items = $box->find("div[class=table] div");

            foreach ($items as $item) {
                $h5 = $item->find("h5", 0);

                if (isset($h5)) {
                    $category = $h5->plaintext;
                    $link = $item->find("a", 0);

                    switch (strtolower($category)) {
                        case "developer" :
                            $profile["developer_name"] = $link->plaintext;

                            $dev_html = file_get_html("http://www.indiedb.com".$link->href);
                            $profile["developer_url"]  = $dev_html->find("div[class=normalbox]", 4)->find("div[class=rowalt]", 1)->find("a", 0)->href;
                            break;

                        case "official page" :
                            $profile['links'][] = array(
                                'name' => 'Official Website',
                                'url' => $link->href,
                            );
                            break;

                        case "release date":
                            // format :
                            // TBD
                            // Coming Apr 15, 2013
                            // Released Apr 15, 2013
                            // DateTime::createFromFormat ('M j, Y', 'Nov 7, 2011')
                            $rd = trim($item->find("span", 0)->plaintext);
                            if ($rd != 'TBD') {
                                $rd = str_replace('Coming ', '', $rd);
                                $rd = str_replace('Released ', '', $rd);
                                $profile['release_date'] = DateTime::createFromFormat('M j, Y', $rd)->format(Config::get('vgc.date_formats.datetime_sql'));
                            }
                            break;

                        case "platforms":
                            $as = $item->find('span[class=summary] a');
                            $profile['operatingsystems'] = array();
                            $profile['devices'] = array();

                            foreach ($as as $a) {
                                $platform = trim(strtolower(str_replace("/platforms/set/", "", $a->href)));
                                
                                switch ($platform) {
                                    case "pc":
                                        $profile['devices'][] = "pc";
                                        $profile['operatingsystems'][] = "windowsdesktop";
                                    break;

                                    case "mac":
                                        $profile['devices'][] = "mac";
                                        $profile['operatingsystems'][] = "mac";
                                    break;

                                    case "linux":
                                        $profile['devices'][] = "pc";
                                        $profile['operatingsystems'][] = "linux";
                                    break;

                                    // flash
                                    case "web":
                                        $profile['devices'][] = "browser";
                                    break;

                                    case "iphone":
                                        $profile['devices'][] = "iphone";
                                        $profile['operatingsystems'][] = "ios";
                                    break;

                                    case "ipad":
                                        $profile['devices'][] = "ipad";
                                        $profile['operatingsystems'][] = "ios";
                                    break;

                                    case "android":
                                        $profile['devices'][] = "adroidsmartphone";
                                        $profile['operatingsystems'][] = "android";
                                    break;

                                    case "androidtab":
                                        $profile['devices'][] = "androidtablet";
                                        $profile['operatingsystems'][] = "android";
                                    break;

                                    case "androidconsole":
                                        $profile['devices'][] = "ouya";
                                        $profile['operatingsystems'][] = "android";
                                    break;

                                    case "metro":
                                        $profile['devices'][] = "pc";
                                        $profile['operatingsystems'][] = "windows8metro";
                                    break;

                                    case "vita":
                                        $profile['devices'][] = "psvita";
                                    break;

                                    case "x360":
                                        $profile['devices'][] = "xbox360";
                                    break;

                                    default:
                                        $found = false;
                                        if (in_array($platform, Config::get('vgc.devices'))) {
                                            $profile['devices'][] = $platform;
                                            $found = true;
                                        } elseif (in_array($platform, Config::get('vgc.operatingsystems'))) {
                                            $profile['operatingsystems'][] = $platform;
                                            $found = true;
                                        }

                                        if ($found == false) Log::write('crawler text-no-fields', "Text '$platform' was not found in the 'devices' or 'operatingsystems' array fields for the game '".$profile['name']."'.");
                                    break;
                                }
                            } // end foreach $as

                            // remove double entries in devices and operatingsystems
                            $profile['devices'] = array_values(array_unique($profile['devices']));
                            $profile['operatingsystems'] = array_values(array_unique($profile['operatingsystems']));
                            break;

                        case "engine":
                            $engine = trim(strtolower(str_replace("/engines/", "", $item->find('span[class=summary] a', 0)->href)));
                            $profile['technologies'] = array();
                            
                            switch ($engine) {
                                case 'custom-built':
                                    $profile['technologies'][] = 'custom';
                                    break;

                                case 'cryengine-3':
                                    $profile['technologies'][] = 'cryengine';
                                    break;

                                case 'unity':
                                    $profile['technologies'][] = 'unity3d';
                                    break;

                                case 'unreal-development-kit':
                                    $profile['technologies'][] = 'udk';
                                    break;

                                case 'ogre-engine':
                                    $profile['technologies'][] = 'ogre3d';
                                    break;

                                case 'blender-game-engine':
                                    $profile['technologies'][] = 'blender';
                                    break;

                                case 'torque-3d':
                                    $profile['technologies'][] = 'torque';
                                    break;

                                case 'shiva3d-19':
                                    $profile['technologies'][] = 'shiva3d';
                                    break;
                                                                    
                                default:
                                    $found = false;
                                    if (in_array($engine, Config::get('vgc.technologies'))) {
                                        $profile['technologies'][] = $engine;
                                        $found = true;
                                    } 
                                    if ($found == false) Log::write('crawler text-no-fields', "Text '$engine' was not found in the 'technologies' array fields for the game '".$profile['name']."'.");
                                    break;
                            }

                            if (strpos($engine, 'rpg-maker') !== false) $profile['technologies'][] = "rpsmaker";
                            elseif (strpos($engine, 'unreal-engine') !== false) $profile['technologies'][] = "unrealengine";
                            elseif (strpos($engine, 'construct') !== false) $profile['technologies'][] = "construct";
                            elseif (strpos($engine, 'torque') !== false) $profile['technologies'][] = "torque";
                            break;
                    }
                }
            }

            // style
            $box = $sidecolumn->find("div[class=normalbox]", 2);
            $items = $box->find("div[class=table] div");
 
            foreach ($items as $item) {
                $h5 = $item->find("h5", 0);

                if (isset($h5)) {
                    $category = $h5->plaintext;
                    $span = $item->find("span", 0);
                    $text = trim(strtolower($span->plaintext));

                    switch (strtolower($category)) {
                        case "players" :
                            if ($text = 'single &amp; multiplayer') {
                                $text = array('singleplayer', 'multiplayer');
                            } elseif ($players = 'single &amp; co-op') {
                                $text = array('singleplayer', 'coop');
                            } elseif ($players == 'single player') {
                                $text = 'singleplayer';
                            }
                            break;

                        case "boxshot" :
                            $profile["screenshots"][] = array(
                                'name' => "Boxshot",
                                'url' => $item->find("img", 0)->src
                            );
                            break;
                    }

                    $profile = static::register_field_text($text, $profile);
                }
            }

            // twitter
            $box = $sidecolumn->find("#twitterfeed", 0);
            if ($box !== null) {
                $item = $box->find("div[class=table] div", 0);
                $profile['links'][] = array(
                    'name' => 'Twitter',
                    'url' => $item->find("a", 0)->href,
                );
            }

            // images
            $images_html = file_get_html($url.'/images');
            $imgs = $images_html->find('#imagebox img');
            foreach($imgs as $img) {
                $profile['screenshots'][] = array(
                    'name' => $img->parent->title,
                    'url' => $img->src,
                );
            }

            // videos
            $videos_html = file_get_html($url.'/videos');
            $links = $videos_html->find('#imagebox a');
            
            foreach($links as $a) {
                // get the video embed's url (so that it is not done later, when the profiles are loaded)
                $page_url = str_replace("#imagebox", '', "http://www.indiedb.com".$a->href);
                $video_html = file_get_html($page_url);
                // <meta property="og:video" content="http://www.indiedb.com/media/embed/721445">
                preg_match('#\<meta property="og:video" content="([^"]+)"#i', $video_html->find("head", 0)->innertext, $video_url);

                if (isset($video_url[1])) {
                    $profile['videos'][] = array(
                        'name' => $a->title,
                        'url' => $video_url[1],
                    );
                }
            }
        } // end Indie DB

        return $profile;
    }


    public static function register_field_text($texts, $profile)
    {
        if (is_string($texts)) $texts = array($texts);

        foreach ($texts as $text) {
            $found = false;
            foreach (Game::$array_fields as $field) {
                if (in_array($text, Config::get('vgc.'.$field))) {
                    if ( ! isset($profile[$field])) $profile[$field] = array();
                    $profile[$field][] = $text;
                    $found = true;
                }
            }

            if ($found == false) Log::write('crawler text-no-fields', "Text '$text' was not found in any array fields for the game '".$profile['name']."'.");
        }

        return $profile;
    }
}


