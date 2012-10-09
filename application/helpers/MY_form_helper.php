<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Wraper around validation_errors() of the Form_validation library
 * 	that allow to add my own errors mesages
 * @param array The $form array that may contains the 'errors' key
 * @return string The formated error messages
 */
function get_form_errors( $form = null ) {
	$errors = validation_errors();

	if( isset( $form ) && isset( $form['errors'] ) )
		$errors .= "\n".$form['errors'];

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
function get_form_success( $form ) {
	$html = '';

	if( isset($form['success']) ) {
		$html = 
'<div class="form_success">
	'.$form['success'].'
</div>';
	}

	return $html;
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