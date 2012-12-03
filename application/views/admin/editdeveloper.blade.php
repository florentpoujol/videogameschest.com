<?php
$rules = array(
	'name' => 'required|min:5',

);

Former::populate(Dev::find($profile_id));

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
		{{ Former::select($item)->options(get_array_lang($items, $item.'_'))->multiple()->size($size) }}
		@endforeach
		
		

		<input type="submit" value="Edit this developer profile" class="btn btn-primary">
	</form>
</div>
<!-- /#user_form --> 
