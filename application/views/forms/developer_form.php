<?php
$form_data = get_static_data("form");

if (!isset($form))
	$form = array();

$form = init_dev_infos($form);

?>
		<section id="developer_form">
<?php

// page title
$title = 'Edit your developer profile';
if (METHOD == "adddeveloper" || CONTROLLER == "adddeveloper" )
	$title = lang("adddeveloper_form_title");
elseif (METHOD == "editdeveloper" )
	$title = 'Edit a developper profile'
?>
			<h1 id="page_title"><?php echo $title;?></h1>

<?php
// explanation text



// display errors or success confirmation
echo get_form_errors($form);
echo get_form_success($form);


// form opening tag
$method = METHOD;
if (CONTROLLER == "adddeveloper")
	$method = "adddeveloper";
?>
			<?php echo form_open( "admin/".$method ); ?> 

<?php

// profile id
if (METHOD == "editdeveloper"): ?>

				Profile Id : <?php echo $form["id"];?> <input type="hidden" name="form[id]" value="<?php echo $form["id"];?>"> <br>
<?php endif;

// required fields
if (CONTROLLER == "adddeveloper" )
	echo '<p>'.lang("adddeveloper_required_field").'</p>';


// name
$input = array(
	"id"=>"name",
	"lang"=>"adddeveloper_name",
	"value"=>$form["name"],
	"tooltip" => "developer_name"
);
?>
				<?php echo form_input_extended($input); ?> 
<?php
if (CONTROLLER == "adddeveloper"):
	//email
	$input = array(
		"type"=>"email",
		"id"=>"email",
		"lang"=>"adddeveloper_email",
		"value"=>$form["email"]	
	);
?>
				<?php echo form_input_extended($input); ?> 
<?php
endif;

// user
?>

				<?php echo form_dropdown( "form[user_id]", get_users_array("dev"), $form["user_id"], 'id="user_id"' ); ?> 
				<label for="user_id">User</label> <br> 
<?php
// profile privacy
if (METHOD == "editdeveloper"):
	if ($form["privacy"] == "private"): ?>
				<?php echo '<br> <input type="checkbox" name="form[privacy]" id="is_public" value="public"> <label for="is_public">Make your profile public</label> <br>'; ?>
	<?php else: ?>
				<?php echo '<br>Your profile is public<br>'; ?> 
	<?php endif; // end if privacy public 
endif;
?>

				<br>
				<label for="pitch"><?php echo lang("adddeveloper_pitch");?></label> <br>
				<textarea name="form[data][pitch]" id="pitch" placeholder="<?php echo lang("adddeveloper_pitch");?>" rows="7" cols="30"><?php echo $form["data"]["pitch"];?></textarea> <br>

<?php


// string fields
$inputs = array("logo"=>"url", "website"=>"url", "blogfeed"=>"url");

foreach ($inputs as $name => $type) {
	$input_data = array(
		"type"=>$type,
		"name"=>"form[data][".$name."]",
		"id"=>$name,
		"lang"=>"adddeveloper_".$name,
		"value"=>$form["data"][$name]	
	);
?>
				<?php echo form_input_extended($input_data);?> 
<?php
}


// teamsize
$input_data = array(
	"type"=>"number",
	"name"=>"form[data][teamsize]",
	"id"=>"teamsize",
	"lang"=>"adddeveloper_teamsize",
	"value"=> ($form["data"]["teamsize"] == '' ? 1 : $form["data"]["teamsize"])
);
?>
				<?php echo form_input_extended($input_data);?> 
<?php

// country
?>
				<?php echo form_dropdown( "form[data][country]", get_array_lang($form_data->countries, "countries_"), $form["data"]["country"], 'id="country"' ); ?> 
				<?php echo form_label( lang("adddeveloper_country"), "country" ).' <br>'; ?> 

<?php

// social networks
?>
				
				<br>
				<?php echo form_label( lang( "addgame_socialnetworks" ), "socialnetworks" ); ?> <br>
<?php

foreach ($form["data"]["socialnetworks"]["names"] as $array_id => $site_id) {
	if ($form["data"]["socialnetworks"]["urls"][$array_id] == '') // that's the 3 "new" fields, it happens when $form comes from the form itself
		continue;
?>
				<?php echo form_dropdown( "form[data][socialnetworks][names][".$array_id."]", get_array_lang($form_data->socialnetworks, "socialnetworks_"), $site_id, 'id="socialnetwork_site_'.$array_id.'"' ); ?> 
				<?php echo '<input type="url" name="form[data][socialnetworks][urls]['.$array_id.']" id="socialnetworks_url_'.$array_id.'" placehodder="Full profile url" value="'.$form["data"]["socialnetworks"]["urls"][$array_id].'"> <br> '; ?> 
<?php
}

$count = count( $form["data"]["socialnetworks"]["names"] );
for ($i = $count; $i < $count+4; $i++) {
?>
				<?php echo form_dropdown( "form[data][socialnetworks][names][]", get_array_lang($form_data->socialnetworks, "socialnetworks_"), null, 'id="socialnetworks_site_'.$i.'"' ); ?> 
				<?php echo '<input type="url" name="form[data][socialnetworks][urls][]" id="socialnetworks_url_'.$i.'" placehodder="Full profile url" value=""> <br> '; ?> 
<?php
}


// other array fields
$multiselect_form_items = array("technologies", "operatingsystems", "devices","stores");
foreach ($multiselect_form_items as $key) {
	$size = count( $form_data->$key );
	if ($size > 10 )
		$size = 10;
?>
				
				<br>
				<?php echo '<label for="'.$key.'">'.lang( "adddeveloper_".$key ).'</label> <br> ';
				echo form_multiselect( "form[data][".$key."][]", get_array_lang($form_data->$key, $key."_"), $form["data"][$key], 'id="'.$key.'" size="'.$size.'"' ); ?> <br> 
<?php
}

//--------------------


$submit_value = 'Edit this developer account';

if (CONTROLLER == "adddeveloper" || METHOD == "adddeveloper")
	$submit_value = lang("adddeveloper_submit");

if (CONTROLLER == "adddeveloper")
	echo '<input type="hidden" name="from_adddeveloper_page" value="true">';
?>
				<br>
				<br>
				<input type="submit" name="developer_form_submitted" value="<?php echo $submit_value;?>">
			</form>
		</section> <!-- /#developer_form -->
