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
			<?php echo form_open("admin/".METHOD, array("class"=>"form-horizontal")); ?> 
<?php if (METHOD == 'edituser'): ?>
				<legend>Edit your user account</legend>
<?php else: ?>
				<legend>Create user account</legend>
<?php endif; ?>
			
<?php if (METHOD == 'edituser' && $form["type"] == "dev"): ?>
				<p>Your "developer" user account is a different entity than your developer profile, while both are created at the same time, with the same name.</p>
<?php endif; ?>

				<!-- forms errors and success -->
				<?php echo get_form_errors(); ?> 
				<?php echo get_form_success(); ?> 
				<!-- /forms errors and success -->

<?php if (METHOD == 'edituser'): ?>
				<div class="control-group">
					<label class="control-label">Account creation date</label>
					<input type="date" class="controls" readonly="true" name="form[creation_date]" value="<?php echo $form["creation_date"]; ?>">
				</div>

				<div class="control-group">
					<label class="control-label">Account/User Id</label>
					<input type="number" class="controls" readonly="true" name="form[id]" value="<?php echo $form["id"]; ?>">
					<span class="help-inline">Keep in mind that it's not the same as your developer profile id.</span>
				</div>

				<div class="control-group">
					<label class="control-label">Account key</label>
					<input class="controls input-xlarge" readonly="true" type="text" name="form[key]" value="<?php echo $form["key"]; ?>">
					<span class="help-inline">It's a "secret" key used to retreive "secret" data as your message's RSS feed.</span>
				</div>

<?php endif; ?>
<?php if (IS_ADMIN): ?>
				<div class="control-group">
					<label class="control-label" for="account_type">Account type</label>
					<input class="controls" type="text" name="form[type]" id="account_type" placeholder="dev or admin" value="<?php echo $form['type']; ?>">
					<span class="help-inline">"dev" or "admin"</span> 
				</div>
<?php endif; ?>
				<div class="control-group">
					<label class="control-label" for="name">Name</label>
					<input class="controls" type="text" name="form[name]" id="name" placeholder="Name" value="<?php echo $form['name'];?>">
				</div>

				<div class="control-group">
					<label class="control-label" for="email">Email</label>
					<input class="controls" type="email" name="form[email]" id="email" placeholder="Email" value="<?php echo $form['email'];?>">
				</div>

				<div class="control-group">
					<label class="control-label" for="password">Password</label>
					<input class="controls" type="password" name="form[password]" id="password" placeholder="Write here only to update" value="">
				</div>

				<div class="control-group">
					<label class="control-label" for="password2">Password confirmation</label>
					<input class="controls" type="password" name="form[password2]" id="password2" placeholder="Same as above" >
				</div>
<?php if (METHOD == 'edituser'): ?>
				<div class="control-group">
					<label class="control-label" for="oldpassword">Old password</label>
					<input class="controls" type="password" name="form[oldpassword]" id="oldpassword" placeholder="Your old password" >
					<span class="help-inine">In order to update your password, enter your old password here.</span>
				</div>
<?php endif; ?>
				<input type="submit" name="user_form_submitted" value="Submit">
			</form>
		</div>
		<!-- /#user_form --> 