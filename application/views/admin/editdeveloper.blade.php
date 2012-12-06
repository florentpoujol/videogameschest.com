<?php
$rules = array(
	'name' => 'required|min:5|unique:users,username',
    'logo' => 'url|active_url',
    'website' => 'url|active_url',
    'blogfeed' => 'url|active_url',
    'teamsize' => 'min:1'
);

$dev = Dev::find($profile_id);
Former::populate($dev);

$old = Input::old();
if ( ! empty($old)) {
	Former::populate($old);
}
?>
<div id="editdeveloper_form">
	{{ Former::open_vertical('admin/editdeveloper')->rules($rules) }} 
		<legend>{{ lang('editdeveloper_title') }}</legend>
		
		{{ Form::token() }}
		{{ Form::hidden('id', $profile_id) }}

		{{ Former::text('name', 'vgc.developer_name') }}

		{{ Former::textarea('pitch', 'vgc.developer_pitch') }}

		{{ Former::url('logo', 'vgc.developer_logo') }}
		{{ Former::url('website', 'vgc.developer_website') }}
		{{ Former::url('blogfeed', 'vgc.developer_blogfeed') }}

		{{ Former::number('teamsize', 'vgc.developer_teamsize') }}

		{{ Former::select('country')->options(get_array_lang(Config::get('vgc.countries'), 'countries.')) }}

		<?php
		$multiselect_form_items = array("technologies", "operatingsystems", "devices","stores");
		
		foreach ($multiselect_form_items as $item):
			$items = Config::get('vgc.'.$item);
			$options = get_array_lang($items, $item.'.');
			$values = $dev->json_to_array($item);
			$size = count($items);
		    if ($size > 10) {
		        $size = 10;
		    }
		?>
		
		{{ Former::multiselect($item)->options($options)->forceValue($values)->size($size) }}
		@endforeach

		<fieldset>
			<legend>{{ lang('developer_socialnetworks') }}</legend>
			
			<?php
			$socialnetworks = $dev->json_to_array('socialnetworks');
			$options = get_array_lang(Config::get('vgc.socialnetworks'), 'socialnetworks.');
			$length = count($socialnetworks['names']);
			for ($i = 0; $i < $length; $i++):
			?>
				{{ Former::select('socialnetworks[names][]', 'vgc.developer_socialnetworks_name')->options($options)->value($socialnetworks['names'][$i]) }} 
				{{ Former::url('socialnetworks[urls][]', 'vgc.developer_socialnetworks_url')->value($socialnetworks['urls'][$i]) }}
			@endfor

			@for ($i = 1; $i < 5; $i++)
				{{ Former::select('socialnetworks[names][]', 'vgc.developer_socialnetworks_name')->options($options) }} 
				{{ Former::url('socialnetworks[urls][]', 'vgc.developer_socialnetworks_url') }}
			@endfor
		</fieldset>
		
		<input type="submit" value="{{ __('vgc.editdeveloper_submit') }}" class="btn btn-primary">
	</form>
</div>
<!-- /#user_form --> 
