		<div id="admin_report_form">
			<h1>Reports</h1>

<?php if (IS_ADMIN): ?>
			<input type="checkbox" name="profile_type" value="developer"> Developer <br>
			<input type="checkbox" name="profile_type" value="admin"> Admins <br>
			<input type="checkbox" name="profile_type" value="both" checked="checked"> Both <br>
			<br>
			Sort by
			<input type="radio" name="sort_by" value="date asc" checked="checked">Date Asc<br>
			<input type="radio" name="sort_by" value="date desc">Date desc <br>




<?php if (IS_ADMIN): ?>

			<fieldset>
				<legend>Critical reports</legend>
	
<?php


//if( $messages->num_rows() > 0 ): 
?>
				<?php echo form_open( 'admin/reports' ); ?> 
					<table>
						<tr>
							<th>Profile type</th>
							<th>Profile Id</th>
							<th>Date</th>
							<th>Recipient</th>
							<th>Text</th>
							<th>Delete ?</th>
						</tr>
<?php

	foreach( $reports as $report ):
		
?>
						<?php echo '<tr>
							<td>'.$report->profile_type.'</td>
							<td>'.$report->profile_id.'</td>
							<td>'.$report->date.'</td>
							<td>'.$report->recipient.'</td>
							<td>'.$report->description.'</td>
							<td><input type="checkbox" name="delete[]" value="'.$report->profile_id.'"></td>

						</tr>'; ?> 
<?php endforeach; ?>
					</table>
					<input type="submit" name="delete_inbox_form_submitted" value="Delete the selected reports">
				</form>

			</fieldset>
<?php endif; // end if is admin ?>

		</div>