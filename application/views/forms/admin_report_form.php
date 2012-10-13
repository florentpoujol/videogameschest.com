		<div id="admin_report_form">
			<h1>Reports</h1>

			<!--<input type="checkbox" name="profile_type" value="developer"> Developer <br>
			<input type="checkbox" name="profile_type" value="admin"> Admins <br>
			<input type="checkbox" name="profile_type" value="both" checked="checked"> Both <br>
			<br>
			Sort by
			<input type="radio" name="sort_by" value="date asc" checked="checked">Date Asc<br>
			<input type="radio" name="sort_by" value="date desc">Date desc <br>-->
<?php
echo get_form_success($reports);
unset($reports["success"]);

if (count($reports) > 0):
	$format = "d M Y - G\hi s\s";
	if(LANGUAGE == "english")
		$format = "d M Y - g:ia s\s";
?>
			<?php echo form_open( 'admin/reports' ); ?> 
				<table>
					<tr>
						<th>Profile type</th>
						<th>Profile Name</th>
						<th>Date</th>
						<?php echo (ISADMIN ? "<th>Recipient</th>": ""); ?> 
						<th>Text</th>
						<th>Delete ?</th>
					</tr>
<?php
	foreach( $reports as $report ):
		$type = $report->profile_type;
		$name = get_db_row( $type."s", $type."_id", $report->profile_id )->name;
?>
					<?php echo '<tr>
						<td>'.$type.'</td>
						<td>'.$name.'</td>
						<td>'.date_create($report->date)->format($format).'</td>
						'.
						(ISADMIN ? '<td>'.$report->recipient.'</td>
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