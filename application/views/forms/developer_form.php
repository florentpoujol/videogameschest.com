<?php

$fields = array( 'id', 'name', 'email', 'is_admin', 'statut', 'pitch', 'logo', 'blogfeed', 'website', 'country', 'teamsize',
'operatingsystems' => 'array', 'engines' => 'array', 'devices' => 'array', 'stores' => 'array', 'socialnetworks' => 'array' );

// initialise variable with default values (empty string or array)
foreach( $fields as $field => $value ) {
	if( $value == 'array' ) {
		if( $developer_data != false && $developer_data->$field != '' )
			${$field} = json_decode( $developer_data->$field );
		else
			${$field} = array();
	}
	else {
		if( $developer_data != false )
			${$value} = $developer_data->$value;
		else
			${$value} = '';
	}
}

$site_data = get_site_data();

?>


<div id="developer_form">

	<h2>Edit your developer account</h2>

<?php 
	echo form_open( 'admin/'.get_admin_page() );
?>
		Id : <?php echo $id;?> <br>
		<input type="text" name="name" id="name" placeholder="Your name" value="<?php echo $name;?>" > <label for="name">Name</label> <br>
		<input type="email" name="email" id="email" placeholder="Your email" value="<?php echo $email;?>" > <label for="email">Email</label> <br>
		<input type="password" name="password" id="password" placeholder="Write only to update" > <label for="password">Password</label> <br>
		<input type="password" name="password2" id="password2" placeholder="Write the same thing as above" > <label for="password2">Password, again</label> <br>
<?php if( $is_admin == 0 ): ?>
		<br>Statut :
<?php if( $statut == '' ) $statut = 'private'; ?>
		<input type="radio" name="statut" id="statut_private" value="private" <?php if($statut=='private') echo 'checked="checked"'; ?>> <label for="statut_private">Private</label>
		<input type="radio" name="statut" id="statut_public" value="public" <?php if($statut=='public') echo 'checked="checked"'; ?>> <label for="statut_public">Public</label> <br>
<?php endif; ?>
		<br>
		<label for="pitch">Explain about your company below :</label> <br>
		<textarea name="pitch" id="pitch" placeholder="Explain about your company" rows="10" cols="40"><?php echo $pitch;?></textarea> <br>
		<input type="url" name="logo" id="logo" placeholder="Logo's url" value="<?php echo $logo;?>" > <label for="logo">Your logo's url</label> <br>
		<br>
		<input type="url" name="website" id="website" placeholder="Website url" value="<?php echo $website;?>" > <label for="website">Your website url</label> <br>
		<input type="url" name="blogfeed" id="blogfeed" placeholder="Blog RSS/Atom feed" value="<?php echo $blogfeed;?>" > <label for="blogfeed">Your blog feed (RSS or Atom flux)</label> <br>
<?php
if( $country != '' )
	$country = array_search( $country, $site_data->countries );

echo form_dropdown( 'country', $site_data->countries, $country, 'id="country"' );
?>
		<label for="country">Your country</label> <br>
		<input type="number" min="1" name="teamsize" id="teamsize" placeholder="Teamsize" value="<?php echo $teamsize == '' ? 1: $teamsize;?>" > <label for="teamsize">The size of your team</label> <br>
		<br>
		<label for="stores">The social websites you are active on :</label> <br>
<?php
foreach( $socialnetworks as $site => $url ) {
	$site = array_search( $site, $site_data->socialnetworks );
	echo form_dropdown( 'socialnetwork_name_'.$site, $site_data->socialnetworks, $site, 'id="socialnetwork_name_'.$site.'"' );
	echo '<input type="url" name="socialnetwork_url_'.$site.'" id="socialnetwork_url_'.$site.'" placehodder="Full profile url" value="'.$url.'"> <label for="socialnetwork_url_'.$site.'">Leave url blanck to delete</label> <br>';
}

for( $i=1; $i<=3; $i++ ) {
	echo form_dropdown( 'newsocialnetwork_name_'.$i, $site_data->socialnetworks, null, 'id="newsocialnetwork_name_'.$i.'"' );
	echo '<input type="url" name="newsocialnetwork_url_'.$i.'" id="newsocialnetwork_url_'.$i.'" placehodder="Full profile url"> <label for="newsocialnetwork_url_'.$i.'">New social network '.$i.'</label> <br>';
}
?>
		If you want to add more than 3 networks, add 3 now, save, then you will be able to add 3 more. <br>
		<br>
		<label for="engines">The engines/technologies you are developping your games with :</label> <br>
<?php
for( $i = 0; $i < count( $engines ); $i++ ) {
	$engines[$i] = array_search( $engines[$i], $site_data->engines );
	
	if( $engines[$i] === false )
		$engines[$i] = null;
}

echo form_multiselect( 'engines', $site_data->engines, $engines, 'id="engines" size="10"' );
?>		
		<br>
		<br>
		<label for="operatingsystems">The operating systems your games are available on :</label> <br>
<?php
for( $i = 0; $i < count( $operatingsystems ); $i++ ) {
	$operatingsystems[$i] = array_search( $operatingsystems[$i], $site_data->operatingsystems );

	if( $operatingsystems[$i] === false )
		$operatingsystems[$i] = null;
}

echo form_multiselect( 'operatingsystems', $site_data->operatingsystems, $operatingsystems, 'id="operatingsystems" size="7"' );
?>
		<br>
		<br>
		<label for="devices">The devices your games are playable on :</label> <br>
<?php
for( $i = 0; $i < count( $devices ); $i++ ) {
	$devices[$i] = array_search( $devices[$i], $site_data->devices );

	if( $devices[$i] === false )
		$devices[$i] = null;
}

echo form_multiselect( 'devices', $site_data->devices, $devices, 'id="devices" size="10"' );
?>		
		<br>
		<br>
		<label for="stores">The stores you sells your games on :</label> <br>
<?php
for( $i = 0; $i < count( $stores ); $i++ ) {
	$stores[$i] = array_search( $stores[$i], $site_data->stores );

	if( $stores[$i] === false )
		$stores[$i] = null;
}

echo form_multiselect( 'stores', $site_data->stores, $stores, 'id="stores" size="10"' );
?>		
		<br> <br>
		<input type="hidden" name="id" value="<?php echo $id;?>">
		<input type="submit" name="<?php echo get_admin_page().'_form_submitted'; ?>" value="Validate"> <br> <br> <br>
	</form>
</div>