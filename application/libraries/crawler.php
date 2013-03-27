<?php

class Crawler 
{
    public static $patterns = array(
        'meta_keywords' => '\<meta name="keywords" content="(.*)"',
        'meta_description' => '\<meta name="description" content="(.*)"',
    );
    
    public static function make($url)
    {
        if (strpos($url, "indiedb.com") !== false) {
            return static::crawl_indiedb($url);
        }
    }

    // find the game RELATIVE url (/games/[name]) from a game news @indiedb
    public static function get_indiedb_game_url_from_news($url) {
        $game_url = '';

        // try to find the game name in the url
        preg_match("#(/games/[^/]+)/?#", $url, $game_name);
        if (isset($game_name[1])) {
            $game_url = $game_name[1];
        } else {
            // fetch the new's related games
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
        }

        return $game_url;
    }

    public static function crawl_indiedb($url)
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
            $boxes = $sidecolumn->find("div[class=normalbox]");
            
            foreach ($boxes as $box) {
                $heading = $box->find('span[class=heading]', 0);
                if ($heading !== null && in_array(trim($heading->plaintext), array('Profile', 'Style'))) {
                    
                    $rows = $box->find("div[class=clear]");
                    foreach ($rows as $row) {
                        $h5 = $row->find("h5", 0);

                        if ($h5 !== null) {
                            $row_title = trim(strtolower($h5->plaintext));
                            $links = $row->find("a");
                            if (isset($links[0])) $link = $links[0];

                            switch ($row_title) {
                                case "developer" :
                                case "developed by":
                                case "developer &amp; publisher":
                                    $profile["developer_name"] = $link->plaintext;

                                    if (strpos($link->href, "/company/") !== false) {
                                        $dev_html = file_get_html("http://www.indiedb.com".$link->href);
                                        $boxes = $dev_html->find("div[class=normalbox]");

                                        foreach ($boxes as $box) {
                                            $heading = $box->find('span[class=heading]', 0);
                                            if ($heading !== null && trim($heading->plaintext) == "Profile") {
                                                $rows = $box->find("div[class=clear]");

                                                foreach ($rows as $row) {
                                                    $h5 = $row->find("h5", 0);

                                                    if ($h5 !== null && trim($h5->plaintext) == "Homepage") {
                                                        $a = $row->find("a", 0);
                                                        $profile["developer_url"] = $a->href;
                                                    }
                                                }
                                            }
                                        }
                                        
                                    }
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
                                    // Coming Apr 2013
                                    // Coming Q1 2013
                                    // Released Apr 15, 2013
                                    // DateTime::createFromFormat ('M j, Y', 'Nov 7, 2011')
                                    $rd = $text;
                                    
                                    if ($rd != 'tbd') {
                                        $rd = str_replace('Coming ', '', $rd);
                                        $rd = str_replace('Released ', '', $rd);

                                        $date = DateTime::createFromFormat('M j, Y', $rd);
                                        if ($date === false) $date = DateTime::createFromFormat('M Y', $rd);

                                        if ($date !== false) {
                                            $profile['release_date'] = $date->format(Config::get('vgc.date_formats.datetime_sql'));
                                        }
                                    }
                                    break;

                                /*case "boxshot" :
                                    $profile["screenshots"][] = array(
                                        'name' => "Boxshot",
                                        'url' => $row->find("img", 0)->src
                                    );
                                    break;*/

                                case "platforms":
                                    $as = $row->find('span[class=summary] a');
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

                                    break;

                                case "engine":
                                    $engine = trim(strtolower(str_replace("/engines/", "", $link->href)));
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

                                    if (strpos($engine, 'rpg-maker') !== false) $profile['technologies'][] = "rpgmaker";
                                    elseif (strpos($engine, 'unreal-engine') !== false) $profile['technologies'][] = "unrealengine";
                                    elseif (strpos($engine, 'construct') !== false) $profile['technologies'][] = "construct";
                                    elseif (strpos($engine, 'torque') !== false) $profile['technologies'][] = "torque";
                                    elseif (strpos($engine, 'cocos2d') !== false) $profile['technologies'][] = "cocos2d";
                                    break;
                            } // end switch($h5_name)

                            // working on the $text
                            $span = $row->find("span", 0);
                            $text = trim(strtolower($span->plaintext));
                            $profile_field_items = array($text, str_replace(" ", "", $text));
                            
                            $some_items = array('strategy', 'real time', 'turn based', 'tactical', 'shooter', 'first person', 'puzzle', 'futuristic', 'combat', 'sim');
                            foreach ($some_items as $item) {
                                if (strpos($text, $item) !== false) { // ei: 'strategy' is found in 'real time strategy'
                                    if ($item == 'sim') $item == 'simulation';

                                    $profile_field_items[] = str_replace(" ", "", $item); // ie: 'first person' become 'firstperson'
                                }
                            }

                            // other cases
                            switch ($text) {                                                         
                                case "massively multiplayer": 
                                    $profile_field_items[] = "mmo";
                                    break;

                                case 'single &amp; multiplayer':
                                    $profile_field_items[] = 'singleplayer';
                                    $profile_field_items[] = 'multiplayer';
                                    break;

                                case 'single &amp; co-op':
                                    $profile_field_items[] = 'singleplayer';
                                    $profile_field_items[] = 'coop';
                                    break;

                                case "comedy": 
                                    $profile_field_items[] = "cartoon";
                                    break;

                                case "Hack 'n' Slash":
                                    $profile_field_items[] = "hackandslash";
                                    break;

                                case "fighter":
                                    $profile_field_items[] = "fighting";
                            }

                            $profile = static::register_field_items($profile_field_items, $profile);
                        } // end $h5 !== null
                    } // end foreach ($rows as $row) {
                } // end heading !== null
            } // end foreach ($boxes as $box) {

