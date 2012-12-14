<?php
$old = Input::old();
if (isset($old)) {
 var_dump($old);
 Former::populate($old);
}


$data = array('name' => 'Game', 'pitch' => 'bla', 'developper_id' =>15);
$game = Game::create($data);
/*$game = new Game;
$game->name = 'Game';
$game->pitch = 'Bla';*/
var_dump($game);
// $game->save();
$data = array('pitch' => 'bloblo');
 $game = Game::update($game->id, $data);
var_dump($game);




$rules = array(
	'test' => 'min:5'
);

$options = array('1' => 'un', '2'=>'deux');
?>
{{ Former::open_vertical('test')->rules($rules) }} 
	

	{{ Former::multiselect('field[]')->options($options) }}
	{{ Former::text('test') }}


	{{ Former::submit('submit') }}
</form>