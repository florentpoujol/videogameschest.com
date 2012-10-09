<?php
$site_data = get_site_data();
$page = get_page();
$admin_page = get_admin_page();

if( !isset($form) )
	$form = array();

$form = init_developer_form($form);

?>
		<section id="developer_form">
<?php

// page title
$title = 'Edit your developer profile';
if( $admin_page == 'adddeveloper' || $page == 'adddeveloper' )
	$title = lang('adddeveloper_form_title');
elseif( $admin_page == 'editdeveloper' )
	$title = 'Edit a developper profile'
?>
			<h1 id="page_title"><?php echo $title;?></h1>

<?php
// explanation text



// display errors or success confirmation
echo get_form_errors($form);
echo get_form_success($form);


// form opening tag
if( $page == 'adddeveloper')
	$admin_page = 'adddeveloper';

echo form_open( 'admin/'.$admin_page ).'
';


// profile id
if( $admin_page == 'editdeveloper' ): ?>

				Id : <?php echo $form['developer_id'];?> <input type="hidden" name="form[developer_id]" value="<?php echo $form['developer_id'];?>"> <br>
<?php endif;

// required fields
if( $page == 'adddeveloper' )
	echo '<p>'.lang('adddeveloper_required_field').'</p>';


// name
$input = array(
	'id'=>'name',
	'lang'=>'adddeveloper_name',
	'value'=>$form['name']	
);
?>
				<?php echo form_input_extended($input); ?> 
<?php

//email
$input = array(
	'type'=>'email',
	'id'=>'email',
	'lang'=>'adddeveloper_email',
	'value'=>$form['email']	
);
?>
				<?php echo form_input_extended($input); ?> 
<?php

if( $page == 'admin' ):
?>

				<input type="password" name="form[password]" id="password" placeholder="Write here only to update" value=""> <label for="password">Password (write only to update your existing password, don't forget to write it again below)</label> <br>
				<input type="password" name="form[password2]" id="password2" placeholder="Same as above" value=""> <label for="password2">Password confirmation</label> <br>
				
<?php if( $form['is_public'] == 0 ): ?>
				<?php echo '<br> <input type="checkbox" name="form[is_public]" id="is_public" value="1"> <label for="is_public">Make your profile public</label> <br>'; ?>
<?php else: ?>
				<?php echo '<br>Your profile is public<br>'; ?> 
<?php endif;
// end if( $page == 'admin' ):
else: ?>
				<input type="hidden" name="form[password]" value=""> 
<?php endif; ?>

				<br>
				<label for="pitch"><?php echo lang('adddeveloper_pitch');?></label> <br>
				<textarea name="form[pitch]" id="pitch" placeholder="<?php echo lang('adddeveloper_pitch');?>" rows="7" cols="30"><?php echo $form['pitch'];?></textarea> <br>

<?php


// string fields
$inputs = array('logo'=>'url', 'website'=>'url', 'blogfeed'=>'url');

foreach( $inputs as $name => $type ) {
	$input_data = array(
		'type'=>$type,
		'name'=>'form['.$name.']',
		'id'=>$name,
		'lang'=>'adddeveloper_'.$name,
		'value'=>$form[$name]	
	);
?>
				<?php echo form_input_extended($input_data);?> 
<?php
}


// teamsize
$input_data = array(
	'type'=>'number',
	'id'=>'teamsize',
	'lang'=>'adddeveloper_teamsize',
	'value'=> ($form['teamsize'] == '' ? 1 : $form['teamsize'])
);
?>
				<?php echo form_input_extended($input_data);?> 
<?php


// country
if( $form['country'] == '' )
	$form['country'] = 'usa';
?>
				<?php echo form_dropdown( 'form[country]', $site_data->countries, $form['country'], 'id="country"' ); ?> 
				<?php echo form_label( lang('adddeveloper_country'), 'country' ).' <br>'; ?> 

<?php

// social networks
?>
				
				<br>
				<?php echo form_label( lang( 'addgame_socialnetworks' ), 'socialnetworks' ); ?> <br>
<?php

foreach( $form['socialnetworks']['names'] as $array_id => $site_id ) {
	if( $form['socialnetworks']['urls'][$array_id] == '' ) // that's the 3 "new" fields, it happens when $form comes from the form itself
		continue;
?>
				<?php echo form_dropdown( 'form[socialnetworks][names]['.$array_id.']', $site_data->socialnetworks, $site_id, 'id="socialnetwork_site_'.$array_id.'"' ); ?> 
				<?php echo '<input type="url" name="form[socialnetworks][urls]['.$array_id.']" id="socialnetworks_url_'.$array_id.'" placehodder="Full profile url" value="'.$form['socialnetworks']['urls'][$array_id].'"> <br> '; ?> 
<?php
}

$count = count( $form['socialnetworks']['names'] );
for( $i = $count; $i < $count+4; $i++ ) {
?>
				<?php echo form_dropdown( 'form[socialnetworks][names][]', $site_data->socialnetworks, null, 'id="socialnetworks_site_'.$i.'"' ); ?> 
				<?php echo '<input type="url" name="form[socialnetworks][urls][]" id="socialnetworks_url_'.$i.'" placehodder="Full profile url" value=""> <br> '; ?> 
<?php
}


// other array fields
foreach( $multiselect_form_items as $key ) {
	$size = count( $site_data->$key );
	if( $size > 10 )
		$size = 10;
?>
				
				<br>
				<?php echo '<label for="'.$key.'">'.lang( 'adddeveloper_'.$key ).'</label> <br> ';
				echo form_multiselect( 'form['.$key.'][]', $site_data->$key, $form[$key], 'id="'.$key.'" size="'.$size.'"' ); ?> <br> 
<?php
}

//--------------------


$submit_value = 'Edit this developer account';

if( $page == 'adddeveloper' || $admin_page == 'adddeveloper' )
	$submit_value = lang('adddeveloper_submit');

if( $page == 'adddeveloper' )
	echo '<input type="hidden" name="from_adddeveloper_page" value="true">';
?>
				<br>
				<br>
				<input type="submit" name="developer_form_submitted" value="<?php echo $submit_value;?>">
			</form>
		</section> <!-- /#developer_form -->
