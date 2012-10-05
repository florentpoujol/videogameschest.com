<div id="message_form">
<?php
$is_admin = userdata( 'is_admin' );

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
if( $is_admin )
	echo form_dropdown( 'form[recipient_id]', get_developers(), null, 'id="recipient_id"' );
else
	echo '<input type="hidden" name="form[recipient_id]" value="admins">The message will be sent to the administrators';
?>
			<br>
			<input type="submit" name="write_message_form_submitted" value="Send this message">
		</form>
	</fieldset>

	<fieldset>
		<legend>Message inbox</legend>
		
	</fieldset>

	<fieldset>
		<legend>Message sent</legend>
		
	</fieldset>		
</div> <!-- /#message_form -->