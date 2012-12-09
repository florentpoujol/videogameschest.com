<?php
if (IS_DEVELOPER) $games = Auth::user()->dev->games->get(array('id', 'name'));
else $games = Game::get(array('id', 'name'));

?>
<div id="selecteditdeveloper_form">
	{{ Former::open_vertical('admin/selecteditgame')->rules(array('game_name' => 'required')) }} 
		<legend>Select the game to edit</legend>
		{{ Form::token() }}

		{{ Former::text('game_name', 'Name or id')->useDatalist($games, 'name') }}

		<input type="submit" value="Edit this game" class="btn btn-primary">
	</form>
</div>
<!-- /#user_form --> 
