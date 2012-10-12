		<div id="report_form">
			<h1><?php echo lang("report_title"); ?></h1>
<?php
if ( ! isset($item_type) )
    $item_type = "";
if ( ! isset($item_id) )
    $item_id = "";

if(isset($form))
	echo get_form_errors($form);
?>		

			<?php echo form_open("admin/reports"); ?>
				<label for="description"><?php echo lang("report_description");?></label> <br>
				<textarea name="form[description]" id="description"></textarea> <br>
				<br>
				<?php echo lang("report_type"); ?> :<br>
				<input type="radio" name="form[report_type]" value="noncritical" id="reporttype_noncritical" checked="checked"> <label for="reporttype_noncritical"><?php echo lang("report_noncritical");?></label> <br>
				<input type="radio" name="form[report_type]" value="critical" id="reporttype_critical"> <label for="reporttype_critical"><?php echo lang("report_critical");?></label> <br>
				<br>
				<?php echo '<input type="hidden" name="form[item_type]" value="'.$item_type.'"'; ?>>
				<?php echo '<input type="hidden" name="form[item_id]" value="'.$item_id.'"'; ?>>
				<input type="submit" name="report_form_submitted" value="<?php echo lang("report_submit");?>">
			</form>
			<br>
			<?php echo '<a href="'.site_url("$item_type/$item_id").'">'.lang("report_gobacktoprofile").'</a>'; ?>
		</div> <!-- /#report_form -->