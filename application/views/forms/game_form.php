<?php
$id = 0;
$developer_id = 0;
$statut = 'private';
$name = '';

if( isset( $account_data ) ) {
	$id = $account_data->id;
	$developer_id = $account_data->developer_id;
	$statut = $account_data->statut;
	$name = $account_data->name;
}


$fields = array( 'pitch', 'logo', 'devname', 'devid', 'publishername',
'blogfeed', 'website', 'soundtrack', 'country', 'price',
'screenshots', 'videos', 'players', 'languages', 'tags',
'operatingsystems', 'engines', 'devices', 'stores', 'socialnetworks' );


foreach( $fields as $field )
	${$field} = '';

if( isset( $dev_data ) ) {
	foreach( $fields as $field )
		${$field} = $dev_data->$field;
}

?>
<div id="game_form">
<?php 
	echo form_open( 'admin/'.get_admin_page() );
?>
		<fieldset>
			<legend>Account</legend>

			<input type="text" name="name" id="name" placeholder="Name" value="<?php echo $name;?>" > <label for="name">Name</label> <br>
<?php
if( userdata( 'isadmin' ) ) { // allow to change developer
	$raw_devs = get_rows( 'users', 'statut', 'public' );
	$devs = array();
	foreach( $raw_devs->result() as $dev ) {
		$devs[$dev->id] = $dev->name;
	}

	echo form_dropdown( 'developer_id', $devs, $developer_id, 'id="developer_id"' );
}
?>
		</fieldset>

		<fieldset>
			<legend>Data</legend>

			<label for="pitch">Pitch the game below :</label> <br>
			<textarea name="pitch" id="pitch" placeholder="Pitch the game" rows="10" cols="40"><?php echo $pitch;?></textarea> <br>
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
			<input type="number" min="1" name="price" id="price" placeholder="Price" value="<?php echo $price == '' ? 1: $teamsize;?>" > <label for="teamsize">The size of your team</label> <br>
			<br>
			<label for="stores">The social websites you are active on :</label> <br>
<?php
if( is_array( $socialnetworks ) ) {
	foreach( $socialnetworks as $site => $url ) {
		$site = array_search( $site, $site_data->socialnetworks );
		echo form_dropdown( 'socialnetwork_name_'.$site, $site_data->socialnetworks, $site, 'id="socialnetwork_name_'.$site.'"' );
		echo '<input type="url" name="socialnetwork_url_'.$site.'" id="socialnetwork_url_'.$site.'" placehodder="Full profile url" value="'.$url.'"> <label for="socialnetwork_url_'.$site.'">Leave url blanck to delete</label> <br>';
	}
}

for( $i=1; $i<=3; $i++ ) {
	echo form_dropdown( 'newsocialnetwork_name_'.$i, $site_data->socialnetworks, null, 'id="newsocialnetwork_name_'.$i.'"' );
	echo '<input type="url" name="newsocialnetwork_url_'.$i.'" id="newsocialnetwork_url_'.$i.'" placehodder="Full profile url"> <label for="newsocialnetwork_url_'.$i.'">New social network '.$i.'</label> <br>';
}
?>
			<br>
			<label for="engines">The engines/technologies you are developping your games with :</label> <br>
<?php
if( is_array( $engines ) ) {
	for( $i = 0; $i < count( $engines ); $i++ )
		$engines[$i] = array_search( $engines[$i], $site_data->engines );
}

echo form_multiselect( 'engines', $site_data->engines, $engines, 'id="engines" size="10"' );
?>		
			<br>
			<br>
			<label for="operatingsystems">The operating systems your games are available on :</label> <br>
<?php
if( is_array( $operatingsystems ) ) {
	for( $i = 0; $i < count( $operatingsystems ); $i++ )
		$operatingsystems[$i] = array_search( $operatingsystems[$i], $site_data->operatingsystems );
}

echo form_multiselect( 'operatingsystems', $site_data->operatingsystems, $operatingsystems, 'id="operatingsystems" size="7"' );
?>
			<br>
			<br>
			<label for="devices">The devices your games are playable on :</label> <br>
<?php
if( is_array( $devices ) ) {
	for( $i = 0; $i < count( $devices ); $i++ )
		$devices[$i] = array_search( $devices[$i], $site_data->devices );
}

echo form_multiselect( 'devices', $site_data->devices, $devices, 'id="devices" size="10"' );
?>		
			<br>
			<br>
			<label for="stores">The stores you sells your games on :</label> <br>
<?php
if( is_array( $stores ) ) {
	for( $i = 0; $i < count( $stores ); $i++ )
		$stores[$i] = array_search( $stores[$i], $site_data->stores );
}

echo form_multiselect( 'stores', $site_data->stores, $stores, 'id="stores" size="10"' );
?>		

		</fieldset>

		<input type="hidden" name="id" value="<?php echo $id;?>">
		<input type="submit" name="<?php echo get_admin_page().'_form_submit'; ?>" value="Validate">
	</form>
</div>

name : string
statut : string
pitch : string
logo : string (url)
devname : (nom du dev sur le même site)
devid : int (id dev sur le site)
publishername : (string)

blogfeed : string (url)
soudtrack : string (url)
website : string (url)
contry : string
price : int

screenshots : array (string) (urls)
videos : array (string) (urls)
players : array (string)
operatingsys : array (string)
platforms : array (string)
engines : array (string)
languages : array (string)
tags : array (string)

socialnetworks : assoc array (string, string) (name, url)