            // remove double entries in the array fields
            foreach (Game::$array_fields as $field) {
                if (isset($profile[$field])) {
                    $profile[$field] = array_values(array_unique($profile[$field]));
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

            // price (in a banner for Desura)
            $desura_banner = $body->find("#desuraisawesome", 0);
            if ($desura_banner !== null) {
                $profile['links'][] = array(
                    'name' => 'Play on Desura',
                    'url' => str_replace("indiedb", "desura", $url),
                );
                $price = $desura_banner->find("span[class=desuratexttwo]", 0)->plaintext;
                // price may be :
                // Only 3,99€
                // 50% off - now 3,99€
                // The easiest way to play => free

                if (strpos($price, "The easiest way to play") !== false) {
                    $profile['price'] = 0;
                } 
                elseif (strpos($price, "Only") !== false) {
                    preg_match("#([0-9,]+)#", $price, $price);
                    if (isset($price[1])) $profile['price'] = $price[1];
                } 
                elseif (strpos($price, "now") !== false) {
                    preg_match("#([0-9]+)%#", $price, $percentage);
                    preg_match("#now ([0-9,]+)#", $price, $price);

                    if (isset($percentage[1]) && isset($price[1])) {
                        $percentage = (int)$percentage[1] / 100.0;
                        $profile['price'] = (float)str_replace(',', '.', $price[1]) / $percentage;
                    }
                }
            }
        } // end Indie DB

        return $profile;
    }


    public static function register_field_items($items, $profile)
    {
        if (is_string($items)) $items = array($items);

        foreach ($items as $item) {
            $found = false;
            foreach (Game::$array_fields as $field) {
                if (in_array($item, Config::get('vgc.'.$field))) {
                    if ( ! isset($profile[$field])) $profile[$field] = array();
                    $profile[$field][] = $item;
                    $found = true;
                }
            }

            if ($found == false) Log::write('crawler item-no-fields', "Item '$item' was not found in any array fields for the game '".$profile['name']."'.");
        }

        return $profile;
    }
}


