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


function GetSiteData( $raw = false ) {
	return get_instance()->main_model->GetSiteData( $raw );
}

/*function DownloadLink( $file ) {
	return base_url().'assets/dowloads/'.$file;
}


function ImageLink( $file = array() ) {
	return base_url().'assets/images/'.$file;
}


function image( $data = array()  )
{
	$file = 'nodata';
	$alt = '';
	$title = '';
	$height = '';
	$width = '';
	$class = '';
	
	if( is_array( $data ) )
	{
		foreach( $data as $key => $value )
		{
			if( $key == 'file' )
				$file = $value;
			else
				${$key} = $key.'="'.$value.'"';
		}
	}
	elseif( is_string( $data ) )
		$file = $data;
	
	return '<img src="'.base_url().'assets/images/'.$file.'" '.$alt.' '.$height.' '.$width.' '.$class.' />';
}*/

?>