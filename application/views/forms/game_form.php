<?php
$form_data = get_static_data('form');


if (!isset($form)) // when adding a profile, and no $form has been passed to the view
	$form = array();

$form = init_game_form($form);

?>
		<section id="developer_form">
<?php

// page title
$title = 'Edit a game';
if (METHOD == 'addgame' || CONTROLLER == 'addgame' )
	$title = lang('addgame_form_title');
?>
			<h1 id="page_title"><?php echo $title;?></h1>

<?php
// explanation text



// display errors or success confirmation
echo get_form_errors($form);
echo get_form_success($form);


// form opening tag
$method = METHOD;
if (CONTROLLER == 'addgame')
	$method = 'addgame';

echo form_open( 'admin/'.$method );


// profile id
if (METHOD == 'editgame' ): ?>
			
			Id : <?php echo $form['game_id'];?> <input type="hidden" name="form[game_id]" value="<?php echo $form['game_id'];?>"> <br>
<?php endif;

// required fields
if (CONTROLLER == 'addgame' )
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

// developer select
if (IS_ADMIN || CONTROLLER == 'addgame' ):
	if (IS_ADMIN)
		$db_devs = get_db_rows( 'developers' );
	else
		$db_devs = get_db_rows( 'developers', 'is_public', 1 );

	$developers = array();
	if (is_object( $db_devs ))
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
				<fieldset>
					<legend><?php echo lang('addgame_developementstates_legend');?></legend>
<?php

// state of developement
foreach( $form_data->developementstates as $state_key ):
	$radio = array(
    'name'        => 'developementstate',
    'id'          => 'developementstate_'.$state_key,
    'value'       => $state_key,
    );
?>
					<?php echo form_radio($radio).' '.form_label(lang('developementstates_'.$state_key), 'developementstate_'.$state_key); ?> <br> 
<?php endforeach; ?>
				</fieldset>


				<br>
				<label for="pitch"><?php echo lang('addgame_pitch');?></label> <br>
				<textarea name="form[data][pitch]" id="pitch" placeholder="<?php echo lang('addgame_pitch');?>" rows="7" cols="30"><?php echo $form['data']['pitch'];?></textarea> <br>

<?php
// string fields
$inputs = array('logo'=>'url', 'website'=>'url', 'blogfeed'=>'url', 'publishername'=>'text',
 'publisherurl'=>'url', 'soundtrack'=>'url',
 'price'=>'text', 'releasedate'=>'date');

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
$namestext_urls_arrays = array('screenshots', 'videos');

foreach( $namestext_urls_arrays as $item ):
?>
				
				<br>
				<?php echo form_label( lang( 'addgame_'.$item ), $item );?> <br>
<?php
	foreach( $form['data'][$item]['names'] as $array_id => $name ):
		if ($form['data'][$item]['urls'] == '' ) // that's the 4 "new" fields, it happens when $form comes from the form itself => actually no it is cleanned in the controller
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
endforeach; // end foreach $namestext_urls_arrays 


// name dropdown/url arrays
$namesdropdown_urls_arrays = array('socialnetworks', 'stores');

foreach( $namesdropdown_urls_arrays as $array ):
?>
				
				<br>
				<?php echo form_label( lang( 'addgame_'.$array ), $array ); ?> <br>
<?php
	foreach( $form['data'][$array]['names'] as $array_id => $site_key ):
		if ($form['data'][$array]['urls'][$array_id] == '' ) // that's the 4 "new" fields, it happens when $form comes from the form itself
			continue;
?>
				<?php echo form_dropdown( 'form[data]['.$array.'][names]['.$array_id.']', get_array_lang($form_data->$array, $array."_"), $site_key, 'id="'.$array.'_site_'.$array_id.'"' ); ?> 
				<?php echo '<input type="url" name="form[data]['.$array.'][urls]['.$array_id.']" id="'.$array.'_url_'.$array_id.'" placeholder="Profile URL" value="'.$form['data'][$array]['urls'][$array_id].'"> <br>'; ?> 
<?php
	endforeach;

	$count = count( $form['data'][$array]['names'] );
	for( $i = $count; $i < $count+4; $i++ ):
?>
				<?php echo form_dropdown( 'form[data]['.$array.'][names][]', get_array_lang($form_data->$array, $array."_"), null, 'id="'.$array.'_site_'.$i.'"' ); ?> 
				<?php echo '<input type="url" name="form[data]['.$array.'][urls][]" id="'.$array.'_url_'.$i.'" placeholder="Profile URL" value=""><br>'; ?> 
<?php
	endfor;
endforeach;


// other array (multiselect) fields
$array_keys = array('languages', 'technologies', 'operatingsystems', 'devices',
'genres', 'themes', 'viewpoints', 'nbplayers', 'tags' );

foreach( $array_keys as $key ):
	$size = count( $form_data->$key );
	if ($size > 10 )
		$size = 10;
?>
				
				<br>
				<?php echo '<label for="'.$key.'">'.lang( 'addgame_'.$key ).'</label> <br> ';
				echo form_multiselect( 'form[data]['.$key.'][]', get_array_lang($form_data->$key, $key."_"), $form['data'][$key], 'id="'.$key.'" size="'.$size.'"' ); ?> <br> 
<?php endforeach; ?>
				<br>
				<br>
<?php if (CONTROLLER == 'addgame' ): ?>
				<input type="hidden" name="from_addgame_page" value="true">
<?php endif;

$submit_value = 'Edit this game profile';
if (CONTROLLER == 'addgame' || METHOD == 'addgame' )
	$submit_value = lang('addgame_submit');
?>
		
				<input type="submit" name="game_form_submitted" value="<?php echo $submit_value;?>">
<?php
if (METHOD == 'editgame' && $form['profile_privacy'] == 'private' ):
?>
				<input type="submit" name="send_game_in_review" value="Send this game profile in peer review">
<?php endif; ?>
			</form>
		</section> <!-- /#game_form -->
