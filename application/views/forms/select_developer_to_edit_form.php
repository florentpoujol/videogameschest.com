
		<div id="select_developer_to_edit_form">
			<?php echo form_open("admin/editdeveloper", array("class"=>"form-horizontal")); ?> 
				<legend>Select developer to edit</legend>

				<?php echo get_form_errors(); ?> 

				<div class="control-group">
					<?php echo ' <label class="control-label" for="developer_id_select">Select the developer</label>'; ?> 
					<?php echo form_dropdown("developer_id_select", get_users_array("dev"), null, 'id="developer_id_select" class="controls"' ); ?> 
				</div>
				<div class="control-group">
					<label class="control-label" for="developer_id_text">Or write its id</label> 
					<input type="number" class="controls" min="1" name="developer_id_text" id="developer_id_text"> 					
				</div>

				<input type="submit" name="select_developer_to_edit_form_submitted" value="Edit this developer"> <br>
			</form>
		</div>
