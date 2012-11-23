            <section id="game_form">
<?php
$form_data = $this->static_model->form;

if ( ! isset($form)) { // when adding a profile, and no $form has been passed to the view
    $form = array();
}

$form = set_default_game_infos($form);


// form opening tag
$method = METHOD;
if (CONTROLLER == "addgame") {
    $method = "addgame";
}
?>
            {{ form_open("admin/$method", array("class"=>"form-horizontal")) }} 
<?php

// form legend
$legend = 'Edit a game';
if (METHOD == "addgame" || CONTROLLER == "addgame" ) {
    $legend = lang("addgame_title");
}
?>
                <legend>{{ legend }}</legend>

                <!-- display form errors and success -->
                {{ get_form_errors($form) }} 
                {{ get_form_success($form) }} 
                <!-- / -->

<?php
// profile id
if (METHOD == "editgame"): ?>
                Game profile id : {{ form.id }} 
                <input type="hidden" name="form[id]" value="{{ form.id }}"> <br> 
<?php 
endif;

// name
$input = array(
    "id"=>"name",
    "lang"=>"game_name",
    "value"=>$form["name"],
    "help"=>'<span class="label label-important">'.lang("help_required_field").'</span>',
    "required"=>"required",
);
?>
                {{ form_input_extended($input) }} 

