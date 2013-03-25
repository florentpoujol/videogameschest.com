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
                $game_url = "http://www.indiedb.com".$row->find('a', 0)->href;
                break;
            }
        }

        return $game_url;
    }

    public static function crawl_game($url)
    {
        // get the type of url
        $profile = array(
            'links' => array('IndieDB' => $url),
            'screenshots' => array(),
        );

        // INDIE DB
        if (strpos($url, 'indiedb.com') !== false) {
            $source_code = file_get_contents($url);

            preg_match("#/games/([^/]+)(/.*)?^#i", $url, $name);
            if (isset($name[1]))
                $profile['name'] = ucfirst(url_to_name($name[1]));

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
                            $profile["developer_name"] = $item->find("a", 0)->plaintext;
                            break;

                        case "official page" :
                            $profile['links']['website'] = $link->href;
                            break;

                        case "release date":
                            $profile['release_date'] = $item->find("span", 0)->plaintext;
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

                    switch (strtolower($category)) {
                        case "genre":
                            $profile["genres"] = array($span->plaintext, );
                            break;

                        case "theme" :
                            $profile["looks"] = array($span->plaintext, );
                            break;

                        case "players" :
                            $profile["players"] = array($span->plaintext, );
                            break;

                        case "boxshot" :
                            $profile["screenshots"]["Boxshot"] = $item->find("img", 0)->src;
                            break;

                    }
                }
            }

            // twitter
            $box = $sidecolumn->find("#twitterfeed", 0);
            if ($box !== null) {
                $item = $box->find("div[class=table] div", 0);
                $profile['links']['Twitter'] = $item->find("a", 0)->href;
            }
        }


        return $profile;
    }

    public static function innerHTML( $contentdiv ) 
    {
        $r = '';
        //$elements = $contentdiv->childNodes;
        foreach( $contentdiv as $element ) { 
            if ( $element->nodeType == XML_TEXT_NODE ) {
                $text = $element->nodeValue;
                // IIRC the next line was for working around a
                // WordPress bug
                //$text = str_replace( '<', '&lt;', $text );
                $r .= $text;
            }    
            // FIXME we should return comments as well
            elseif ( $element->nodeType == XML_COMMENT_NODE ) {
                $r .= '';
            }    
            else {
                $r .= '<';
                $r .= $element->nodeName;
                if ( $element->hasAttributes() ) { 
                    $attributes = $element->attributes;
                    foreach ( $attributes as $attribute )
                        $r .= " {$attribute->nodeName}='{$attribute->nodeValue}'" ;
                }    
                $r .= '>';
                $r .= innerHTML( $element );
                $r .= "</{$element->nodeName}>";
            }    
        }    
        return $r;
    }
}
