		<div id="select_game_to_edit_form">
			<?php echo form_open("admin/editgame", array("class"=>"form-horizontal")); ?> 
				<legend>Select the game to edit</legend>

				<?php echo get_form_errors(); ?> 

<?php
if (IS_ADMIN) // allow to edit any games
	$raw_games = get_db_rows("id, name", "profiles", "type = 'game'");
else
	$raw_games = get_db_rows("id, name", "profiles", "type = 'game' AND user_id = '".USER_ID."'");

$games = array();
if ($raw_games !== false) {
	foreach ($raw_games->result() as $game)
		$games[$game->id] = $game->name;
}
?>
				<div class="control-group">
					<label class="control-label" for="game_id_select">Select the game</label> 
					<?php echo form_dropdown("game_id_select", $games, null, 'id="game_id_select" class="controls"'); ?> 
				</div>
				<div class="control-group">
					<label class="control-label" for="game_id_text">Or write its id</label>
					<input type="number" class="controls" min="1" name="game_id_text" id="game_id_text"> <br>
				</div>

				<input type="submit" name="select_game_to_edit_form_submitted" value="Edit this game">
			</form>
		</div> <!-- /#select_game_to_edit_form -->