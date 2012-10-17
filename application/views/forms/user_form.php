<?php
// form to edit/add a user form
if (!isset($form))
	$form = array();
elseif( is_object($form) )
	$form = get_object_vars($form);

$form_items = array("user_id", "name", "email", "password", "password2", "key", "creation_date", "type");

foreach ($form_items as $item) {
	if( ! isset($form[$item]) )
		$form[$item] = "";
}
?>
		<div id="user_form">
		<?php if (METHOD == 'edituser'): ?>
			<h1>Edit your user account</h1>
		<?php else: ?>
			<h1>Create user account</h1>
		<?php endif; ?>

			<!-- forms errors and success -->
			<?php echo get_form_errors($form); ?> 
			<?php echo get_form_success($form); ?> 
			<!-- /forms errors and success -->

			<?php echo form_open("admin/".METHOD); ?>
			<?php if (METHOD == 'edituser'): ?>
				<?php echo '<input type="hidden" name="form[creation_date]" value="'.$form['creation_date'].'">Your account creation date : '.$form["creation_date"]; ?> <br>
				<?php echo '<input type="hidden" name="form[user_id]" value="'.$form['user_id'].'"> Your user Id : '.$form["user_id"]; ?> <br>
				<?php echo '<input type="hidden" name="form[key]" value="'.$form['key'].'"> Your user key : '.$form["key"]; ?> <br>
				<?php echo '<input type="hidden" name="form[type]" value="'.$form['type'].'">Your have a '.$form["type"].' account.'; ?> <br>
			<?php endif; ?>
				<?php if (IS_ADMIN): ?>
				<input type="text" name="form[type]" id="account_type" placeholder="dev or admin" value="<?php echo $form['type']; ?>"> <label for="account_type">Account type</label> <br>
				<?php endif; ?>
				<input type="text" name="form[name]" id="name" placeholder="Name" value="<?php echo $form['name'];?>"> <label for="name">Name</label> <br>
				<input type="email" name="form[email]" id="email" placeholder="Email" value="<?php echo $form['email'];?>"> <label for="email">Email</label> <br>
				<input type="password" name="form[password]" id="password" placeholder="Write here only to update" value=""> <label for="password">Password</label> <br>
				<input type="password" name="form[password2]" id="password2" placeholder="Same as above" > <label for="password2">Password confirmation</label> <br>
			<?php if (METHOD == 'edituser'): ?>	
				<input type="password" name="form[oldpassword]" id="oldpassword" placeholder="Your old password" > <label for="oldpassword">To update your password, enter your old password here</label> <br>
			<?php endif; ?>
				<input type="submit" name="user_form_submitted" value="Submit">
			</form>
		</div>
		<!-- /#user_form --> 