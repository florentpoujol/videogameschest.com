<?php
$site_data = get_site_data();
$page = get_page();
$admin_page = get_admin_page();


if( !isset($form) )
	$form = array();
elseif( is_object($form) )
	$form = get_object_vars($form);


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
$multiselect_form_items = array('technologies', 'operatingsystems', 'devices', 'stores');

foreach( $multiselect_form_items as $item ) {
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
elseif( is_string( $form['socialnetworks'] ) )
	$form['socialnetworks'] = json_decode( $form['socialnetworks'], true );



echo '<div id="developer_form">
';

// page title
if( $admin_page == 'adddeveloper' || $page == 'adddeveloper' )
	echo '	<h2>'.lang('adddeveloper_create_developer').'</h2>
';
elseif( $admin_page == 'editdeveloper' )
	echo '	<h2>Edit a developper</h2>
';
elseif( $admin_page == 'edityouraccount' )
	echo '	<h2>Edit your account</h2>
';


// display oerrors or success confirmation
echo get_form_errors($form);
echo get_form_success($form);


// form opening tag
if( $page == 'adddeveloper')
	$admin_page = 'adddeveloper';

echo form_open( 'admin/'.$admin_page ).'
';


// explanation text
if( $page == 'adddeveloper' )
	echo '<p>'.lang('adddeveloper_required_field').'</p>';


// profile id
if( $admin_page == 'editdeveloper' )
	echo 'Id : '.$form['id'].' <input type="hidden" name="form[id]" value="'.$form['id'].'"> <br>
';


// name
$input = array(
	'id'=>'name',
	'lang'=>'adddeveloper_name',
	'value'=>$form['name']	
);
echo form_input_extended($input);


//email
$input = array(
	'type'=>'email',
	'id'=>'email',
	'lang'=>'adddeveloper_email',
	'value'=>$form['email']	
);
echo form_input_extended($input);


if( $page == 'admin' ){
?>
		<input type="password" name="form[password]" id="password" placeholder="Write here only to update" value=""> <label for="password">Password (write only to update your existing password, don't forget to write it again below)</label> <br>
		<input type="password" name="form[password2]" id="password2" placeholder="Same as above" value=""> <label for="password2">Password confirmation</label> <br>
		
		<!--Statut :
		<input type="radio" name="form[is_public]" id="statut_private" value="0" <?php if($form['is_public']=='0'||$form['is_public']=='') echo 'checked="checked"'; ?>> <label for="statut_private">Private</label>
		<input type="radio" name="form[is_public]" id="statut_public" value="1" <?php if($form['is_public']=='1') echo 'checked="checked"'; ?>> <label for="statut_public">Public</label> <br> -->
<?php 
	if( $form['is_public'] == 0 )
		echo '<br> <input type="checkbox" name="form[is_public]" id="is_public" value="1"> <label for="is_public">Make your profile public</label> <br>';
	else
		echo '<br>Your profile is public<br>';
}
else { // end if( $page == 'admin' ): ?>
		<input type="hidden" name="form[password]" value=""> 
<?php } ?>
		<br>
		<label for="pitch"><?php echo lang('adddeveloper_pitch');?></label> <br>
		<textarea name="form[pitch]" id="pitch" placeholder="<?php echo lang('adddeveloper_pitch');?>" rows="7" cols="30"><?php echo $form['pitch'];?></textarea> <br>
<?php
// logo
$input = array(
	'type'=>'url',
	'id'=>'logo',
	'lang'=>'adddeveloper_logo',
	'value'=>$form['logo']	
);
echo form_input_extended($input);


// website url
$input = array(
	'type'=>'url',
	'id'=>'website',
	'lang'=>'adddeveloper_website',
	'value'=>$form['website']	
);
echo form_input_extended($input);


// blogfeed
$input = array(
	'type'=>'url',
	'id'=>'blogfeed',
	'lang'=>'adddeveloper_blogfeed',
	'value'=>$form['blogfeed']	
);
echo form_input_extended($input);


// teamsize
$input = array(
	'type'=>'number',
	'id'=>'teamsize',
	'lang'=>'adddeveloper_teamsize',
	'value'=> ($form['teamsize'] == '' ? 1 : $form['teamsize'])
);
echo form_input_extended($input);


// country
if( $form['country'] == '' )
	$form['country'] = 'usa';

echo form_dropdown( 'form[country]', $site_data->countries, $form['country'], 'id="country"' );
echo form_label( lang('adddeveloper_country'), 'country' ).' <br>';
 

// social networks
echo '<br> '.form_label( lang( 'adddeveloper_socialnetworks' ), 'socialnetworks' ).' <br>';

foreach( $form['socialnetworks']['sites'] as $array_id => $site_id ) {
	if( $form['socialnetworks']['urls'][$array_id] == '' ) // that's the 3 "new" fields, it happens when $form comes from the form itself
		continue;

	echo form_dropdown( 'form[socialnetworks][sites]['.$array_id.']', $site_data->socialnetworks, $site_id, 'id="socialnetwork_site_'.$array_id.'"' );
	echo '<input type="url" name="form[socialnetworks][urls]['.$array_id.']" id="socialnetworks_url_'.$array_id.'" placehodder="Full profile url" value="'.$form['socialnetworks']['urls'][$array_id].'"> <!--<label for="socialnetwork_url_">Leave url blanck to delete</label>--> <br>';
}

$count = count( $form['socialnetworks']['sites'] );
for( $i = $count; $i < $count+4; $i++ ) {
	echo form_dropdown( 'form[socialnetworks][sites][]', $site_data->socialnetworks, null, 'id="socialnetworks_site_'.$i.'"' );
	echo '<input type="url" name="form[socialnetworks][urls][]" id="socialnetworks_url_'.$i.'" placehodder="Full profile url" value=""><br>';
}


// other array fields
foreach( $multiselect_form_items as $key ) {
	$size = count( $site_data->$key );
	if( $size > 10 )
		$size = 10;

	$lang = lang( 'adddeveloper_'.$key );

	echo '
<br><br>
<label for="'.$key.'">'.$lang.'</label> <br>'.
form_multiselect( 'form['.$key.'][]', $site_data->$key, $form[$key], 'id="'.$key.'" size="'.$size.'"' ).'
';
}

//--------------------

$name = $admin_page.'_form_submitted';
$value = 'Edit this developer account';

if( $page == 'adddeveloper' || $admin_page == 'adddeveloper' )
	$value = lang('adddeveloper_submit');
elseif( $form['id'] == userdata('user_id') )
	$value = 'Edit your account';

if( $page == 'adddeveloper' )
	echo '<input type="hidden" name="from_adddeveloper_page" value="true">';
?>
		<br>
		<br>
		<input type="submit" name="developer_form_submitted" value="<?php echo $value;?>">
	</form>
</div> <!-- /#developer_form -->