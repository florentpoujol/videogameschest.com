<?php

class Crawler 
{
    
    public function __construct($profile_to_crawl)
    {

    }

    public static function crawl($profile_to_crawl)
    {
        $link = $profile_to_crawl->link;
        $profile_type = $profile_to_crawl->profile_type;

        $func_name = "crawl_$profile_type";
        return static::$func_name($link);
    }


    public static function crawl_game($link)
    {
        // get the type of link
    }
}
