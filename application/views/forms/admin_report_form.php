		<div id="admin_report_form">
<?php if (userdata("is_admin")): ?>
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
							<td>'.$report->text.'</td>
							<td><input type="checkbox" name="delete[]" value="'.$report->profile_id.'"></td>

						</tr>'; ?> 
<?php endforeach; ?>
					</table>
					<input type="submit" name="delete_inbox_form_submitted" value="Delete the selected reports">
				</form>

			</fieldset>
<?php endif; // end if is admin ?>

		</div>