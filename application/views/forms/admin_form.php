<?php
// form to edit an admin account

if( !isset($form) )
	$form = array();
elseif( is_object($form) )
	$form = get_object_vars($form);

$form_items = array( 'id', 'name', 'email', 'password', 'password2' );

foreach( $form_items as $item ) {
	if( !isset($form[$item]) )
		$form[$item] = '';
}


echo '<div id="admin_form">
';

echo get_form_errors();
echo get_form_success($form);

echo form_open( 'admin/editadmin' );

echo 'Id : '.$form['id'].'
<input type="hidden" name="form[id]" value="'.$form['id'].'"><br>';
?>
		<input type="text" name="form[name]" id="name" placeholder="Name" value="<?php echo $form['name'];?>"> <label for="name">Name</label> <br>
		<input type="email" name="form[email]" id="email" placeholder="Email" value="<?php echo $form['email'];?>"> <label for="email">Email</label> <br>
		<input type="password" name="form[password]" id="password" placeholder="Write here only to update" value=""> <label for="password">Password</label> <br>
		<input type="password" name="form[password2]" id="password2" placeholder="Same as above" > <label for="password2">Password confirmation</label> <br>
		<input type="submit" name="admin_form_submitted" value="Submit">
	</form>
</div> <!-- /#admin_form -->