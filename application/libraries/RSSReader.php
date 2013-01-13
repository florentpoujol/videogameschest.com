<?php

class RSSReader 
{
    /**
     * Cache lifetime in minutes
     * 0 to disable caching
     * 1440 = 1 day
     */
    public static $cache_life = 0;


    // ----------------------------------------------------------------------------------

    public static function read($uri, $max_item_count = 0) 
    {
        $feed = array('channel' => array(), 'items' => array());

        if (trim($uri) == '')
            return $feed;

        $cache_item_name = 'rssreader_'.md5($uri);

        // read from cache ?
        if (static::$cache_life > 0) {
            $cached_value = Cache::get($cache_item_name);

            if ( ! is_null($cached_value)) {
                return json_decode($cached_value, true);
            }
        }

        //Parse the document
        $rawFeed = file_get_contents($uri);
        $xml = new SimpleXmlElement($rawFeed);

        // Assign the channel data
        $keys = get_object_vars($xml->channel);
        
        foreach ($keys as $key => $value) {
            if ($key != 'item') $feed['channel'][$key] = (string)$value;
        }
        
        // Build the items array
        $item_count = 0;
        
        foreach ($xml->channel->item as $feed_item) {
            if ($max_item_count > 0 && $item_count >= $max_item_count) break;
            $item_count++;

            $data = array();

            $keys = get_object_vars($feed_item);
            foreach ($keys as $key => $value) {
                $data[$key] = (string)$value;
            }
            
            $feed['items'][] = $data;
        }

        // Cache the feed ?
        if (static::$cache_life > 0) {
            Cache::put($cache_item_name, json_encode($feed), static::$cache_life); 
            // using json_encode here because serialize wouldn't serialize a SimpleXmlElement object
        }
        
        return $feed;
    }
}
