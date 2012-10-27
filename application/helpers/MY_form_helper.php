<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Wraper around validation_errors() of the Form_validation library
 * 	that allow to add my own errors mesages
 * @param array The $form array that may contains the "errors" key
 * @return string The formated error messages
 */
function get_form_errors( $form = null ) {
	$userdata_errors = userdata("form_errors");
	$validation_errors = validation_errors(); // get errors from the form_validation class
	$errors = "";

	if ($userdata_errors != "" || $validation_errors != "") {
		$errors .= '<div class="alert alert-error">
		<button type="button" class="close" data-dismiss="error">×</button>
		';

		if ($userdata_errors != "") {
			$userdata_errors = json_decode($userdata_errors, true);
			foreach ($userdata_errors as $error) {
				$errors .= $error.' <br>
				';
			}
		}

		$errors .= $validation_errors."
		</div>
		";
	}

	set_userdata("form_errors", "");

	return $errors;
}


//----------------------------------------------------------------------------------

/**
 * Register an error to be displayed the next time get_form_error is called
 * @param string $error The error
 */
function set_form_error($error) {
	$userdata_errors = userdata("form_errors");
	
	if ($userdata_errors != "") {
		$userdata_errors = json_decode($userdata_errors, true);
		$userdata_errors[] = '"'.$error.'"';
		set_userdata("form_errors", json_encode($userdata_errors));
	}
	else
		set_userdata("form_errors", '["'.$error.'"]');
}


//----------------------------------------------------------------------------------


function escape_json_chars($input) {
	$input = str_replace("[", "\[", $input);
	$input = str_replace("]", "\]", $input);
	$input = str_replace("{", "\{", $input);
	$input = str_replace("}", "\}", $input);
	$input = str_replace('"', '\"', $input);
	return $input;
}


// ----------------------------------------------------------------------------------

/**
 * Allow to set and display my own success message
 * @param array The $form array that may contains the "success" key
 * @return string The formated success messages
 */
function get_form_success( $form = null ) {
	$success = "";

	if ( ! isset($form) || $form === false)		
		return;

	if(is_array($form) && isset($form["success"])) //  isset($form["success"]) returns true when $form is a string ?
		$success = $form["success"];
	elseif (is_string($form) && trim($form) != "")
		$success = $form;
	
	if ($success != "") {
		$success = 
'<div class="form_success">
	'.$success.'
</div>';
	}

	return $success;
}


// ----------------------------------------------------------------------------------

/**
 * Wrapper around form_input() and form_label() of the Form helper
 */
function form_input_extended( $input, $br = ' <br>' ) {
	if( !isset( $input["name"] ) )
		$input["name"] = 'form['.$input["id"].']';

	$lang = lang($input["lang"]);
	unset($input["lang"]);

	if( !isset( $input["placeholder"] ) )
		$input["placeholder"] = $lang;

	if( !isset( $input["type"] ) || $input["type"] == "text" || $input["type"] == "url" )
		$input["maxlength"] = '255';
	
	$tooltip = isset( $input["tooltip"] ) ? $input["tooltip"]: '';
	unset( $input["tooltip"] );

	$html = form_input($input).' '.form_label( $lang, $input["id"] );

	if( $tooltip != '' )
		$html .= ' '.form_tooltip( $tooltip );

	return $html.$br;
}


// ----------------------------------------------------------------------------------

/**
 * Make sure that all potential $form keys and $form["data"] keys exists and have a default value
 * @param array $form An assoc array with where criteria or a single key as string
 * @return 
 */
function form_tooltip( $key ) {
	$html = '<img src="'.img_link('tooltip.jpg').'" alt="Tooltip image" class="tooltip" title="'.lang( "tooltip_".$key ).'" >';
	return $html;
}


// ----------------------------------------------------------------------------------

/**
 * Make sure that all potential $form keys and $form["data"] keys exists and have a default value
 * @param array $form An assoc array with where criteria or a single key as string
 * @return 
 */
function init_dev_infos( $form ) {
	if( is_object($form) ) // if $form comes from the database
		$form = get_object_vars($form);

	// first make sure that the databse fields exists (+ password2)
	$db_fields = array( "id", "name", "email", "type", "user_id", "password", "password2", "privacy", "data");

	foreach( $db_fields as $field ) {
		if( !isset( $form[$field] ) )
			$form[$field] = '';
	}

	// then take care of data
	if( is_string($form["data"]) && trim($form["data"]) == '' )
		$form["data"] = array();
	elseif( is_string( $form["data"] ) )
		$form["data"] = json_decode( $form["data"], true ); // true makes the returned value an array instead of an object

    $data = $form["data"];

    // string data
    $string_keys = array( "pitch", "logo", "blogfeed", "website", "country", "teamsize");

    foreach( $string_keys as $key ) {
        if( !isset( $data[$key] ) )
            $data[$key] = '';
    }

    // array data
    $array_keys = array("technologies", "operatingsystems", "devices","stores");

    foreach( $array_keys as $key ) {
        if( !isset( $data[$key] ) )
            $data[$key] = array();
    }

    // array( "names"=>array(), "urls"=>array() )
    $names_urls_array_keys = array("socialnetworks");

    foreach( $names_urls_array_keys as $key ) {
        if( !isset( $data[$key] ) )
            $data[$key] = array( "names" => array() );
    }


    $form["data"] = $data;
    return $form;
}


// ----------------------------------------------------------------------------------

/**
 * Make sure that all potential $form keys and $form["data"] keys exists and have a default value
 * @param array $form An assoc array with where criteria or a single key as string
 * @return 
 */
function init_game_infos( $form ) {
	if (is_object($form)) // if $form comes from the database
		$form = get_object_vars($form);

	// first make sure that the databse fields exists (+ password2)
	$db_fields = array("id", "type", "user_id", "name", "creation_date", "profile_privacy", "publication_date", "data");

	foreach ($db_fields as $field) {
		if( ! isset($form[$field]))
			$form[$field] = '';
	}

	// then take care of data
	if (is_string($form["data"]) && trim($form["data"]) == "")
		$form["data"] = array();
	elseif (is_string($form["data"]))
		$form["data"] = json_decode( $form["data"], true );

    $data = $form["data"];

    // string data
    $string_keys = array( "pitch", "logo", "blogfeed", "website",
    "publishername", "publisherurl", "price", "soundtrack", "releasedate" );

    foreach ($string_keys as $key) {
        if( ! isset($data[$key]))
            $data[$key] = "";
    }

    // array data
    $array_keys = array("languages", "technologies", "operatingsystems", "devices",
     "genres", "themes", "viewpoints", "nbplayers", "tags" );

    foreach ($array_keys as $key) {
        if( ! isset($data[$key]))
            $data[$key] = array();
    }

    // arrays "names/url"
    $names_urls_array_keys = array("screenshots", "videos", "socialnetworks", "stores");

    foreach ($names_urls_array_keys as $key) {
        if( ! isset($data[$key]))
            $data[$key] = array("names" => array());
    }

    $form["data"] = $data;
    return $form;
}


//----------------------------------------------------------------------------------
