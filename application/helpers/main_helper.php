<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


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
 * Get the infos inside the file siteData.json
 */
function get_site_data() {
	global $site_data;

	if($site_data != null)
		return $site_data;

    $filePath = APPPATH.'data/siteData.json'; // in application folder
    
    if( !file_exists( $filePath ) )
    	die( "siteData.json does not exists at path : ".$filePath);

    $siteData = read_file( $filePath );

    if( $siteData == false ) // $siteData is false because read_file() failed, otherwise it is a string
    	die( $filePath." could not be read !" );

    $site_data = json_decode( $siteData );
    return $site_data;
}


function get_info( $table, $field, $criteria, $value = null ) {
	return get_instance()->main_model->get_info( $table, $field, $criteria, $value );
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
 * Does the opposite of url_title() from the url helper
 * Replace dashes and %20 by spaces
 * @param the url segment
 * @return the name
 */
function userdata( $key ) {
	return get_instance()->session->userdata( $key );
}


// ----------------------------------------------------------------------------------

/**
 * 
 *
 */
function post( $key ) {
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
	global $adminPage;
	$text = '';

	if($adminPage == $item)
		$text = 'id="admin_menu_selected" ';

	return $text;
}

?>