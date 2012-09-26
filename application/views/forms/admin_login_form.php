<div id="admin_login">
<?php if( $error != '' )
	echo '<p class="error">'.$error.'</p>'; ?>

<?php echo form_open( 'admin/login' ); ?>
		<label for="name">Name</label> <br>
		<input type="text" name="name" placeholder="your developer name" value="<?php echo $name; ?>"/> <br>
		<br>
		<label for="password">Password</label> <br>
		<input type="password" name="password" placeholder="your password" /> <br>
		<br>
		<input type="submit" name="admin_login_form_submitted" value="Log in" /> <br>
		<input type="submit" name="admin_login_form_lostpassword" value="I lost my password" />
	</form>
</div>