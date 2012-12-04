<?php
$rules = array(
	'name' => 'required|min:5',

);

$dev = Dev::find($profile_id);
//Former::populate($dev);

/*$json_fields = array(
	
	'operatingsystems' => json_decode($dev->operatinsystems, true),
	'devices' => json_decode($dev->devices, true),
	'stores' => json_decode($dev->stores, true),
	'socialnetworks' => json_decode($dev->socialnetworks, true),
	'technologies' => $dev->technologies,
);
foreach ($json_fields as $key => $value) {
	Former::populateField($key, $value);
}*/

//Former::populateField('name', 'test');

//Former::populate($json_fields);

$old = Input::old();
if ( ! empty($old)) {
	Former::populate($old);
}

?>

<div id="editdeveloper_form">
	{{ Former::open_vertical('admin/editdeveloper')->rules($rules) }} 
		<legend>Edit your developer profile</legend>
		{{ Form::token() }}
		{{ Form::hidden('id', $profile_id) }}

		{{ Former::text('name', __('vgc.developer_name')) }}

		{{ Former::textarea('pitch', __('vgc.developer_pitch')) }}

		{{ Former::url('logo', __('vgc.developer_logo')) }}
		{{ Former::url('website', __('vgc.developer_website')) }}
		{{ Former::url('blogfeed', __('vgc.developer_blogfeed')) }}

		{{ Former::number('teamsize', __('vgc.developer_teamsize')) }}

		{{ Former::select('country')->options(get_array_lang(Config::get('vgc.countries'), 'countries_')) }}

		<?php
		$multiselect_form_items = array("technologies", "operatingsystems", "devices","stores");
		foreach ($multiselect_form_items as $item):
			$items = Config::get('vgc.'.$item);
			$size = count($items);
		    if ($size > 10 ) {
		        $size = 10;
		    }
		?>
		
		{{ Former::multiselect($item, null, get_array_lang($items, $item.'_'), array('flash','flixel')) }}
		@endforeach

		
		
		

		<input type="submit" value="Edit this developer profile" class="btn btn-primary">
	</form>
</div>
<!-- /#user_form --> 
