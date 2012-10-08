<?php
$site_data = get_site_data();
$page = get_page();
$admin_page = get_admin_page();


if( !isset($form) )
	$form = array();
elseif( is_object($form) )
	$form = get_object_vars($form);


//var_dump($form);
// all variables for the form should have been passed to the view (when editing an account)

// initialise variable with default values (empty string or array)
// or values from the database
$form_items = array('game_id', 'developer_id', 'name', 'profile_privacy', 'data');

foreach( $form_items as $item ) {
	if( !isset( $form[$item] ) )
		$form[$item] = '';
}

if( $form['data'] == '' )
	$form['data'] = array();
elseif( is_string( $form['data'] ) )
	$form['data'] = json_decode( $form['data'], true );

/*
$data_strings = array( 'pitch', 'logo', 'blogfeed', 'website', 'country', 'publishername', 'price', 'soundtrack' );

foreach( $data_strings as $string ) {
	if( !isset( $form['data'][$string] ) )
		$form['data'][$string] = '';
}

// arrays
$data_arrays = array('technologies', 'operatingsystems', 'devices',
 'genres', 'themes', 'viewpoints', 'nbplayers',  'tags' );

foreach( $data_arrays as $array ) {
	if( !isset( $form['data'][$array] ) )
		$form['data'][$array] = array();
}


$namestext_urls_arrays = array('screenshots', 'videos');

foreach( $namestext_urls_arrays as $array ) {
	if( !isset( $form['data'][$array] ) )
		$form['data'][$array] = array( 'names' => array() );
}


$namesdropdown_urls_arrays = array('socialnetworks', 'stores');

foreach( $namesdropdown_urls_arrays as $array ) {
	if( !isset( $form['data'][$array] ) )
		$form['data'][$array] = array( 'names' => array() );
}*/


// ----------------------------------------------------------------------------------


?>
		<section id="developer_form">
<?php

// page title
$title = 'Edit a game';
if( $admin_page == 'addgame' || $page == 'addgame' )
	$title = lang('addgame_form_title');
?>
			<h1 id="page_title"><?php echo $title;?></h1>

<?php
// explanation text



// display errors or success confirmation
echo get_form_errors($form);
echo get_form_success($form);


// form opening tag
if( $page == 'addgame')
	$admin_page = 'addgame';

echo form_open( 'admin/'.$admin_page );


// profile id
if( $admin_page == 'editgame' ): ?>
			
			Id : <?php echo $form['game_id'];?> <input type="hidden" name="form[game_id]" value="<?php echo $form['game_id'];?>"> <br>
<?php endif;

// required fields
if( $page == 'addgame' )
	echo '<p>'.lang('addgame_required_field').'</p>';


// name
$input = array(
	'id'=>'name',
	'lang'=>'addgame_name',
	'value'=>$form['name']	
);
?>

				<?php echo form_input_extended($input); ?>

<?php

// developer
if( userdata( 'is_admin' ) || $page == 'addgame' ):
	if( userdata( 'is_admin' ) )
		$db_devs = get_db_rows( 'developers' );
	else
		$db_devs = get_db_rows( 'developers', 'is_public', 1 );

	$developers = array();
	foreach( $db_devs->result() as $dev )
		$developers[$dev->developer_id] = $dev->name;
?>

				<?php echo form_dropdown( 'form[developer_id]', $developers, $form['developer_id'], 'id="developer"' );?>
				<label for="developer"><?php echo lang('addgame_developer');?></label> <br>

<?php else: // user is a developer on the admin panel ?>
				<br> 
				You are the developer. Id=<?php echo $form['developer_id'];?> Name=<?php echo $form['developer_name'];?>
<?php endif; ?>
				<br>
				<label for="pitch"><?php echo lang('addgame_pitch');?></label> <br>
				<textarea name="form[data][pitch]" id="pitch" placeholder="<?php echo lang('addgame_pitch');?>" rows="7" cols="30"><?php echo $form['data']['pitch'];?></textarea> <br>

<?php
// string fields
$inputs = array('logo'=>'url', 'website'=>'url', 'blogfeed'=>'url', 'publishername'=>'text', 'soundtrack'=>'url',
 'price'=>'text');

foreach( $inputs as $name => $type ) {
	$input_data = array(
		'type'=>$type,
		'name'=>'form[data]['.$name.']',
		'id'=>$name,
		'lang'=>'addgame_'.$name,
		'value'=>$form['data'][$name]	
	);
?>
				<?php echo form_input_extended($input_data, '');?> <br>
<?php
}


