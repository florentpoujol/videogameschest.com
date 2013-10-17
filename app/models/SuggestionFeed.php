<?php
class SuggestionFeed extends Eloquent {
    protected $table = "suggestionfeeds";


    public function read() {
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
                Suggestion::whereUrl($item_url)->first() === null &&
                Suggestion::where_title($title)->first() === null &&
                Suggestion::where_guid($guid)->first() === null
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
                        Suggestion::where_url($item_url)->first() !== null ||
                        Suggestion::where_title($title)->first() !== null ||
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

                $profile = new Suggestion;
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
}
