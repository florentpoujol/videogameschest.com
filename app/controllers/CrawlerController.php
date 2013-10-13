<?php

class CrawlerController extends BaseController 
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
            "indiegames.com" => "http://feeds2.feedburner.com/IndependentGaming",
            "rps" => "http://feeds.feedburner.com/RockPaperShotgun",
            "indiedb" => "http://rss.indiedb.com/articles/feed/rss.xml",
            "slidedb" => "http://rss.slidedb.com/articles/feed/rss.xml",
        );

        foreach ($feed_urls as $feed_name => $feed_url) {
            $feed = RSSReader::read($feed_url);
            if (empty($feed['items'])) {
                HTML::set_error("No items in the feed '".$feed_name."'.");
                continue;
            }

            $profiles_added = 0;

            foreach ($feed['items'] as $item) {
                $item_url = $item['link'];
                if (isset($item['guid'])) $guid = $item['guid'];
                else $guid = $item['id']; // Atom feed
                $title = $item['title'];

                if (
                    SuggestedProfile::where_url($item_url)->first() === null &&
                    SuggestedProfile::where_title($title)->first() === null &&
                    SuggestedProfile::where_guid($guid)->first() === null
                ) {
                    // IndieDB, SlideDB
                    if (strpos($item_url, 'edb.com') !== false && strpos($item_url, '/news/') !== false) {
                        // this a news from indiedb or slidedb
                        // need to get the url of the game instead of the news
                        $relativ_game_url = Crawler::get_indiedb_game_url_from_news($item_url);
                        if ($relativ_game_url == '') continue; // the news was not about a game
                        $item_url = "http://www.indiedb.com".$relativ_game_url; // all slide db profile have an indie db profile
                        $title = ucfirst(url_to_name(rtrim(str_replace("http://www.indiedb.com/games/", "", $item_url), '/')));
                        
                        if (
                            SuggestedProfile::where_url($item_url)->first() !== null ||
                            SuggestedProfile::where_title($title)->first() !== null ||
                            Game::where_name($title)->first() !== null
                        ) continue;
                    } 
                    elseif ($feed_name == "indiegames.com") {
                        // IndieGames.com
                        $keywords = array("game pick", "trailer", "road to the igf", "release");
                        $title = strtolower($item['title']);
                        $suggest_article = false;

                        foreach ($keywords as $word) {
                            if (strpos($title, $word) !== false) $suggest_article = true;
                        }
                        
                        if ($suggest_article == false) continue;
                    } 
                    elseif ($feed_name == "rps") {
                        // RockPaperShotgun
                        $keywords = array("wot i think", "impressions", "trailer", "live free, play hard", "kickstarter katchup");
                        $title = strtolower($item['title']);
                        $suggest_article = false;

                        foreach ($keywords as $word) {
                            if (strpos($title, $word) !== false) $suggest_article = true;
                        }
                        
                        if ($suggest_article == false) continue;
                    }

                    $profile = new SuggestedProfile;
                    $profile->url = $item_url;
                    $profile->guid = $guid;
                    $profile->title = $title;
                    $profile->source = 'rss';
                    $profile->statut = 'waiting';
                    $profile->source_feed = $feed_name;
                    $profile->save();
                    $profiles_added++;
                }
            }

            HTML::set_success("$profiles_added urls added from the feed '".$feed_name."'.");
        }

        return Redirect::route('get_crawler_page');
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
        
        return Redirect::route('get_crawler_page');
    }
}
