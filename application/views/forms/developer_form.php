<?php
$site_data = get_site_data();
$page = get_page();
$admin_page = get_admin_page();


if( !isset($form) )
	$form = array();
elseif( is_object($form) )
	$form = get_object_vars($form);


//var_dump($form);
// all variables for the form should have been passed to the view (when editiing an account)

// initialise variable with default values (empty string or array)
// or values from the database
$form_items = array( 'id', 'name', 'email', 'password', 'password2', 'is_public', 
	'pitch', 'logo', 'blogfeed', 'website', 'country', 'teamsize');

foreach( $form_items as $item ) {
	if( !isset( $form[$item] ) )
		$form[$item] = '';
}


// arrays
// if they are set, they can be a string of coma-seprated values to be transformed to an array (when from the database)
// or they can alredy be arrays if it came from the form itself
$form_items = array('operatingsystems', 'technologies', 'devices', 'stores');

foreach( $form_items as $item ) {
	if( !isset( $form[$item] ) )
		$form[$item] = array();
	elseif( is_string( $form[$item] ) )
		$form[$item] = explode( ',', $form[$item] );
}


// social network
// if socialnetworks wasn't set, it is now an empty array
// but if it was already set, it can be a JSON object as a string if comming from the database
// or alredy and array if comming from the form
if( !isset( $form['socialnetworks'] ) )
	$form['socialnetworks'] = array( 'sites' => array() );
elseif( is_string( $form['socialnetworks'] ) )
	$form['socialnetworks'] = json_decode( $form['socialnetworks'], true );


//var_dump($form);
echo '<div id="developer_form">
';

// page title
if( $admin_page == 'adddeveloper' || $page == 'adddeveloper' )
	echo '<h2>Create a developer account</h2>';
elseif( $admin_page == 'editdeveloper' )
	echo '<h2>Edit a developper</h2>';
elseif( $admin_page == 'edityouraccount' )
	echo '<h2>Edit your account</h2>';


echo get_form_errors($form);
echo get_form_success($form);

if( $page == 'adddeveloper')
	$admin_page = 'adddeveloper';

echo form_open( 'admin/'.$admin_page );

// display account id
if( $admin_page == 'editdeveloper' ) {
	echo 'Id : '.$form['id'].' <input type="hidden" name="form[id]" value="'.$form['id'].'"> <br>
';
}
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
		<input type="number" min="1" name="form[teamsize]" id="teamsize" placeholder="Teamsize" value="<?php echo $form['teamsize']=='' ? 1: $form['teamsize'];?>" > <label for="teamsize">The size of your team</label> <br>
		<br>
		<label for="stores">The social networks you are active on (leave url blank to delete existing ones) :</label> <br>
<?php
foreach( $form['socialnetworks']['sites'] as $array_id => $site_id ) {
	if( $form['socialnetworks']['urls'][$array_id] == '' ) // that's the 3 "new" fields, it happens when $form comes from the form itself
		continue;

	echo form_dropdown( 'form[socialnetworks][sites]['.$array_id.']', $site_data->socialnetworks, $site_id, 'id="socialnetwork_site_'.$array_id.'"' );
	echo '<input type="url" name="form[socialnetworks][urls]['.$array_id.']" id="socialnetworks_url_'.$array_id.'" placehodder="Full profile url" value="'.$form['socialnetworks']['urls'][$array_id].'"> <!--<label for="socialnetwork_url_">Leave url blanck to delete</label>--> <br>';
}

$count = count( $form['socialnetworks']['sites'] );
for( $i = $count; $i < $count+3; $i++ ) {
	echo form_dropdown( 'form[socialnetworks][sites][]', $site_data->socialnetworks, null, 'id="socialnetworks_site_'.$i.'"' );
	echo '<input type="url" name="form[socialnetworks][urls][]" id="socialnetworks_url_'.$i.'" placehodder="Full profile url" value=""><!-- <label for="socialnetworks_url_">Leave url blanck to delete</label> --><br>';
}
?>
		If you want to add more than 3 networks, add 3 now, save, then you will be able to add 3 more. <br>
		<br>
		<label for="technologies">The technologies/engines you are developing your games with :</label> <br>
<?php
echo form_multiselect( 'form[technologies][]', $site_data->technologies, $form['technologies'], 'id="technologies" size="10"' );
?>		
		<br>
		<br>
		<label for="operatingsystems">The operating systems your games are available on (not applicable for most consoles) :</label> <br>
<?php
echo form_multiselect( 'form[operatingsystems][]', $site_data->operatingsystems, $form['operatingsystems'], 'id="operatingsystems" size="7"' );
?>
		<br>
		<br>
		<label for="devices">The devices your games are playable on :</label> <br>
<?php
echo form_multiselect( 'form[devices][]', $site_data->devices, $form['devices'], 'id="devices" size="10"' );
?>		
		<br>
		<br>
		<label for="stores">The stores you sells your games on :</label> <br>
<?php
echo form_multiselect( 'form[stores][]', $site_data->stores, $form['stores'], 'id="stores" size="10"' );
?>		
		<br>
		<br>
<?php
$name = $admin_page.'_form_submitted';
$value = 'Edit this developer account';

if( $page == 'adddeveloper' || $admin_page == 'adddeveloper' )
	$value = 'Create this developer account';
elseif( $form['id'] == userdata('user_id') )
	$value = 'Edit your account';

if( $page == 'adddeveloper' )
	echo '<input type="hidden" name="from_adddeveloper_page">';
?>
		
		<input type="submit" name="developer_form_submitted" value="<?php echo $value;?>">
	</form>
</div> <!-- /#developer_form -->