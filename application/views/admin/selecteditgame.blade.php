<?php
if (IS_DEVELOPER) $games = user()->dev->games;
else $games = Game::all('id', 'name');
?>
<div id="selecteditgame_form">
	{{ Former::open_vertical('admin/selecteditgame')->rules(array('game_name' => 'required')) }} 
		<legend>Select the game to edit</legend>
		{{ Form::token() }}

		{{-- Former::text('game_name', 'Name or id')->useDatalist($games, 'name') --}}
        {{ Former::select('game_id', 'Name')->fromQuery($games) }}

		<input type="submit" value="Edit this game" class="btn btn-primary">
	</form>
</div>
<!-- /#user_form --> 
