<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Methods that return the url of CSS, JS or Image files
 * @param string $file Name of the file
 * @param string $ext optionnal The extension of the file
 * @return string The full url
 */
function css_link( $file, $ext = '.css' ) {
	return base_url().'assets/css/'.$file.$ext;
}

function js_link( $file, $ext = '.js' ) {
	return base_url().'assets/js/'.$file.$ext;
}

function img_link( $file ) {
	return base_url().'assets/img/'.$file;
}


// ----------------------------------------------------------------------------------

/**
 * Get data stored in json files
 * @param string $data_name The name of the data which is also the name of the file
 * @param bool $return_as_array=false Return the data as an aray instead of an object
 * @return The data object (or array)
 */
function get_static_data( $data_name, $return_as_array = false ) {
	static $data_cache = null;

	if( $data_cache != null && isset($data_cache[$data_name]) ) {
		if( $return_as_array )
			return get_object_vars( $data_cache[$data_name] );
		else
			return $data_cache[$data_name];
	}

    $file_path = APPPATH.'/../assets/json/'.$data_name.'.json';
   	// DO NOT USE base_url() !!
   	// file_exists will return false, even if the file is accessible
 
    if( !file_exists( $file_path ) )
    	die( "[$data_name.json] does not exists at path : $file_path" );
    

    $string_data = file_get_contents( $file_path );

    if( $string_data == false ) // $string_data may be false because read_file() failed, otherwise it is a string
    	die( "[$file_path] could not be read !" );

    $data = json_decode( $string_data );

	//cache
	$data_cache[$data_name] = $data;

    if( $return_as_array )
		return get_object_vars( $data );
	else
		return $data;
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

/**
 * Helper function for the main model
 * @return The database object
 */
function get_db_rows( $select, $from = null, $where = null, $order_by = null, $limit = null, $limit_end = null ) {
	return get_instance()->main_model->get_rows( $select, $from, $where, $order_by, $limit, $limit_end );
}

function get_db_row( $select, $from = null, $where = null, $order_by = null, $limit = null, $limit_end = null ) {
	return get_instance()->main_model->get_row( $select, $from, $where, $order_by, $limit, $limit_end );
}

/*function get_db_info( $table, $searched_field, $where, $value = null ) {
	return get_instance()->main_model->get_info( $table, $searched_field, $where, $value );
}

function get_db_data( $table, $where, $value = null ) {
	return get_instance()->main_model->get_data( $table, $where, $value );
}*/


// ----------------------------------------------------------------------------------

/**
 * Does the opposite of url_title() from the url helper
 * Replace dashes and %20 by spaces
 * @param the url segment
 * @return the name
 */
function url_to_name( $url ) {
	$url = str_replace( array( '-', '%20' ), ' ', $url );
	return $url;
}
function name_to_url( $name ) {
	return url_title( $name );
}


// ----------------------------------------------------------------------------------
// SESSION

/**
 * Helper functions for the Session library
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
// INPUT

/**
 * Helper function for the Input library
 */
function post( $key = null ) {
	return get_instance()->input->post( $key );
}


// ----------------------------------------------------------------------------------

/**
 * Allow to easily share the name of the current page
 */
/*$page = '';

function set_page( $name ) {
	global $page;
	$page = $name;
}

function get_page() {
	global $page;
	return $page;
}*/


/**
 * Allow to easily share the name of the current admin page
 */
/*$admin_page = '';

function set_admin_page( $name ) {
	global $admin_page;
	$admin_page = $name;
}

function get_admin_page() {
	global $admin_page;
	return $admin_page;
}*/


// ----------------------------------------------------------------------------------

/**
 * Return a text if the current page or admin_page match the name passed as parameter
 * Allow to style the menu items matching the current page
 */
function menu_selected( $item ) {
	//global $page;
	$text = '';
	if (CONTROLLER == $item)
		$text = 'id="menu_selected" ';
	return $text;
}

function admin_menu_selected( $item ) {
	//global $admin_page;
	$text = '';
	if (METHOD == $item)
		$text = 'id="admin_menu_selected" ';
	return $text;
}


// ----------------------------------------------------------------------------------

/**
 */
function get_users_array( $user_type ) {
	static $users = null;

	if (is_array($users) && isset($users[$user_type])))
		return $users[$user_type];

	$raw_users = get_db_rows("user_id, name", "users", "type = '$user_type'");

	if ($raw_users === false)
		return $raw_users;

	$users[$user_type] = array();

	foreach ($raw_users->result() as $user)
		$users[$user_type][$user->user_id] = $user->name;

	return $users[$user_type];
}
function get_developers() {
	return get_users_array("dev");
}



