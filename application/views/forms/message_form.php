<div id="message_form">
<?php
if(!isset($form))
	$form = '';

echo get_form_errors($form);
echo get_form_success($form);
?>
	<h2>Message center</h2>

	<fieldset>
		<legend>Write a message</legend>

<?php
echo form_open( 'admin/messages' );
?>
		<label for="write_message">Message text</label> <br>
		<textarea name="form[message]" id="write_message" placeholder="10 characters min" cols="30" rows="7"></textarea>
		<br>
		<label for="recipient_id">Recipient</label> 

<?php
if( userdata( 'is_admin' ) )
	echo form_dropdown( 'form[recipient_id]', get_developers(), null, 'id="recipient_id"' );
else
	echo '<input type="hidden" name="form[recipient_id]" value="admins">The message will be sent to the administrators';
?>
			<br>
			<input type="submit" name="write_message_form_submitted" value="Send this message">
		</form>
	</fieldset>
	<br>
	<fieldset>
		<legend>Message inbox</legend>

<?php
echo form_open( 'admin/messages' );

if( userdata( 'is_admin' ) )
	$messages = $this->main_model->get_messages( array('owner_id'=>0, 'recipient_id'=>0), 'sender_id' );
else {
	$messages = $this->main_model->get_messages( array(
		'owner_id' => userdata( 'user_id' ),
		'recipient_id' => userdata( 'user_id' )
	) );
}

if( $messages->num_rows() > 0 ) {
	$table = '
		<table>
			<tr>
				<th>Sender name</th>
				<th>Date</th>
				<th>Message text</th>
				<th>Delete ?</th>
			</tr>';

	foreach( $messages->result() as $msg ) {
		if( userdata( 'is_developer' ) )
			$name = 'admin';
		else
			$name = $msg->name;

		$table .= '
			<tr>
				<td>'.$name.'</td>
				<td>'.$msg->date.'</td>
				<td>'.$msg->message.'</td>
				<td><input type="checkbox" name="delete[]" value="'.$msg->msg_id.'"></td>
			</tr>';
	}

	$table .= '
		</table>
		<input type="submit" name="delete_inbox_form_submitted" value="Delete the selected messages">';

		echo $table;
}
else
	echo 'Your inbox is empty.';
?>

		</form>
	</fieldset>
	<br>
	<fieldset>
		<legend>Message sent</legend>

<?php
echo form_open( 'admin/messages' );

if( userdata( 'is_admin' ) )
	$messages = $this->main_model->get_messages( array('owner_id'=>0, 'sender_id'=>0), 'recipient_id' );
else {
	$messages = $this->main_model->get_messages( array(
		'owner_id' => userdata( 'user_id' ),
		'sender_id' => userdata( 'user_id' )
	) );
}


if( $messages->num_rows() > 0 ) {
	$table = '
		<table>
			<tr>
				<th>Recipient name</th>
				<th>Date</th>
				<th>Message text</th>
				<th>Delete ?</th>
			</tr>';

	foreach( $messages->result() as $msg ) {
		if( userdata( 'is_developer' ) )
			$name = 'admin';
		else
			$name = $msg->name;

		$table .= '
			<tr>
				<td>'.$name.'</td>
				<td>'.$msg->date.'</td>
				<td>'.$msg->message.'</td>
				<td><input type="checkbox" name="delete[]" value="'.$msg->msg_id.'"></td>
			</tr>';
	}

	$table .= '
		</table>
		<input type="submit" name="delete_outbox_form_submitted" value="Delete the selected messages">';

		echo $table;
}
else
	echo 'You didn\'t sent any messages';
?>
		</form>
	</fieldset>		
</div> <!-- /#message_form -->