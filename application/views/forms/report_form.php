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
					<?php echo lang("report_type"); ?> :<br>
					<input type="radio" name="report_form[type]" value="noncritical" id="reporttype_noncritical" checked="checked"> <label for="reporttype_noncritical"><?php echo lang("report_noncritical");?></label> <br>
					<input type="radio" name="report_form[type]" value="critical" id="reporttype_critical"> <label for="reporttype_critical"><?php echo lang("report_critical");?></label> <br>
					<br>
					<?php echo '<input type="hidden" name="report_form[item_type]" value="'.get_page().'"'; ?>>
					<?php echo '<input type="hidden" name="report_form[item_id]" value="'.$item_id.'"'; ?>>
					<input type="submit" name="report_form_submitted" value="<?php echo lang("report_submit");?>">
				</form>
			</fieldset>
		</div> 
		<!-- /#report_form -->