// ----------------------------------------------------------------------------------

/**
 * strip out empty name/url in names/urls arrays (socialnetworks, stores, screenshots, videos)
 * then rebuilt it's index
 * @param assoc array $array
 * @return the array cleaned up
 */
function clean_names_urls_array( $array ) {
    $max_count = count( $array['names'] );

    for( $i = 0; $i < $max_count; $i++ ) {
        if( isset( $array['names'][$i] ) &&
         	(trim( $array['names'][$i] ) == '' || trim( $array['urls'][$i] ) == '') ) 
        {
            unset( $array['names'][$i] );
            unset( $array['urls'][$i] );
            $i--; // go back one index since unsetting change the size of the array and the keys of the remaining values
        }
    }

    // rebuilt indexes so that json_encode consider them as array an not as object
    if( isset( $array['names'] ) ) {
        array_values( $array['names'] );
        array_values( $array['urls'] );
    }

    return $array;
}


// ----------------------------------------------------------------------------------

/**
 * Parse bbCode
 * @param string $input the input text
 * @return string the input string with the bbCode replaced by html tags
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


// ----------------------------------------------------------------------------------

/**
 * Hash the password and return the hash and the salt
 * @return An assoc array containing the salt and the password hash
 */
function hash_password( $password ) {
	$pass_complexifier = "#:T{9 o]5A;-i|dT((5m7,!DF.&@x";
	// mixes the pass_complexifier to the password to be hashed so that it is very long and unique
	// and *impossible* to match with a rainbow table if the site's source code is unknow
	
	// It uses only one character with CRYPT_DES because this algo only cares for 8 characters
	// made the pass slightly harder to guess without source code, even with source, there is still 7 characters to guess
	
	if( CRYPT_EXT_DES == 1 ) // CRYPT_EXT_DES hash more than 8 characters
		$hash = crypt( $pass_complexifier.$password, get_ext_des_salt() );
	else
		$hash = crypt( $pass_complexifier[0].$password, get_des_salt() );

	return $hash;
}


// ----------------------------------------------------------------------------------

/**
 * [get_des_salt description]
 * @return string The salt
 */
function get_des_salt() {
	$alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789./";
	return $alphabet[mt_rand( 0, strlen($alphabet)-1 )].$alphabet[mt_rand( 0, strlen($alphabet)-1 )];
}


//----------------------------------------------------------------------------------

/**
 * [get_ext_des_salt description]
 * @return string The salt
 */
function get_ext_des_salt() {
	$alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789./";
	$salt = '_';
	
	for( $i = 0; $i < 8; $i++ )
		$salt .= $alphabet[mt_rand( 0, strlen($alphabet)-1 )];
	
	return $salt;
}


// ----------------------------------------------------------------------------------

/**
 * Check the user password against the encoded password
 * @param  string $password The password provided by the user
 * @param  string $hash     The hash from the data to check thr password against 
 * @return boolean          True if the password is valid, false otherwise
 */
function check_password( $password, $hash ) {
	$pass_complexifier = "#:T{9 o]5A;-i|dT((5m7,!DF.&@x";

	if( CRYPT_EXT_DES == 1 )
		return ($hash == crypt( $pass_complexifier.$password, $hash ));
	else
		return ($hash == crypt( $pass_complexifier[0].$password, $hash ));
}


//----------------------------------------------------------------------------------

/**
 * [get_array_lang description]
 * @param  [type] $array_keys [description]
 * @param  [type] $lang_key   [description]
 * @return [type]             [description]
 */
function get_array_lang( $array_keys, $lang_key ) {
	$array = array();

	foreach ($array_keys as $key) {
		$array[$key] = lang($lang_key.$key);
	}

	asort($array);
	return $array;
}


/* End of file main_helper.php */
/* Location: ./application/helpers/main_helper.php */