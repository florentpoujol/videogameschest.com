		<section id="game_form">
<?php
$form_data = $this->static_model->form;

if (!isset($form)) // when adding a profile, and no $form has been passed to the view
	$form = array();

$form = set_default_game_infos($form);


// form opening tag
$method = METHOD;
if (CONTROLLER == "addgame")
	$method = "addgame";
?>
			<?php echo form_open("admin/$method", array("class"=>"form-horizontal")); ?> 
<?php

// form legend
$legend = 'Edit a game';
if (METHOD == "addgame" || CONTROLLER == "addgame" )
	$legend = lang("addgame_form_title");
?>
				<legend><?php echo $legend; ?></legend>

				<?php echo get_form_errors(); ?> 
				<?php echo get_form_success(); ?> 

<?php
// profile id
if (METHOD == "editgame"): ?>
				Game profile id : <?php echo $form["id"]; ?> 
				<input type="hidden" name="form[id]" value="<?php echo $form["id"]; ?>"> <br> 
<?php 
endif;

// required fields
if (CONTROLLER == "addgame" )
	echo '<p>'.lang("addgame_required_field").'</p>';


// name
$input = array(
	"id"=>"name",
	"lang"=>"addgame_name",
	"value"=>$form["name"]	
);
?>
				<?php echo form_input_extended($input); ?> 

<?php

// developer select
if (IS_ADMIN || CONTROLLER == "addgame" ):
	if (IS_ADMIN)
		$developers = get_users_array("dev");
	else
		$developers = get_users_array(array("type"=>"dev", "privacy"=>"public"));
?>
				<div class="control-group">
					<label class="control-label" for="developer"><?php echo lang("addgame_developer"); ?></label>
					<?php echo form_dropdown( 'form[user_id]', $developers, $form["user_id"], 'id="developer" class="controls"' ); ?> 
					<!--<span class="help-inline">This field display the users which are developers, not the developer profiles.</span>-->
				</div>
<?php else: // user is a developer on the admin panel ?>
				<p>You are the developer of this game.</p>
<?php endif; ?>
				
				<div class="control-group">
					<label class="control-label" for="developementstate"><?php echo lang("addgame_developementstates_legend"); ?></label>
<?php
// state of developement
$dev_states = array();

foreach ($form_data->developmentstates as $state_key)
	$dev_states[$state_key] = lang("developementstates_$state_key");
?>
					<?php echo form_dropdown('form[data][developmentstate]', $dev_states, $form["data"]["developmentstate"], 'id="developmentstate" class="controls"'); ?> 
				</div>

				<div class="control-group">
					<label class="control-label" for="pitch"><?php echo lang("addgame_pitch"); ?></label> <br>
					<textarea class="controls" name="form[data][pitch]" id="pitch" placeholder="<?php echo lang("addgame_pitch"); ?>" rows="7" cols="50"><?php echo $form["data"]["pitch"]; ?></textarea>
				</div>
<?php
// string fields
$inputs = array("logo"=>"url", "website"=>"url", "blogfeed"=>"url", "publishername"=>"text",
 "publisherurl"=>"url", "soundtrack"=>"url",
 "price"=>"text", "releasedate"=>"date");

foreach( $inputs as $name => $type ):
	$input_data = array(
		"type"=>$type,
		"name"=>'form[data]['.$name.']',
		"id"=>$name,
		"lang"=>"addgame_".$name,
		"value"=>$form["data"][$name]	
	);

	if ($name == "price")
		$input_data["help"] = lang("help_addgame_price");
?>
				
				<?php echo form_input_extended($input_data); ?> 
<?php
endforeach;

// name text/url arrays
$namestext_urls_arrays = array("screenshots", "videos");

foreach( $namestext_urls_arrays as $item ):
?>
				
				<br>
				<?php echo form_label( lang( "addgame_".$item ), $item ); ?> <br>
				<div class="form-inline">
<?php
	foreach( $form["data"][$item]["names"] as $array_id => $name ):
		if ($form["data"][$item]["urls"] == '' ) // that's the 4 "new" fields, it happens when $form comes from the form itself => actually no it is cleanned in the controller
			continue;

		// the name field
		$input_data = array(
			"name"=>'form[data]['.$item.'][names]['.$array_id.']',
			"id"=>$item."_names_".$array_id,
			"lang"=>"addgame_".$item."_name",
			"value"=>$form["data"][$item]["names"][$array_id],
			"placeholder"=>lang("addgame_".$item."_url")
		);
?>
					<?php echo form_label(lang("addgame_".$item."_name"), $item."_names_".$array_id); ?> 
					<?php echo form_input($input_data); ?> 
