<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


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
	return get_instance()->static_model->$data_name;
	/*static $data_cache = null;

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
		return $data;*/
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

function controller_selected($menu_item, $already_inside_class = false) {
	if (CONTROLLER == $menu_item) {
		if ($already_inside_class)
			return "active";
		else
			return 'class="active"';
	}
	return "";
}
function method_selected($menu_item) {
	if (METHOD == $menu_item)
		return 'class="active"';
	return "";
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
 * Returna an array from the users table where keys are user_id and values user name
 * @param  string/array $where 	The user type (field users.type) or an array with other WHERE critera
 * @return array  $users    	The user array
 */
function get_users_array( $where ) {
	if (is_string($where))
		$where = array("type"=>$where);

	$raw_users = get_db_rows("id, name", "users", $where);

	if ($raw_users === false)
		return false;

	$users = array();

	foreach ($raw_users->result() as $user)
		$users[$user->id] = $user->name;

	return $users;
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
 * Complexify the password then hash it 
 * @param  string $password The password to be hashed
 * @return string The password hash
 */
function hash_password( $password ) {
	if (CRYPT_EXT_DES == 1) // CRYPT_EXT_DES hash more than 8 characters
		return crypt(complexify_password($password), "_".get_random_string(8));
	else
		return crypt(complexify_password($password), get_random_string(2)); // with CRYPT_STD_DES, only 8 characters are hashed
}


// ----------------------------------------------------------------------------------

/**
 * Check the user password against the encoded password
 * @param  string $password The password provided by the user
 * @param  string $hash     The hash from the data to check thr password against 
 * @return boolean          True if the password is valid, false otherwise
 */
function check_password( $password, $hash ) {
	if (CRYPT_EXT_DES == 1)
		return ($hash == crypt( complexify_password($password), $hash ));
	else
		return ($hash == crypt( complexify_password($password), $hash ));
}


// ----------------------------------------------------------------------------------

/**
 * Complexify and lengthen the password
 * @param  string $password The password to be complexified
 * @return string $password The complexified password
 */
function complexify_password( $password ) {
	$complexifier = "ï & £ . '   µ ( [ à : ¤ Ç _ è Ô ) ë = } + , Î ; # ² ] À ! § * { ù % $ | é @ ê - î ô ö ç î ^";

	if (CRYPT_EXT_DES == 1) { 
		// CRYPT_EXT_DES hash more than 8 characters
		// add complexifier characters into password
		// ie : "password" becomes "pïa&s£s.w'o rµd"
		$old_password = $password;
		for ($i = 0; $i < strlen($old_password); $i++) {
			if ($i%2 == 0 && isset($complexifier[$i])) // if $i is pair
				$password .= $complexifier[$i];

			$password .= $old_password[$i];
		}
	}
	else {
		// CRYPT_STD_DES hash only 8 characters
		$password = $password[3]."}".$password[2].$password[0].";".$password[1].$password[4];
		
		if (isset($password[5]))
			$password = $password[5].$password;
		else
			$password += "ê";
	}

	return $password;
}


//----------------------------------------------------------------------------------

/**
 * Return a random string of the requested length
 * @param int $length The length f the random strng to be returned
 * @return string The generated string
 */
function get_random_string( $length ) {
	$alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789./";
	$string = "";

	for ($i = 0; $i < $length; $i++)
		$string .= $alphabet[mt_rand(0, strlen($alphabet)-1)];
	
	return $string;
}


//----------------------------------------------------------------------------------

/**
 * Return an array whose keys are provided in the first argument and the corresponding values are the corresponding localized string
 * @param  array $array_keys Array containing localization keys
 * @param  string $lang_key  A prefix to the localization key
 * @return assoc array       The assoc array containing the keys/localization strings
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