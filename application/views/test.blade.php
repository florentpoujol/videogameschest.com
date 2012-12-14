<?php
$old = Input::old();
if (isset($old)) {
 var_dump($old);
 Former::populate($old);
}


echo Game::find(2)->test();

$rules = array(
	'test' => 'min:5'
);

$options = array('1' => 'un', '2'=>'deux');
?>
{{ Former::open_vertical('test')->rules($rules) }} 
	

	{{ Former::multiselect('field[]')->options($options) }}
	{{ Former::text('test') }}


	{{ Former::submit('submit') }}
</form>