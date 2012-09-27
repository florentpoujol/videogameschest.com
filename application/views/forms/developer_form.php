<?php
$site_data = get_site_data();
$page = get_page();
$admin_page = get_admin_page();

// all variables for the form should have been passed to the view (when editiing an account)

// initialise variable with default values (empty string or array)
// or values from the database
$variables = array( 'id', 'name', 'email', 'is_admin', 'statut', 'pitch', 'logo', 'blogfeed', 'website', 'country', 'teamsize',
'operatingsystems' => 'array', 'engines' => 'array', 'devices' => 'array', 'stores' => 'array', 'socialnetworks' => 'array' );
/* 
about the array values :
when the data they contain comes from the database, it is a JSON array
	the PHP array returned by json_decode then contain the name of the items
but when the data comes from the form itself, it is already an array that contains the id of the items

so the arrays like $operatingsystems, $engines, $devices and $stores are regular arrays that contains ids of the selected items

about $socialnetworks :
the form return it as an associative array containing two keys 'site' and 'url' which are arrays
the site array contain the name of the site, we just need to replace them by their id, like for other arrays
*/


foreach( $variables as $var => $value ) {
	if( $value == 'array' ) {
		if( !isset( ${$var} ) || ${$var} == null ) {
			${$var} = array();
			continue;
		}

		if( is_string( ${$var} ) ) { // the data is a JSON array
			$item_names_array = json_decode( ${$var}, true ); // now a regular array that contains names
			
			if( $var == 'socialnetworks' ) {
				$socialnetworks = $item_names_array;
				//$socialnetworks['site'] = get_assoc_array( $socialnetworks['site'] );
			}
			else
				${$var} = $item_names_array;
			/*else {
				${$var} = array();
				foreach( $item_names_array as $item_name )
					${$var}[] = array_search( $item_name, $site_data->$var );
			}*/
		}
	}
	elseif( !isset( ${$value} ) )
			${$value} = '';
}
?>
<div id="developer_form">
<?php


// page title
if( $admin_page == 'adddeveloper' || $page == 'adddeveloper' )
	echo '<h2>Create a developer account</h2>';
elseif( $admin_page == 'editdeveloper' )
	echo '<h2>Edit a developper</h2>';
elseif( $admin_page == 'edityouraccount' )
	echo '<h2>Edit your account</h2>';

if( isset( $errors ) )
	echo display_errors( $errors );

// opening form tag
echo form_open( 'admin/'.$admin_page );

// display account id
if( $admin_page == 'editdeveloper' || $admin_page == 'edityouraccount' )
	echo 'Id : '.$id.' <br>';
?>
		<input type="text" name="name" id="name" placeholder="(Company) name" value="<?php echo $name;?>"> <label for="name">Your name (the company name, if applicable)</label> <br>
		<input type="email" name="email" id="email" placeholder="Email" value="<?php echo $email;?>"> <label for="email">Your email</label> <br>
<?php if( $page == 'admin' ): ?>
		<input type="password" name="password" id="password" placeholder="Write here only to update" > <label for="password">Password</label> <br>
		<input type="password" name="password2" id="password2" placeholder="Same as above" > <label for="password2">Password, again</label> <br>

<?php 
//if( $is_admin != '1' ) { // draw the rest of the form only if the account is a developer
?>
		<br>
		Statut :
<?php 
if( $statut == '' )
	$statut = 'private';
?>
		<input type="radio" name="statut" id="statut_private" value="private" <?php if($statut=='private') echo 'checked="checked"'; ?>> <label for="statut_private">Private</label>
		<input type="radio" name="statut" id="statut_public" value="public" <?php if($statut=='public') echo 'checked="checked"'; ?>> <label for="statut_public">Public</label> <br>
