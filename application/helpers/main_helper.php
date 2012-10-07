<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Set of 
 */
function css_link( $file ) {
	return base_url().'assets/css/'.$file.'.css';
}

function js_link( $file ) {
	return base_url().'assets/js/'.$file.'.js';
}

function img_link( $file ) {
	return base_url().'assets/img/'.$file;
}


// ----------------------------------------------------------------------------------

$site_data = null;

/**
 * Get the site data (devices, stores list...)
 * the infos inside the file application/data/site_data.json
 * @return 
 */
function get_site_data( $return_as_array = false ) {
	global $site_data;

	if( $site_data != null ) {
		if( $return_as_array )
			return get_object_vars( $site_data );
		else
			return $site_data;
	}

    $filePath = APPPATH.'data/site_data.json'; // in application folder
    
    if( !file_exists( $filePath ) )
    	die( "site_data.json does not exists at path : ".$filePath );

    $string_site_data = read_file( $filePath );

    if( $string_site_data == false ) // $site_data may be false because read_file() failed, otherwise it is a string
    	die( $filePath." could not be read !" );

    $site_data = json_decode( $string_site_data );

    // sort all arrays
    $members = get_object_vars( $site_data );

    foreach( $site_data as $member_name => $object_value) {
    	$site_data->$member_name = get_object_vars( $object_value );
    	asort( $site_data->$member_name ); 
    	// asort() sort the array but values and keeps the key/value relation ships
    	// sort() replace the keys by numbers
	}

    if( $return_as_array )
		return get_object_vars( $site_data );
	else
		return $site_data;
}


// ----------------------------------------------------------------------------------

/**
 * Turn an array to an associative array where keys = value
 * @param the array
 * @return the associative array
 */
function get_assoc_array( $array ) {
	$assoc_array = array();
	foreach( $array as $value ) {
		$assoc_array[$value] = $value;
	}
	return $assoc_array;
}


// ----------------------------------------------------------------------------------
// DATABASE

function get_db_rows( $table, $where = null, $value = null ) {
	return get_instance()->main_model->get_rows( $table, $where, $value );
}

function get_db_row( $table, $where, $value = null, $rowId = null ) {
	return get_instance()->main_model->get_row( $table, $where, $value, $rowId );
}

function get_db_info( $table, $searched_field, $where, $value = null ) {
	return get_instance()->main_model->get_info( $table, $searched_field, $where, $value );
}

function get_db_data( $table, $where, $value = null ) {
	return get_instance()->main_model->get_data( $table, $where, $value );
}


// ----------------------------------------------------------------------------------

/**
 * Does the opposite of url_title() from the url helper
 * Replace dashes and %20 by spaces
 * @param the url segment
 * @return the name
 */
function title_url( $url ) {
	$url = str_replace( array( '-', '%20' ), ' ', $url );
	return $url;
}



// ----------------------------------------------------------------------------------

/**
 * 
 */
function userdata( $key = null ) {
	return get_instance()->session->userdata( $key );
}

function set_userdata( $key, $value = null ) {
	if( is_array( $key ) )
		get_instance()->session->set_userdata( $key );
	else
		get_instance()->session->set_userdata( $key, $value );
}


// ----------------------------------------------------------------------------------

/**
 * 
 *
 */
function post( $key = null ) {
	return get_instance()->input->post( $key );
}


// ----------------------------------------------------------------------------------

/**
 * current page name
 */
$page = '';

function set_page( $name ) {
	global $page;
	$page = $name;
}

function get_page() {
	global $page;
	return $page;
}


/**
 * current admin page name
 */
$admin_page = '';

function set_admin_page( $name ) {
	global $admin_page;
	$admin_page = $name;
}

function get_admin_page() {
	global $admin_page;
	return $admin_page;
}

// ----------------------------------------------------------------------------------

/**
 *
 */
function menu_selected( $item ) {
	global $page;
	$text = '';

	if($page == $item)
		$text = 'id="menu_selected" ';

	return $text;
}

function admin_menu_selected( $item ) {
	global $admin_page;
	$text = '';

	if($admin_page == $item)
		$text = 'id="admin_menu_selected" ';

	return $text;
}


// ----------------------------------------------------------------------------------

// mode
// raw : raw data from database
// clean : clean array key = dev id value = name
function get_developers( $raw = false ) {
	static $raw_devs = null;
	static $clean_devs = null;

	if( $raw_devs != null && $raw == true )
		return $raw_devs;

	if( $clean_devs != null && $raw == false )
		return $clean_devs;

	$raw_devs = get_db_rows( 'developers' );

	if( $raw == true )
		return $raw_devs;

	$clean_devs = array();
	foreach( $raw_devs->result() as $dev ) {
		$clean_devs[$dev->developer_id] = $dev->name;
	}

	return $clean_devs;
}




// ----------------------------------------------------------------------------------

/**
 * Wraper around validation_errors()
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

/**
 *
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
 * strip out empty url from the socialnetwork array
 * then rebuilt it's index
 *
 * @param assoc array $socialnetworks the socialnetworks array
 * @return the array clened up
 */
function clean_names_urls_array( $array ) {
    $max_count = count( $array['names'] );

    for( $i = 0; $i < $max_count; $i++ ) {
        if( isset( $array['names'][$i] ) &&
         	(trim( $array['names'][$i] ) == '' || trim( $array['urls'][$i] ) == '') ) 
        {
            unset( $array['names'][$i] );
            unset( $array['urls'][$i] );
            $i--; // unsetting change the size of the array and the keys of the remaining values
        }
    }

    // rebuilt the sites and urls index
    // so that json_encode consider them as array an not as object
    if( isset( $array['names'] ) ) {
        array_values( $array['names'] );
        array_values( $array['urls'] );
    }

    return $array;
}

// ----------------------------------------------------------------------------------

/**
 *
 */
function form_input_extended( $input, $br =  ' <br>' ) {
	if( !isset( $input['name'] ) )
		$input['name'] = 'form['.$input['id'].']';

	$lang = lang($input['lang']);
	unset($input['lang']);

	if( !isset( $input['placeholder'] ) )
		$input['placeholder'] = $lang;

	if( !isset( $input['type'] ) || $input['type'] == 'text' || $input['type'] == 'url' )
		$input['maxlength'] = '255';
	
	return form_input($input).' '.form_label( $lang, $input['id'] ).$br;
}


// ----------------------------------------------------------------------------------

/**
 * Parse bbCode
 * @param string $input the input text
 * @return 
 */
function parse_bbcode( $input ) {
	$input = preg_replace( "#\[b\](.+)\[/b\]#", "<strong>$1</strong>" ,$input);
	$input = preg_replace( "#\[i\](.+)\[/i\]#", "<em>$1</em>" ,$input);
	$input = preg_replace( "#https?://[^ ]+#i", '<a href="$0">$0</a>' ,$input);
	//$input = preg_replace( "#\[url\](.+)\[/url\]#", '<a href="$1">$1</a>' ,$input);
	$input = preg_replace( "#\[url=(.+)\](.+)\[/url\]#", '<a href="$1">$2</a>' ,$input);
	$input = preg_replace( "#/n#", '<br>' ,$input);
	return $input;
}