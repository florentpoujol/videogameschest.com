		<div id="select_game_to_edit_form">
			<h1>Select the game to edit</h1>

			<?php
			if (isset($form))
				echo get_form_errors($form); ?> 

			<?php echo form_open("admin/editgame"); ?> 
<?php
if (IS_ADMIN) // allow to edit any games
	$raw_games = get_db_rows("profile_id, name", "profiles");
else
	$raw_games = get_db_rows("profile_id, name", "profiles", "developer_id = '".USER_ID."'");

$games = array();
if ($raw_games !== false) {
	foreach ($raw_games->result() as $game)
		$games[$game->profile_id] = $game->name;
}
?>
				<?php echo form_dropdown("game_id_select", $games, null, 'id="game_id_select"'); ?> 
				<?php echo ' <label for="game_id_select">Select the game</label>'; ?> 
				<br>
				<input type="number" min="1" name="game_id_text" id="game_id_text"> <label for="game_id_text">Or write its id</label> <br>
				<br>
				<input type="submit" name="select_game_to_edit_form_submitted" value="Edit this game"> <br>
			</form>
		</div> <!-- /#select_game_to_edit_form -->