<?php
		// the url field
		$input_data = array(
			"type"=>"url",
			"name"=>'form[data]['.$item.'][urls]['.$array_id.']',
			"id"=>$item."_urls_".$array_id,
			"lang"=>"addgame_".$item."_url",
			"value"=>$form["data"][$item]["urls"][$array_id],
			"placeholder"=>lang("addgame_".$item."_url")
		);
?>
					<?php echo form_label(lang("addgame_".$item."_url"), $item."_urls_".$array_id); ?> 
					<?php echo form_input($input_data); ?> <br>
<?php
	endforeach;

	$count = count( $form["data"][$item]["names"] );
	for( $i = $count; $i < $count+4; $i++ ):
		$input_data = array(
			"name"=>'form[data]['.$item.'][names][]',
			"id"=>$item."_names_".$i,
			"lang"=>"addgame_".$item."_name",
			"placeholder"=>lang("addgame_".$item."_name")
		);
?>
					<?php echo form_label(lang("addgame_".$item."_name"), $item."_names_".$i); ?> 
					<?php echo form_input($input_data); ?> 
<?php

		// the url field
		$input_data = array(
			"type"=>"url",
			"name"=>'form[data]['.$item.'][urls][]',
			"id"=>$item."_urls_".$i,
			"lang"=>"addgame_".$item."_url",
			"placeholder"=>lang("addgame_".$item."_url")
		);
?>
					<?php echo form_label(lang("addgame_".$item."_url"), $item."_urls_".$i); ?> 
					<?php echo form_input($input_data); ?> <br>
<?php endfor; ?>
				</div>
				<!-- /.form-inline -->

<?php
endforeach; // end foreach $namestext_urls_arrays 


// name dropdown/url arrays
$namesdropdown_urls_arrays = array("socialnetworks", "stores");

foreach( $namesdropdown_urls_arrays as $array ):
?>
				
				<br>
				<label for="<?php echo $array; ?>"><?php echo lang("addgame_".$array); ?></label> <br>
<?php
	foreach( $form["data"][$array]["names"] as $array_id => $site_key ):
		if ($form["data"][$array]["urls"][$array_id] == '' ) // that's the 4 "new" fields, it happens when $form comes from the form itself
			continue;
?>
				<?php echo form_dropdown( 'form[data]['.$array.'][names]['.$array_id.']', get_array_lang($form_data->$array, $array."_"), $site_key, 'id="'.$array."_site_".$array_id.'"' ); ?> 
				<?php echo '<input type="url" name="form[data]['.$array.'][urls]['.$array_id.']" id="'.$array."_url_".$array_id.'" placeholder="Profile URL" value="'.$form["data"][$array]["urls"][$array_id].'"> <br>'; ?> 
<?php
	endforeach;

	$count = count( $form["data"][$array]["names"] );
	for( $i = $count; $i < $count+4; $i++ ):
?>
				<?php echo form_dropdown( 'form[data]['.$array.'][names][]', get_array_lang($form_data->$array, $array."_"), null, 'id="'.$array."_site_".$i.'"' ); ?> 
				<?php echo '<input type="url" name="form[data]['.$array.'][urls][]" id="'.$array."_url_".$i.'" placeholder="Profile URL" value=""><br>'; ?> 
<?php
	endfor;
endforeach;
?>
				
				<br>

<?php

// other array (multiselect) fields
$array_keys = array("languages", "technologies", "operatingsystems", "devices",
"genres", "themes", "viewpoints", "nbplayers", "tags" );

foreach( $array_keys as $key ):
	$size = count( $form_data->$key );
	if ($size > 10 )
		$size = 10;
?>
				<div class="control-group">
					<?php echo '<label class="control-label" for="'.$key.'">'.lang( "addgame_".$key ).'</label>'; ?> 
					<?php echo form_multiselect('form[data]['.$key.'][]', get_array_lang($form_data->$key, $key."_"), $form["data"][$key], 'id="'.$key.'" size="'.$size.'" class="controls"' ); ?> 
				</div>
				<!-- /.control-group -->

<?php endforeach; ?>

				<br>
				<br>
<?php if (CONTROLLER == "addgame" ): ?>
				<input type="hidden" name="from_addgame_page">
<?php endif;

$submit_value = 'Edit this game profile';
if (CONTROLLER == "addgame" || METHOD == "addgame" )
	$submit_value = lang("addgame_submit");
?>
		
				<input type="submit" class="btn btn-primary" name="game_form_submitted" value="<?php echo $submit_value; ?>">
<?php
if (METHOD == "editgame" && $form["profile_privacy"] == "private" ):
?>
				<input type="submit" name="send_game_in_review" value="Send this game profile in peer review">
<?php endif; ?>
			</form>
		</section>
		<!-- /#game_form -->
