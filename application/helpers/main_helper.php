<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


function CSSLink( $file ) {
	return base_url().'assets/css/'.$file.'.css';
}

function JSLink( $file ) {
	return base_url().'assets/js/'.$file.'.js';
}

function ImageLink( $file ) {
	return base_url().'assets/img/'.$file;
}


// ----------------------------------------------------------------------------------

/**
 * Get the infos inside the file siteData.json
 */
function GetSiteData() {
    $filePath = APPPATH.'data/siteData.json'; // in application folder
    
    if( !file_exists( $filePath ) )
    	die( "siteData.json does not exists at path : ".$filePath);

    $siteData = read_file( $filePath );

    if( $siteData == false ) // $siteData is false because read_file() failed, otherwise it is a string
    	die( $filePath." could not be read !" );

    return json_decode( $siteData );
}


function GetInfo( $table, $field, $criteria, $value = null ) {
	return get_instance()->main_model->GetInfo( $table, $field, $criteria, $value );
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

$page = '';
$adminPage = '';

/**
 *
 */
function menu_selected( $item, $admin = '' ) {
	$text = '';

	if($page == $item) {
		if($admin != '')
			$text = 'id="admin_menu_selected" '
		else
			$text = 'id=""';
	}
		$text = 'id="menu_selected" '

	if($admin != '' && $admin == $adminPage)

	return $text;
}

?>