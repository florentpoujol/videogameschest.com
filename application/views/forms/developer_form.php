
            <section id="developer_form">
<?php
$form_data = $this->static_model->form;

if ( ! isset($form)) {
    $form = array();
}

$form = set_default_dev_infos($form);


// form opening tag
$method = METHOD;
if (CONTROLLER == "adddeveloper") {
    $method = "adddeveloper";
}
?>
                {{ form_open("admin/".$method, array("class"=>"form-horizontal")) }} 
<?php
// page title
$legend = 'Edit your developer profile';
if (METHOD == "adddeveloper" || CONTROLLER == "adddeveloper") {
    $legend = lang("adddeveloper_legend");
}
elseif (METHOD == "editdeveloper") {
    $legend = 'Edit a developper profile';
}
?>
                    <legend>{{ legend }}</legend>

                    <!-- display form errors and success -->
                    {{ get_form_errors() }} 
                    {{ get_form_success() }} 
                    <!-- / -->
<?php
// profile id
if (METHOD == "editdeveloper"): ?>
                    Developer profile id : {{ form.id }}
                    <input type="hidden" name="form[id]" value="{{ form.id }}"> <br>           
<?php 
endif;

// name
$input = array(
    "id"=>"name",
    "lang"=>"developer_name",
    "value"=>$form["name"],
    "help"=>'<span class="label label-important">'.lang("help_required_field").'</span> '.lang("help_developer_name"),
    "required"=>"required"
);
?>
                    {{ form_input_extended($input) }} 
<?php
if (CONTROLLER == "adddeveloper"):
    //email
    $input = array(
        "type"=>"email",
        "id"=>"email",
        "lang"=>"developer_email",
        "value"=>$form["email"] ,
        "help"=>'<span class="label label-important">'.lang("help_required_field").'</span>',
        "required"=>"required",
    );
?>
                    {{ form_input_extended($input) }} 
<?php
endif;

// user
if (IS_ADMIN && METHOD == "editdeveloper"): // an empty dev prodile is always created at the same time as the dev user account
?>
                    <div class="control-group">
                        <label class="control-label" for="user_id">User</label> 
                        {{ form_dropdown("form[user_id]", get_users_array("dev"), $form["user_id"], 'id="user_id" class="controls"') }} 
                    </div>
<?php
endif;

// profile privacy
if (METHOD == "editdeveloper"):
    if ($form["privacy"] == "private"): ?>
                    <input type="checkbox" name="form[privacy]" id="is_public" value="public"> <label for="is_public">Make your profile public</label> <br>
    {% else %}
                <br>
                    Your profile is public <br>
    <?php endif; // end if privacy public 
endif;
?>

                    <div class="control-group">
                        <label class="control-label" for="pitch">{{ lang("developer_pitch") }}</label>
                        <textarea class="controls" name="form[data][pitch]" id="pitch" placeholder="{{ lang("developer_pitch") }}" rows="7" cols="30">{{ form.data.pitch }}</textarea>
                    </div>

<?php
// string fields
$inputs = array("logo"=>"url", "website"=>"url", "blogfeed"=>"url");

foreach ($inputs as $name => $type):
    $input_data = array(
        "type"=>$type,
        "name"=>"form[data][".$name."]",
        "id"=>$name,
        "lang"=>"developer_".$name,
        "value"=>$form["data"][$name]   
    );
?>
                    {{ form_input_extended($input_data) }} 
<?php
endforeach;

// teamsize
$input_data = array(
    "type"=>"number",
    "name"=>"form[data][teamsize]",
    "id"=>"teamsize",
    "lang"=>"developer_teamsize",
    "value"=> ($form["data"]["teamsize"] == '' ? 1 : $form["data"]["teamsize"])
);
?>
                    {{ form_input_extended($input_data) }} 
<?php

// country
?>
                    <div class="control-group">
                        <label class="control-label" for="country">{{ lang("developer_country") }}</label>
                        {{ form_dropdown( "form[data][country]", get_array_lang($form_data->countries, "countries_"), $form["data"]["country"], 'id="country" class="controls"' ) }} 
                    </div>
<?php
// social networks
?>
                    
                    <div class="control-group">
                        <label class="control-label" for="socialnetworks">{{ lang("developer_socialnetworks") }}</label>
                        <div class="controls">
<?php

foreach ($form["data"]["socialnetworks"]["names"] as $array_id => $site_id):
    if ($form["data"]["socialnetworks"]["urls"][$array_id] == '') // that's the 3 "new" fields, it happens when $form comes from the form itself
        continue;
?>
                            {{ form_dropdown("form[data][socialnetworks][names][".$array_id."]", get_array_lang($form_data->socialnetworks, "socialnetworks_"), $site_id, 'id="socialnetwork_site_'.$array_id.'"' ) }} 
                            <input type="url" name="form[data][socialnetworks][urls][{{ array_id }}]" id="socialnetworks_url_{{ array_id }}" value="{{ form.data.socialnetworks.urls.$array_id }}">
<?php
endforeach;

$count = count( $form["data"]["socialnetworks"]["names"] );
for ($i = $count; $i < $count+4; $i++):
?>
                            {{ form_dropdown( "form[data][socialnetworks][names][]", get_array_lang($form_data->socialnetworks, "socialnetworks_"), null, 'id="socialnetworks_site_'.$i.'"' ) }} 
                            <input type="url" name="form[data][socialnetworks][urls][]" id="socialnetworks_url_{{ i }}" placeholder="{{ lang("developer_socialnetworks_url_placeholder") }}"> <br> 
<?php endfor; ?>
                        </div>
                    </div>
                    <!-- /.control-groups socialnetworks -->
                    
<?php
// other array fields
$multiselect_form_items = array("technologies", "operatingsystems", "devices","stores");
foreach ($multiselect_form_items as $key):
    $size = count( $form_data->$key );
    if ($size > 10 ) {
        $size = 10;
    }
?>
                    <div class="control-group">
                        <label class="control-label" for="{{ key }}">{{ lang("developer_".$key) }}</label>
                        {{ form_multiselect( "form[data][".$key."][]", get_array_lang($form_data->$key, $key."_"), $form["data"][$key], 'id="'.$key.'" class="controls" size="'.$size.'"' ) }} 
                        <span class="help-inline">{{ lang("help_developer_".$key) }}</span>
                    </div>

<?php
endforeach;

//--------------------


$submit_value = 'Edit this developer account';

if (CONTROLLER == "adddeveloper" || METHOD == "adddeveloper")
    $submit_value = lang("adddeveloper_submit");

if (CONTROLLER == "adddeveloper"): ?>
               <input type="hidden" name="from_adddeveloper_page">
{% endif %}
                    <br>
                    <br>
                    <input type="submit" class="btn btn-primary" name="developer_form_submitted" value="{{ submit_value }}">
                </form>
            </section> 
            <!-- /#developer_form -->
