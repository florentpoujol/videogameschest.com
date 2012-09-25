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
			<textarea name="pitch" id="pitch" placeholder="Explain about your company" rows="10" cols="40"><?php echo $pitch;?></textarea> <br>
			<input type="url" name="logo" id="logo" placeholder="Logo's url" value="<?php echo $logo;?>" > <label for="logo">Your logo's url</label> <br>
			<br>
			<input type="url" name="website" id="website" placeholder="Website url" value="<?php echo $website;?>" > <label for="website">Your website url</label> <br>
			<input type="url" name="blogfeed" id="blogfeed" placeholder="Blog RSS/Atom feed" value="<?php echo $blogfeed;?>" > <label for="blogfeed">Your blog feed (RSS or Atom flux)</label> <br>
<?php
$site_data = get_site_data();

if( $country != '' )
	$country = array_search( $country, $site_data->countries );

echo form_dropdown( 'country', $site_data->countries, $country, 'id="country"' );
?>
			<label for="country">Your country</label> <br>
			<input type="number" min="1" name="teamsize" id="teamsize" placeholder="Teamsize" value="<?php echo $teamsize == '' ? 1: $teamsize;?>" > <label for="teamsize">The size of your team</label> <br>
			<br>

<?php
$operatingsystems = array( 'Mac', 'Linux');

if( is_array( $operatingsystems ) ) {
	for( $i = 0; $i < count( $operatingsystems ); $i++ )
		$operatingsystems[$i] = array_search( $operatingsystems, $site_data->operatingsystems );
}

echo form_multiselect( 'operatingsystem', $site_data->operatingsystems, $operatingsystems, 'id="operatingsystem" rows="10"' );
?>		
			<label for="operatingsystem">The operating systems your games are available on</label> <br>




		</fieldset>

		<input type="submit" name="<?php echo get_admin_page().'_form'; ?>" value="Validate">
	</form>
</div>



<br>
<br>
<br>
						blogfeed : string (url)
						website : string (url)
						contry : string
						teamsize : int

						operatingsys : array (string)
						platforms : array (string)
						engines : array (string)

						socialnetworks : assoc array (string, string) (name, url)