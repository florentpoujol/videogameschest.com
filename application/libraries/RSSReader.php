<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class RSSReader {
    
    private $ci;

    /* Associative array containing all the feed items */
    public $feed_items = array();

    /* Store RSS Channel Data in an array */
    public $channel_data = array();

    /* Code Ignitor cache directory */
    public $cache_dir = '';

    /* Cache lifetime */
    public $cache_life = 0;

    /* Flag to write to cache - defaulted to false*/
    public $write_cache_flag = false;


    // ----------------------------------------------------------------------------------

    /**
     * Constructor
     */
    function RSSReader( $uri = '' ) {
        $this->ci =& get_instance();

        if( $this->ci->config->item('cache_path') != '' )
            $this->cache_dir = $this->ci->config->item('cache_path');
        else
            $this->cache_dir = FCPATH.APPPATH.'cache/';

        $this->cache_life = 0;

        //Attempt to parse the feed
        if( $uri != '' )
            $this->parse( $uri );
    }


    // ----------------------------------------------------------------------------------

    /**
     * 
     */
    function parse( $uri ) {
        if( trim( $uri ) == '' )
            return $this;

        // check the cache file
        if( $this->cache_life > 0 ) {
            $cache_file = $this->cache_dir.'rssreader_feed_cache_'.md5( $uri );

            if( file_exists( $cache_file ) ) {
                // cache still valid ?
                $timedif = (time() - filemtime( $cache_file ));
                if( $timedif < ($this->cache_life * 60) ) {
                    //its ok - so we can skip all the parsing and just return the cached array here
                    $this->feed_items = unserialize( implode( '', file( $cache_file ) ) );
                    return $this;
                }

                //So raise the falg
                $this->write_cache_flag = true;
            } 
            else {
               //Raise the flag to write the cache
               $this->write_cache_flag = true;
            }
        }

        //Parse the document
        $rawFeed = file_get_contents( $uri );
        $xml = new SimpleXmlElement( $rawFeed );

        //Assign the channel data
        $this->channel_data['title'] = $xml->channel->title;
        $this->channel_data['description'] = $xml->channel->description;

        //Build the item array
        foreach( $xml->channel->item as $item ) {
            $data = array();
            $data['title'] = (string)$item->title;
            $data['description'] = (string)$item->description;
            $data['pubDate'] = (string)$item->pubDate;
            $data['link'] = (string)$item->link;
            $this->feed_items[] = $data;
        }

        //Do we need to write the cache file?
        if( $this->write_cache_flag ) {
            $file = @fopen( $file_name, 'wb' );
            if( ! $file ) {
                echo "ERROR";
                log_message('error', "Unable to write cache file: ".$cache_path);
                return;
            }

            flock( $file, LOCK_EX );
            fwrite( $file, serialize( $this->feed_items ) );
            flock( $file, LOCK_UN );
            fclose( $file);
        }

        return $this;
    }


    // ----------------------------------------------------------------------------------

    /**
     * Return the feeds one at a time: when there are no more feeds return false
     * @param int $item_count Number of items to return from the feed
     * @return array An array containing the wanted items from the feed
     */
    function get_feed_items( $item_count ) {
        $items = array();

        for( $i = 0; $i < $item_count; $i++ ) {
            if( isset( $this->feed_items[$i] ) )
                $items[] = $this->feed_items[$i];
            else
                break;
        }

        return $items;
    }
}

/* End of file RSSReader.php */
/* Location: ./application/libraries/RSSReader.php */