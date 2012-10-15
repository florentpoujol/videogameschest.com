<?php
// form to edit a user form
if (!isset($form))
	$form = array();
elseif( is_object($form) )
	$form = get_object_vars($form);

$form_items = array("user_id", "name", "email", "password", "password2", "key");

foreach ($form_items as $item) {
	if( ! isset($form[$item]) )
		$form[$item] = "";
}
?>
		<div id="user_form">
			<h1>Edit your user account</h1>

			<!-- forms errors and success -->
			<?php echo get_form_errors($form); ?> 
			<?php echo get_form_success($form); ?> 
			<!-- /forms errors and success -->

			<?php echo form_open("admin/edituser"); ?>
				<?php echo '<input type="hidden" name="form[user_id]" value="'.$form['user_id'].'"> Your user Id : '.$form["user_id"]; ?> <br>
				<?php echo 'Your user key : '.$form["key"]; ?> <br>
				<input type="text" name="form[name]" id="name" placeholder="Name" value="<?php echo $form['name'];?>"> <label for="name">Name</label> <br>
				<input type="email" name="form[email]" id="email" placeholder="Email" value="<?php echo $form['email'];?>"> <label for="email">Email</label> <br>
				<input type="password" name="form[password]" id="password" placeholder="Write here only to update" value=""> <label for="password">Password</label> <br>
				<input type="password" name="form[password2]" id="password2" placeholder="Same as above" > <label for="password2">Password confirmation</label> <br>
				<input type="submit" name="user_form_submitted" value="Submit">
			</form>
		</div>
		<!-- /#user_form --> 