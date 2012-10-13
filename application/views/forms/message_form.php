		<section id="message_form">
<?php
if(!isset($form))
	$form = '';
?>
			<?php echo get_form_errors($form);
			echo get_form_success($form); ?>

			<h1 id="page_title">Message center</h1>

			<fieldset>
				<legend>Write a message</legend>

				<?php echo form_open( 'admin/messages' ); ?>

					<label for="write_message">Message text</label> <br>
					<textarea name="form[message]" id="write_message" placeholder="10 characters min" cols="30" rows="7"></textarea>
					<br>
					<label for="recipient_id">Recipient</label> 
<?php
if( userdata( 'is_admin' ) ):?>
					<?php echo form_dropdown( 'form[recipient_id]', get_developers(), null, 'id="recipient_id"' ); ?> 
<?php else: ?>
					<?php echo '<input type="hidden" name="form[recipient_id]" value="admins">The message will be sent to the administrators'; ?> 
<?php endif; ?>
					<br>
					<input type="submit" name="write_message_form_submitted" value="Send this message">
				</form>
			</fieldset>

			<br>

			<fieldset>
				<legend>Inbox</legend>
	
<?php
if( userdata( 'is_admin' ) )
	$messages = $this->main_model->get_messages( array('owner_id'=>0, 'recipient_id'=>0), 'sender_id' );
else {
	$messages = $this->main_model->get_messages( array(
		'owner_id' => userdata( 'user_id' ),
		'recipient_id' => userdata( 'user_id' )
	) );
}

if( $messages->num_rows() > 0 ): 
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
	foreach( $messages->result() as $msg ):
		if( userdata( 'is_developer' ) ) {
			$name = 'admin';
		}else{
			$name = $msg->name;
		}
?>
						<?php echo '<tr>
							<td>'.$name.'</td>
							<td>'.$msg->date.'</td>
							<td>'.$msg->message.'</td>
							<td><input type="checkbox" name="delete[]" value="'.$msg->message_id.'"></td>
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
if( userdata( 'is_admin' ) )
	$messages = $this->main_model->get_messages( array('owner_id'=>0, 'sender_id'=>0), 'recipient_id' );
else {
	$messages = $this->main_model->get_messages( array(
		'owner_id' => userdata( 'user_id' ),
		'sender_id' => userdata( 'user_id' )
	) );
}

if( $messages->num_rows() > 0 ): 
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
	foreach( $messages->result() as $msg ):
		if( userdata( 'is_developer' ) ) {
			$name = 'admin';
		}else{
			$name = $msg->name;
		}
?>
						<?php echo '<tr>
							<td>'.$name.'</td>
							<td>'.$msg->date.'</td>
							<td>'.$msg->message.'</td>
							<td><input type="checkbox" name="delete[]" value="'.$msg->message_id.'"></td>
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
