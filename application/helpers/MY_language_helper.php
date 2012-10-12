<?php
function lang( $key, $id = '' ) {
	$line = get_instance()->lang->line( $key );
	
	if( !$line )
		$line = get_instance()->lang->line( $key, get_instance()->config->item( 'language' ) );

	if( !$line )
		$line = "[Missing lang key : $key]";

	if( $id != '' )
		$line = '<label for="'.$id.'">'.$line.'</label>';
	
	return $line;
}
?>