<?php

/*
report form via colorbox

<a href="http://the link" id="theid">link</a>
$("#theid").colorbox({iframe:true, width:"400px", height:"200px"});
 */


// $url = "http://www.indiedb.com/games/piratehell";

// $game = Crawler::make($url);
// // Game::create($game);
// var_dump($game);


?>
<a href="{{ route('get_blacklist_update', array(user_id(), user()->url_key, 'add', 25))}}">click</a>