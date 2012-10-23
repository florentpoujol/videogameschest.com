<?php
// form to edit/add a user form
if (!isset($form))
	$form = array();
elseif( is_object($form) )
	$form = get_object_vars($form);

$form_items = array("id", "name", "email", "password", "password2", "key", "creation_date", "type");

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

		<?php if (METHOD == 'edituser' && $form["type"] == "dev"): ?>
			<p>
				Your "developer" user account a different entity than your developer profile, while both are created at the same time, with the same name.
			</p>
		<?php endif; ?>

			<!-- forms errors and success -->
			<?php echo get_form_errors($form); ?> 
			<?php echo get_form_success($form); ?> 
			<!-- /forms errors and success -->

			<?php echo form_open("admin/".METHOD); ?>
			<?php if (METHOD == 'edituser'): ?>
				<?php echo '<input type="hidden" name="form[creation_date]" value="'.$form["creation_date"].'">Account creation date : '.$form["creation_date"]; ?> <br>
				<?php echo '<input type="hidden" name="form[id]" value="'.$form["id"].'">Account/User Id : '.$form["id"]; ?> <br>
				Keep in mind that it's not the same as your developer profile id. <br>
				<br>
				<?php echo '<input type="hidden" name="form[key]" value="'.$form["key"].'">Account key : '.$form["key"]; ?> <br>
				It's a secret key used to retreive "secret" data as your message's RSS feed. <br>
				<br>				
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