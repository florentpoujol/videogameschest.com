<?php

class Crawler_Controller extends Base_Controller 
{
    public function get_index()
    {
        $this->layout->nest('page_content', 'crawler');
    }

    // READ RSS FEED
    // read the rss feed then extract unknow games
    public function get_read_feed_urls() 
    {
        $feed_urls = array(
            "http://rss.indiedb.com/articles/feed/rss.xml",
            "http://rss.slidedb.com/articles/feed/rss.xml",

        );

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
                    // IndieDB, SlideDB
                    if (strpos($item_url, 'edb.com') !== false && strpos($item_url, '/news/') !== false) {
                        // this a news from indiedb or slidedb
                        // need to get the url of the game instead of the news
                        $relativ_game_url = Crawler::get_indiedb_game_url_from_news($item_url);
                        if ($relativ_game_url == '') continue; // the news was not about a game
                        $item_url = "http://www.indiedb.com".$relativ_game_url; // all slide db profile have an indie db profile

                        if (SuggestedProfile::where_url($item_url)->first() !== null) continue;

                        $name = ucfirst(url_to_name(rtrim(str_replace("http://www.indiedb.com/games/", "", $item_url), '/')));
                        if (Game::where_name($name)->first() !== null) continue;
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


    // action :
    // "discard" : the game is not woth adding to the list
    // "add" : the game page is crawled
    public function post_perform_actions()
    {
        $profiles = Input::get('profiles');
        $profiles_to_crawl = array();

        foreach ($profiles as $id => $profile) {
            $action = $profile['action'];
            $url = $profile['url'];

            if ($action  == 'discard' || $action  == 'manually-added') {
                SuggestedProfile::update($id, array(
                    'url' => $url,
                    'statut' => $action,
                ));
            } elseif ($action == 'delete') {
                SuggestedProfile::find($id)->delete();
            }
            elseif ($action == 'crawl') {
                $profiles_to_crawl[$id] = $profile;
            }
        }

        foreach ($profiles_to_crawl as $id => $profile) {
            $url = $profile['url'];

            $profile_data = Crawler::make($url);
            $game = Game::create($profile_data);

            SuggestedProfile::update($id, array(
                'url' => $url,
                'statut' => 'crawled',
                'profile_type' => 'game',
                'profile_id' => $game->id,
            ));
        }
        
        return Redirect::to_route('get_crawler_page');
    }
}
