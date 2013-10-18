<?php

class Crawler 
{
    public static $patterns = array(
        'meta_keywords' => '\<meta name="keywords" content="(.*)"',
        'meta_description' => '\<meta name="description" content="(.*)"',
    );
    
    public static function crawl($url)
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
            'medias' => array(),
            'tags' => array(),
            'suggested_new_tags' => array(),
        );

        $temp_tags = Tag::all(array('id', 'name'));
        $tags = array();
        foreach ($temp_tags as $tag) {
            $tags[ $tag->id ] = $tag->name;
        }

        // INDIE DB
        if (strpos($url, 'indiedb.com') !== false) {
            // get name
            preg_match("#/games/([^/]+)#i", $url, $name);
            if (isset($name[1])) $profile['name'] = ucfirst(url_to_name($name[1]));

            // use simple_html_dom_parser
            $html = file_get_html($url);
            $body = $html->find("body", 0);

            $description_block = $body->find("div[class=headernormalbox normalbox] div[class=inner] div[class=body]", 0);
            $profile['description'] = trim($description_block->plaintext);

            // get all links in the description
            $desc_links = $description_block->find("a");
            foreach ($desc_links as $key => $link) {
                $profile['links'][] = array(
                    'name' => $link->plaintext,
                    'url' => $link->href,
                );
            }

            $sidecolumn = $body->find("div[class=sidecolumn]", 0);
            $boxes = $sidecolumn->find("div[class=normalbox]");
            // the boxes are the "Profile", "News", "Related Games" boxes in the side bar
            // only looking for Profile and Style

            foreach ($boxes as $box) {
                $heading = $box->find('span[class=heading]', 0);
                if ($heading !== null && in_array(trim($heading->plaintext), array('Profile', 'Style'))) {
                    
                    $rows = $box->find("div[class=clear]");
                    // $rows = each rows in the box "Icon", "Platform", "Developed By", ...
                    foreach ($rows as $row) {
                        $h5 = $row->find("h5", 0);

                        if ($h5 !== null) {
                            $row_title = trim(strtolower($h5->plaintext));
                            $row_text = trim(strtolower( $row->find('span', 0)->plaintext ));
                            
                            $links = $row->find("a");
                            if (isset($links[0])) $link = $links[0];

                            switch ($row_title) {
                                /*case "developer" :
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
                                    break;*/


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
                                    
                                    $rd = $row_text;
                                    
                                    if ($rd != 'tbd') {
                                        $rd = str_replace('coming ', '', $rd);
                                        $rd = str_replace('released ', '', $rd);

                                        $date = DateTime::createFromFormat('M j, Y', $rd);
                                        if ($date === false) $date = DateTime::createFromFormat('M Y', $rd);

                                        if ($date !== false) {
                                            $profile['release_date'] = $date->format(Config::get('vgc.date_formats.date_sql'));
                                        }
                                    }
                                    break;

                                case "platforms":
                                    $as = $row->find('span[class=summary] a');

                                    foreach ($as as $a) {
                                        $platform = trim(strtolower(str_replace("/platforms/set/", "", $a->href)));
                                        
                                        switch ($platform) {
                                            case "linux":
                                                $profile['tags'][] = "linux";
                                                $profile['tags'][] = "pc";
                                            break;

                                            case "web":
                                                $profile['tags'][] = "browser";
                                            break;

                                            case "iphone":
                                                $profile['tags'][] = "iphone";
                                                $profile['tags'][] = "ios";
                                            break;

                                            case "ipad":
                                                $profile['tags'][] = "ipad";
                                                $profile['tags'][] = "ios";
                                            break;

                                            case "android":
                                                $profile['tags'][] = "smartphone";
                                                $profile['tags'][] = "android";
                                            break;

                                            case "androidtab":
                                                $profile['tags'][] = "tablet";
                                                $profile['tags'][] = "android";
                                            break;

                                            case "androidconsole":
                                                $profile['tags'][] = "console";
                                                $profile['tags'][] = "android";
                                            break;

                                            case "metro":
                                                $profile['tags'][] = "pc";
                                                $profile['tags'][] = "windows8";
                                            break;

                                            case "vita":
                                                $profile['tags'][] = "psvita";
                                            break;

                                            case "x360":
                                                $profile['tags'][] = "xbox360";
                                            break;

                                            default:
                                                if (in_array($platform, $tags)) {
                                                    $profile['tags'][] = $platform;
                                                }
                                                else
                                                    $profile['suggested_new_tags'][] = $platform;
                                            break;
                                        }
                                    } // end foreach platforms links

                                    break;

                                case "engine":
                                    $engine = trim(strtolower(str_replace("/engines/", "", $link->href)));
                                    
                                    switch ($engine) {
                                        case 'construct-2':
                                            $profile['tags'][] = 'construct';
                                            break;

                                        case 'custom-built':
                                            $profile['tags'][] = 'custom-engine';
                                            break;

                                        case 'cryengine-3':
                                            $profile['tags'][] = 'cry-engine';
                                            break;

                                        case 'unity':
                                            $profile['tags'][] = 'unity-3d';
                                            break;

                                        case 'ogre-engine':
                                            $profile['tags'][] = 'ogre-3d';
                                            break;

                                        case 'blender-game-engine':
                                            $profile['tags'][] = 'blender';
                                            break;

                                        case 'torque-3d':
                                            $profile['tags'][] = 'torque-engine';
                                            break;

                                        case 'shiva3d-19':
                                            $profile['tags'][] = 'shiva-3d';
                                            break;

                                        case 'unknow': // do nothing
                                        break;
                                        
                                        default:
                                            if (strpos($engine, 'rpg-maker') !== false) $profile['tags'][] = "rpg-maker";
                                            elseif (strpos($engine, 'unreal-engine') !== false) $profile['tags'][] = "unreal-engine";
                                            elseif (strpos($engine, 'torque') !== false) $profile['tags'][] = "torque-engine";
                                            elseif (strpos($engine, 'cocos2d') !== false) $profile['tags'][] = "cocos-2d";

                                            elseif (in_array($engine, $tags)) {
                                                $profile['tags'][] = $platform;
                                            }
                                            else
                                                $profile['suggested_new_tags'][] = $engine;
                                        break;
                                    }

                                break;
                            } // end switch($row_title)

                            // working on the $row_text
                            foreach ($tags as $id => $tag) {
                                if (strpos($row_text, $tag) !== false && ! in_array($tag, $profile['tags']))
                                    $profile['tags'][] = $tag;
                            }

                            if ( strpos( $row_text, "massively multiplayer" ) !== false ) { 
                                $profile['tags'][] = "mmo";
                            }
                            elseif ( strpos( $row_text, 'single &amp; multiplayer' ) !== false ) {
                                $profile['tags'][] = 'single-player';
                                $profile['tags'][] = 'multi-player';
                            }
                            elseif ( strpos( $row_text, 'single &amp; co-op' ) !== false ) {
                                $profile['tags'][] = 'single-player';
                                $profile['tags'][] = 'coop';
                            }
                            elseif ( strpos( $row_text, "comedy" ) !== false ) {
                                $profile['tags'][] = "cartoon";
                            }
                            elseif ( strpos( $row_text, "Hack 'n' Slash" ) !== false ) {
                                $profile['tags'][] = "hack-and-slash";
                            }
                            elseif ( strpos( $row_text, "fighter" ) !== false ) {
                                $profile['tags'][] = "fighting";
                            }
                            
                        } // end $h5 !== null   the row has a title
                    } // end foreach ($rows as $row) {
                } // end heading !== null    The box has a title
            } // end foreach ($boxes as $box) {
           
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
                $profile['medias'][] = array(
                    'name' => str_replace( "View ", "", $img->parent->title ),
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
                        'name' => str_replace( "View ", "", $a->title ),
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
                $tag = "";


                if (strpos($price, "The easiest way to play") !== false) {
                    $tags = 'free';
                } 
                elseif (strpos($price, "Only") !== false) {
                    preg_match("#([0-9,]+)#", $price, $price);
                    if (isset($price[1])) 
                        $price = $price[1];
                } 
                elseif (strpos($price, "now") !== false) {
                    preg_match("#([0-9]+)%#", $price, $percentage);
                    preg_match("#now ([0-9,]+)#", $price, $price);

                    if (isset($percentage[1]) && isset($price[1])) {
                        $percentage = (int)$percentage[1] / 100.0;
                        $price = (float)str_replace(',', '.', $price[1]) / $percentage;
                    }
                }

                if (is_numeric($price)) {
                    $tag = "price:";
                    if ( $price > 0 && $price<= 5 )
                        $tag .= "0-5";
                    elseif ( $price > 5 && $price <= 10 )
                        $tag .= "5-10";
                    elseif ( $price > 10 && $price <= 15 )
                        $tag .= "10-15";
                    elseif ( $price > 15 && $price <= 20 )
                        $tag .= "15-20";
                    elseif ( $price > 20 && $price <= 25 )
                        $tag .= "20-25";
                    elseif ( $price > 25 && $price <= 30 )
                        $tag .= "25-30";
                    elseif ( $price > 30 )
                        $tag .= "30+";
                }

                if ($tag != "")
                    $profile['tags'][] = $tag;
            }
        }


        // fix some suggested tags
        /*foreach ($profile['suggested_new_tags'] as $key => $value) {
            switch( $value ){
                case 'mobile':
                    $profile['tags'][] = "smartphone";
                    unset( $profile['suggested_new_tags'] );
                break;

                case 'androidtab'
            }
            
        }*/

        //$profile['links'] = clean_names_urls_array( $profile['links'] );
        dd( $profile );
        return $profile;
    } // end Indie DB
}