<?php endif; // end if( $page == 'admin' ): ?>
		<br>
		<label for="pitch">Pitch your philosophy, goals below :</label> <br>
		<textarea name="pitch" id="pitch" placeholder="Pitch the company" rows="10" cols="40"><?php echo $pitch;?></textarea> <br>
		<input type="url" name="logo" id="logo" placeholder="Logo's URL" value="<?php echo $logo;?>" > <label for="logo">Your logo's URL</label> <br>
		<br>
		<input type="url" name="website" id="website" placeholder="Website's URL" value="<?php echo $website;?>" > <label for="website">Your website's URL (company website or personal blog/portfolio)</label> <br>
		<input type="url" name="blogfeed" id="blogfeed" placeholder="Blog RSS/Atom feed" value="<?php echo $blogfeed;?>" > <label for="blogfeed">Your blog feed (RSS or Atom flux)</label> <br>
<?php
//if( !is_numeric( $country ) )
//	$country = array_search( $country, $site_data->countries );

echo form_dropdown( 'country', $site_data->countries, $country, 'id="country"' );
?>
		<label for="country">Country</label> <br>
		<input type="number" min="1" name="teamsize" id="teamsize" placeholder="Teamsize" value="<?php echo $teamsize == '' ? 1: $teamsize;?>" > <label for="teamsize">The size of your team</label> <br>
		<br>
		<label for="stores">The social networks you are active on (leave url blank to delete existing ones) :</label> <br>
<?php
if( !array_key_exists( 'site', $socialnetworks ) ) // happend on adddeveloper
	$socialnetworks['site'] = array();

foreach( $socialnetworks['site'] as $array_id => $site_id ) {
	if( $socialnetworks['url'][$array_id] == '' )
		continue;

	echo form_dropdown( 'socialnetworks[site]['.$array_id.']', $site_data->socialnetworks, $site_id, 'id="socialnetwork_site_'.$array_id.'"' );
	echo '<input type="url" name="socialnetworks[url]['.$array_id.']" id="socialnetwork_url_'.$array_id.'" placehodder="Full profile url" value="'.$socialnetworks['url'][$array_id].'"> <!--<label for="socialnetwork_url_">Leave url blanck to delete</label>--> <br>';
}

$count = count( $socialnetworks['site'] );
for( $i = $count; $i < $count+3; $i++ ) {
	echo form_dropdown( 'socialnetworks[site]['.$i.']', $site_data->socialnetworks, null, 'id="socialnetwork_site_'.$i.'"' );
	echo '<input type="url" name="socialnetworks[url]['.$i.']" id="socialnetwork_url_'.$i.'" placehodder="Full profile url" value=""><!-- <label for="socialnetwork_url_">Leave url blanck to delete</label> --><br>';
}
?>
		If you want to add more than 3 networks, add 3 now, save, then you will be able to add 3 more. <br>
		<br>
		<label for="engines">The engines/technologies you are developing your games with :</label> <br>
<?php
//var_dump($engines);
echo form_multiselect( 'engines[]', $site_data->engines, $engines, 'id="engines" size="10"' );
?>		
		<br>
		<br>
		<label for="operatingsystems">The operating systems your games are available on (not applicable for most consoles) :</label> <br>
<?php
echo form_multiselect( 'operatingsystems[]', $site_data->operatingsystems, $operatingsystems, 'id="operatingsystems" size="7"' );
?>
		<br>
		<br>
		<label for="devices">The devices your games are playable on :</label> <br>
<?php
echo form_multiselect( 'devices[]', $site_data->devices, $devices, 'id="devices" size="10"' );
?>		
		<br>
		<br>
		<label for="stores">The stores you sells your games on :</label> <br>
<?php
echo form_multiselect( 'stores[]', $site_data->stores, $stores, 'id="stores" size="10"' );
?>		
		<br> <br>
<?php
 // end the if( $is_admin != 1 ):

if( $id != '' )
	echo '<input type="hidden" name="id" value="'.$id.'">';

$name = $admin_page.'_form_submitted';
$name = 'developer_form_submitted';
$value = '';

if( $page == 'adddeveloper' || $admin_page == 'adddeveloper' ) {
	$value = 'Create this developer account';
}
elseif( $admin_page == 'editdeveloper' )
	$value = 'Edit this developer account';
elseif( $admin_page == 'edityouraccount' )
	$value = 'Edit your account';

echo '<input type="submit" name="'.$name.'" value="'.$value.'">';
?>
	</form>
	<br> <br> <br>
</div>