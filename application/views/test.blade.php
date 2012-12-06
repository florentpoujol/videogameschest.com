<?php
$old = Input::old();
if (isset($old)) {
	var_dump($old);
}
Former::populate($old);
?>
{{ Former::open_vertical('test') }} 
	

	{{ Former::text('field[0]') }}
	{{ Former::text('field[1]') }}
	{{ Former::text('field[2]') }}


	{{ Former::submit('submit') }}
</form>