// name text/url arrays
foreach( $namestext_urls_arrays as $item ):
?>
				
				<br>
				<?php echo form_label( lang( 'addgame_'.$item ), $item );?> <br>
<?php
	foreach( $form['data'][$item]['names'] as $array_id => $name ):
		if( $form['data'][$item]['urls'] == '' ) // that's the 4 "new" fields, it happens when $form comes from the form itself
			continue;

		// the name field
		$input_data = array(
			'name'=>'form[data]['.$item.'][names]['.$array_id.']',
			'id'=>$item.'_names_'.$array_id,
			'lang'=>'addgame_'.$item.'_name',
			'value'=>$form['data'][$item]['names'][$array_id]
		);
?>
				<?php echo form_input_extended($input_data, ''); ?> 
<?php
		// the url field
		$input_data = array(
			'type'=>'url',
			'name'=>'form[data]['.$item.'][urls]['.$array_id.']',
			'id'=>$item.'_urls_'.$array_id,
			'lang'=>'addgame_'.$item.'_url',
			'value'=>$form['data'][$item]['urls'][$array_id]
		);
?>
				<?php echo form_input_extended($input_data, ''); ?> <br>
<?php
	endforeach;

	$count = count( $form['data'][$item]['names'] );
	for( $i = $count; $i < $count+4; $i++ ):
		$input_data = array(
			'name'=>'form[data]['.$item.'][names][]',
			'id'=>$item.'_names_'.$i,
			'lang'=>'addgame_'.$item.'_name',
		);
?>
				<?php echo form_input_extended($input_data, ''); ?> 
<?php

		// the url field
		$input_data = array(
			'type'=>'url',
			'name'=>'form[data]['.$item.'][urls][]',
			'id'=>$item.'_urls_'.$i,
			'lang'=>'addgame_'.$item.'_url',
		);
?>
				<?php echo form_input_extended($input_data, ''); ?> <br>
<?php
	endfor;
endforeach; // end foreach( $namestext_urls_arrays as $item ) { 


// name dropdown/url arrays
foreach( $namesdropdown_urls_arrays as $array ):
?>
				
				<br>
				<?php echo form_label( lang( 'addgame_'.$array ), $array ); ?> <br>
<?php
	foreach( $form['data'][$array]['names'] as $array_id => $site_key ):
		if( $form['data'][$array]['urls'][$array_id] == '' ) // that's the 4 "new" fields, it happens when $form comes from the form itself
			continue;
?>
				<?php echo form_dropdown( 'form[data]['.$array.'][names]['.$array_id.']', $site_data->$array, $site_key, 'id="'.$array.'_site_'.$array_id.'"' ); ?> 
				<?php echo '<input type="url" name="form[data]['.$array.'][urls]['.$array_id.']" id="'.$array.'_url_'.$array_id.'" placeholder="Profile URL" value="'.$form['data'][$array]['urls'][$array_id].'"> <br>'; ?> 
<?php
	endforeach;

	$count = count( $form['data'][$array]['names'] );
	for( $i = $count; $i < $count+4; $i++ ):
?>
				<?php echo form_dropdown( 'form[data]['.$array.'][names][]', $site_data->$array, null, 'id="'.$array.'_site_'.$i.'"' ); ?> 
				<?php echo '<input type="url" name="form[data]['.$array.'][urls][]" id="'.$array.'_url_'.$i.'" placeholder="Profile URL" value=""><br>'; ?> 
<?php
	endfor;
endforeach;


// other array (multiselect) fields
foreach( $data_arrays as $key ):
	$size = count( $site_data->$key );
	if( $size > 10 )
		$size = 10;
?>
				
				<br>
				<?php echo '<label for="'.$key.'">'.lang( 'addgame_'.$key ).'</label> <br> ';
				echo form_multiselect( 'form[data]['.$key.'][]', $site_data->$key, $form['data'][$key], 'id="'.$key.'" size="'.$size.'"' ); ?> <br> 
<?php endforeach; ?>
				<br>
				<br>
<?php if( $page == 'addgame' ): ?>
				<input type="hidden" name="from_addgame_page" value="true">
<?php endif;

$submit_value = 'Edit this game profile';
if( $page == 'addgame' || $admin_page == 'addgame' )
	$submit_value = lang('addgame_submit');
?>
		
				<input type="submit" name="game_form_submitted" value="<?php echo $submit_value;?>">
<?php
if( $admin_page == 'editgame' && $form['profile_privacy'] == 'private' ):
?>
				<input type="submit" name="send_game_in_review" value="Send this game profile in peer review">
<?php endif; ?>
			</form>
		</section> <!-- /#game_form -->
