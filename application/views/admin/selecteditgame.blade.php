<?php
if (is_admin()) $games = Game::all('id', 'name');
else $games = user()->games;
?>
<div id="selecteditgame-form">
	{{ Former::open_vertical(route('post_selecteditgame'))->rules(array('game_id' => 'required')) }} 
		<legend>Select the game to edit</legend>
		{{ Form::token() }}

		{{-- Former::text('game_name', 'Name or id')->useDatalist($games, 'name') --}}
        {{ Former::select('game_id', 'Name')->fromQuery($games) }}

		<input type="submit" value="Edit this game" class="btn btn-primary">
	</form>
</div> <!-- /#selecteditgame-form --> 