{#// developer select// show the select form to anyone but the logged in developer #}
{% if IS_DEVELOPER %}
                <div class="control-group">
                    <label class="control-label" for="developer">{{ lang("game_developer") }}</label>
                    <span class="controls">You are the developer of this game.</span> <br>
                    <input type="hidden" name="form[user_id]" value="{{ USER_ID }}">
                </div>
{% else %} {# // user is not logged in or an admin #}
                <div class="control-group">
                    <label class="control-label" for="developer">{{ lang("game_developer") }}</label>
                    {{ form_dropdown('form[user_id]', get_users_array("dev"), $form["user_id"], 'id="developer" class="controls" required="required"') }}
                    <span class="help-inline"><span class="label label-important">{{ lang("help_required_field") }}</span></span>
                </div>
{% endif %}
                
                <div class="control-group">
                    <label class="control-label" for="developementstate">{{ lang("game_developementstates_legend") }}</label>
<?php
// state of developement
$dev_states = array();

foreach ($form_data->developmentstates as $state_key) {
    $dev_states[$state_key] = lang("developementstates_$state_key");
}
?>
                    {{ form_dropdown('form[data][developmentstate]', $dev_states, $form["data"]["developmentstate"], 'id="developmentstate" class="controls"') }}
                </div>

                <div class="control-group">
                    <label class="control-label" for="pitch">{{ lang("game_pitch") }}</label> <br>
                    <textarea class="controls" name="form[data][pitch]" id="pitch" placeholder="{{ lang("game_pitch") }}" rows="7" cols="50">{{ form.data.pitch }}</textarea>
                </div>
<?php
// string fields
$inputs = array("logo"=>"url", "website"=>"url", "blogfeed"=>"url", "publishername"=>"text",
 "publisherurl"=>"url", "soundtrack"=>"url",
 "price"=>"text", "releasedate"=>"date");

foreach ($inputs as $name => $type):
    $input_data = array(
        "type"=>$type,
        "name"=>'form[data]['.$name.']',
        "id"=>$name,
        "lang"=>"game_".$name,
        "value"=>$form["data"][$name]   
    );

    if ($name == "price")
        $input_data["help"] = lang("help_game_price");
?>
                
                {{ form_input_extended($input_data) }} 
{% endfor %}

<hr>
<?php

// name text/url arrays
$namestext_urls_arrays = array("screenshots", "videos");

foreach ($namestext_urls_arrays as $item):
?>
                
                <div class="control-group">
                    <label class="control-label">{{ lang("game_".$item) }}</label>
                    <div class="controls form-inline">
                        
<?php
    foreach ($form["data"][$item]["names"] as $array_id => $name):
        if ($form["data"][$item]["urls"] == '' ) // that's the 4 "new" fields, it happens when $form comes from the form itself => actually no it is cleanned in the controller
            continue;

        // the name field
        $input_data = array(
            "name"=>'form[data]['.$item.'][names]['.$array_id.']',
            "id"=>$item."_names_".$array_id,
            "lang"=>"game_".$item."_name",
            "value"=>$form["data"][$item]["names"][$array_id],
            "placeholder"=>lang("game_".$item."_url")
        );
?>
                        {{ form_label(lang("game_".$item."_name"), $item."_names_".$array_id) }} {{ form_input($input_data) }} 
<?php
        // the url field
        $input_data = array(
            "type"=>"url",
            "name"=>'form[data]['.$item.'][urls]['.$array_id.']',
            "id"=>$item."_urls_".$array_id,
            "lang"=>"game_".$item."_url",
            "value"=>$form["data"][$item]["urls"][$array_id],
            "placeholder"=>lang("game_".$item."_url")
        );
?>
                        {{ form_label(lang("game_".$item."_url"), $item."_urls_".$array_id) }} {{ form_input($input_data) }} <br>
<?php
    endforeach;

    $count = count( $form["data"][$item]["names"] );
    for ($i = $count; $i < $count+4; $i++):
        $input_data = array(
            "name"=>'form[data]['.$item.'][names][]',
            "id"=>$item."_names_".$i,
            "lang"=>"game_".$item."_name",
            "placeholder"=>lang("game_".$item."_name")
        );
?>
                        {{ form_label(lang("game_".$item."_name"), $item."_names_".$i) }} {{ form_input($input_data) }} 
<?php

        // the url field
        $input_data = array(
            "type"=>"url",
            "name"=>'form[data]['.$item.'][urls][]',
            "id"=>$item."_urls_".$i,
            "lang"=>"game_".$item."_url",
            "placeholder"=>lang("game_".$item."_url")
        );
?>
                        {{ form_label(lang("game_".$item."_url"), $item."_urls_".$i) }} {{ form_input($input_data) }} <br>
<?php endfor; ?>
                    </div>
                </div>
                <!-- /.form-control {{ iem }} -->

<?php
endforeach; // end foreach $namestext_urls_arrays 


// name dropdown/url arrays
$namesdropdown_urls_arrays = array("socialnetworks", "stores");

foreach ($namesdropdown_urls_arrays as $array):
?>
                
                <div class="control-group">
                    <label class="control-label">{{ lang("game_".$array) }}</label>
                    <div class="controls form-inline">
<?php
    foreach ($form["data"][$array]["names"] as $array_id => $site_key):
        if ($form["data"][$array]["urls"][$array_id] == '' ) { // that's the 4 "new" fields, it happens when $form comes from the form itself
            continue;
        }
?>
                        {{ form_dropdown( 'form[data]['.$array.'][names]['.$array_id.']', get_array_lang($form_data->$array, $array."_"), $site_key, 'id="'.$array."_site_".$array_id.'"' ) }}
                        <input type="url" name="form[data][{{ array }}][urls][{{ array_id }}]" id="<?php echo $array."_url_".$array_id; ?>" placeholder="{{ lang("game_socialnetworks_placeholder_url") }}" value="{{ form.data.$array.urls.$array_id }}"> <br> 
<?php
    endforeach;

    $count = count($form["data"][$array]["names"]);
    for ($i = $count; $i < $count+4; $i++):
?>
                        {{ form_dropdown( 'form[data]['.$array.'][names][]', get_array_lang($form_data->$array, $array."_"), null, 'id="'.$array."_site_".$i.'"' ) }} 
                        <input type="url" name="form[data][{{ array }}][urls][]" id="<?php echo $array."_url_".$i; ?>" placeholder="{{ lang("game_socialnetworks_placeholder_url") }}" value=""><br>
<?php endfor; ?>
                     </div>
                </div>
                <!-- /.control-group {{ array }} -->
<?php 
endforeach; 

// other array (multiselect) fields
$array_keys = array("languages", "technologies", "operatingsystems", "devices",
"genres", "themes", "viewpoints", "nbplayers", "tags" );

foreach( $array_keys as $key ):
    $size = count( $form_data->$key );
    if ($size > 10 )
        $size = 10;
?>
                <div class="control-group">
                    <label class="control-label" for="{{ $key }}">{{ lang("game_".$key) }}</label>
                    {{ form_multiselect('form[data]['.$key.'][]', get_array_lang($form_data->$key, $key."_"), $form["data"][$key], 'id="'.$key.'" size="'.$size.'" class="controls"' ) }} 
                    <span class="help-inline">{{ lang("help_game_".$key) }}</span>
                </div>
                <!-- /.control-group {{ key }} -->

{% endfor %}

                <br>
                <br>
            {% if CONTROLLER == "addgame" %}
                <input type="hidden" name="from_addgame_page">
            {% endif %}
<?php 

$submit_value = 'Edit this game profile';
if (CONTROLLER == "addgame" || METHOD == "addgame" )
    $submit_value = lang("addgame_submit");
?>
        
                <input type="submit" class="btn btn-primary" name="game_form_submitted" value="{{ submit_value }}">

            {% if METHOD == "editgame" and $form["profile_privacy"] == "private" %}
                <input type="submit" name="send_game_in_review" value="Send this game profile in peer review">
            {% endif %}
            </form>
        </section>
        <!-- /#game_form -->
