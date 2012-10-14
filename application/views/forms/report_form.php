		<div id="report_form">
			<fieldset>
				<legend><?php echo lang("report_title"); ?></legend>
<?php
echo get_form_errors( $this->session->flashdata('report_errors') );
echo get_form_success( $this->session->flashdata('report_success') );
$uri_strings = explode( "/", uri_string() ); // uri_string return the actual URL, not the rerouted one
$item_id = $uri_strings[1];
?>			
				<?php echo form_open("admin/reports"); ?>
					<label for="description"><?php echo lang("report_description");?></label> <br>
					<textarea name="report_form[description]" id="description"></textarea> <br>
					<br>
					<?php echo lang("report_recipient"); ?> :<br>
					<input type="radio" name="report_form[recipient]" value="developer" id="report_developer" checked="checked"> <label for="report_developer"><?php echo lang("report_developer");?></label> <br>
					<input type="radio" name="report_form[recipient]" value="admin" id="report_admin"> <label for="report_admin"><?php echo lang("report_admin");?></label> <br>
					<br>
					<?php echo '<input type="hidden" name="report_form[profile_type]" value="'.CONTROLLER.'"'; ?>>
					<?php echo '<input type="hidden" name="report_form[profile_id]" value="'.$item_id.'"'; ?>>
					<input type="submit" name="new_report_form_submitted" value="<?php echo lang("report_submit");?>">
				</form>
			</fieldset>
		</div> 
		<!-- /#report_form -->
