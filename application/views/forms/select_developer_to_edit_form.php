		<div id="select_developer_to_edit_form">
			<h1>Select the developer to edit</h1>
		
			<?php echo form_open("admin/editdeveloper"); ?>

				<?php echo form_dropdown("developer_id_select", get_users_array("dev"), null, 'id="developer_id_select"' ); ?>
				<?php echo ' <label for="developer_id_select">Select the developer</label>'; ?>

				<br>
				<input type="number" min="1" name="developer_id_text" id="developer_id_text"> <label for="developer_id_text">Or write its id</label> <br>
				<br>
				<input type="submit" name="select_developer_to_edit_form_submitted" value="Edit this developer"> <br>
				<!--<input type="submit" name="edit_own_account_form_submitted" value="Edit my own account">-->
			</form>
		</div>