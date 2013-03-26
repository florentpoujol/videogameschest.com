<?php

class Crawler_Controller extends Base_Controller 
{
    public function get_index()
    {
        $thhis->layout->nest('page_content', 'crawler');
    }

    // READ RSS FEED
    public function get_read_feed_urls() 
    {
        $feed_urls = json_decode(DBConfig::get('crawler_feed_urls', '[]'), true);

        if (empty($feed_urls)) {
            HTML::set_error("No feed urls where found in the database.");
        }

        foreach ($feed_urls as $url) {
            $feed = RSSReader::read($url);
            if (empty($feed['items'])) {
                HTML::set_error("No items in the feed url '".$url."'.");
                continue;
            }

            $profiles_added = 0;

            foreach ($feed['items'] as $item) {
                $item_url = $item['link'];
                $guid = $item['guid'];

                if (SuggestedProfile::where_url($item_url)->first() === null && SuggestedProfile::where_guid($guid)->first() === null) {
                    if (strpos($item_url, 'edb.com') !== false && strpos($item_url, '/news/') !== false) {
                        // slidedb.com
                        // indiedb.com
                        // need to get the url of the game instead of the news
                        $relativ_game_url = Crawler::get_indiedb_profile_url_from_news($item_url);
                        if ($relativ_game_url == '') continue; // the news was not about a game
                        $item_url = "http://www.indiedb.com".$relativ_game_url;
                        // all slide db profile have an indie db profile
                    }

                    $profile = new SuggestedProfile;
                    $profile->url = $item_url;
                    $profile->guid = $guid;
                    $profile->source = 'rss';
                    $profile->source_feed = $url;
                    $profile->statut = 'waiting';
                    $profile->save();
                    $profiles_added++;
                }
            }

            HTML::set_success("$profiles_added urls added from the feed url '".$url."'.");
        }

        return Redirect::to_route('get_crawler_page');
    }


    public function post_add_feed_url()
    {
        $db_entry = DBConfig::where('_key', '=', 'crawler_feed_urls')->first();
        
        $feed_urls = json_decode($db_entry->value, true);
        $feed_urls[] = Input::get('feed_url');
        
        $db_entry->value = json_encode($feed_urls);
        $db_entry->save();
        
        return Redirect::to_route('get_crawler_page');
    }


    public function pos_perform_actions()
    {
        $profiles = Input::get('profiles');

        foreach ($profiles as $id => $profile) {
            if ($profile['action'] == 'add') {
                $profile_data = Crawler::crawl_game($profile['url']);
                
                $game = Game::create($profile_data);

                $suggested_profile = SuggestedProfile::find($id);
                $suggested_profile->statut = 'added';
                $suggested_profile->profile_type = 'game';
                $suggested_profile->profile_id = $game->id;
                $suggested_profile->save();
            } elseif ($profile['action']  == 'discard') {
                SuggestedProfile::update($id, array('statut' => 'discarded'));
            }   
        }
        
        return Redirect::to_route('get_crawler_page');
    }
}
