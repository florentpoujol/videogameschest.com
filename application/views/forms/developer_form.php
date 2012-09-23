<?php
$name = '';
$email = '';
$statut = '';

if( isset( $account_data ) ) {
	$name = $account_data->name;
	$email = $account_data->email;
	$statut = $account_data->statut;
}


$fields = array( 'pitch', 'logo', 'blogfeed', 'website', 'country', 'teamsize',
'operatingsystems', 'engines', 'portals', 'socialnetworks' );

foreach( $fields as $field )
	${$field} = '';

if( isset( $dev_data ) ) {
	foreach( $fields as $field )
		${$field} = $dev_data->$field;
}

?>
<div id="developer_form">
<?php 
	echo form_open('admin/adddeveloper');
?>
		<fieldset>
			<legend>Account</legend>

			<input type="text" name="name" id="name" placeholder="Your name" value="<?php echo $name;?>" > <label for="name">Name</label> <br>
			<input type="password" name="password" id="password" placeholder="Write only to update" > <label for="password">Password</label> <br>
			<input type="email" name="email" id="email" placeholder="Your email" value="<?php echo $email;?>" > <label for="email">Email</label> <br>

			Statut :
			<input type="radio" name="statut" id="statut_private" value="private" <?php if($statut=='private') echo 'checked="checked"'; ?>> <label for="statut_private">Private</label>
			<input type="radio" name="statut" id="statut_public" value="public" <?php if($statut=='public') echo 'checked="checked"'; ?>> <label for="statut_public">Public</label> <br>
			
		</fieldset>

		<fieldset>
			<legend>Data</legend>

			<label for="pitch">Explain about your company below :</label> <br>
			<textarea name="pitch" id="pitch" placeholder="Explain about you"><?php echo $pitch;?></textarea> <br>
			<input type="url" name="logo" id="logo" placeholder="Logo's url" value="<?php echo $logo;?>" > <label for="logo">Your company logo's url</label> <br>

			
		</fieldset>

		<input type="submit" name="<?php echo get_admin_page().'_form'; ?>" value="Validate">
	</form>
</div>
