<div id="select_developer_to_edit_form">
	<h2>Select the developer to edit</h2>
<?php 
echo form_open( 'admin/editdeveloper' );

$devs = get_developers();
echo form_dropdown( 'developer_id_select', $devs, null, 'id="developer_id_select"' );
echo ' <label for="developer_id_select">Select the developer</label>';
?>
		<br>
		<input type="number" min="1" name="developer_id_text" id="developer_id_text"> <label for="developer_id_text">Or write its id</label> <br>
		<br>
		<input type="submit" name="select_developer_to_edit_form_submitted" value="Edit this developer"> <br>
		<!--<input type="submit" name="edit_own_account_form_submitted" value="Edit my own account">-->
	</form>
</div>