		<div id="select_game_to_edit_form">
<?php
echo get_form_errors().'
';
?>
			<h2>Select the game to edit</h2>

<?php
echo form_open( 'admin/editgame' );

if( userdata( 'is_admin' ) ) // allow to edit any games
	$raw_games = get_db_rows( 'games' );
else
	$raw_games = get_db_rows( 'games', 'developer_id', userdata( 'user_id' ) );

$games = array();
foreach( $raw_games->result() as $game ) {
	$games[$game->game_id] = $game->name;
}

echo form_dropdown( 'game_id_select', $games, null, 'id="game_id_select"' );
echo ' <label for="game_id_select">Select the game</label>';
?>
				<br>
				<input type="number" min="1" name="game_id_text" id="game_id_text"> <label for="game_id_text">Or write its id</label> <br>
				<br>
				<input type="submit" name="select_game_to_edit_form_submitted" value="Edit this game"> <br>
			</form>
		</div> <!-- /#select_game_to_edit_form -->