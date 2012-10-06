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
$form_items = array('id', 'developer_id', 'name', 'profile_privacy', 'data');

foreach( $form_items as $item ) {
	if( !isset( $form[$item] ) )
		$form[$item] = '';
}

if( $form['data'] == '' )
	$form['data'] = array();
elseif( is_string( $form['data'] ) )
	$form['data'] = json_decode($form['data'], true);


$data_strings = array( 'pitch', 'logo', 'blogfeed', 'website' );

foreach( $data_strings as $string ) {
	if( !isset( $form['data'][$string] ) )
		$form['data'][$string] = '';
}

// arrays
$data_arrays = array('technologies', 'operatingsystems', 'devices',
 'nbplayers', 'themes', 'genres', 'tags', 'viewpoints');

foreach( $data_arrays as $array ) {
	if( !isset( $form['data'][$array] ) )
		$form['data'][$array] = array();
}


$data_site_url_arrays = array('socialnetworks', 'stores');

foreach( $data_site_url_arrays as $array ) {
	if( !isset( $form['data'][$array] ) )
	$form['data'][$array] = array( 'sites' => array() );
}



echo '<div id="developer_form">
';


// page title
if( $admin_page == 'addgame' || $page == 'addgame' )
	echo '<h2>Create a game account</h2>';
elseif( $admin_page == 'editgame' )
	echo '<h2>Edit a game</h2>';


// display oerrors or success confirmation
echo get_form_errors();
echo get_form_success($form);


// form opening tag
if( $page == 'addgame')
	$admin_page = 'addgame';

echo form_open( 'admin/'.$admin_page );


// explanation text
if( $page == 'addgame' )
	echo '<p>'.lang('addgame_required_field').'</p>';


// profile id
if( $admin_page == 'editgame' )
	echo 'Id : '.$form['id'].' <input type="hidden" name="form[id]" value="'.$form['id'].'"> <br>';


// name
$input = array(
	'id'=>'name',
	'lang'=>'addgame_name',
	'value'=>$form['name']	
);
echo form_input_extended($input);


// developer
if( userdata( 'is_admin' ) || $page == 'addgame' ) {
	$db_devs = get_db_rows( 'developers', 'is_public', 1 );
	$developers = array();
	foreach( $db_devs->result() as $dev )
		$developers[$dev->id] = $dev->name;

	echo form_dropdown( 'form[developer_id]', $developers, $form['developer_id'], 'id="developer"' );
}
else // user is a developer on the admin panel 
	echo '<br> You are the developer. Id='.$form['developer_id'].' Name='.$form['developer_name'];
?>
		<br>
		<label for="pitch"><?php echo lang('addgame_pitch');?></label> <br>
		<textarea name="form[data][pitch]" id="pitch" placeholder="<?php echo lang('addgame_pitch');?>" rows="7" cols="30"><?php echo $form['data']['pitch'];?></textarea> <br>
<?php
$inputs = array('logo', 'website', 'blogfeed');
foreach( $inputs as $input_name ) {
	$input_data = array(
		'type'=>'url',
		'name'=>'form[data]['.$input_name.']',
		'id'=>$input_name,
		'lang'=>'addgame_'.$input_name,
		'value'=>$form['data'][$input_name]	
	);
	echo form_input_extended($input_data);
}
// logo


/*
// website url
$input = array(
	'type'=>'url',
	'name'=>'form[data][url]',
	'id'=>'website',
	'lang'=>'addgame_website',
	'value'=>$form['data']['website']	
);
echo form_input_extended($input);


// blogfeed
$input = array(
	'type'=>'url',
	'name'=>'form[data][blogfeed]',
	'id'=>'blogfeed',
	'lang'=>'addgame_blogfeed',
	'value'=>$form['data']['blogfeed']	
);
echo form_input_extended($input);*/


// site/url arrays
foreach( $data_site_url_arrays as $array ) {
	echo '<br> '.form_label( lang( 'addgame_'.$array ), $array ).' <br>';

	foreach( $form['data'][$array]['sites'] as $array_id => $site_id ) {
		if( $form['data'][$array]['urls'][$array_id] == '' ) // that's the 4 "new" fields, it happens when $form comes from the form itself
			continue;

		echo form_dropdown( 'form[data]['.$array.'][sites]['.$array_id.']', $site_data->$array, $site_id, 'id="'.$array.'_site_'.$array_id.'"' );
		echo '<input type="url" name="form[data]['.$array.'][urls]['.$array_id.']" id="'.$array.'_url_'.$array_id.'" placehodder=URL" value="'.$form['data'][$array]['urls'][$array_id].'"> <br>';
	}

	$count = count( $form['data'][$array]['sites'] );
	for( $i = $count; $i < $count+4; $i++ ) {
		echo form_dropdown( 'form[data]['.$array.'][sites][]', $site_data->$array, null, 'id="'.$array.'_site_'.$i.'"' );
		echo '<input type="url" name="form[data]['.$array.'][urls][]" id="'.$array.'_url_'.$i.'" placehodder="URL" value=""><br>';
	}
}


// other array fields
foreach( $data_arrays as $key ) {
	$size = count( $site_data->$key );
	if( $size > 10 )
		$size = 10;

	echo '
<br><br>
<label for="'.$key.'">'.lang( 'addgame_'.$key ).'</label> <br>'.
form_multiselect( 'form[data]['.$key.'][]', $site_data->$key, $form['data'][$key], 'id="'.$key.'" size="'.$size.'"' ).'
';
}
?>
		<br>
		<br>
<?php
$value = 'Edit this game account';

if( $page == 'addgame' || $admin_page == 'addgame' )
	$value = lang('addgame_submit');
elseif( $form['id'] == userdata('user_id') )
	$value = 'Edit your account';
?>
		
		<input type="submit" name="game_form_submitted" value="<?php echo $value;?>">
	</form>

	<input type="hidden" name="form[profile_privacy]" value="private">
</div> <!-- /#game_form -->