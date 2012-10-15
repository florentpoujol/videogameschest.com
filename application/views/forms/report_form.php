		<div id="report_form">
			<fieldset>
				<legend><?php echo lang("report_title"); ?></legend>
				
				<!-- report form errors and success -->
				<?php echo get_form_errors($this->session->flashdata("report_errors")); ?>
				<?php echo get_form_success($this->session->flashdata("report_success")); ?>
				<!-- /report form errors and success -->
<?php
// extract profile id from the url
$uri_strings = explode("/", uri_string()); // uri_string return the actual URL, not the rerouted one
$profile_id = $uri_strings[1];
?>			
				<?php echo form_open("admin/reports"); ?>
					<label for="description"><?php echo lang("report_description");?></label> <br>
					<textarea name="report_form[description]" id="description"></textarea> <br>
					<br>
					<?php echo lang("report_recipient"); ?> :<br>
					<input type="radio" name="report_form[type]" value="dev" id="report_developer" checked="checked"> <label for="report_developer"><?php echo lang("report_developer");?></label> <br>
					<input type="radio" name="report_form[type]" value="admin" id="report_admin"> <label for="report_admin"><?php echo lang("report_admin");?></label> <br>
					<br>
					<?php //echo '<input type="hidden" name="report_form[profile_type]" value="'.CONTROLLER.'"'; ?>
					<?php echo '<input type="hidden" name="report_form[profile_id]" value="'.$profile_id.'"'; ?>
					<?php echo '<input type="hidden" name="report_form[url]" value="'.uri_string().'"'; ?>
					<input type="submit" name="new_report_form_submitted" value="<?php echo lang("report_submit");?>">
				</form>
			</fieldset>
		</div> 
		<!-- /#report_form -->
