<?php
$old = Input::old();
if (isset($old)) {
 var_dump($old);
 Former::populate($old);
}






$rules = array(
	'test' => 'min:5'
);

$options = array('1' => 'un', '2'=>'deux');
?>
{{ Former::open_vertical('test')->rules($rules) }} 
	

	{{ Former::multiselect('field[]')->options($options) }}
	{{ Former::text('test', 'truc')->required_text() }}
    
    {{ Former::danger_button('truc1') }}
    {{ Former::danger_reset('truc2') }}
    {{ Former::danger_submit('truc3') }}
    {{ Former::primary_text('truc4') }}

	{{ Former::submit('submit') }}
</form>