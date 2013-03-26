<?php

/*
report form via colorbox

<a href="http://the link" id="theid">link</a>
$("#theid").colorbox({iframe:true, width:"400px", height:"200px"});
 */


$url = "http://www.indiedb.com/games/minecraft";

$game = Crawler::crawl_game($url);
// Game::create($game);
var_dump($game);

?>
