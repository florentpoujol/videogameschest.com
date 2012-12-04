<?php
$items = Config::get('vgc.technologies');
$options = get_array_lang($items, 'technologies_');
$values = array('flash', 'adventuregamestudio', 'blender');

?>
{{ Former::open_vertical() }} 

	{{ Former::multiselect('select')->options($options)->value($values) }}

	{{ Former::select('foo')->fromQuery(User::all(), 'username', 'id') }}
</form>