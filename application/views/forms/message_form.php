		<div id="message_form">
			<h1>Message center</h1>

			<p>
<?php
$url = site_url("feed/newmessages/".USER_ID."/".userdata("profile_key"));
?>
				Here is your <a href="<?php echo $url; ?>" title="Messages RSS feed">messages RSS feed</a>.
			</p>
<?php
if(!isset($form))
	$form = "";
?>
			<?php echo get_form_errors($form);
			echo get_form_success($form); ?>

			<fieldset>
				<legend>Write a message</legend>

				<?php echo form_open( 'admin/messages' ); ?>

					<label for="write_message">Message text</label> <br>
					<textarea name="form[message]" id="write_message" placeholder="10 characters min" cols="30" rows="7"></textarea>
					<br>
					<label for="recipient">Recipient : </label> 
<?php if (IS_ADMIN):?>
					<input type="hidden" name="form[administrator_id]" value="<?php echo USER_ID;?>">
					<?php echo form_dropdown( 'form[developer_id]', get_users_array("dev"), null, 'id="recipient"' ); ?> 
<?php else: ?>
					<input type="hidden" name="form[sent_by_developer]" value="1">
					<input type="hidden" name="form[developer_id]" value="<?php echo USER_ID;?>">
					<?php echo form_dropdown( 'form[administrator_id]', get_users_array("admin"), null, 'id="recipient"' ); ?> 
<?php endif; ?>
					<br>
					<input type="submit" name="write_message_form_submitted" value="Send this message">
				</form>
			</fieldset>

			<p>
				Deleting a message delete it from the database, it will not exists anymore for the sender nor for the recipient.
			</p>

			<fieldset>
				<legend>Inbox</legend>
	
<?php
$site_data = get_static_data('site');
$format = $site_data->date_formats->nonenglish;

if (LANGUAGE == "english")
	$format = $site_data->date_formats->english;

if ($messages["inbox"]->num_rows() > 0): 
?>
				<?php echo form_open( 'admin/messages' ); ?> 
					<table>
						<tr>
							<th>Sender name</th>
							<th>Date</th>
							<th>Message text</th>
							<th>Delete ?</th>
						</tr>
<?php
	foreach ($messages["inbox"]->result() as $msg):
?>
						<?php echo '<tr>
							<td>'.$msg->name.'</td>
							<td>'.date_create($msg->date)->format($format).'</td>
							<td>'.$msg->message.'</td>
							<td><input type="checkbox" name="delete[]" value="'.$msg->id.'"></td>
						</tr>'; ?> 
<?php endforeach; ?>
					</table>
					<input type="submit" name="delete_inbox_form_submitted" value="Delete the selected messages">
				</form>
<?php else: ?>
				Your inbox is empty.
<?php endif; ?>
			</fieldset>

			<br>

			<fieldset>
				<legend>Outbox</legend>

<?php
if ($messages["outbox"]->num_rows() > 0): 
?>
				<?php echo form_open( 'admin/messages' ); ?>
					<table>
						<tr>
							<th>Recipient name</th>
							<th>Date</th>
							<th>Message text</th>
							<th>Delete ?</th>
						</tr>
<?php
	foreach ($messages["outbox"]->result() as $msg):
?>
						<?php echo '<tr>
							<td>'.$msg->name.'</td>
							<td>'.date_create($msg->date)->format($format).'</td>
							<td>'.$msg->message.'</td>
							<td><input type="checkbox" name="delete[]" value="'.$msg->id.'"></td>
						</tr>'; ?> 
<?php endforeach; ?>
					</table>
					<input type="submit" name="delete_inbox_form_submitted" value="Delete the selected messages">
				</form>
<?php else: ?>
				Your outbox is empty.
<?php endif; ?>
			</fieldset>		
		</section> <!-- /#message_form -->
