<?php

/*
report form via colorbox

<a href="http://the link" id="theid">link</a>
$("#theid").colorbox({iframe:true, width:"400px", height:"200px"});
 */

/*function convert($array)
{
    if (!isset($array['names'])) return $array;
    $items  = array();
    for ($i=0; $i < count($array['names']); $i++) { 
        $items[] = array(
            'name' => $array['names'][$i],
            'url' => $array['urls'][$i],
        );
    }
    return $items;
}

$games = Game::all();

foreach ($games as $game) {
    $game->screenshots = convert($game->screenshots);
    $game->videos = convert($game->videos);
    $game->links = convert($game->links);
    $game->save();
}*/

var_dump(Game::find(2)->screenshots);

// $profile = Crawler::crawl_game("http://www.indiedb.com/games/minecraft");
// Game::create($profile);

// var_dump(Crawler::get_indiedb_profile_url_from_news('http://www.indiedb.com/news/dev-log-05-damn'));

// var_dump(DateTime::createFromFormat ('M j, Y', 'Nov 7, 2011'));
// $url = '/games/minecraft/videos/official-minecon-intro-2012#imagebox';
// var_dump(str_replace("#imagebox", '', "http://ww.indiedb.com".$url))
?>



@section('jQuery')
    

@endsection


