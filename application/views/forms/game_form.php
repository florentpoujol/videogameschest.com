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
$form_items = array('id', 'developer_id', 'name', 'account_statut', 'data');

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
$data_arrays = array('operatingsystems', 'technologies', 'devices', 'stores',
 'nbplayers', 'themes', 'genres', 'tags', 'viewpoints');

foreach( $data_arrays as $array ) {
	if( !isset( $form['data'][$array] ) )
		$form['data'][$array] = array();
}

if( !isset( $form['data']['socialnetworks'] ) )
	$form['data']['socialnetworks'] = array( 'sites' => array() );


echo '<div id="developer_form">
';

// page title
if( $admin_page == 'addgame' || $page == 'addgame' )
	echo '<h2>Create a game account</h2>';
elseif( $admin_page == 'editgame' )
	echo '<h2>Edit a game</h2>';


echo get_form_errors();
echo get_form_success($form);

echo form_open( 'admin/'.$admin_page );


// display account id
if( $admin_page == 'editgame' ) {
	echo 'Id : '.$form['id'].' <input type="hidden" name="form[id]" value="'.$form['id'].'"> <br>
';
}
?>
		<input type="text" name="form[name]" id="name" placeholder="Title" value="<?php echo $form['name'];?>"> <label for="name">The name of the game</label> <br>
		<input type="hidden" name="form[account_state]" value="private">
<?php if( userdata( 'is_admin' ) || $page == 'addgame' ) {
	$db_devs = get_db_rows( 'developers' );
	$developers = array();
	foreach( $db_devs->result() as $dev )
		$developers[$dev->id] = $dev->name;

	echo form_dropdown( 'form[developer_id]', $developers, $form['developer_id'], 'id="developer"' );
}
else { // user is a developer on the admin panel ?>
		Developer: You ! Id=<?php echo $form['developer_id'];?> Name=<?php echo $form['developer_name'];?>
<?php } // end if( $page == 'admin' ): ?>
		<br>
		<label for="pitch">Pitch the game story, features below :</label> <br>
		<textarea name="form[data][pitch]" id="pitch" placeholder="Pitch the company" rows="10" cols="40"><?php echo $form['data']['pitch'];?></textarea> <br>
		<input type="url" name="form[data][logo]" id="logo" placeholder="Logo's URL" value="<?php echo $form['data']['logo'];?>" > <label for="logo">Your logo's URL</label> <br>
		<br>
		<input type="url" name="form[data][website]" id="website" placeholder="Website's URL" value="<?php echo $form['data']['website'];?>" > <label for="website">Your website's URL (company website or personal blog/portfolio)</label> <br>
		<input type="url" name="form[data][blogfeed]" id="blogfeed" placeholder="Blog RSS/Atom feed" value="<?php echo $form['data']['blogfeed'];?>" > <label for="blogfeed">Your blog feed (RSS or Atom flux)</label> <br>
		<br>
		<label for="stores">The social networks you are active on (leave url blank to delete existing ones) :</label> <br>
<?php
foreach( $form['data']['socialnetworks']['sites'] as $array_id => $site_id ) {
	if( $form['data']['socialnetworks']['urls'][$array_id] == '' ) // that's the 3 "new" fields, it happens when $form comes from the form itself
		continue;

	echo form_dropdown( 'form[data][socialnetworks][sites]['.$array_id.']', $site_data->socialnetworks, $site_id, 'id="socialnetwork_site_'.$array_id.'"' );
	echo '<input type="url" name="form[data][socialnetworks][urls]['.$array_id.']" id="socialnetworks_url_'.$array_id.'" placehodder="Full profile url" value="'.$form['data']['socialnetworks']['urls'][$array_id].'"> <!--<label for="socialnetwork_url_">Leave url blanck to delete</label>--> <br>';
}

$count = count( $form['data']['socialnetworks']['sites'] );
for( $i = $count; $i < $count+3; $i++ ) {
	echo form_dropdown( 'form[data][socialnetworks][sites][]', $site_data->socialnetworks, null, 'id="socialnetworks_site_'.$i.'"' );
	echo '<input type="url" name="form[data][socialnetworks][urls][]" id="socialnetworks_url_'.$i.'" placehodder="Full profile url" value=""><!-- <label for="socialnetworks_url_">Leave url blanck to delete</label> --><br>';
}
?>
		If you want to add more than 3 networks, add 3 now, save, then you will be able to add 3 more. <br>
<?php
$infos = array(
	'technologies' => 'The technologies/engines the game is developed with :',
	'operatingsystems' => 'The operating systems the game runs on (not applicable for most consoles) :',
	'devices' => 'The devices the game is playable on :',
	'stores' => 'The stores the game is purchable from :',
	'nbplayers' => 'The number of players who can join the fun :',
	'themes' => 'The themes of the game :',
	'viewpoints' => 'The point of view (not applicable for 2D games, not always for 3D games) :',
	'genres' => 'The type of the game :',
	'tags' => 'The tags :',
	);

foreach( $infos as $key => $label ) {
	$size = count( $site_data->$key );
	if( $size > 10 )
		$size = 10;

	echo '
		<br>
		<br>
		<label for="'.$key.'">'.$label.'</label> <br>'.
		form_multiselect( 'form[data]['.$key.'][]', $site_data->$key, $form['data'][$key], 'id="'.$key.'" size="'.$size.'"' ).'
';
}
?>
		
		<br>
		<br>
<?php
$value = 'Edit this game account';

if( $page == 'addgame' || $admin_page == 'addgame' )
	$value = 'Create this game account';
elseif( $form['id'] == userdata('user_id') )
	$value = 'Edit your account';
?>
		
		<input type="submit" name="game_form_submitted" value="<?php echo $value;?>">
	</form>
</div> <!-- /#developer_form -->