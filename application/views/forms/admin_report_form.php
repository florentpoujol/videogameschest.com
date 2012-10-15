		<div id="admin_report_form">
			<h1>Reports</h1>

			<!--<input type="checkbox" name="profile_type" value="developer"> Developer <br>
			<input type="checkbox" name="profile_type" value="admin"> Admins <br>
			<input type="checkbox" name="profile_type" value="both" checked="checked"> Both <br>
			<br>
			Sort by
			<input type="radio" name="sort_by" value="date asc" checked="checked">Date Asc<br>
			<input type="radio" name="sort_by" value="date desc">Date desc <br>-->
			<?php //echo get_form_success($reports); ?> 
<?php
//unset($reports["success"]);

if ($reports->num_rows() > 0):
	$site_data = get_static_data('site');
	$format = $site_data->date_formats->nonenglish;

	if (LANGUAGE == "english")
		$format = $site_data->date_formats->english;
?>
			<?php echo form_open( 'admin/reports' ); ?> 
				<table>
					<tr>
						<th>Profile type</th>
						<th>Profile Name</th>
						<th>Date</th>
						<?php echo (IS_ADMIN ? "<th>Report Type</th>": ""); ?> 
						<th>Text</th>
						<th>Delete ?</th>
					</tr>
<?php
	foreach ($reports->result() as $report):
		//$name = get_db_row( "name", "profiles", "profile_id", $report->profile_id )->name;
?>
					<?php echo '<tr>
						<td>'.$report->profile_type.'</td>
						<td>'.$report->profile_name.'</td>
						<td>'.date_create($report->date)->format($format).'</td>
						'.
						(IS_ADMIN ? '<td>'.$report->type.'</td>
						': null)
						.'<td>'.$report->description.'</td>
						<td><input type="checkbox" name="delete[]" value="'.$report->report_id.'"></td>
					</tr>'; ?> 
<?php endforeach; ?>
				</table>
				<input type="submit" name="delete_report_form_submitted" value="Delete the selected reports">
			</form>
<?php else: // no report ?>
			No report.
<?php endif;  ?>
		</div>
		<!-- /#admin_report_form -->