<?php
$site_data = get_site_data();
$page = get_page();
$admin_page = get_admin_page();


if( !isset($form) )
	$form = array();
elseif( is_object($form) )
	$form = get_object_vars($form);

$form_items = array( 'id', 'name', 'email', 'password', 'password2' );


// all variables for the form should have been passed to the view (when editiing an account)

// initialise variable with default values (empty string or array)
// or values from the database
$form_items = array( 'id', 'name', 'email', 'password', 'password2', 'is_public', 'pitch', 'logo', 'blogfeed', 'website', 'country', 'teamsize',
'operatingsystems' => 'array', 'technologies' => 'array', 'devices' => 'array', 'stores' => 'array', 'socialnetworks' => 'json' );
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


foreach( $form_items as $item => $value ) {
	if( $value == 'array' ) {
		if( !isset($form[$item]) ) {
			$form[$item] = array();
			continue;
		}

		
		$item_names_array = json_decode( $form[$item], true ); // now a regular array that contains names
		
		if( $var == 'socialnetworks' ) {
			$socialnetworks = $item_names_array;
			//$socialnetworks['site'] = get_assoc_array( $socialnetworks['site'] );
		}
		else
			$form[$item] = $item_names_array;

		
	}
	elseif( $value == "json") {
		if( is_string( $form[$item] ) ) { // the data is a JSON array
			$item_names_array = json_decode( $form[$item], true ); // now a regular array that contains names
			
			if( $var == 'socialnetworks' ) {
				$socialnetworks = $item_names_array;
				//$socialnetworks['site'] = get_assoc_array( $socialnetworks['site'] );
			}
			else
				$form[$item] = $item_names_array;

		}
	}
	elseif( !isset($form[$value]) )
		$form[$value] = '';
}
/*

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

		}
	}
	elseif( !isset( ${$value} ) )
			${$value} = '';
}

*/


echo '<div id="developer_form">
';

// page title
if( $admin_page == 'adddeveloper' || $page == 'adddeveloper' )
	echo '<h2>Create a developer account</h2>';
elseif( $admin_page == 'editdeveloper' )
	echo '<h2>Edit a developper</h2>';
elseif( $admin_page == 'edityouraccount' )
	echo '<h2>Edit your account</h2>';


echo get_form_errors();
echo get_form_success($form);

echo form_open( 'admin/'.$admin_page );

// display account id
if( $admin_page == 'editdeveloper' )
	echo 'Id : '.$form['id'].' <input type="hidden" name="form[form[id]" value="'.$form['id'].'"> <br>';
?>
		<input type="text" name="form[name]" id="name" placeholder="(Company) name" value="<?php echo $form['name'];?>"> <label for="name">Your name (the company name, if applicable)</label> <br>
		<input type="email" name="form[email]" id="email" placeholder="Email" value="<?php echo $form['email'];?>"> <label for="email">Your email</label> <br>
<?php if( $page == 'admin' ): ?>
		<input type="password" name="form[password]" id="password" placeholder="Write here only to update" value=""> <label for="password">Password</label> <br>
		<input type="password" name="form[password2]" id="password2" placeholder="Same as above" value=""> <label for="password2">Password confirmation</label> <br>
		<br>
		Statut :
		<input type="radio" name="form[is_public]" id="statut_private" value="0" <?php if($form['is_public']=='0'||$form['is_public']=='') echo 'checked="checked"'; ?>> <label for="statut_private">Private</label>
		<input type="radio" name="form[is_public]" id="statut_public" value="1" <?php if($form['is_public']=='1') echo 'checked="checked"'; ?>> <label for="statut_public">Public</label> <br>
<?php endif; // end if( $page == 'admin' ): ?>
		<br>
		<label for="pitch">Pitch your philosophy, goals below :</label> <br>
		<textarea name="form[pitch]" id="pitch" placeholder="Pitch the company" rows="10" cols="40"><?php echo $form['pitch'];?></textarea> <br>
		<input type="url" name="form[logo]" id="logo" placeholder="Logo's URL" value="<?php echo $form['logo'];?>" > <label for="logo">Your logo's URL</label> <br>
		<br>
		<input type="url" name="form[website]" id="website" placeholder="Website's URL" value="<?php echo $form['website'];?>" > <label for="website">Your website's URL (company website or personal blog/portfolio)</label> <br>
		<input type="url" name="form[blogfeed]" id="blogfeed" placeholder="Blog RSS/Atom feed" value="<?php echo $form['blogfeed'];?>" > <label for="blogfeed">Your blog feed (RSS or Atom flux)</label> <br>
<?php
echo form_dropdown( 'form[country]', $site_data->countries, $form['country'], 'id="country"' );
?>
		<label for="country">Country</label> <br>
		<input type="number" min="1" name="form[teamsize]" id="teamsize" placeholder="Teamsize" value="<?php echo $form['teamsize']=='' ? 1: $teamsize;?>" > <label for="teamsize">The size of your team</label> <br>
		<br>
		<!-- <label for="stores">The social networks you are active on (leave url blank to delete existing ones) :</label> <br>
<?php/*
if( !array_key_exists( 'site', $socialnetworks ) ) // happend on adddeveloper
	$socialnetworks['site'] = array();

foreach( $socialnetworks['site'] as $array_id => $site_id ) {
	if( $socialnetworks['url'][$array_id] == '' )
		continue;

	echo form_dropdown( 'socialnetworks[site]['.$array_id.']', $site_data->socialnetworks, $site_id, 'id="socialnetwork_site_'.$array_id.'"' );
	echo '<input type="url" name="form[socialnetworks[url]['.$array_id.']" id="socialnetwork_url_'.$array_id.'" placehodder="Full profile url" value="'.$socialnetworks['url'][$array_id].'"> <!--<label for="socialnetwork_url_">Leave url blanck to delete</label>--> <br>';
}

$count = count( $socialnetworks['site'] );
for( $i = $count; $i < $count+3; $i++ ) {
	echo form_dropdown( 'socialnetworks[site]['.$i.']', $site_data->socialnetworks, null, 'id="socialnetwork_site_'.$i.'"' );
	echo '<input type="url" name="form[socialnetworks[url]['.$i.']" id="socialnetwork_url_'.$i.'" placehodder="Full profile url" value=""><!-- <label for="socialnetwork_url_">Leave url blanck to delete</label> --><br>';
}*/
?>
		If you want to add more than 3 networks, add 3 now, save, then you will be able to add 3 more. <br>
		<br>-->
		<label for="technologies">The technologies/engines you are developing your games with :</label> <br>
<?php
//var_dump($engines);
echo form_multiselect( 'form[technologies][]', $site_data->technologies, $form['technologies'], 'id="technologies" size="10"' );
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
	echo '<input type="hidden" name="form[id]" value="'.$id.'">';

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

echo '<input type="submit" name="form['.$name.'" value="'.$value.'">';
?>
	</form>
	<br> <br> <br>
</div> <!-- /#developer_form -->