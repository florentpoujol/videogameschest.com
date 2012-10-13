<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Wraper around validation_errors() of the Form_validation library
 * 	that allow to add my own errors mesages
 * @param array The $form array that may contains the 'errors' key
 * @return string The formated error messages
 */
function get_form_errors( $form = null ) {
	$errors = validation_errors();
	
	if ( ! isset($form) || $form === false)
		return;
	
	if (is_array($form) && isset( $form['errors'] ) )
		$errors .= "\n".$form['errors'];
	elseif (is_string($form) && trim($form) != "")
		$errors = "\n $form";
	
	if( $errors != '' ) {
		$errors =
'<div class="form_errors">
	'.$errors.'
</div>';
	}
	
	return $errors;
}


// ----------------------------------------------------------------------------------

/**
 * Allow to set and display my own success message
 * @param array The $form array that may contains the 'success' key
 * @return string The formated success messages
 */
function get_form_success( $form = null ) {
	$success = "";

	if ( ! isset($form) || $form === false)		
		return;

	if(is_array($form) && isset($form['success'])) //  isset($form['success']) returns true when $form is a string ?
		$success = $form['success'];
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
	if( !isset( $input['name'] ) )
		$input['name'] = 'form['.$input['id'].']';

	$lang = lang($input['lang']);
	unset($input['lang']);

	if( !isset( $input['placeholder'] ) )
		$input['placeholder'] = $lang;

	if( !isset( $input['type'] ) || $input['type'] == 'text' || $input['type'] == 'url' )
		$input['maxlength'] = '255';
	
	$tooltip = isset( $input['tooltip'] ) ? $input['tooltip']: '';
	unset( $input['tooltip'] );

	$html = form_input($input).' '.form_label( $lang, $input['id'] );

	if( $tooltip != '' )
		$html .= ' '.form_tooltip( $tooltip );

	return $html.$br;
}


// ----------------------------------------------------------------------------------

/**
 * Make sure that all potential $form keys and $form['data'] keys exists and have a default value
 * @param array $form An assoc array with where criteria or a single key as string
 * @return 
 */
function form_tooltip( $key ) {
	$html = '<img src="'.img_link('tooltip.jpg').'" alt="Tooltip image" class="tooltip" title="'.lang( 'tooltip_'.$key ).'" >';
	return $html;
}


// ----------------------------------------------------------------------------------

/**
 * Make sure that all potential $form keys and $form['data'] keys exists and have a default value
 * @param array $form An assoc array with where criteria or a single key as string
 * @return 
 */
function init_developer_form( $form ) {
	if( is_object($form) ) // if $form comes from the database
		$form = get_object_vars($form);

	// first make sure that the databse fields exists (+ password2)
	$db_fields = array( 'developer_id', 'name', 'email', 'password', 'password2', 'is_public', 'data');

	foreach( $db_fields as $field ) {
		if( !isset( $form[$field] ) )
			$form[$field] = '';
	}

	// then take care of data
	if( is_string($form['data']) && trim($form['data']) == '' )
		$form['data'] = array();
	elseif( is_string( $form['data'] ) )
		$form['data'] = json_decode( $form['data'], true ); // true makes the returned value an array instead of an object

    $data = $form['data'];

    // string data
    $string_keys = array( 'pitch', 'logo', 'blogfeed', 'website', 'country', 'teamsize');

    foreach( $string_keys as $key ) {
        if( !isset( $data[$key] ) )
            $data[$key] = '';
    }

    // array data
    $array_keys = array('technologies', 'operatingsystems', 'devices','stores');

    foreach( $array_keys as $key ) {
        if( !isset( $data[$key] ) )
            $data[$key] = array();
    }

    // array( 'names'=>array(), 'urls'=>array() )
    $names_urls_array_keys = array('socialnetworks');

    foreach( $names_urls_array_keys as $key ) {
        if( !isset( $data[$key] ) )
            $data[$key] = array( 'names' => array() );
    }


    $form['data'] = $data;
    return $form;
}


// ----------------------------------------------------------------------------------

/**
 * Make sure that all potential $form keys and $form['data'] keys exists and have a default value
 * @param array $form An assoc array with where criteria or a single key as string
 * @return 
 */
function init_game_form( $form ) {
	if( is_object($form) ) // if $form comes from the database
		$form = get_object_vars($form);

	// first make sure that the databse fields exists (+ password2)
	$db_fields = array('game_id', 'developer_id', 'name', 'profile_privacy', 'data');

	foreach( $db_fields as $field ) {
		if( !isset( $form[$field] ) )
			$form[$field] = '';
	}

	// then take care of data
	if( is_string($form['data']) && trim($form['data']) == '' )
		$form['data'] = array();
	elseif( is_string( $form['data'] ) )
		$form['data'] = json_decode( $form['data'], true );

    $data = $form['data'];

    // string data
    $string_keys = array( 'pitch', 'logo', 'blogfeed', 'website',
    'publishername', 'publisherurl', 'price', 'soundtrack', 'releasedate' );

    foreach( $string_keys as $key ) {
        if( !isset( $data[$key] ) )
            $data[$key] = '';
    }

    // array data
    $array_keys = array('languages', 'technologies', 'operatingsystems', 'devices',
     'genres', 'themes', 'viewpoints', 'nbplayers', 'tags' );

    foreach( $array_keys as $key ) {
        if( !isset( $data[$key] ) )
            $data[$key] = array();
    }

    // array( 'names'=>array(), 'urls'=>array() )
    $names_urls_array_keys = array('screenshots', 'videos', 'socialnetworks', 'stores');

    foreach( $names_urls_array_keys as $key ) {
        if( !isset( $data[$key] ) )
            $data[$key] = array( 'names' => array() );
    }

    $form['data'] = $data;
    return $form;
}